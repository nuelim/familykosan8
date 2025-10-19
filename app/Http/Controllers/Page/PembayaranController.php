<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use App\Models\Penyewaan;
use App\Models\Bayar;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Log;
use Exception;
use DataTables;
use PDF;

class PembayaranController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}

	public function index(Request $request)
	{
		$url = $request->fullUrl();
		if (strpos($url, 'type=') === false) {
			if (strpos($url, '?') !== false) {
				$url .= '&type=pembayaran';
			} else {
				$url .= '?type=pembayaran';
			}
		}
		if (empty($request->type)) {
			return redirect()->to($url);
		}
		
		$tagihan_sudah_bayar = Bayar::getIndexBayar($request);
		$type = $request->type;
		return view('page.super_admin.bayar.index',compact('tagihan_sudah_bayar','type'))->with('label_cabang', $this->label_cabang);
	}
	public function save_pembayaran(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_penyewaan' => 'required',
			'tipe_bayar' => 'required',
			'tanggal_bayar' => 'required'
		];
		$validateMessage += [
			'tipe_bayar.required' => 'Tipe Pembayaran harus dipilih.',
			'tanggal_bayar.required' => 'Tanggal Pembayaran harus diisi.'
		];
		if ($request->tipe_bayar == 'Transfer') {
			$validateRules += [
				'bukti_bayar' => 'required'
			];
			$validateMessage += [
				'bukti_bayar.required' => 'Bukti Pembayaran harus disertakan.',
			];
		}
		if ($request->jenis_bayar == 'lain-lain') {
			$validateRules += [
				'harga_pembayaran' => 'required',
				'keterangan_bayar' => 'required'
			];
			$validateMessage += [
				'harga_pembayaran.required' => 'Nominal Bayar harus diisi.',
				'keterangan_bayar.required' => 'Keterangan Bayar harus diisi.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$shuffle  = substr(str_shuffle($karakter), 0, 8);
			if (!empty($request->file('bukti_bayar'))) {
				$ambil=$request->file('bukti_bayar');
				$name=$ambil->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $name;
				$ambil->move(\base_path()."/public/bukti_bayar", $namaFileBaru);
			}else{
				$namaFileBaru = NULL;
			}
			if (Auth::user()->level == 'Penghuni') {
				$tanggal_bayar = NULL;
			}else{
				$tanggal_bayar = $request->tanggal_bayar;
			}
			$data = New Bayar();
			// $data -> id_penyewaan = $request->id_penyewaan;
			$data -> kode_bayar = $shuffle;
			$data -> tipe_bayar = $request->tipe_bayar;
			$data -> tanggal_bayar = $request->tanggal_bayar;
			$data -> bukti_bayar = $namaFileBaru;
			$data -> keterangan_bayar = $request->keterangan_bayar;
			$data -> keterangan_bayar = $request->keterangan_bayar;
			if (Auth::user()->level == 'Penghuni') {
				$data -> status_bayar = 'Sedang di cek';
			} else {
				// TAMBAHKAN INI untuk Admin atau Operator
				$data -> status_bayar = 'Sudah Bayar';
			}
			// $data -> created_by = Auth::user()->id;
			$data -> save();

			if ($request->jenis_bayar == 'kos') {
				$pilih_tagihan = $request->pilih_tagihan;
				if (!empty($pilih_tagihan)) {
					// dd(count($pilih_tagihan));
					foreach ($request->bulan_pembayaran as $key => $value) {
						if (in_array($request->bulan_pembayaran[$key], $pilih_tagihan)) {
							$cek_valid_pembayaran = Bayar::cekValidPembayaran($request,$key);
							if ($cek_valid_pembayaran) {
								DB::table('pembayaran')->where('id_pembayaran',$request->id_pembayaran[$key])->update([
									'id_bayar'=>$data->id_bayar,
									'tanggal_pembayaran'=>$tanggal_bayar
								]);
							}else{
								return response()->json(['status'=>'warning', 'message'=>'Tagihan Pembayaran anda tidak ditemukan.']);
							}
						}
					}					
					if (Auth::user()->level == 'Penghuni') {
						//Bayar::PushNotifikasiPembayaran($request);
						$result_email = Bayar::sendReminderToPengurus($request);
						$detail_sewa = $result_email['detail_sewa'];
						$operator = $result_email['operator'];
						$super_admin = $result_email['super_admin'];
						$pengurus = $result_email['pengurus'];
						foreach ($pengurus as $peng) {
							if ($peng->email != NULL) {
								$details = [
									'status'=>'Pembayaran Kos',
									'subject'=>'Pembayaran Kos',
									'nama_penghuni'=>Auth::user()->name,
									'kode_penyewaan'=>$detail_sewa->kode_penyewaan,
									'tanggal_bayar'=>$request->tanggal_bayar,
									'nomor_kamar'=>$detail_sewa->nomor_kamar,
									'nama_operator'=>$peng->name,
									'harga_kamar'=>$detail_sewa->harga_kamar
								];
								\Mail::to($peng->email)->send(new \App\Mail\SendMail($details));
							}
						}
						Penyewaan::where('id_penyewaan',$request->id_penyewaan)->update([
							'tenggat'=>NULL
						]);
						$pembayaran_bulan = count($pilih_tagihan);
						$pesan = "Halo!\n\n";
						$pesan .= "Saya ingin meminta konfirmasi untuk pembayaran kos berikut:\n";
						$pesan .= "*Kamar*: ".$detail_sewa->nama_cabang.' / '.$detail_sewa->nomor_kamar."\n";
						$pesan .= "*Jenis*: ".$detail_sewa->jenis_kamar."\n";
						$pesan .= "*Harga/bulan*: Rp. ".number_format($detail_sewa->harga_kamar, 0, ",", ".")."\n";
						$pesan .= "*Pembayaran untuk*: ".$pembayaran_bulan.' bulan'."\n";
						if ($request->keterangan_bayar != '') {
							$pesan .= "*Keterangan Pembayaran*: ".$request->keterangan_bayar."\n";
						}
						$pesan .= "*Total*: Rp. ".number_format($detail_sewa->harga_kamar * $pembayaran_bulan, 0, ",", ".")."\n";
						$pesan .= "Saya sangat tertarik dengan kos yang ditawarkan dan ingin segera menyelesaikan proses pembayaran.\n";
						$pesan .= "Mohon konfirmasi mengenai pembayaran saya dan langkah-langkah yang perlu saya lakukan.\n\n";
						$pesan .= "An: ".Auth::user()->name."\n";
						$pesan .= "Terima kasih!\n";
						if (!empty($operator)) {
							$nomor_tujuan_wa = $operator->ponsel;
						}else{
							$nomor_tujuan_wa = $super_admin->ponsel;
						}
						$whatsappURL = 'https://wa.me/62'.substr($nomor_tujuan_wa,1).'?text='.urlencode($pesan);
					}else{
						Penyewaan::where('id_penyewaan',$request->id_penyewaan)->update([
							'status_penyewaan'=>'A',
							'tenggat'=>NULL
						]);
					}
				}else{
					return response()->json(['status'=>'warning', 'message'=>'Minimal 1 tagihan harus dipilih.']);
				}
			}else{
				if (Auth::user()->level != 'Penghuni') {
					$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
					$harga_pembayaran = preg_replace("/[^aZ0-9]/", "", $request->harga_pembayaran);
					$dateTime = new \DateTime($request->tanggal_bayar);
					$bulan = $dateTime->format('m');
					$tahun = $dateTime->format('Y');
					DB::table('pembayaran')->insert([
						'id_penyewaan'=>$request->id_penyewaan,
						'id_bayar'=>$data->id_bayar,
						'jenis_pembayaran'=>'lain-lain',
						'tanggal_pembayaran'=>$request->tanggal_bayar,
						'bulan_pembayaran'=>$bulan,
						'tahun_pembayaran'=>$tahun,
						'harga_pembayaran'=>$harga_pembayaran
					]);
				}
			}
			if (Auth::user()->level == 'Penghuni') {
				$url = $whatsappURL;
			}else{
				$url = '';
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Pembayaran berhasil disimpan !!','send_wa'=>$url]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit_pembayaran($id_bayar)
	{
		$data = Bayar::getEditPembayaran($id_bayar);
		return response()->json($data);
	}
	// public function update_pembayaran(Request $request)
	// {
	// 	// $validateRules = [];
	// 	// $validateMessage = [];

	// 	// $validateRules += [
	// 	// 	'id_penyewaan' => 'required',
	// 	// 	'tipe_bayar' => 'required',
	// 	// 	'tanggal_bayar' => 'required'
	// 	// ];
	// 	// $validateMessage += [
	// 	// 	'tipe_bayar.required' => 'Tipe Pembayaran harus dipilih.',
	// 	// 	'tanggal_bayar.required' => 'Tanggal Pembayaran harus diisi.'
	// 	// ];
	// 	// // if ($request->tipe_bayar == 'Transfer') {
	// 	// // 	$validateRules += [
	// 	// // 		'bukti_bayar' => 'required'
	// 	// // 	];
	// 	// // 	$validateMessage += [
	// 	// // 		'bukti_bayar.required' => 'Bukti Pembayaran harus diupload.',
	// 	// // 	];
	// 	// // }
	// 	// $validateRules += [
	// 	// 	'harga_pembayaran' => 'required',
	// 	// 	'keterangan_bayar' => 'required'
	// 	// ];
	// 	// $validateMessage += [
	// 	// 	'harga_pembayaran.required' => 'Nominal Bayar harus diisi.',
	// 	// 	'keterangan_bayar.required' => 'Keterangan Bayar harus diisi.'
	// 	// ];
	// 	// $request->validate($validateRules, $validateMessage);
	// 	// try {
	// 	// 	DB::beginTransaction();
	// 	// 	$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	// 	// 	$shuffle  = substr(str_shuffle($karakter), 0, 8);
	// 	// 	if (!empty($request->file('bukti_bayar'))) {
	// 	// 		$ambil=$request->file('bukti_bayar');
	// 	// 		$name=$ambil->getClientOriginalName();
	// 	// 		$namaFileBaru = uniqid();
	// 	// 		$namaFileBaru .= $name;
	// 	// 		$ambil->move(\base_path()."/public/bukti_bayar", $namaFileBaru);
	// 	// 	}else{
	// 	// 		$namaFileBaru = $request->bukti_bayarLama;
	// 	// 	}
	// 	// 	$data = Bayar::where('id_bayar',$request->id_bayar)->first();
	// 	// 	$data -> tipe_bayar = $request->tipe_bayar;
	// 	// 	$data -> tanggal_bayar = $request->tanggal_bayar;
	// 	// 	$data -> bukti_bayar = $namaFileBaru;
	// 	// 	$data -> keterangan_bayar = $request->keterangan_bayar;
	// 	// 	$data -> updated_by = Auth::user()->id;
	// 	// 	$data -> save();

	// 	// 	$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
	// 	// 	$harga_pembayaran = preg_replace("/[^aZ0-9]/", "", $request->harga_pembayaran);
	// 	// 	$dateTime = new \DateTime($request->tanggal_bayar);
	// 	// 	$bulan = $dateTime->format('m');
	// 	// 	$tahun = $dateTime->format('Y');
	// 	// 	DB::table('pembayaran')->where('id_bayar',$request->id_bayar)->update([
	// 	// 		'tanggal_pembayaran'=>$request->tanggal_bayar,
	// 	// 		'bulan_pembayaran'=>$bulan,
	// 	// 		'tahun_pembayaran'=>$tahun,
	// 	// 		'harga_pembayaran'=>$harga_pembayaran
	// 	// 	]);
	// 	// 	DB::commit();
	// 	// 	return response()->json(['status'=>'true', 'message'=>'Pembayaran berhasil disimpan !!']);
	// 	// } catch (\Exception $e) {
	// 	// 	DB::rollBack();
	// 	// 	Log::error($e);
	// 	// 	return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
	// 	// }
	// }
	public function delete_pembayaran(Request $request, $id_bayar)
	{
		try {
			DB::beginTransaction();
			if ($request->action_bayar == 'delete') {
				if ($request->jenis_bayar == 'kos') {
					DB::table('pembayaran')->where('jenis_pembayaran','kos')->where('id_bayar',$id_bayar)->update([
						'id_bayar'=>NULL,
						'tanggal_pembayaran'=>NULL
					]);
					Bayar::where('id_bayar',$id_bayar)->delete();
				}else{
					DB::table('pembayaran')->where('jenis_pembayaran','lain-lain')->where('id_bayar',$id_bayar)->delete();
					Bayar::where('id_bayar',$id_bayar)->delete();
				}
				$currentTime = time();
				$tenggatTimestamp = $currentTime + 600;
				$tenggat = date('Y-m-d H:i:s', $tenggatTimestamp);
				$cek_bayar = Penyewaan::leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
				->where('pembayaran.id_bayar','!=',NULL)
				->where('pembayaran.jenis_pembayaran','kos')
				->where('pembayaran.id_penyewaan',$request->id_penyewaan)
				->orderBy('pembayaran.id_pembayaran','ASC')
				->first();
				if (empty($cek_bayar)) {
					Penyewaan::where('id_penyewaan',$request->id_penyewaan)->update([
						'tenggat'=>$tenggat,
						'status_penyewaan'=>'P'
					]);
				}
			}else{
				$bayar = Bayar::where('id_bayar',$id_bayar)->first();
				$bayar -> status_bayar = 'Sudah Bayar';
				$bayar -> save();
				DB::table('pembayaran')->where('jenis_pembayaran','kos')->where('id_bayar',$id_bayar)
				->update([
					'tanggal_pembayaran'=>$bayar->tanggal_bayar
				]);
				Penyewaan::where('id_penyewaan',$request->id_penyewaan)->update([
					'status_penyewaan'=>'A',
					'tenggat'=>NULL
				]);
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Pembayaran berhasil dikonfirmasi !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function laporan(Request $request)
	{
		$result = Bayar::getLaporanBayar($request);
		$data = $result['data'];
		$tagihan = $result['tagihan'];
		return view('page.super_admin.laporan.pembayaran.index',compact('data','tagihan'))->with('label_cabang', $this->label_cabang);
	}
	public function laporan_export(Request $request)
	{
		$result = Bayar::getLaporanBayar($request);
		$data = $result['data'];
		$tagihan = $result['tagihan'];
		if ($request->type == 'PDF') {
			$label_cabang = $this->label_cabang;
			$pdf=PDF::loadview('page.super_admin.laporan.pembayaran.export',compact('data','tagihan','label_cabang'))->setPaper('A4','landscape');
			return $pdf->stream();
		}else{
			return view('page.super_admin.laporan.pembayaran.export',compact('data','tagihan'))->with('label_cabang', $this->label_cabang);
		}
	}
	public function saveToken(Request $request)
	{
		auth()->user()->update(['device_token'=>$request->token]);
		return response()->json(['token saved successfully.']);
	}
	public function invoice($id_bayar)
	{
		$result = Bayar::getInvoice($id_bayar);
		$data = $result['data'];
		$pembayaran = $result['pembayaran'];
		$pdf=PDF::loadview('page.invoice.index',compact('data','pembayaran'))->setPaper('A4','potrait');
		$pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
		// return $pdf->stream();
		return $pdf->download('Invoice #'.$data[0]->kode_bayar.'.pdf');
	}
}

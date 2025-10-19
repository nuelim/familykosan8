<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendMail;
use App\Models\Fasilitas;
use App\Models\Penyewaan;
use App\Models\Kamar;
use Carbon\Carbon;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;
use PDF;
use DataTables;

class PenyewaanController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}
	public function index(Request $request)
	{
		$data = Penyewaan::getPenyewaan($request);
		$kamar = Penyewaan::getKamar($request);
		$cabang = Cabang::where('status_cabang','A');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$cabang->where('kode_cabang',$request->access);
		}else{
			$cabang->whereIn('id_cabang',session('site'));
		}
		$cabang = $cabang->get();

		if ($request->ajax()) {
			$data = Penyewaan::getPenyewaan($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_penyewaan.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_penyewaan.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.penyewaan.index',compact('kamar','data','cabang'))->with('label_cabang', $this->label_cabang);
	}
	public function get_penghuni(Request $request)
	{
		$data = Penyewaan::getPenghuni($request);
		return response()->json($data);
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_user' => 'required',
			'id_kamar' => 'required',
			'tanggal_penyewaan' => 'required'
			// 'tanggal_selesai' => 'required',
			// 'rentang_bayar' => 'required',
			// 'checklist' => 'required'
		];
		$validateMessage += [
			'id_user.required' => 'Nama Penghuni harus dimasukkan.',
			'id_kamar.required' => 'Kamar Kos harus dipilih.',
			'tanggal_penyewaan.required' => 'Tanggal Mulai Sewa harus diisi.'
			// 'tanggal_selesai.required' => 'Tanggal Akhir Sewa harus diisi.',
			// 'rentang_bayar.required' => 'Rentang Pembayaran harus dipilih.',
			// 'checklist.required' => 'Wajib Checklist.'
		];
		if (Auth::user()->level != 'Penghuni') {
			$validateRules += [
				'tanggal_selesai' => 'required',
				'checklist' => 'required'
			];
			$validateMessage += [
				'tanggal_selesai.required' => 'Tanggal Akhir Sewa harus diisi.',
				'checklist.required' => 'Wajib Checklist.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$result = Penyewaan::cekValidasiPenyewaan($request);
			$kamar = $result['kamar'];
			$penghuni = $result['penghuni'];
			$cabang_kamar = $result['cabang_kamar'];
			$cabang_user = $result['cabang_user'];
			$nama_penghuni = $result['nama_penghuni'];
			if (!empty($kamar)) {
				return response()->json(['status'=>'warning','message'=>'Kamar yang anda pilih sudah berpenghuni.']);
			}
			if (!empty($penghuni)) {
				return response()->json(['status'=>'warning','message'=>'Penghuni anda masih aktif dalam Penyewaan Kos.']);
			}
			if (Auth::user()->level != 'Penghuni') {
				if ($cabang_user->id_cabang != $cabang_kamar->id_cabang) {
					return response()->json(['status'=>'warning','message'=>'Penghuni yang anda pilih tidak sesuai dengan Cabang kamar Kos yang dipilih.']);
				}
			}
			if (Auth::user()->level == 'Penghuni') {
				$tanggal_penyewaan = $request->tanggal_penyewaan;
				$tanggal_selesai = date('Y-m-d', strtotime('+1 month', strtotime($tanggal_penyewaan)));
				// list($tanggal_penyewaan, $tanggal_selesai) = explode(' - ', $date);
				DB::table('biodata')->where('id_user',Auth::user()->id)
				->update([
					'id_cabang'=>$cabang_kamar->id_cabang
				]);
			}else{
				$tanggal_penyewaan = $request->tanggal_penyewaan;
				$tanggal_selesai = $request->tanggal_selesai;
			}
			$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			$shuffle  = substr(str_shuffle($karakter), 0, 8);
			$tanggalMulaiCarbon = Carbon::parse($tanggal_penyewaan);
			$tanggalSelesaiCarbon = Carbon::parse($tanggal_selesai);
			$selisihBulan = $tanggalMulaiCarbon->diffInMonths($tanggalSelesaiCarbon);
			if ($selisihBulan == '0') {
				return response()->json(['status'=>'warning','message'=>'Penyewaan Kos minimal dengan 1 Bulan.']);
			}
			if (Auth::user()->level == 'Penghuni') {
				$text = 'Penyewaan berhasil dibuat, silahkan lakukan pembayaran awal kosan anda.';
				$route = route('penghuni.dashboard');
				$status_penyewaan = 'P';
			}else{
				$text = 'Penyewaan Kos berhasil ditambahkan !!';
				$route = route('index.penyewaan');
				$status_penyewaan = 'A';
			}
			if (Auth::user()->level == 'Penghuni') {
				$currentTime = time();
				$tenggatTimestamp = $currentTime + 600;
				$tenggat = date('Y-m-d H:i:s', $tenggatTimestamp);
			} else {
				$tenggat = NULL;
			}
			// if ($selisihBulan < $request->rentang_bayar) {
			// 	return response()->json(['status'=>'warning','message'=>'Rentang Bayar dan Akhir Penyewaan tidak sesuai.']);
			// }
			$data = New Penyewaan();
			$data -> id_user = $request->id_user;
			$data -> id_kamar = $request->id_kamar;
			$data -> kode_penyewaan = $shuffle;
			$data -> tanggal_penyewaan = $tanggal_penyewaan;
			$data -> tanggal_selesai = $tanggal_selesai;
			$data -> status_penyewaan = $status_penyewaan;
			$data -> rentang_bayar = '1';
			$data -> catatan_penyewaan = $request->catatan_penyewaan;
			$data -> tenggat = $tenggat;
			$data -> created_by = Auth::user()->id;
			$data -> updated_by = Auth::user()->id;
			$data -> save();

			Kamar::where('id_kamar',$request->id_kamar)->update([
				'status_kamar'=>'Terpakai'
			]);

			if ($nama_penghuni->email != NULL) {
				$details = [
					'status'=>'Penyewaan Baru',
					'subject'=>'Penyewaan Kos',
					'nama_penghuni'=>$nama_penghuni->name,
					'nomor_kamar'=>$cabang_kamar->nomor_kamar,
					'harga_kamar'=>$cabang_kamar->harga_kamar,
					'tanggal_penyewaan'=>$request->tanggal_penyewaan
				// 'rentang_bayar'=>$request->rentang_bayar
				];
				\Mail::to($nama_penghuni->email)->send(new \App\Mail\SendMail($details));
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>$text,'redirect'=>$route]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_penyewaan)
	{
		$data = Penyewaan::getEditPenyewaan($id_penyewaan);
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'tanggal_selesai' => 'required',
			'status_penyewaan' => 'required',
			'checklist' => 'required'
		];
		$validateMessage += [
			'tanggal_selesai.required' => 'Tanggal Akhir Sewa harus diisi.',
			'status_penyewaan.required' => 'Status Penyewaan harus dipilih.',
			'checklist.required' => 'Wajib Checklist.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$result = Penyewaan::cekValidasiPenyewaan($request);
			$kamar = $result['kamar'];

			$data = Penyewaan::where('id_penyewaan',$request->id_penyewaan)->first();
			// if ($data->status_penyewaan == 'A') {
				$tanggalMulaiCarbon = Carbon::parse($data->tanggal_penyewaan);
				$tanggalSelesaiCarbon = Carbon::parse($request->tanggal_selesai);
				$selisihBulan = $tanggalMulaiCarbon->diffInMonths($tanggalSelesaiCarbon);
				if ($selisihBulan == '0') {
					return response()->json(['status'=>'warning','message'=>'Penyewaan Kos minimal dengan 1 Bulan.']);
				}
				$kamar_sebelumnya = $data->id_kamar;
				$id_kamar_baru = $request->id_kamar;

				$history = '';

				if ($kamar_sebelumnya != $id_kamar_baru) {
					$data->id_kamar = $id_kamar_baru;

					$kamar_awal = Kamar::where('id_kamar', $kamar_sebelumnya)->first();
					$kamar_pindah = Kamar::where('id_kamar', $id_kamar_baru)->first();

					if (!empty($kamar)) {
						return response()->json(['status'=>'warning','message'=>'Kamar yang anda pilih sudah berpenghuni.']);
					}

					$kamar_awal -> status_kamar = 'Belum Terpakai';
					$kamar_awal -> save();
					$kamar_pindah -> status_kamar = 'Terpakai';
					$kamar_pindah -> save();
					$history = ' / Pindah kamar dari: Kamar ' . $kamar_awal->nomor_kamar 
					. ' ke '
					. ' Kamar ' . $kamar_pindah->nomor_kamar .' pada tanggal '.tanggal_indonesia(date('Y-m-d'));
				}
    			// dd($kamar_sebelumnya . ' / ' . $id_kamar_baru);
				$data -> tanggal_selesai = $request->tanggal_selesai;
				$data -> status_penyewaan = $request->status_penyewaan;
				$data -> catatan_penyewaan = $request->catatan_penyewaan.$history;
				$data -> created_by = Auth::user()->id;
				$data -> updated_by = Auth::user()->id;
				$data -> save();
				if ($request->status_penyewaan == 'I') {
					Kamar::where('id_kamar',$data->id_kamar)->update([
						'status_kamar'=>'Belum Terpakai'
					]);
					User::where('id',$data->id_user)->update([
						'status_user'=>'Non Aktif'
					]);
				}
			//}else{
			//	return response()->json(['status'=>'warning', 'message'=>'Penyewaan Kos telah Non Aktif, anda tidak dapat merubah data Penyewaan.']);
			//}
			$route = route('index.penyewaan');
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Penyewaan Kos berhasil diubah !!','redirect'=>$route]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_penyewaan)
	{
		try {
			DB::beginTransaction();
			$data = Penyewaan::where('id_penyewaan',$id_penyewaan)->first();

			// --- TAMBAHAN UNTUK MENGHAPUS RELASI ---
			
			// 1. Ambil semua data pembayaran terkait
			$pembayaran_records = DB::table('pembayaran')->where('id_penyewaan', $id_penyewaan)->get();

			// 2. Ambil semua id_bayar yang unik (jika ada) dari pembayaran tersebut
			$id_bayar_list = $pembayaran_records->pluck('id_bayar')->filter()->unique();

			// 3. Hapus data dari tabel 'bayar' terlebih dahulu
			if ($id_bayar_list->isNotEmpty()) {
				DB::table('bayar')->whereIn('id_bayar', $id_bayar_list)->delete();
			}

			// 4. Hapus data dari tabel 'pembayaran'
			DB::table('pembayaran')->where('id_penyewaan', $id_penyewaan)->delete();
			
			// --- AKHIR TAMBAHAN ---

			Kamar::where('id_kamar',$data->id_kamar)->update([
				'status_kamar'=>'Belum Terpakai'
			]);
			//User::where('id',$data->id_user)->update([
			//	'status_user'=>'Non Aktif'
			//]);
			
			$data -> delete(); // <-- Baris ini sekarang aman untuk dijalankan
			
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Penyewaan berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function detail_penyewan($kode_penyewaan, $id_penyewaan)
	{
		Penyewaan::cekTagihanDouble($kode_penyewaan,$id_penyewaan);
		$result = Penyewaan::getDetailPenyewaan($kode_penyewaan,$id_penyewaan);
		$data = $result['data'];
		$tagihan_belum_bayar = $result['tagihan_belum_bayar'];
		$tagihan_sudah_bayar = $result['tagihan_sudah_bayar'];
		$pembayaran_belum_dikonfirmasi = $result['pembayaran_belum_dikonfirmasi'];
		$pembayaran_lainnya = $result['pembayaran_lainnya'];
		$reminder = $result['reminder'];
		return view('page.super_admin.penyewaan.view.index',compact('data','tagihan_belum_bayar','tagihan_sudah_bayar','pembayaran_belum_dikonfirmasi','pembayaran_lainnya','reminder'));
	}
	public function laporan(Request $request)
	{
		$data = Penyewaan::getLaporan($request);
		return view('page.super_admin.laporan.penyewaan.index',compact('data'))->with('label_cabang', $this->label_cabang);
	}
	public function laporan_export(Request $request)
	{
		$data = Penyewaan::getLaporan($request);
		if ($request->type == 'PDF') {
			$label_cabang = $this->label_cabang;
			$pdf = PDF::loadview('page.super_admin.laporan.penyewaan.export',compact('data','label_cabang'))->setPaper('A4','landscape');
			return $pdf->stream();
		}else{
			return view('page.super_admin.laporan.penyewaan.export',compact('data'))->with('label_cabang', $this->label_cabang);
		}
	}
	public function reminder_send(Request $request)
	{
		try {
			DB::beginTransaction();
			$email_penghuni = Penyewaan::reminderSend($request);
			$pilih_tagihan = $request->pilih_tagihan;
			if (empty($pilih_tagihan)) {
				return response()->json(['status'=>'warning', 'message'=>'Minimal 1 tagihan harus dipilih.']);
			}
			if ($email_penghuni->email != NULL) {
				if (!empty($pilih_tagihan)) {
					foreach ($request->pilih_tagihan as $key => $value) {
						DB::table('pembayaran')
						->where('id_penyewaan',$request->id_penyewaan)
						->where('id_pembayaran',$request->id_pembayaran[$key])
						->update([
							'status_reminder'=>'1'
						]);
					}
					DB::table('penyewaan_reminder')->insert([
						'id_penyewaan'=>$request->id_penyewaan,
						'tanggal_reminder'=>date('Y-m-d'),
						'text_pesan'=>$request->reminderTextContent
					]);
					$details = [
						'status'=>'Reminder',
						'subject'=>'Reminder Pembayaran Kos',
						'isi_reminder'=>$request->reminderTextContent
					];
					\Mail::to($email_penghuni->email)->send(new \App\Mail\SendMail($details));
				}
			}else{
				return response()->json(['status'=>'warning', 'message'=>'Email kosong, tidak terdapat email Penghuni.']);
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Reminder berhasil terkirim !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function penyewaan_reminder(Request $request)
	{
		$reminder = DB::table('reminder')->limit('1')->get();
		$data = Penyewaan::getPenyewaanReminder($request);
		if ($request->ajax()) {
			$data = Penyewaan::getPenyewaanReminder($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="'.route('detail_penyewan',['kode_penyewaan'=>$data->kode_penyewaan,'id_penyewaan'=>$data->id_penyewaan,'access'=>request()->input('access', session('access', null))]).'" more_id="'.$data->id_penyewaan.'" class="btn btn-outline-primary rounded-pill btn-sm"><i class="fa fa-eye"></i></a> ';
				$button .= '<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal_reminder'.$data->id_penyewaan.'" more_id="'.$data->id_penyewaan.'" class="btn btn-outline-danger rounded-pill btn-sm"><i class="bx bx-envelope"></i></a>';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.penyewaan_reminder.index',compact('data','reminder'))->with('label_cabang', $this->label_cabang);
	}
	public function riwayat_reminder(Request $request)
	{
		if ($request->ajax()) {
			$data = Penyewaan::getRiwayatReminder($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" title="Detail Pesan" class="btn btn-primary rounded-pill btn-sm view" more_id="'.$data->id_penyewaan_reminder.'"><i class="fa fa-eye"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_penyewaan_reminder.'" class="btn btn-danger rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a>';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.penyewaan_reminder.riwayat')->with('label_cabang', $this->label_cabang);
	}
	public function get_edit_riwayat_reminder($id_penyewaan_reminder)
	{
		$data = Penyewaan::getEditRiwayatReminder($id_penyewaan_reminder);
		return response()->json($data);
	}
	public function delete_reminder($id_penyewaan_reminder)
	{
		try {
			DB::beginTransaction();
			DB::table('penyewaan_reminder')->where('id_penyewaan_reminder',$id_penyewaan_reminder)->delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Riwayat Pesan Reminder berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function hapus_tenggat()
	{
		
		$data = Penyewaan::where('tenggat','<',date('Y-m-d H:i:s'))
		->where('tenggat','!=',NULL)
		->where('status_penyewaan','P')
		->get();
		try {
			DB::beginTransaction();
			foreach ($data as $result) {
				Penyewaan::where('id_penyewaan',$result->id_penyewaan)->delete();
				Kamar::where('id_kamar',$result->id_kamar)->update([
					'status_kamar'=>'Belum Terpakai'
				]);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
		}
		if (Auth::user()) {
			if (Auth::user()->level == 'Penghuni') {
				$location = route('penghuni.dashboard');
			}else if(Auth::user()->level == 'Super Admin'){
				$location = route('index.dashboard');
			}else{
				$location = route('operator.dashboard');
			}
		}else{
			$location = '';
		}
		return response()->json(['data'=>$data,'location'=>$location]);	
	}
}
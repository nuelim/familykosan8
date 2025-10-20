<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kamar;
use App\Models\Bayar;
use App\Models\Fasilitas;
use App\Models\Cabang;
use File;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$results = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			DB::raw('SUM(CASE WHEN pembayaran.id_bayar IS NOT NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN kamar.harga_kamar ELSE 0 END ELSE 0 END) AS kos'),
			DB::raw('SUM(CASE WHEN pembayaran.id_bayar IS NOT NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "lain-lain" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS lain'),
			// DB::raw('SUM(pembayaran.harga_pembayaran) as total_pembayaran'),
			DB::raw('MONTH(bayar.tanggal_bayar) as month')
		);
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$results->where('cabang.kode_cabang',$request->access);
		}else{
			$results->whereIn('kamar.id_cabang',session('site'));
		}
		$results = $results->groupBy('month')
		->orderBy('month')
		->whereYear('bayar.tanggal_bayar',date('Y'))
		->where('bayar.status_bayar','Sudah Bayar')
		->get();

		$data = [];
		for ($month = 1; $month <= 12; $month++) {
			$monthLabel = date('F', mktime(0, 0, 0, $month, 1));
			$data['label'][] = $monthLabel;
			$resultForMonth = $results->firstWhere('month', $month);
			$data['data']['kos'][] = $resultForMonth ? $resultForMonth->kos : 0;
			$data['data']['lain'][] = $resultForMonth ? $resultForMonth->lain : 0;
			// $data['data']['total_pembayaran'][] = $resultForMonth ? $resultForMonth->total_pembayaran : 0;
		}
		$data['chart_data'] = json_encode($data);
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$user->where('cabang.kode_cabang',$request->access);
		}else{
			$user->whereIn('biodata.id_cabang',session('site'));
		}
		$operator = clone $user;
		$operator = $operator->where('users.level','Operator')
		->count();
		$penghuni = clone $user;
		$penghuni = $penghuni->where('users.level','Penghuni')
		->count();
		$kamar_result = Kamar::leftJoin('cabang','cabang.id_cabang','=','kamar.id_cabang');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$kamar_result->where('cabang.kode_cabang',$request->access);
		}else{
			$kamar_result->whereIn('kamar.id_cabang',session('site'));
		}
		$semua_kamar = clone $kamar_result;
		$semua_kamar = $semua_kamar->count();
		$kamar_tersedia = clone $kamar_result;
		$kamar_tersedia = $kamar_tersedia->where('kamar.status_kamar','Belum Terpakai')->count();
		$fasilitas = Fasilitas::getFasilitas();
		$cabang = Cabang::count();
		if ($kamar_tersedia == '0') {
			$warna_text_kamar = '';
		}else{
			$warna_text_kamar = 'text-danger';
		}
		return view('page.super_admin.dashboard.index',$data,compact('operator','penghuni','semua_kamar','kamar_tersedia','fasilitas','cabang','warna_text_kamar'));
	}
	public function myprofil()
	{
		$data = User::getMyProfil();
		return view('page.myprofil.index',compact('data'));
	}
	public function update_profil(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'ponsel' => 'required'
		];
		$validateMessage += [
			'email.required' => 'Email harus diisi.',
			'ponsel.required' => 'No. HP harus diisi.'
		];
		if (Auth::user()->level == 'Super Admin') {
			$validateRules += [
				'email' => 'required',
				'tempat_lahir' => 'required',
				'tgl_lahir' => 'required',
				'alamat' => 'required'
			];
			$validateMessage += [
				'name.required' => 'Nama harus diisi.',
				'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
				'tgl_lahir.required' => 'Tanggal Lahir harus diisi.',
				'alamat.required' => 'Alamat harus diisi.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->where('users.id',Auth::user()->id)->first();
		if ($user->email != $request->email) {
			$request->validate([
				'email' => 'unique:users,email'
			],[
				'email.unique' => 'Email yang anda masukkan sudah terdaftar.',
			]);
		}
		if ($user->ponsel != $request->ponsel) {
			$request->validate([
				'ponsel' => 'unique:biodata,ponsel'
			],[
				'ponsel.unique' => 'No. HP yang anda masukkan sudah terdaftar.',
			]);
		}
		try {
			DB::beginTransaction();
			$data = User::where('id',Auth::user()->id)->first();
			$data -> name = $request->name;
			if (Auth::user()->level == 'Super Admin') {
				$data -> email = $request->email;
			}
			if ($request->password != '') {
				$data -> password = hash::make($request->password);
			}
			$data -> updated_by = Auth::user()->id;
			$data -> save();

			if (!empty($request->file('foto'))) {
				$ambil=$request->file('foto');
				$name=$ambil->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $name;
				$ambil->move(\base_path()."/public/foto_profil", $namaFileBaru);
				$berkas = public_path("foto_profil/".$request->fotoLama);
				File::delete($berkas);
			}else{
				$namaFileBaru = $request->fotoLama;
			}
			DB::table('biodata')->where('id_user',Auth::user()->id)->update([
				'tgl_lahir'=>$request->tgl_lahir,
				'tempat_lahir'=>$request->tempat_lahir,
				'ponsel'=>$request->ponsel,
				'alamat'=>$request->alamat,
				'foto'=>$namaFileBaru
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Profil berhasil diperbarui !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_notifikasi(Request $request)
	{
		$data = Bayar::getNotifikasi($request);
		return response()->json($data);
	}
	public function save_reminder(Request $request)
	{
		try {
			DB::beginTransaction();
			DB::table('reminder')->where('id','1')->update([
				'pesan_reminder'=>$request->pesan_reminder
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'v']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_reminder(Request $request)
	{
		$data = Bayar::getReminder($request);
		return response()->json($data);
	}
}

<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use File;
use Exception;
use DataTables;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}
	public function index(Request $request, $level)
	{
		if ($request->ajax()) {
			$data = User::getUser($request, $level);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		$cabang = Cabang::where('status_cabang','A');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$cabang->where('kode_cabang',$request->access);
		}else if (Auth::user()->level == 'Operator' && empty($request->access)) {
			$cabang->whereIn('id_cabang',session('site'));
		}
		$cabang = $cabang->get();
		if ($level == 'operator') {
			return view('page.super_admin.user.operator.index',compact('cabang','level'))->with('label_cabang', $this->label_cabang);
		}elseif ($level == 'penghuni') {
			return view('page.super_admin.user.penghuni.index',compact('cabang','level'))->with('label_cabang', $this->label_cabang);
		}
	}
	public function save_operator(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required|unique:users,email',
			'password' => 'required',
			'id_cabang' => 'required',
			'status_user' => 'required',
			'ponsel' => 'required|unique:biodata,ponsel'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'email.required' => 'Email harus diisi.',
			'email.unique' => 'Email yang digunakan sudah terdaftar.',
			'password.required' => 'Password harus diisi.',
			'id_cabang.required' => 'Cabang harus dipilih.',
			'status_user.required' => 'Status harus dipilih.',
			'ponsel.required' => 'No. HP harus diisi.',
			'ponsel.unique' => 'No. HP yang digunakan sudah terdaftar.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			// $date = new \DateTime($request->tgl_lahir);
			// $password = $date->format('Ymd');
			$password = $request->name;

			$data = New User();
			$data -> name = $request->name;
			$data -> email = $request->email;
			$data -> password = hash::make($request->password);
			$data -> level = 'Operator';
			$data -> status_user = $request->status_user;
			$data -> created_by = Auth::user()->id;
			$data -> save();

			DB::table('biodata')->insert([
				'id_user'=>$data->id,
				'id_cabang'=>$request->id_cabang,
				'nama_lengkap' => $request->name,
				'alamat'=>$request->alamat,
				'ponsel'=>$request->ponsel
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'User Operator berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit_operator($id)
	{
		$data = User::getEditOperator($id);
		return response()->json($data);
	}
	public function update_operator(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required',
			'id_cabang' => 'required',
			'status_user' => 'required',
			'ponsel' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'email.required' => 'Email harus diisi.',
			'id_cabang.required' => 'Cabang harus dipilih.',
			'status_user.required' => 'Status harus dipilih.',
			'ponsel.required' => 'No. HP harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->where('users.id',$request->id_user)->first();
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
			$data = User::where('id',$request->id_user)->first();
			$data -> name = $request->name;
			$data -> email = $request->email;
			if ($request->password != '') {
				$data -> password = hash::make($request->password);
			}
			$data -> status_user = $request->status_user;
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			DB::table('biodata')->where('id_user',$request->id_user)->update([
				'id_cabang'=>$request->id_cabang,
				'ponsel'=>$request->ponsel
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'User Operator berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id)
	{
		try {
			DB::beginTransaction();
			$data = User::where('id',$id)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'User berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	// penghuni
	public function save_penghuni(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			// 'email' => 'required|unique:users,email',
			'password' => 'required',
			'status_user' => 'required',
			'nik' => 'required',
			'tempat_lahir' => 'required',
			'tgl_lahir' => 'required',
			'ponsel' => 'required|unique:biodata,ponsel',
			'no_darurat' => 'required',
			'alamat' => 'required',
			'foto' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			// 'email.required' => 'Email harus diisi.',
			// 'email.unique' => 'Email yang digunakan sudah terdaftar.',
			'password.required' => 'Password harus diisi.',
			'status_user.required' => 'Status harus dipilih.',
			'nik.required' => 'NIK harus diisi.',
			'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
			'tgl_lahir.required' => 'Tanggal Lahir harus diisi.',
			'ponsel.required' => 'No. HP harus diisi.',
			'ponsel.unique' => 'No. HP yang digunakan sudah terdaftar.',
			'no_darurat.required' => 'No. Darurat harus diisi.',
			'alamat.required' => 'Alamat harus diisi.',
			'foto.required' => 'Foto KTP harus diupload.'
		];
		if (Auth::user()) {
			$validateRules += [
				'id_cabang' => 'required'
			];
			$validateMessage += [
				'id_cabang.required' => 'Cabang harus dipilih.'
			];
		}elseif (empty(Auth::user())) {
			$validateRules += [
				'email' => 'required|unique:users,email'
			];
			$validateMessage += [
				'email.required' => 'Email harus diisi.',
				'email.unique' => 'Email yang digunakan sudah terdaftar.'
			];
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$date = new \DateTime($request->tgl_lahir);
			$password = $date->format('Ymd');

			$data = New User();
			$data -> name = $request->name;
			$data -> email = $request->email;
			$data -> password = hash::make($request->password);
			$data -> level = 'Penghuni';
			$data -> status_user = $request->status_user;
			if (Auth::user()) {
				$data -> created_by = Auth::user()->id;
			}
			$data -> save();

			$ambil=$request->file('foto');
			$name=$ambil->getClientOriginalName();
			$namaFileBaru = uniqid();
			$namaFileBaru .= $name;
			$ambil->move(\base_path()."/public/foto_ktp", $namaFileBaru);

			DB::table('biodata')->insert([
				'id_user'=>$data->id,
				'id_cabang'=>$request->id_cabang,
				'nama_lengkap' => $request->name,
				'nik'=>$request->nik,
				'tgl_lahir'=>$request->tgl_lahir,
				'tempat_lahir'=>$request->tempat_lahir,
				'ponsel'=>$request->ponsel,
				'alamat'=>$request->alamat,
				'foto'=>$namaFileBaru,
				'no_darurat'=>$request->no_darurat
			]);
			if (Auth::user()) {
				$text = 'User Penghuni berhasil ditambahkan !!';
			}else{
				$text = 'Register Berhasil, silahkan login dan nikmati kenyamanan serta fasilitas Kos !!';
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>$text]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit_penghuni($id)
	{
		$data = User::getEditPenghuni($id);
		return response()->json($data);
	}
	public function update_penghuni(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'name' => 'required',
			'email' => 'required',
			'status_user' => 'required',
			'id_cabang' => 'required',
			'nik' => 'required',
			'tempat_lahir' => 'required',
			'tgl_lahir' => 'required',
			'ponsel' => 'required',
			'no_darurat' => 'required',
			'alamat' => 'required'
		];
		$validateMessage += [
			'name.required' => 'Nama harus diisi.',
			'email.required' => 'Email harus diisi.',
			'status_user.required' => 'Status harus dipilih.',
			'id_cabang.required' => 'Cabang harus dipilih.',
			'nik.required' => 'NIK harus diisi.',
			'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
			'tgl_lahir.required' => 'Tanggal Lahir harus diisi.',
			'ponsel.required' => 'No. Telp harus diisi.',
			'no_darurat.required' => 'No. Darurat harus diisi.',
			'alamat.required' => 'Alamat harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->where('users.id',$request->id_user)->first();
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
			$date = new \DateTime($request->tgl_lahir);
			$password = $date->format('Ymd');

			$data = User::where('id',$request->id_user)->first();
			$data -> name = $request->name;
			$data -> email = $request->email;
			if ($request->password != '') {
				$data -> password = hash::make($request->password);
			}
			$data -> status_user = $request->status_user;
			$data -> updated_by = Auth::user()->id;
			$data -> save();

			if (!empty($request->file('foto'))) {
				$ambil=$request->file('foto');
				$name=$ambil->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $name;
				$ambil->move(\base_path()."/public/foto_ktp", $namaFileBaru);
				$berkas = public_path("foto_ktp/".$request->fotoLama);
				File::delete($berkas);
			}else{
				$namaFileBaru = $request->fotoLama;
			}

			DB::table('biodata')->where('id_user',$request->id_user)->update([
				'id_cabang'=>$request->id_cabang,
				'nik'=>$request->nik,
				'tgl_lahir'=>$request->tgl_lahir,
				'tempat_lahir'=>$request->tempat_lahir,
				'ponsel'=>$request->ponsel,
				'alamat'=>$request->alamat,
				'foto'=>$namaFileBaru,
				'no_darurat'=>$request->no_darurat
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'User Penghuni berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}

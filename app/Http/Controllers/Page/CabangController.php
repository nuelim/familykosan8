<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use DataTables;
use Illuminate\Support\Facades\Crypt;

class CabangController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = Cabang::all();
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_cabang.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_cabang.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.cabang.index');
	}
	public function get_id_cabang()
	{
		$data = Cabang::all();
		// ->where('users.id',Auth::user()->id)
		// ->first();
		return response()->json($data);
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_cabang' => 'required',
			'alamat_cabang' => 'required',
			'link_cabang' => 'required',
			'status_cabang' => 'required'
		];
		$validateMessage += [
			'nama_cabang.required' => 'Nama Cabang harus diisi.',
			'alamat_cabang.required' => 'Alamat harus diisi.',
			'link_cabang.required' => 'Url/Link harus diisi.',
			'status_cabang.required' => 'Status harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$kode_cabang = 'CBG-' . strtoupper(uniqid());

			$data = New Cabang();
			$data -> nama_cabang = $request->nama_cabang;
			$data -> kode_cabang = $kode_cabang;
			$data -> alamat_cabang = $request->alamat_cabang;
			$data -> link_cabang = $request->link_cabang;
			$data -> status_cabang = $request->status_cabang;
			$data -> created_by = Auth::user()->id;
			$data -> save();

			if ($request->id_cabang_add == '') {
				$id_cabang_add = $data->id_cabang;
			}else{
				$id_cabang_add = $request->id_cabang_add.','.$data->id_cabang;
			}
			DB::table('biodata')->join('users','users.id','=','biodata.id_user')
			->where('users.level','Super Admin')
			->update([
				'id_cabang'=>$id_cabang_add
			]);
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Cabang berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_cabang)
	{
		$data = Cabang::where('id_cabang',$id_cabang)->get();
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_cabang' => 'required',
			'alamat_cabang' => 'required',
			'link_cabang' => 'required',
			'status_cabang' => 'required'
		];
		$validateMessage += [
			'nama_cabang.required' => 'Nama Cabang harus diisi.',
			'alamat_cabang.required' => 'Alamat harus diisi.',
			'link_cabang.required' => 'Url/Link harus diisi.',
			'status_cabang.required' => 'Status harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$kode_cabang = 'CBG-' . strtoupper(uniqid());
			
			$data = Cabang::where('id_cabang',$request->id_cabang)->first();
			$data -> nama_cabang = $request->nama_cabang;
			$data -> kode_cabang = $kode_cabang;
			$data -> alamat_cabang = $request->alamat_cabang;
			$data -> link_cabang = $request->link_cabang;
			$data -> status_cabang = $request->status_cabang;
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Cabang berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete(Request $request, $id_cabang)
	{
		try {
			DB::beginTransaction();
			$biodata = User::join('biodata','biodata.id_user','=','users.id')->get();
			$new_id_cabang = null;
			foreach ($biodata as $bio) {
				$cabang_array = explode(',', $bio->id_cabang);
				$cabang_array = array_filter($cabang_array, function($value) use ($id_cabang) {
					return $value != $id_cabang;
				});
				$temp_new_id_cabang = implode(',', $cabang_array);

				if ($temp_new_id_cabang != '') {
					if ($new_id_cabang == null) {
						$new_id_cabang = $temp_new_id_cabang;
					} else {
						$new_id_cabang .= ',' . $temp_new_id_cabang;
					}
				}
			}
			if ($new_id_cabang != null) {
				$new_id_cabang_array = explode(',', $new_id_cabang);
				$new_id_cabang_array = array_unique($new_id_cabang_array);
				$new_id_cabang = implode(',', $new_id_cabang_array);
			}
			DB::table('biodata')->join('users','users.id','=','biodata.id_user')
			->where('users.level','Super Admin')
			->update([
				'id_cabang' => $new_id_cabang
			]);
			$data = Cabang::where('id_cabang',$id_cabang)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Cabang berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function beralih(Request $request, $id_cabang)
	{
		try {
			DB::beginTransaction();
			if ($id_cabang > 0) {
				$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				$shuffle  = substr(str_shuffle($karakter), 0, 8);
				$kode_cabang = Crypt::encrypt($id_cabang.$shuffle);

				$data = Cabang::where('id_cabang',$id_cabang)->first();
				$data -> kode_cabang = $kode_cabang;
				$data -> save();
			}else{
				$kode_cabang = null;

				$data = Cabang::all();
				foreach ($data as $dt) {
					$karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
					$shuffle  = substr(str_shuffle($karakter), 0, 8);
					$md5 = md5($id_cabang);
					$kode = Crypt::encrypt($md5);
					Cabang::where('id_cabang',$dt->id_cabang)->update([
						'kode_cabang'=>$kode
					]);
				}
			}
			$request->session()->forget('access');
			if ($id_cabang > 0) {
				$request->session()->put('access', $kode_cabang);
			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>$kode_cabang]);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}

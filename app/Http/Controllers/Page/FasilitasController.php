<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Log;
use Exception;
use DataTables;

class FasilitasController extends Controller
{
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data=Fasilitas::all();
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_fasilitas.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_fasilitas.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.fasilitas.index');
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_fasilitas' => 'required'
		];
		$validateMessage += [
			'nama_fasilitas.required' => 'Fasilitas harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$data = New Fasilitas();
			$data -> nama_fasilitas = $request->nama_fasilitas;
			$data -> created_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Fasilitas berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_fasilitas)
	{
		$data = Fasilitas::where('id_fasilitas',$id_fasilitas)->get();
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'nama_fasilitas' => 'required'
		];
		$validateMessage += [
			'nama_fasilitas.required' => 'Fasilitas harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$data = Fasilitas::where('id_fasilitas',$request->id_fasilitas)->first();
			$data -> nama_fasilitas = $request->nama_fasilitas;
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Fasilitas berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_fasilitas)
	{
		try {
			DB::beginTransaction();
			$data = Fasilitas::where('id_fasilitas',$id_fasilitas)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Fasilitas berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}

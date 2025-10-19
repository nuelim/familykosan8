<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Aduan;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use DataTables;
use Illuminate\Support\Facades\Crypt;

class AduanController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$data = Aduan::getPenghuniAduan($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_ajuan.'" class="btn btn-info text-white rounded-pill btn-sm view"><i class="fa fa-eye"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_ajuan.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.penghuni.aduan.index')->with('label_cabang', $this->label_cabang);
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'isi' => 'required'
		];
		$validateMessage += [
			'isi.required' => 'Keterangan/Isi Aduan harus diisi.'
		];
		if (isset($request->ajuan)) {
			foreach ($request->ajuan as $key => $value) {
				$validateRules += [
					'ajuan.*.foto_ajuan' => 'required',
				];
				$validateMessage += [
					'ajuan.*.foto_ajuan.required' => 'Foto harus diupload.'
				];
			}
		}
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$id_penyewaan = Penyewaan::where('id_user',Auth::user()->id)->where('status_penyewaan','A')->first();
			if ($id_penyewaan) {
				$data = New Aduan();
				$data -> id_penyewaan = $id_penyewaan->id_penyewaan;
				$data -> isi = $request->isi;
				$data -> created_by = Auth::user()->id;
				$data -> save();
				if (isset($request->ajuan)) {
					foreach ($request->ajuan as $key => $value) {
						$ambil=$value['foto_ajuan'];
						$name=$ambil->getClientOriginalName();
						$namaFileBaru = uniqid();
						$namaFileBaru .= $name;
						$ambil->move(\base_path()."/public/foto_ajuan", $namaFileBaru);
						DB::table('ajuan_detail')->insert([
							'id_ajuan'=>$data->id_ajuan,
							'foto_ajuan'=>$namaFileBaru
						]);
					}
				}
			}else{

			}
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Terimakasih telah membuat aduan, aduan anda akan segera kami cek !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_ajuan)
	{
		$result = Aduan::getEdit($id_ajuan);
		$data = $result['data'];
		$foto = $result['foto'];
		return response()->json(['data'=>$data,'foto'=>$foto]);
	}
	public function delete($id_ajuan)
	{
		try {
			DB::beginTransaction();
			$data = Aduan::where('id_ajuan',$id_ajuan)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Aduan dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
}

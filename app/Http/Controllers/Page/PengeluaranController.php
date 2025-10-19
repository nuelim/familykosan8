<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengeluaran;
use App\Models\Cabang;
use Illuminate\Support\Facades\Log;
use File;
use Exception;
use DataTables;
use PDF;

class PengeluaranController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}
	
	public function index(Request $request)
	{
		$cabang = Cabang::where('status_cabang','A');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$cabang->where('kode_cabang', $request->access);
		} else {
			$cabang->whereIn('id_cabang', session('site'));
		}
		
		$cabang = $cabang->get();
		if ($request->ajax()) {
			$data=Pengeluaran::getPengeluaran($request);
			return DataTables::of($data)
			->addIndexColumn()
			->addColumn('', function($data) {
				$a = '';
				return $a;
			})
			->addColumn('action', function($data) {
				$button = '<a href="javascript:void(0)" more_id="'.$data->id_pengeluaran.'" class="btn btn-success text-white rounded-pill btn-sm edit"><i class="bx bx-edit"></i></a> ';
				$button .= '<a href="javascript:void(0)" more_id="'.$data->id_pengeluaran.'" class="btn btn-danger text-white rounded-pill btn-sm delete"><i class="bx bx-trash"></i></a> ';
				return $button;
			})
			->rawColumns(['action'])
			->make(true);
		}
		return view('page.super_admin.pengeluaran.index',compact('cabang'))->with('label_cabang', $this->label_cabang);
	}
	public function save(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_cabang' => 'required',
			'nominal_pengeluaran' => 'required',
			'tanggal_pengeluaran' => 'required',
			'bukti_pengeluaran' => 'required',
			'keterangan_pengeluaran' => 'required'
		];
		$validateMessage += [
			'id_cabang.required' => 'Cabang harus dipilih.',
			'nominal_pengeluaran.required' => 'Nominal harus diisi.',
			'tanggal_pengeluaran.required' => 'Tanggal harus diisi.',
			'bukti_pengeluaran.required' => 'Butki/Foto harus diupload.',
			'keterangan_pengeluaran.required' => 'Keterangan harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			$ambil=$request->file('bukti_pengeluaran');
			$name=$ambil->getClientOriginalName();
			$namaFileBaru = uniqid();
			$namaFileBaru .= $name;
			$ambil->move(\base_path()."/public/bukti_pengeluaran", $namaFileBaru);
			$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
			$nominal_pengeluaran = preg_replace("/[^aZ0-9]/", "", $request->nominal_pengeluaran);
			$data = New Pengeluaran();
			$data -> id_cabang = $request->id_cabang;
			$data -> nominal_pengeluaran = $nominal_pengeluaran;
			$data -> tanggal_pengeluaran = $request->tanggal_pengeluaran;
			$data -> bukti_pengeluaran = $namaFileBaru;
			$data -> keterangan_pengeluaran = $request->keterangan_pengeluaran;
			$data -> created_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Pengeluaran berhasil ditambahkan !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function get_edit($id_pengeluaran)
	{
		$data = Pengeluaran::getEditPengeluaran($id_pengeluaran);
		return response()->json($data);
	}
	public function update(Request $request)
	{
		$validateRules = [];
		$validateMessage = [];

		$validateRules += [
			'id_cabang' => 'required',
			'nominal_pengeluaran' => 'required',
			'tanggal_pengeluaran' => 'required',
			// 'bukti_pengeluaran' => 'required',
			'keterangan_pengeluaran' => 'required'
		];
		$validateMessage += [
			'id_cabang.required' => 'Cabang harus dipilih.',
			'nominal_pengeluaran.required' => 'Nominal harus diisi.',
			'tanggal_pengeluaran.required' => 'Tanggal harus diisi.',
			// 'bukti_pengeluaran.required' => 'Butki/Foto harus diupload.',
			'keterangan_pengeluaran.required' => 'Keterangan harus diisi.'
		];
		$request->validate($validateRules, $validateMessage);
		try {
			DB::beginTransaction();
			if (!empty($request->file('bukti_pengeluaran'))) {
				$ambil=$request->file('bukti_pengeluaran');
				$name=$ambil->getClientOriginalName();
				$namaFileBaru = uniqid();
				$namaFileBaru .= $name;
				$ambil->move(\base_path()."/public/bukti_pengeluaran", $namaFileBaru);
				$berkas = public_path("bukti_pengeluaran/".$request->bukti_pengeluaranLama);
				File::delete($berkas);
			}else{
				$namaFileBaru = $request->bukti_pengeluaranLama;
			}
			$string = "Suka*()bumi #$^%& Kode ($%^2&^)*(0&*^19.";
			$nominal_pengeluaran = preg_replace("/[^aZ0-9]/", "", $request->nominal_pengeluaran);
			$data = Pengeluaran::where('id_pengeluaran',$request->id_pengeluaran)->first();
			$data -> id_cabang = $request->id_cabang;
			$data -> nominal_pengeluaran = $nominal_pengeluaran;
			$data -> tanggal_pengeluaran = $request->tanggal_pengeluaran;
			$data -> bukti_pengeluaran = $namaFileBaru;
			$data -> keterangan_pengeluaran = $request->keterangan_pengeluaran;
			$data -> updated_by = Auth::user()->id;
			$data -> save();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Pengeluaran berhasil diubah !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function delete($id_pengeluaran)
	{
		try {
			DB::beginTransaction();
			$data = Pengeluaran::where('id_pengeluaran',$id_pengeluaran)->first();
			$data -> delete();
			DB::commit();
			return response()->json(['status'=>'true', 'message'=>'Pengeluaran berhasil dihapus !!']);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error($e);
			return response()->json(['status' => 'false', 'message' => 'Permintaan Data terjadi kesalahan !! [' . $e->getMessage() . ']']);
		}
	}
	public function laporan(Request $request)
	{
		$result = Pengeluaran::getLaporanPengeluaran($request);
		$data = $result['data'];
		return view('page.super_admin.laporan.pengeluaran.index',compact('data'))->with('label_cabang', $this->label_cabang);
	}
	public function laporan_export(Request $request)
	{
		$result = Pengeluaran::getLaporanPengeluaran($request);
		$data = $result['data'];
		$total = $result['total'];
		if ($request->type == 'PDF') {
			$label_cabang = $this->label_cabang;
			$pdf=PDF::loadview('page.super_admin.laporan.pengeluaran.export',compact('data','total','label_cabang'))->setPaper('A4','landscape');
			return $pdf->stream();
		}else{
			return view('page.super_admin.laporan.pengeluaran.export',compact('data','total'))->with('label_cabang', $this->label_cabang);
		}
	}
}

<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Penyewaan;
use App\Models\Cabang;
use Carbon\Carbon;

class HomeController extends Controller
{
	public function index(Request $request)
	{
		$kamar = Kamar::leftJoin('cabang', 'cabang.id_cabang', '=', 'kamar.id_cabang')
		->leftJoin('kamar_foto', 'kamar_foto.id_kamar', '=', 'kamar.id_kamar')
		->where(function ($query) {
			$query->whereNotExists(function ($subquery) {
				$subquery->select(DB::raw(1))
				->from('penyewaan')
				->whereColumn('penyewaan.id_kamar', '=', 'kamar.id_kamar')
				->where('penyewaan.status_penyewaan', '!=', 'I')
				->where('kamar_foto.tipe', 'utama');
			})->orWhereNull('kamar.id_kamar');
		})
		->select(
			DB::raw('kamar.created_at as created_at'),
			DB::raw('kamar.id_kamar as id_kamar'),
			DB::raw('kamar.nomor_kamar as nomor_kamar'),
			DB::raw('cabang.nama_cabang as nama_cabang'),
			DB::raw('kamar.jenis_kamar as jenis_kamar'),
			DB::raw('kamar.harga_kamar as harga_kamar'),
			DB::raw('kamar_foto.foto as foto')
		)
		->groupBy(
			'kamar.id_kamar',
			'kamar.nomor_kamar',
			'kamar.harga_kamar',
			'kamar_foto.foto',
			'kamar.jenis_kamar',
			'kamar.created_at',
			'cabang.nama_cabang'
		)
		->where('kamar_foto.tipe', 'utama');

		if (!empty($request->cabang_search)) {
			$cabangSearch = is_array($request->cabang_search) 
			? $request->cabang_search 
			: [$request->cabang_search];
			$kamar->whereIn('kamar.id_cabang', $cabangSearch);
		}
		if (!empty($request->start)) {
			$start = preg_replace("/[^aZ0-9]/", "", $request->start);
			$end = preg_replace("/[^aZ0-9]/", "", $request->end);
			$kamar->whereBetween('kamar.harga_kamar',[$start,$end]);
		}
		$kamar = $kamar->paginate(10);

		$totalKamar = Kamar::query();
		if (!empty($request->cabang_search)) {
			$totalKamar->whereIn('id_cabang', $cabangSearch);
		}
		if (!empty($request->start)) {
			$start = preg_replace("/[^aZ0-9]/", "", $request->start);
			$end = preg_replace("/[^aZ0-9]/", "", $request->end);
			$kamar->whereBetween('harga_kamar',[$start,$end]);
		}
		$totalKamar = $totalKamar->count();
		$fasilitas = Fasilitas::all();


		$rekomendasi = Penyewaan::join('users', 'users.id', '=', 'penyewaan.id_user')
		->join('biodata', 'biodata.id_user', '=', 'users.id')
		->join('kamar', 'kamar.id_kamar', '=', 'penyewaan.id_kamar')
		->leftJoin('kamar_foto', 'kamar_foto.id_kamar', '=', 'kamar.id_kamar')
		->join('cabang', 'cabang.id_cabang', '=', 'kamar.id_cabang')
		->where('penyewaan.status_penyewaan', 'I')
		->whereIn('penyewaan.id_kamar', function ($query) {
			$query->select('id_kamar')
			->from('penyewaan')
			->where('status_penyewaan', 'I')
			->groupBy('id_kamar');
			// ->havingRaw('COUNT(id_kamar) > ?', [0]);
		})
		->select(
			DB::raw('penyewaan.kode_penyewaan as kode_penyewaan'),
			DB::raw('penyewaan.id_penyewaan as id_penyewaan'),
			DB::raw('users.name as name'),
			DB::raw('kamar.nomor_kamar as nomor_kamar'),
			DB::raw('kamar_foto.foto as foto'),
			DB::raw('kamar.id_kamar as id_kamar'),
			DB::raw('kamar.jenis_kamar as jenis_kamar'),
			DB::raw('cabang.nama_cabang as nama_cabang'),
			DB::raw('kamar.harga_kamar as harga_kamar'),
			DB::raw('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			DB::raw('penyewaan.tanggal_selesai as tanggal_selesai'),
			DB::raw('TIMESTAMPDIFF(DAY, penyewaan.tanggal_penyewaan, penyewaan.tanggal_selesai) as lama_hari'),
			DB::raw('FLOOR(TIMESTAMPDIFF(DAY, penyewaan.tanggal_penyewaan, penyewaan.tanggal_selesai) / 30.44) as lama_bulan'),
			DB::raw('COUNT(penyewaan.id_kamar) as jumlah_disewa')
		)
		->groupBy(
			'penyewaan.kode_penyewaan',
			'penyewaan.id_penyewaan',
			'users.name',
			'kamar.nomor_kamar',
			'cabang.nama_cabang',
			'kamar.harga_kamar',
			'kamar.jenis_kamar',
			'kamar.id_kamar',
			'kamar_foto.foto',
			'penyewaan.tanggal_penyewaan',
			'penyewaan.tanggal_selesai'
		)
		->where('kamar_foto.tipe', 'utama')
		// ->havingRaw('lama_bulan > ?', [0])
		->orderBy('penyewaan.tanggal_penyewaan', 'ASC')
		->get();
		$cabang = Cabang::where('status_cabang', 'A')->get();
		return view('home.home.index',compact('kamar','totalKamar','fasilitas','rekomendasi','cabang'));
	}
	public function view($id_kamar)
	{
		// $kamar = Kamar::paginate(10);
		$result = Kamar::getViewKamar($id_kamar);
		$data = $result['data'];
		$foto = $result['foto'];
		$fasilitas = $result['fasilitas'];
		return view('home.view.index',compact('data','fasilitas','foto'));
	}
}

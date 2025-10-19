<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Penyewaan extends Model
{
    // use HasFactory;
	protected $table="penyewaan";
	protected $primaryKey="id_penyewaan";

	public static function getPenghuni($request)
	{
		// $data = User::join('biodata','biodata.id_user','=','users.id')
		// ->where('users.level','Penghuni')
		// ->where('users.status_user','Aktif')
		// ->get();
		$data = User::join('biodata','biodata.id_user','=','users.id')
		->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang')
		->where('users.level','Penghuni')
		->where('users.status_user','Aktif');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('biodata.id_cabang',session('site'));
		}
		$data = $data->where(function ($query) {
			$query->whereNotExists(function ($subquery) {
				$subquery->select(\DB::RAW(1))
				->from('penyewaan')
				->whereColumn('penyewaan.id_user', '=', 'users.id')
				->where('penyewaan.status_penyewaan','!=','I');
			})->orWhereNull('users.id');
		})
		->get();
		return $data;
	}
	public static function getKamar($request)
	{
		$kamar = Kamar::leftJoin('cabang','cabang.id_cabang','=','kamar.id_cabang');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$kamar->where('cabang.kode_cabang',$request->access);
		}else{
			$kamar->whereIn('kamar.id_cabang',session('site'));
		}
		$kamar = $kamar->where(function ($query) {
			$query->whereNotExists(function ($subquery) {
				$subquery->select(\DB::RAW(1))
				->from('penyewaan')
				->whereColumn('penyewaan.id_kamar', '=', 'kamar.id_kamar')
				->where('penyewaan.status_penyewaan','!=','I');
			})->orWhereNull('kamar.id_kamar');
		})
		// ->get();
		// ->where('kamar.status_kamar','Belum Terpakai')
		->get();
		return $kamar;
	}
	public static function cekValidasiPenyewaan($request)
	{
		$kamar = Penyewaan::leftJoin('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->where('penyewaan.status_penyewaan','!=','I')
		->where('kamar.id_kamar',$request->id_kamar)
		->where('kamar.status_kamar','Terpakai')
		->first();
		$penghuni = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->where('penyewaan.status_penyewaan','!=','I')
		->where('users.id',$request->id_user)
		->first();
		$cabang_kamar = Kamar::select(
			\DB::RAW('id_cabang as id_cabang'),
			\DB::RAW('harga_kamar as harga_kamar'),
			\DB::RAW('nomor_kamar as nomor_kamar')
		)->where('id_kamar',$request->id_kamar)->first();
		$cabang_user = User::join('biodata','biodata.id_user','=','users.id')
		->select(\DB::RAW('biodata.id_cabang as id_cabang'))
		->where('users.id',$request->id_user)
		->first();
		$nama_penghuni = User::select(
			\DB::RAW('name as name'),
			\DB::RAW('email as email')
		)
		->where('users.id',$request->id_user)
		->first();
		return ['kamar'=>$kamar,'penghuni'=>$penghuni,'cabang_kamar'=>$cabang_kamar,'cabang_user'=>$cabang_user,'nama_penghuni'=>$nama_penghuni];
	}
	public static function getPenyewaan($request)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->where('penyewaan.status_penyewaan','!=','I');
		// ->where('penyewaan.status_penyewaan','A');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		if (!empty($request->exp)) {
			$data->whereMonth('penyewaan.tanggal_selesai',date('m'))
			->whereYear('penyewaan.tanggal_selesai',date('Y'));
		}
		$data->select(
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.catatan_penyewaan as catatan_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar'),
			\DB::RAW('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			\DB::RAW('penyewaan.tanggal_selesai as tanggal_selesai'),
			\DB::RAW('COUNT(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN 1 END END) AS jumlah_tagihan'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS total_tagihan')
		)->groupBy('penyewaan.kode_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang','kamar.harga_kamar','penyewaan.tanggal_penyewaan','penyewaan.id_penyewaan','penyewaan.tanggal_selesai','penyewaan.catatan_penyewaan');
		$data = $data->orderBy('penyewaan.tanggal_penyewaan','DESC')
		->get();
		return $data;
	}
	public static function getEditPenyewaan($id_penyewaan)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->leftJoin('kamar_fasilitas','kamar_fasilitas.id_kamar','=','kamar.id_kamar')
		->leftJoin('fasilitas','fasilitas.id_fasilitas','=','kamar_fasilitas.id_fasilitas')
		->where('penyewaan.status_penyewaan','!=','I');
		// ->where('penyewaan.status_penyewaan','A');
		$data->select(
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.rentang_bayar as rentang_bayar'),
			\DB::RAW('penyewaan.status_penyewaan as status_penyewaan'),
			\DB::RAW('penyewaan.catatan_penyewaan as catatan_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('users.email as email'),
			\DB::RAW('biodata.nik as nik'),
			\DB::RAW('biodata.tempat_lahir as tempat_lahir'),
			\DB::RAW('biodata.tgl_lahir as tgl_lahir'),
			\DB::RAW('biodata.ponsel as ponsel'),
			\DB::RAW('biodata.alamat as alamat'),
			\DB::RAW('biodata.no_darurat as no_darurat'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('kamar.id_kamar as id_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar'),
			\DB::RAW('kamar.jenis_kamar as jenis_kamar'),
			\DB::RAW('kamar.keterangan_kamar as keterangan_kamar'),
			\DB::RAW('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			\DB::RAW('penyewaan.tanggal_selesai as tanggal_selesai'),
			\DB::RAW('COUNT(kamar_fasilitas.id_fasilitas) as jumlah_fasilitas')
		)->groupBy('penyewaan.kode_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang','kamar.harga_kamar','penyewaan.tanggal_penyewaan','penyewaan.id_penyewaan','penyewaan.rentang_bayar','penyewaan.status_penyewaan','users.name','users.email','biodata.nik','biodata.tempat_lahir','biodata.tgl_lahir','biodata.ponsel','biodata.alamat','biodata.no_darurat','kamar.jenis_kamar','kamar.keterangan_kamar','penyewaan.catatan_penyewaan','tanggal_selesai','kamar.id_kamar')
		->where('penyewaan.id_penyewaan',$id_penyewaan);
		$data = $data->get();
		return $data;
	}
	public static function getTagihanTiapBulan()
	{
		try {
			DB::beginTransaction();
			// $penyewaan = Penyewaan::join('users', 'users.id', '=', 'penyewaan.id_user')
			// ->join('kamar', 'kamar.id_kamar', '=', 'penyewaan.id_kamar')
			// ->where('users.status_user', 'Aktif')
			// ->where('penyewaan.status_penyewaan', 'A')
			// ->get();
			// foreach ($penyewaan as $sewa) {
			// 	$tahun_awal_sewa = date('Y', strtotime($sewa->tanggal_penyewaan));
			// 	$bulan_awal_sewa = date('n', strtotime($sewa->tanggal_penyewaan));

			// 	// $tahun_sekarang = date('Y');
			// 	// $bulan_sekarang = date('n');

			// 	$tahun_sekarang = date('Y', strtotime($sewa->tanggal_selesai));
			// 	$tanggal_kurang_satu_bulan = date('Y-m-d', strtotime('-1 month', strtotime($sewa->tanggal_selesai)));
			// 	$bulan_sekarang = date('n', strtotime($tanggal_kurang_satu_bulan));

			// 	$jumlah_bulan = $sewa->rentang_bayar;

			// 	for ($tahun = $tahun_awal_sewa; $tahun <= $tahun_sekarang; $tahun++) {
			// 		$bulan_awal = ($tahun == $tahun_awal_sewa) ? $bulan_awal_sewa : 1;
			// 		$bulan_akhir = ($tahun == $tahun_sekarang) ? $bulan_sekarang : 12;

			// 		for ($bulan = $bulan_awal; $bulan <= $bulan_akhir; $bulan++) {
			// 			$bulan_formatted = sprintf("%02d", $bulan);
			// 			$cek_pembayaran = DB::table('pembayaran')
			// 			->join('penyewaan', 'penyewaan.id_penyewaan', '=', 'pembayaran.id_penyewaan')
			// 			->where('pembayaran.bulan_pembayaran', $bulan_formatted)
			// 			->where('penyewaan.id_penyewaan', $sewa->id_penyewaan)
			// 			->where('pembayaran.jenis_pembayaran', 'kos')
			// 			->exists();

			// 			if (!$cek_pembayaran) {
			// 				DB::table('pembayaran')->insert([
			// 					'id_penyewaan' => $sewa->id_penyewaan,
			// 					'jenis_pembayaran' => 'kos',
			// 					'bulan_pembayaran' => $bulan_formatted,
			// 					'tahun_pembayaran' => $tahun,
			// 					'harga_pembayaran' => $sewa->harga_kamar
			// 				]);
			// 			}
			// 		}
			// 	}
			// }
			$penyewaan = Penyewaan::join('users', 'users.id', '=', 'penyewaan.id_user')
			->join('kamar', 'kamar.id_kamar', '=', 'penyewaan.id_kamar')
			->where('users.status_user', 'Aktif')
			->where('penyewaan.status_penyewaan','!=','I')
			->get();

			foreach ($penyewaan as $sewa) {
				$tahun_awal_sewa = date('Y', strtotime($sewa->tanggal_penyewaan));
				$bulan_awal_sewa = date('n', strtotime($sewa->tanggal_penyewaan));

				$tahun_sekarang = date('Y', strtotime($sewa->tanggal_selesai));
				$bulan_sekarang = date('n', strtotime($sewa->tanggal_selesai));

				for ($tahun = $tahun_awal_sewa; $tahun <= $tahun_sekarang; $tahun++) {
					$bulan_awal = ($tahun == $tahun_awal_sewa) ? $bulan_awal_sewa : 1;
					$bulan_akhir = ($tahun == $tahun_sekarang) ? $bulan_sekarang - 1 : 12;

					for ($bulan = $bulan_awal; $bulan <= $bulan_akhir; $bulan++) {
						$bulan_formatted = sprintf("%02d", $bulan);
						$cek_pembayaran = DB::table('pembayaran')
						->join('penyewaan', 'penyewaan.id_penyewaan', '=', 'pembayaran.id_penyewaan')
						->where('pembayaran.bulan_pembayaran', $bulan_formatted)
						->where('penyewaan.id_penyewaan', $sewa->id_penyewaan)
						->where('pembayaran.jenis_pembayaran', 'kos')
						->exists();

						if (!$cek_pembayaran) {
							DB::table('pembayaran')->insert([
								'id_penyewaan' => $sewa->id_penyewaan,
								'jenis_pembayaran' => 'kos',
								'bulan_pembayaran' => $bulan_formatted,
								'tahun_pembayaran' => $tahun,
								'harga_pembayaran' => $sewa->harga_kamar
							]);
						}
					}
				}
			}
				// $jumlah_pembayaran = DB::table('pembayaran')
				// ->where('id_penyewaan', $sewa->id_penyewaan)
				// ->where('pembayaran.jenis_pembayaran', 'kos')
				// ->count();
				// if ($jumlah_pembayaran % $jumlah_bulan != 0) {
				// 	$pembayaran_terbaru = DB::table('pembayaran')
				// 	->select('tahun_pembayaran', 'bulan_pembayaran')
				// 	->where('id_penyewaan', $sewa->id_penyewaan)
				// 	->where('pembayaran.jenis_pembayaran', 'kos')
				// 	->orderBy('tahun_pembayaran', 'desc')
				// 	->orderBy('bulan_pembayaran', 'desc')
				// 	->first();

				// 	$bulan_terbaru = $pembayaran_terbaru->bulan_pembayaran;
				// 	$tahun_terbaru = $pembayaran_terbaru->tahun_pembayaran;

				// 	$bulan_berikutnya = $bulan_terbaru + 1;
				// 	$tahun_berikutnya = $tahun_terbaru;
				// 	if ($bulan_berikutnya > 12) {
				// 		$bulan_berikutnya = 1;
				// 		$tahun_berikutnya++;
				// 	}
				// 	$bulan_berikutnya_formatted = sprintf("%02d", $bulan_berikutnya);
				// 	DB::table('pembayaran')->insert([
				// 		'id_penyewaan' => $sewa->id_penyewaan,
				// 		'jenis_pembayaran' => 'kos',
				// 		'bulan_pembayaran' => $bulan_berikutnya_formatted,
				// 		'tahun_pembayaran' => $tahun_berikutnya,
				// 		'harga_pembayaran' => $sewa->harga_kamar
				// 	]);
				// }
			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();
		}
		// date_default_timezone_set('Asia/Jakarta');
		// try {
		// 	DB::beginTransaction();
		// 	$penyewaan = Penyewaan::join('users', 'users.id', '=', 'penyewaan.id_user')
		// 	->join('kamar', 'kamar.id_kamar', '=', 'penyewaan.id_kamar')
		// 	->where('users.status_user', 'Aktif')
		// 	->where('penyewaan.status_penyewaan', 'A')
		// 	->get();

		// 	foreach ($penyewaan as $sewa) {
		// 		$tanggal_selesai_sewa = strtotime($sewa->tanggal_selesai);
		// 		$tahun_awal_sewa = date('Y', strtotime($sewa->tanggal_penyewaan));
		// 		$bulan_awal_sewa = date('n', strtotime($sewa->tanggal_penyewaan));

		// 		$tahun_sekarang = date('Y');
		// 		$bulan_sekarang = date('n');
		// 		$tanggal_sekarang = strtotime(date('Y-m-d'));

		// 		$jumlah_bulan = $sewa->rentang_bayar;

		// 		for ($tahun = $tahun_awal_sewa; $tahun <= $tahun_sekarang; $tahun++) {
		// 			$bulan_awal = ($tahun == $tahun_awal_sewa) ? $bulan_awal_sewa : 1;
		// 			$bulan_akhir = ($tahun == $tahun_sekarang) ? $bulan_sekarang : 12;

		// 			for ($bulan = $bulan_awal; $bulan <= $bulan_akhir; $bulan++) {
		// 				$bulan_formatted = sprintf("%02d", $bulan);

		// 				$is_last_month = ($tahun == date('Y', $tanggal_selesai_sewa) && $bulan == date('n', $tanggal_selesai_sewa));
		// 				$should_save = !$is_last_month || ($is_last_month && $tanggal_selesai_sewa > $tanggal_sekarang);

		// 				if ($should_save) {
		// 					$cek_pembayaran = DB::table('pembayaran')
		// 					->join('penyewaan', 'penyewaan.id_penyewaan', '=', 'pembayaran.id_penyewaan')
		// 					->where('pembayaran.bulan_pembayaran', $bulan_formatted)
		// 					->where('penyewaan.id_penyewaan', $sewa->id_penyewaan)
		// 					->where('pembayaran.jenis_pembayaran', 'kos')
		// 					->exists();

		// 					if (!$cek_pembayaran) {
		// 						DB::table('pembayaran')->insert([
		// 							'id_penyewaan' => $sewa->id_penyewaan,
		// 							'jenis_pembayaran' => 'kos',
		// 							'bulan_pembayaran' => $bulan_formatted,
		// 							'tahun_pembayaran' => $tahun,
		// 							'harga_pembayaran' => $sewa->harga_kamar
		// 						]);
		// 					}
		// 				}
		// 			}
		// 		}

		// 		$jumlah_pembayaran = DB::table('pembayaran')
		// 		->where('id_penyewaan', $sewa->id_penyewaan)
		// 		->where('pembayaran.jenis_pembayaran', 'kos')
		// 		->count();

		// 		if ($jumlah_pembayaran % $jumlah_bulan != 0) {
		// 			$pembayaran_terbaru = DB::table('pembayaran')
		// 			->select('tahun_pembayaran', 'bulan_pembayaran')
		// 			->where('id_penyewaan', $sewa->id_penyewaan)
		// 			->where('pembayaran.jenis_pembayaran', 'kos')
		// 			->orderBy('tahun_pembayaran', 'desc')
		// 			->orderBy('bulan_pembayaran', 'desc')
		// 			->first();

		// 			$bulan_terbaru = $pembayaran_terbaru->bulan_pembayaran;
		// 			$tahun_terbaru = $pembayaran_terbaru->tahun_pembayaran;

		// 			$bulan_berikutnya = $bulan_terbaru + 1;
		// 			$tahun_berikutnya = $tahun_terbaru;
		// 			if ($bulan_berikutnya > 12) {
		// 				$bulan_berikutnya = 1;
		// 				$tahun_berikutnya++;
		// 			}
		// 			$bulan_berikutnya_formatted = sprintf("%02d", $bulan_berikutnya);
		// 			DB::table('pembayaran')->insert([
		// 				'id_penyewaan' => $sewa->id_penyewaan,
		// 				'jenis_pembayaran' => 'kos',
		// 				'bulan_pembayaran' => $bulan_berikutnya_formatted,
		// 				'tahun_pembayaran' => $tahun_berikutnya,
		// 				'harga_pembayaran' => $sewa->harga_kamar
		// 			]);
		// 		}
		// 	}
		// 	DB::commit();
		// } catch (Exception $e) {
		// 	DB::rollBack();
		// }
	}
	public static function getDetailPenyewaan($kode_penyewaan, $id_penyewaan)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->join('biodata as operator','operator.id_user','=','penyewaan.created_by')
		// ->leftJoin('kamar_fasilitas','kamar_fasilitas.id_kamar','=','kamar.id_kamar')
		// ->leftJoin('fasilitas','fasilitas.id_fasilitas','=','kamar_fasilitas.id_fasilitas')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->select(
			\DB::RAW('operator.ponsel as ponsel_operator'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.catatan_penyewaan as catatan_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('users.email as email'),
			\DB::RAW('biodata.ponsel as ponsel'),
			\DB::RAW('biodata.no_darurat as no_darurat'),
			\DB::RAW('biodata.alamat as alamat'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('kamar.id_kamar as id_kamar'),
			\DB::RAW('kamar.jenis_kamar as jenis_kamar'),
			\DB::RAW('kamar.keterangan_kamar as keterangan_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar'),
			\DB::RAW('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			\DB::RAW('penyewaan.tanggal_selesai as tanggal_selesai'),
			\DB::RAW('penyewaan.rentang_bayar as rentang_bayar'),
			\DB::raw('DATEDIFF(penyewaan.tanggal_selesai, CURDATE()) as hari_tersisa'),
			\DB::RAW('(SELECT COUNT(DISTINCT kamar_fasilitas.id_fasilitas) FROM kamar_fasilitas WHERE kamar_fasilitas.id_kamar = kamar.id_kamar) as jumlah_fasilitas'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS tagihan_belum_bayar'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NOT NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS tagihan_sudah_bayar'),
			\DB::RAW('SUM(CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END) as total_tagihan')
		)->groupBy('penyewaan.rentang_bayar','penyewaan.kode_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang','kamar.harga_kamar','penyewaan.tanggal_penyewaan','penyewaan.id_penyewaan','users.email','biodata.ponsel','biodata.no_darurat','biodata.alamat','kamar.keterangan_kamar','kamar.jenis_kamar','kamar.id_kamar','penyewaan.tanggal_selesai','operator.ponsel','penyewaan.catatan_penyewaan')
		->where('penyewaan.status_penyewaan','!=','I')
		->where('penyewaan.kode_penyewaan',$kode_penyewaan)
		->where('penyewaan.id_penyewaan',$id_penyewaan)
		->orderBy('penyewaan.tanggal_penyewaan','DESC')
		->get();
		$result = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->where('penyewaan.status_penyewaan','!=','I')
		->where('penyewaan.kode_penyewaan',$kode_penyewaan)
		->where('penyewaan.id_penyewaan',$id_penyewaan);
		$tagihan_belum_bayar = clone $result;
		$tagihan_belum_bayar = $tagihan_belum_bayar->where('pembayaran.tanggal_pembayaran',NULL)
		->where('pembayaran.jenis_pembayaran','kos')
		// ->orderBy('pembayaran.bulan_pembayaran','ASC')
		->orderBy('pembayaran.tahun_pembayaran','ASC')
		->get();
		$tagihan_sudah_bayar = clone $result;
		$tagihan_sudah_bayar = $tagihan_sudah_bayar->select(
			\DB::RAW('pembayaran.tanggal_pembayaran as tanggal_pembayaran'),
			\DB::RAW('bayar.kode_bayar as kode_bayar'),
			\DB::RAW('bayar.tipe_bayar as tipe_bayar'),
			\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
			\DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
			\DB::RAW('bayar.bukti_bayar as bukti_bayar'),
			\DB::RAW('bayar.keterangan_bayar as keterangan_bayar')
		)
		->groupBy('bayar.kode_bayar','bayar.tipe_bayar','bayar.tanggal_bayar','bayar.bukti_bayar','bayar.keterangan_bayar','pembayaran.tanggal_pembayaran')
		->where('bayar.status_bayar','Sudah Bayar')
		->where('pembayaran.jenis_pembayaran','kos')
		->where('pembayaran.tanggal_pembayaran','!=',NULL)
		->orderBy('bayar.tanggal_bayar','DESC')
		->get();
		$pembayaran_belum_dikonfirmasi = clone $result;
    $pembayaran_belum_dikonfirmasi = $pembayaran_belum_dikonfirmasi
        ->select(
            \DB::RAW('pembayaran.tanggal_pembayaran as tanggal_pembayaran'), // Meskipun tanggal_pembayaran mungkin NULL, kita tetap bisa select
            \DB::RAW('bayar.id_bayar'), // Pastikan id_bayar di-select untuk modal
            \DB::RAW('bayar.kode_bayar as kode_bayar'),
            \DB::RAW('bayar.tipe_bayar as tipe_bayar'),
            \DB::RAW('bayar.tanggal_bayar as tanggal_bayar'), // Ini tanggal submit pembayaran oleh penghuni
            \DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
            \DB::RAW('bayar.bukti_bayar as bukti_bayar'),
            \DB::RAW('bayar.keterangan_bayar as keterangan_bayar')
        )
		// TAMBAHKAN 'bayar.id_bayar' DI BAWAH INI
		->groupBy('bayar.id_bayar', 'bayar.kode_bayar','bayar.tipe_bayar','bayar.tanggal_bayar','bayar.bukti_bayar','bayar.keterangan_bayar','pembayaran.tanggal_pembayaran')
		->where('bayar.status_bayar','Sedang di cek')
		->where('pembayaran.jenis_pembayaran','kos')
		// ->where('pembayaran.tanggal_pembayaran','!=',NULL) // <-- HAPUS ATAU KOMENTARI BARIS INI
		->orderBy('bayar.tanggal_bayar','DESC')
		->get();
		$pembayaran_lainnya = clone $result;
		$pembayaran_lainnya = $pembayaran_lainnya->where('bayar.status_bayar','Sudah Bayar')
		->where('pembayaran.jenis_pembayaran','lain-lain')
		->where('pembayaran.tanggal_pembayaran','!=',NULL)
		->orderBy('bayar.tanggal_bayar','DESC')
		->get();
		$reminder = DB::table('reminder')->limit('1')->get();
		return ['data'=>$data,'tagihan_belum_bayar'=>$tagihan_belum_bayar,'tagihan_sudah_bayar'=>$tagihan_sudah_bayar,'pembayaran_belum_dikonfirmasi'=>$pembayaran_belum_dikonfirmasi,'pembayaran_lainnya'=>$pembayaran_lainnya,'reminder'=>$reminder];
	}
	public static function getDashboardPenyewaanPenghuni($request)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->select(
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.tenggat as tenggat'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS tagihan_belum_bayar'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NOT NULL THEN 
				CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS tagihan_sudah_bayar')
		)
		->groupBy('penyewaan.kode_penyewaan','penyewaan.id_penyewaan','penyewaan.tenggat')
		->where('penyewaan.id_user',Auth::user()->id)
		->where('penyewaan.status_penyewaan','!=','I')
		// ->where('penyewaan.status_penyewaan','A')
		->get();
		return $data;
	}
	public static function getDetailPenyewaanPenghuni($kode_penyewaan, $id_penyewaan)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		// ->leftJoin('kamar_fasilitas','kamar_fasilitas.id_kamar','=','kamar.id_kamar')
		// ->leftJoin('fasilitas','fasilitas.id_fasilitas','=','kamar_fasilitas.id_fasilitas')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->select(
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('penyewaan.catatan_penyewaan as catatan_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('users.email as email'),
			\DB::RAW('biodata.ponsel as ponsel'),
			\DB::RAW('biodata.no_darurat as no_darurat'),
			\DB::RAW('biodata.alamat as alamat'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('kamar.id_kamar as id_kamar'),
			\DB::RAW('kamar.jenis_kamar as jenis_kamar'),
			\DB::RAW('kamar.keterangan_kamar as keterangan_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar'),
			\DB::RAW('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			\DB::RAW('penyewaan.tenggat as tenggat'),
			\DB::RAW('(SELECT COUNT(DISTINCT kamar_fasilitas.id_fasilitas) FROM kamar_fasilitas WHERE kamar_fasilitas.id_kamar = kamar.id_kamar) as jumlah_fasilitas'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS tagihan_belum_bayar'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NOT NULL THEN 
				CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS tagihan_sudah_bayar'),
		)->groupBy('penyewaan.kode_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang','kamar.harga_kamar','penyewaan.tanggal_penyewaan','penyewaan.id_penyewaan','users.email','biodata.ponsel','biodata.no_darurat','biodata.alamat','kamar.keterangan_kamar','kamar.jenis_kamar','kamar.id_kamar','penyewaan.tenggat','penyewaan.catatan_penyewaan')
		->where('penyewaan.status_penyewaan','!=','I')
		// ->where('penyewaan.status_penyewaan','A')
		->where('penyewaan.id_user',Auth::user()->id)
		->where('penyewaan.kode_penyewaan',$kode_penyewaan)
		->where('penyewaan.id_penyewaan',$id_penyewaan)
		->orderBy('penyewaan.tanggal_penyewaan','DESC')
		->get();
		$result = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->where('penyewaan.id_user',Auth::user()->id)
		->where('penyewaan.kode_penyewaan',$kode_penyewaan)
		->where('penyewaan.status_penyewaan','!=','I')
		// ->where('penyewaan.status_penyewaan','A')
		->where('penyewaan.id_penyewaan',$id_penyewaan);
		$tagihan_belum_bayar = clone $result;
		$tagihan_belum_bayar = $tagihan_belum_bayar->where('pembayaran.tanggal_pembayaran',NULL)
		->where('pembayaran.jenis_pembayaran','kos')
		// ->orderBy('pembayaran.bulan_pembayaran','ASC')
		->orderBy('pembayaran.tahun_pembayaran','ASC')
		->get();
		$tagihan_sudah_bayar = clone $result;
		$tagihan_sudah_bayar = $tagihan_sudah_bayar->select(
			\DB::RAW('pembayaran.tanggal_pembayaran as tanggal_pembayaran'),
			\DB::RAW('bayar.kode_bayar as kode_bayar'),
			\DB::RAW('bayar.tipe_bayar as tipe_bayar'),
			\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
			\DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
			\DB::RAW('bayar.bukti_bayar as bukti_bayar'),
			\DB::RAW('bayar.keterangan_bayar as keterangan_bayar')
		)
		->groupBy('bayar.kode_bayar','bayar.tipe_bayar','bayar.tanggal_bayar','bayar.bukti_bayar','bayar.keterangan_bayar','pembayaran.tanggal_pembayaran')
		->where('bayar.status_bayar','Sudah Bayar')
		->where('pembayaran.jenis_pembayaran','kos')
		->where('pembayaran.tanggal_pembayaran','!=',NULL)
		->orderBy('bayar.tanggal_bayar','DESC')
		->get();
		$pembayaran_belum_dikonfirmasi = clone $result;
		$pembayaran_belum_dikonfirmasi = $pembayaran_belum_dikonfirmasi
		->select(
			\DB::RAW('pembayaran.tanggal_pembayaran as tanggal_pembayaran'),
			\DB::RAW('bayar.kode_bayar as kode_bayar'),
			\DB::RAW('bayar.tipe_bayar as tipe_bayar'),
			\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
			\DB::RAW('SUM(pembayaran.harga_pembayaran) as total_tagihan'),
			\DB::RAW('bayar.bukti_bayar as bukti_bayar'),
			\DB::RAW('bayar.keterangan_bayar as keterangan_bayar')
		)
		->groupBy('bayar.kode_bayar','bayar.tipe_bayar','bayar.tanggal_bayar','bayar.bukti_bayar','bayar.keterangan_bayar','pembayaran.tanggal_pembayaran')
		->where('bayar.status_bayar','Sedang di cek')
		->where('pembayaran.jenis_pembayaran','kos')
		->where('pembayaran.tanggal_pembayaran','!=',NULL)
		->orderBy('bayar.tanggal_bayar','DESC')
		->get();
		return ['data'=>$data,'tagihan_belum_bayar'=>$tagihan_belum_bayar,'tagihan_sudah_bayar'=>$tagihan_sudah_bayar,'pembayaran_belum_dikonfirmasi'=>$pembayaran_belum_dikonfirmasi];
	}
	public static function getLaporan($request)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		if (!empty($request->tanggal_awal)) {
			$data->whereBetween('penyewaan.tanggal_penyewaan',[$request->tanggal_awal,$request->tanggal_akhir]);
		}
		$data->select(
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar'),
			\DB::RAW('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			\DB::RAW('penyewaan.tanggal_selesai as tanggal_selesai'),
			\DB::RAW('penyewaan.status_penyewaan as status_penyewaan'),
			\DB::RAW('penyewaan.catatan_penyewaan as catatan_penyewaan'),
			\DB::RAW('COUNT(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN 1 END END) AS jumlah_tagihan'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS total_tagihan')
		)->groupBy('penyewaan.kode_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang','kamar.harga_kamar','penyewaan.tanggal_penyewaan','penyewaan.id_penyewaan','penyewaan.status_penyewaan','penyewaan.catatan_penyewaan','penyewaan.tanggal_selesai')
		->where('penyewaan.status_penyewaan','A');
		$data = $data->orderBy('penyewaan.tanggal_penyewaan','DESC')
		->get();
		return $data;
	}
	public static function reminderSend($request)
	{
		$email_penghuni = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('users as penghuni','penghuni.id','=','penyewaan.id_user')
		->join('users as operator','operator.id','=','penyewaan.id_user')
		->select(
			\DB::RAW('penghuni.email as email')
		)
		->groupBy('penghuni.email')
		->where('pembayaran.tanggal_pembayaran',NULL)
		->where('pembayaran.tanggal_pembayaran',NULL)
		->where('pembayaran.id_penyewaan',$request->id_penyewaan)
		->first();
		return $email_penghuni;
	}
	public static function cekTagihanDouble($kode_penyewaan, $id_penyewaan)
	{
		try {
			DB::beginTransaction();
			$cek = DB::table('pembayaran')
			->join('penyewaan', 'pembayaran.id_penyewaan', '=', 'penyewaan.id_penyewaan')
			->where('pembayaran.id_penyewaan', $id_penyewaan)
			->where('penyewaan.kode_penyewaan', $kode_penyewaan)
			->where('pembayaran.jenis_pembayaran', 'kos')
			->select('pembayaran.bulan_pembayaran', 'pembayaran.tahun_pembayaran', 'pembayaran.id_penyewaan', DB::raw('COUNT(*) as jumlah'))
			->groupBy('pembayaran.bulan_pembayaran', 'pembayaran.tahun_pembayaran', 'pembayaran.id_penyewaan')
			->having('jumlah', '>', 1)
			->get();

			foreach ($cek as $tagihan) {
				$duplikat = DB::table('pembayaran')
				->where('id_penyewaan', $tagihan->id_penyewaan)
				->where('bulan_pembayaran', $tagihan->bulan_pembayaran)
				->where('tahun_pembayaran', $tagihan->tahun_pembayaran)
				->where('jenis_pembayaran', 'kos')
				->orderBy('id_pembayaran', 'asc')
				->skip(1)
				->take($tagihan->jumlah - 1)
				->get();

				foreach ($duplikat as $dup) {
					DB::table('pembayaran')->where('id_pembayaran', $dup->id_pembayaran)->delete();
				}
			}
			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();
		}
	}
	public static function getPenyewaanReminder($request)
	{
		$data = Penyewaan::join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('biodata as operator','operator.id_user','=','penyewaan.created_by')
		->where('penyewaan.status_penyewaan','A');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		$data->select(
			\DB::RAW('operator.ponsel as ponsel_operator'),
			\DB::RAW('penyewaan.kode_penyewaan as kode_penyewaan'),
			\DB::RAW('penyewaan.id_penyewaan as id_penyewaan'),
			\DB::RAW('users.name as name'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('kamar.harga_kamar as harga_kamar'),
			\DB::RAW('penyewaan.tanggal_penyewaan as tanggal_penyewaan'),
			\DB::RAW('penyewaan.tanggal_selesai as tanggal_selesai'),
			\DB::raw('DATEDIFF(penyewaan.tanggal_selesai, CURDATE()) as hari_tersisa'),
			\DB::RAW('COUNT(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN 1 END END) AS jumlah_tagihan'),
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS total_tagihan')
		)->groupBy('penyewaan.kode_penyewaan','users.name','kamar.nomor_kamar','cabang.nama_cabang','kamar.harga_kamar','penyewaan.tanggal_penyewaan','penyewaan.id_penyewaan','penyewaan.tanggal_selesai','operator.ponsel')
		->whereDate('penyewaan.tanggal_selesai', '<=', Carbon::now()->addDays(30))
		->where('pembayaran.tanggal_pembayaran', NULL);
		$data = $data->orderBy('penyewaan.tanggal_selesai','ASC')
		->get();
		return $data;
	}
	public static function getRiwayatReminder($request)
	{
		$data = DB::table('penyewaan_reminder')
		->join('penyewaan','penyewaan.id_penyewaan','=','penyewaan_reminder.id_penyewaan')
		->join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		$data = $data->orderBy('penyewaan_reminder.id_penyewaan_reminder','DESC')
		->get();
		return $data;
	}
	public static function getEditRiwayatReminder($id_penyewaan_reminder)
	{
		$data = DB::table('penyewaan_reminder')
		->join('penyewaan','penyewaan.id_penyewaan','=','penyewaan_reminder.id_penyewaan')
		->join('users','users.id','=','penyewaan.id_user')
		->join('biodata','biodata.id_user','=','users.id')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->where('penyewaan_reminder.id_penyewaan_reminder',$id_penyewaan_reminder);
		$data = $data->get();
		return $data;
	}
}

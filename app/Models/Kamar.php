<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;


class Kamar extends Model
{
	// use HasFactory;
	protected $table = "kamar";
	protected $primaryKey = "id_kamar";

	public static function getKamar()
{
    // Hanya mengambil data dari tabel kamar dan join ke cabang untuk nama
    $data = Kamar::join('cabang','cabang.id_cabang','=','kamar.id_cabang')
                ->select('kamar.*', 'cabang.nama_cabang');
    return $data;
}
	// public static function getKamar($request)
	// {
	// 	
	public static function getEditKamar($id_kamar)
	{
		$data = Kamar::leftJoin('cabang', 'cabang.id_cabang', '=', 'kamar.id_cabang')
			->where('kamar.id_kamar', $id_kamar)
			->get();
		$kamar_fasilitas = Kamar::leftJoin('cabang', 'cabang.id_cabang', '=', 'kamar.id_cabang')
			->leftJoin('kamar_fasilitas', 'kamar_fasilitas.id_kamar', '=', 'kamar.id_kamar')
			->leftJoin('fasilitas', 'fasilitas.id_fasilitas', '=', 'kamar_fasilitas.id_fasilitas')
			->where('kamar.id_kamar', $id_kamar)
			->get();
		$kamar_foto = Kamar::leftJoin('kamar_foto', 'kamar_foto.id_kamar', '=', 'kamar.id_kamar')
			->where('kamar_foto.id_kamar', $id_kamar)
			->get();
		return ['data' => $data, 'kamar_fasilitas' => $kamar_fasilitas, 'kamar_foto' => $kamar_foto];
	}
	public static function getViewKamar($id_kamar)
	{
		$result = Kamar::leftJoin('cabang', 'cabang.id_cabang', '=', 'kamar.id_cabang')
			->where(function ($query) {
				$query->whereNotExists(function ($subquery) {
					$subquery->select(\DB::RAW(1))
						->from('penyewaan')
						->whereColumn('penyewaan.id_kamar', '=', 'kamar.id_kamar')
						->where('penyewaan.status_penyewaan', '!=', 'I');
				})->orWhereNull('kamar.id_kamar');
			})
			->where('kamar.id_kamar', Crypt::decryptString($id_kamar));
		$data = clone $result;
		$data = $data->leftJoin('kamar_foto', 'kamar_foto.id_kamar', '=', 'kamar.id_kamar')
			->select(
				\DB::RAW('kamar.created_at as created_at'),
				\DB::RAW('kamar.id_kamar as id_kamar'),
				\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
				\DB::RAW('cabang.nama_cabang as nama_cabang'),
				\DB::RAW('cabang.alamat_cabang as alamat_cabang'),
				\DB::RAW('kamar.jenis_kamar as jenis_kamar'),
				\DB::RAW('kamar.keterangan_kamar as keterangan_kamar'),
				\DB::RAW('kamar.harga_kamar as harga_kamar'),
				\DB::RAW('kamar_foto.foto as foto')
			)
			->groupBy('kamar.id_kamar', 'kamar.nomor_kamar', 'kamar.harga_kamar', 'kamar_foto.foto', 'jenis_kamar', 'kamar.created_at', 'cabang.nama_cabang', 'kamar.keterangan_kamar', 'cabang.alamat_cabang')
			->where('kamar_foto.tipe', 'utama')
			->get();
		$foto = clone $result;
		$foto = $foto->leftJoin('kamar_foto', 'kamar_foto.id_kamar', '=', 'kamar.id_kamar')
			->select(
				\DB::RAW('kamar_foto.foto as foto')
			)
			->groupBy('kamar_foto.foto')
			->where('kamar_foto.tipe', 'lainnya')
			->get();
		$fasilitas = clone $result;
		$fasilitas = $result
			->leftJoin('kamar_fasilitas', 'kamar_fasilitas.id_kamar', '=', 'kamar.id_kamar')
			->leftJoin('fasilitas', 'fasilitas.id_fasilitas', '=', 'kamar_fasilitas.id_fasilitas')
			->select(
				\DB::RAW('fasilitas.nama_fasilitas as nama_fasilitas')
			)
			->groupBy('fasilitas.nama_fasilitas')
			->get();
		return ['data' => $data, 'foto' => $foto, 'fasilitas' => $fasilitas];
	}
}

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
    protected $fillable = [
		'id_cabang',
		'nama_kamar',
		'nomor_kamar',
		'jenis_kamar',
		'harga_kamar',
		'keterangan_kamar',
		'status_kamar', // <-- Pastikan ini ada
		'created_by',
		'updated_by'
	];

	public static function getKamar($request)
    {
        $data = Kamar::join('cabang','cabang.id_cabang','=','kamar.id_cabang')
        ->select('kamar.*','cabang.nama_cabang');

        if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
            $data->where('cabang.kode_cabang',$request->access);
        }else{
            $data->whereIn('kamar.id_cabang',session('site'));
        }

        // PERBAIKAN LOGIKA PENCARIAN KEYWORD
        if (!empty($request->keyword)) {
            // Ini membungkus orWhere agar tidak bentrok dengan where lain
            $data->where(function($query) use ($request) {
                $query->where('kamar.nama_kamar','LIKE','%'.$request->keyword.'%')
                      ->orWhere('cabang.nama_cabang','LIKE','%'.$request->keyword."%");
            });
        }

        if (!empty($request->cabang)) {
            $data->where('kamar.id_cabang',$request->cabang);
        }

        // TAMBAHKAN LOGIKA INI UNTUK FILTER STATUS KAMAR
        if (!empty($request->status_kamar)) {
            $data->where('kamar.status_kamar', $request->status_kamar);
        }

        // $data = $data->paginate(15); // <-- BARIS INI YANG DIHAPUS
        
        return $data; // Kembalikan $data sebagai Query Builder
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

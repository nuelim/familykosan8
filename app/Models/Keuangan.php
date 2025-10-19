<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengeluaran;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Auth;

class Keuangan extends Model
{
    // use HasFactory;
	public static function getKeuanganPembayaran($request)
	{
		$data = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->join('users','users.id','=','penyewaan.id_user')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			\DB::RAW('pembayaran.jenis_pembayaran as jenis_pembayaran'),
			\DB::RAW('kamar.nomor_kamar as nomor_kamar'),
			\DB::RAW('cabang.nama_cabang as nama_cabang'),
			\DB::RAW('bayar.keterangan_bayar as keterangan_bayar'),
			\DB::RAW('bayar.tanggal_bayar as tanggal_bayar'),
			\DB::RAW('SUM(pembayaran.harga_pembayaran) as harga_pembayaran')
		)
		->where('bayar.tanggal_bayar','!=',NULL)
		->where('penyewaan.status_penyewaan','A')
		->groupBy('bayar.tanggal_bayar','bayar.keterangan_bayar','kamar.nomor_kamar','pembayaran.jenis_pembayaran','cabang.nama_cabang');
		if (!empty($request->tanggal_awal)) {
			$order_by = 'ASC';
			$data->whereBetween('pembayaran.tanggal_pembayaran',[$request->tanggal_awal,$request->tanggal_akhir]);
		}else{
			$order_by = 'DESC';
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		$data = $data->orderBy('bayar.tanggal_bayar',$order_by)->get();
		// 
		$tagihan = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			\DB::RAW('SUM(CASE WHEN pembayaran.tanggal_pembayaran IS NOT NULL THEN pembayaran.harga_pembayaran ELSE 0 END) AS total_pemasukan')
		);
		if (!empty($request->tanggal_awal)) {
			$tagihan->whereBetween('pembayaran.tanggal_pembayaran',[$request->tanggal_awal,$request->tanggal_akhir]);
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
		    $tagihan->where('cabang.kode_cabang',$request->access);
		}else{
		    $tagihan->whereIn('kamar.id_cabang',session('site'));
		}
		$tagihan = $tagihan->where('penyewaan.status_penyewaan','A')->first();
		return ['data'=>$data,'tagihan'=>$tagihan];
	}
	public static function getKeuanganPengeluaran($request)
	{
		$result = Pengeluaran::join('cabang','cabang.id_cabang','=','pengeluaran.id_cabang');
		if (!empty($request->tanggal_awal)) {
			$result->whereBetween('pengeluaran.tanggal_pengeluaran',[$request->tanggal_awal,$request->tanggal_akhir]);
			$order_by = 'ASC';
		}else{
			$order_by = 'DESC';
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$result->where('cabang.kode_cabang',$request->access);
		}else{
			$result->whereIn('pengeluaran.id_cabang',session('site'));
		}
		$data = clone $result;
		$data = $data->orderBy('pengeluaran.tanggal_pengeluaran',$order_by)->get();

		$total = clone $result;
		$total = $total->select(
			\DB::RAW('SUM(pengeluaran.nominal_pengeluaran) as total_pengeluaran')
		)->first();
		return ['data'=>$data,'total'=>$total];
	}
}

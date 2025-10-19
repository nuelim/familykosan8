<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Pengeluaran extends Model
{
    // use HasFactory;
	protected $table="pengeluaran";
	protected $primaryKey="id_pengeluaran";

	public static function getPengeluaran($request)
	{
		$data = Pengeluaran::join('cabang','cabang.id_cabang','=','pengeluaran.id_cabang');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('pengeluaran.id_cabang',session('site'));
		}
		$data = $data->get();
		return $data;
	}
	public static function getEditPengeluaran($id_pengeluaran)
	{
		$data = Pengeluaran::join('cabang','cabang.id_cabang','=','pengeluaran.id_cabang')
		->where('pengeluaran.id_pengeluaran',$id_pengeluaran)
		->get();
		return $data;
	}
	public static function getLaporanPengeluaran($request)
	{
		$result = Pengeluaran::join('cabang','cabang.id_cabang','=','pengeluaran.id_cabang');
		if (!empty($request->tanggal_awal)) {
			$result->whereBetween('pengeluaran.tanggal_pengeluaran',[$request->tanggal_awal,$request->tanggal_akhir]);
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$result->where('cabang.kode_cabang',$request->access);
		}else{
			$result->whereIn('pengeluaran.id_cabang',session('site'));
		}
		$data = clone $result;
		$data = $data->get();

		$total = clone $result;
		$total = $total->select(
			\DB::RAW('SUM(pengeluaran.nominal_pengeluaran) as total_pengeluaran')
		)->first();
		return ['data'=>$data,'total'=>$total];
	}
}

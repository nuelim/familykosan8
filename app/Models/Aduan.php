<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Aduan extends Model
{
    // use HasFactory;
	protected $table="ajuan";
	protected $primaryKey="id_ajuan";

	public static function getPenghuniAduan($request)
	{
		$data = Aduan::join('penyewaan','penyewaan.id_penyewaan','=','ajuan.id_penyewaan')
		->join('users','users.id','=','penyewaan.id_user')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->where('penyewaan.status_penyewaan','A');
		if (Auth::user()->level == 'Penghuni') {
			$data->where('penyewaan.id_user',Auth::user()->id);
		}
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$data->where('cabang.kode_cabang',$request->access);
		}else{
			$data->whereIn('kamar.id_cabang',session('site'));
		}
		$data = $data->get();
		return $data;
	}
	public static function getEdit($id_ajuan)
	{
		$result = Aduan::join('penyewaan','penyewaan.id_penyewaan','=','ajuan.id_penyewaan')
		->join('users','users.id','=','penyewaan.id_user')
		->where('ajuan.id_ajuan',$id_ajuan)
		->where('penyewaan.status_penyewaan','A');
		$data = clone $result;
		$data = $data->get();
		$foto = clone $result;
		$foto = $foto->join('ajuan_detail','ajuan_detail.id_ajuan','=','ajuan.id_ajuan')
		->get();
		return ['data'=>$data,'foto'=>$foto];
	}
}

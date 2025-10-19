<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Cabang;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$results = Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
		->join('kamar','kamar.id_kamar','=','penyewaan.id_kamar')
		->join('cabang','cabang.id_cabang','=','kamar.id_cabang')
		->leftJoin('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
		->select(
			DB::raw('SUM(CASE WHEN pembayaran.id_bayar IS NOT NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "kos" THEN kamar.harga_kamar ELSE 0 END ELSE 0 END) AS kos'),
			DB::raw('SUM(CASE WHEN pembayaran.id_bayar IS NOT NULL THEN CASE WHEN pembayaran.jenis_pembayaran = "lain-lain" THEN pembayaran.harga_pembayaran ELSE 0 END ELSE 0 END) AS lain'),
			// DB::raw('SUM(pembayaran.harga_pembayaran) as total_pembayaran'),
			DB::raw('MONTH(bayar.tanggal_bayar) as month')
		);
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$results->where('cabang.kode_cabang',$request->access);
		}else{
			$results->whereIn('kamar.id_cabang',session('site'));
		}
		$results = $results->groupBy('month')
		->orderBy('month')
		->whereYear('bayar.tanggal_bayar',date('Y'))
		->where('bayar.status_bayar','Sudah Bayar')
		->get();

		$data = [];
		for ($month = 1; $month <= 12; $month++) {
			$monthLabel = date('F', mktime(0, 0, 0, $month, 1));
			$data['label'][] = $monthLabel;
			$resultForMonth = $results->firstWhere('month', $month);
			$data['data']['kos'][] = $resultForMonth ? $resultForMonth->kos : 0;
			$data['data']['lain'][] = $resultForMonth ? $resultForMonth->lain : 0;
			// $data['data']['total_pembayaran'][] = $resultForMonth ? $resultForMonth->total_pembayaran : 0;
		}
		$data['chart_data'] = json_encode($data);
		$user = User::join('biodata','biodata.id_user','=','users.id')
		->leftJoin('cabang','cabang.id_cabang','=','biodata.id_cabang');
		if (Auth::user()->level == 'Super Admin' && !empty($request->access)) {
			$user->where('cabang.kode_cabang',$request->access);
		}else{
			$user->whereIn('biodata.id_cabang',session('site'));
		}
		$penghuni = clone $user;
		$penghuni = $penghuni->where('users.level','Penghuni')
		->count();
		return view('page.operator.dashboard.index',$data,compact('penghuni'));
	}
}

<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penyewaan;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$data = Penyewaan::getDashboardPenyewaanPenghuni($request);
		return view('page.penghuni.dashboard.index',compact('data'));
	}
	public function detail_penyewan($kode_penyewaan, $id_penyewaan)
	{
		Penyewaan::cekTagihanDouble($kode_penyewaan,$id_penyewaan);
		$result = Penyewaan::getDetailPenyewaanPenghuni($kode_penyewaan,$id_penyewaan);
		$data = $result['data'];
		$tagihan_belum_bayar = $result['tagihan_belum_bayar'];
		$tagihan_sudah_bayar = $result['tagihan_sudah_bayar'];
		$pembayaran_belum_dikonfirmasi = $result['pembayaran_belum_dikonfirmasi'];
		return view('page.penghuni.penyewaan.detail',compact('data','tagihan_belum_bayar','tagihan_sudah_bayar','pembayaran_belum_dikonfirmasi'));
	}
}

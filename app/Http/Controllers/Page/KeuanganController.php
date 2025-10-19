<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keuangan;
use App\Models\Cabang;
use PDF;

class KeuanganController extends Controller
{
	protected $label_cabang;

	public function __construct(Request $request)
	{
		$this->label_cabang = Cabang::getLabelCabang($request);
	}

	public function laporan(Request $request)
	{
		// $result_pembayaran = Keuangan::getKeuanganPembayaran($request);
		// $pembayaran = $result_pembayaran['data'];
		// $total_pembayaran = $result_pembayaran['tagihan'];
		// // 
		// $result_pengeluaran = Keuangan::getKeuanganPengeluaran($request);
		// $pengeluaran = $result_pengeluaran['data'];
		// $total_pengeluaran = $result_pengeluaran['total'];

		// return view('page.super_admin.laporan.keuangan.index',compact('pembayaran','total_pembayaran','pengeluaran','total_pengeluaran'))->with('label_cabang', $this->label_cabang);
		// $cabang = $this->label_cabang;
		$result_pembayaran = Keuangan::getKeuanganPembayaran($request);
		$pembayaran = $result_pembayaran['data'];
		$total_pembayaran = $result_pembayaran['tagihan'];

		$result_pengeluaran = Keuangan::getKeuanganPengeluaran($request);
		$pengeluaran = $result_pengeluaran['data'];
		$total_pengeluaran = $result_pengeluaran['total'];

		$combined = [];

		foreach ($pembayaran as $pem) {
			if ($pem->jenis_pembayaran == 'kos') {
				if (empty($request->access)) {
					$keterangan_bayar =  $pem->nomor_kamar.' ('.$pem->nama_cabang.')';
				}else{
					$keterangan_bayar =  $pem->nomor_kamar;
				}
			}else{
				$keterangan_bayar = $pem->keterangan_bayar;
			}
			$combined[] = [
				'date' => $pem->tanggal_bayar,
				'keterangan' => $keterangan_bayar,
				'pemasukan' => $pem->harga_pembayaran,
				'pengeluaran' => null
			];
		}

		foreach ($pengeluaran as $peng) {
			$combined[] = [
				'date' => $peng->tanggal_pengeluaran,
				'keterangan' => $peng->keterangan_pengeluaran,
				'pemasukan' => null,
				'pengeluaran' => $peng->nominal_pengeluaran
			];
		}

		usort($combined, function($a, $b) {
			return strtotime($a['date']) - strtotime($b['date']);
		});
		return view('page.super_admin.laporan.keuangan.index',compact('combined','total_pembayaran','total_pengeluaran'))->with('label_cabang', $this->label_cabang);
	}
	public function laporan_export(Request $request)
	{
		// $result_pembayaran = Keuangan::getKeuanganPembayaran($request);
		// $pembayaran = $result_pembayaran['data'];
		// $total_pembayaran = $result_pembayaran['tagihan'];
		// // 
		// $result_pengeluaran = Keuangan::getKeuanganPengeluaran($request);
		// $pengeluaran = $result_pengeluaran['data'];
		// $total_pengeluaran = $result_pengeluaran['total'];
		// if ($request->type == 'PDF') {
		// 	$label_cabang = $this->label_cabang;
		// 	$pdf=PDF::loadview('page.super_admin.laporan.keuangan.export',compact('pembayaran','total_pembayaran','pengeluaran','total_pengeluaran','label_cabang'))->setPaper('A4','landscape');
		// 	return $pdf->stream();
		// }else{
		// 	return view('page.super_admin.laporan.keuangan.export',compact('pembayaran','total_pembayaran','pengeluaran','total_pengeluaran'))->with('label_cabang', $this->label_cabang);
		// }
		$result_pembayaran = Keuangan::getKeuanganPembayaran($request);
		$pembayaran = $result_pembayaran['data'];
		$total_pembayaran = $result_pembayaran['tagihan'];

		$result_pengeluaran = Keuangan::getKeuanganPengeluaran($request);
		$pengeluaran = $result_pengeluaran['data'];
		$total_pengeluaran = $result_pengeluaran['total'];

		$combined = [];

		foreach ($pembayaran as $pem) {
			if ($pem->jenis_pembayaran == 'kos') {
				$keterangan_bayar = $pem->nomor_kamar;
			}else{
				$keterangan_bayar = $pem->keterangan_bayar;
			}
			$combined[] = [
				'date' => $pem->tanggal_bayar,
				'keterangan' => $keterangan_bayar,
				'pemasukan' => $pem->harga_pembayaran,
				'pengeluaran' => null
			];
		}

		foreach ($pengeluaran as $peng) {
			$combined[] = [
				'date' => $peng->tanggal_pengeluaran,
				'keterangan' => $peng->keterangan_pengeluaran,
				'pemasukan' => null,
				'pengeluaran' => $peng->nominal_pengeluaran
			];
		}

		usort($combined, function($a, $b) {
			return strtotime($a['date']) - strtotime($b['date']);
		});
		if ($request->type == 'PDF') {
			$label_cabang = $this->label_cabang;
			$pdf=PDF::loadview('page.super_admin.laporan.keuangan.export',compact('combined','total_pembayaran','total_pengeluaran','label_cabang'))->setPaper('A4','landscape');
			return $pdf->stream();
		}else{
			return view('page.super_admin.laporan.keuangan.export',compact('combined','total_pembayaran','total_pengeluaran'))->with('label_cabang', $this->label_cabang);
		}
	}
}

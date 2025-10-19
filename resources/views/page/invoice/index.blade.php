@foreach($data as $dt)
<!DOCTYPE html>
<html>
<head>
	<title>Invoice #{{$dt->kode_bayar}}</title>
<!-- 	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('template/dist/assets/css/bootstrap.css')}}">
	<link rel="stylesheet" href="{{asset('template/dist/assets/css/app.css')}}">
-->
<link rel="stylesheet" href="{{asset('print.css')}}">
<link href="{{asset('logo-true.png')}}" rel="icon">

</head>
<style>
	body {
		font-family: 'Calibri', sans-serif;
	}
	.footer_right {
		position: absolute;
		bottom: 0;
		right: 0; /* Letakkan di pojok kanan bawah */
		text-align: right;
		padding: 10px;
		width: 50%; /* Lebar 50% agar ada ruang untuk konten kiri */
	}

	.footer_left {
		position: absolute;
		bottom: 0;
		left: 0; /* Letakkan di pojok kiri bawah */
		text-align: left;
		padding: 10px;
		width: 50%; /* Lebar 50% agar ada ruang untuk konten kanan */
	}

	.signature {
		display: inline-block;
		width: 50%; /* Lebar 50% */
		margin: 0 auto;
	}

</style>
<body>
	<?php  
	function tanggal_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);

		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
	?>
	<?php  
	function bulan_to_nama($bulan) {
		$nama_bulan = array(
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December'
		);
		return $nama_bulan[$bulan];
	}
	?>
	<div class="container-fluid" style="font-family: 'Calibri', sans-serif;font-size: 13px;">
		<div class="row" style="width: 100%;">
			<div class="" style="float: left;">
				<span>
					<b>FAMILY KOS</b><br>
					{{$dt->alamat_cabang}} <br>
					Phone: 0857-4827-5403 <br>
					Email: info.familykos@gmail.com
				</span>
			</div>
			<div class="" style="float: right;">
				<img src="{{asset('logo-true.png')}}" width="80">
			</div>
		</div>
		<div class="row" style="width: 100%;margin-top: 13%;">
			<div class="col-lg-12"><hr style="border: 0.5px solid #aaa;"></div>
			<div class="col-lg-12" style="font-weight: bold;font-size: 15px;"><center>INVOICE PEMBAYARAN</center></div>
			<div class="col-lg-12"><hr style="border: 0.5px solid #aaa;"></div>
			<div><center>No. : {{$dt->kode_bayar}}</center></div>
			<div>
				<table style="width: 100%;padding: 0;margin: 0;margin-left: 5%;" cellpadding="5" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td>Kepada</td>
							<td>:</td>
							<td>{{$dt->name}}</td>
						</tr>
						<tr>
							<td>Kamar</td>
							<td>:</td>
							<td>{{$dt->nomor_kamar}}</td>
						</tr>
						<tr>
							<td>Tarif Kamar</td>
							<td>:</td>
							<td>Rp. {{number_format($dt->harga_kamar,0,",",".")}}</td>
						</tr>
						<tr>
							<td>Informasi</td>
							<td>:</td>
							<td>Pembayaran Bulan:<br>
								@foreach($pembayaran as $pem)
								<b>{{ bulan_to_nama($pem->bulan_pembayaran) }} {{ $pem->tahun_pembayaran }}</b>@if(!$loop->last), @endif
								@endforeach
							</td>
						</tr>
						@if($dt->keterangan_bayar != NULL)
						<tr>
							<td>Catatan/Keterangan</td>
							<td>:</td>
							<td>{{$dt->keterangan_bayar}}</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
		<div class="row" style="width: 70%;text-align: center;margin-top: 8%;">
			<div style="width: 15%;float: left;padding: 15px;border-top: 2px solid #aaa;border-bottom: 1.5px solid #aaa;font-size: 15px;"><b>Total</b></div>
			<div style="width: 50%;float: left;background: #7DF9FF;padding: 15px;margin: 0; border-top: 2px solid #aaa;border-bottom: 1.5px solid #aaa;font-size: 15px;"><b>Rp. {{number_format($dt->total_tagihan,0,",",".")}}</b></div>
		</div>
		<div class="row" style="width: 30%;text-align: center;float: right;">
			<span style="font-family: 'Calibri', sans-serif;">
				{{tanggal_indo($dt->tanggal_bayar)}} <br>
				<img src="{{asset('logo-true.png')}}" width="80">
				<br>
				DANTE KOS
			</span>
		</div>
	</div>
	<div class="footer_left" style="color: #aaa;">
		Receive Payment #{{$dt->kode_bayar}}
	</div>
<!-- <div class="footer_right" style="color: #aaa;">
	Page {PAGE_NUM} of {PAGE_COUNT}
</div> -->
</body>

</html>
@endforeach
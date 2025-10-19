@if($_GET['type']=="Excel")
<?php  
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename=Laporan Pembayaran Kos.xls'); 
?>
@endif
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Pembayaran Kos</title>

  <!-- <link rel="stylesheet" href="{{asset('print.css')}}"> -->
</head>
<style type="text/css">
  @page {
    margin: 100px 25px;
  }

  header {
    position: fixed;
    top: -100px;
    left: 0px;
    right: 0px;
    height: 50px;
    font-size: 20px !important;

    /** Extra personal styles **/
    /*background-color: #008B8B;*/
    /*color: white;*/
    text-align: center;
    line-height: 35px;
  }

  footer {
    position: fixed; 
    bottom: -30px; 
    left: 0px; 
    right: 0px;
    height: 50px; 
    font-size: 20px !important;

    /** Extra personal styles **/
    /*background-color: #008B8B;*/
    /*color: white;*/
    text-align: center;
    line-height: 35px;
  }
</style>
<body>
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
  <header>
    <center>
      <?php  
      $startMonth = date('m', strtotime($_GET['tanggal_awal']));
      $startYear = date('Y', strtotime($_GET['tanggal_awal']));
      $endMonth = date('m', strtotime($_GET['tanggal_akhir']));
      $endYear = date('Y', strtotime($_GET['tanggal_akhir']));
      ?>
      REKAP LAPORAN PEMBAYARAN KOS <br>
      @if(!empty($_GET['tanggal_awal']))
      Periode : {{bulan_to_nama($startMonth)}} {{$startYear}} - {{bulan_to_nama($endMonth)}} {{$endYear}} <br>
      @else
      Semua Periode <br>
      @endif
      <sup><small>
        Laporan ini di Export dalam Aplikasi untuk melakukan rekap data pembayaran {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}</small></sup>
      </center>
    </header>
    <main>
      <div class="card-body" style="font-family: times new roman;">
        <br>
        <label>Tagihan Sudah Bayar : Rp. {{number_format($tagihan->tagihan_sudah_bayar,0,",",".")}}</label><br>
        <label>Tagihan Belum Bayar : Rp. {{number_format($tagihan->tagihan_belum_bayar,0,",",".")}}</label>
        <table class="table table-bordered" style="width: 100%;margin-top: 1rem;" cellpadding="6" cellspacing="0" border="1">
         <thead style="background: #aaa;">
          <tr>
            <th data-priority="" class="">No. </th>
            <th data-priority="" class="">Kode</th>
            <th data-priority="" class="">Nama</th>
            <th data-priority="" class="">Tipe</th>
            <th data-priority="" class="">Kamar Kos</th>
            <th data-priority="" class="">Jenis</th>
            <th data-priority="" class="">Tanggal Bayar</th>
            <th data-priority="" class="">Tagihan untuk</th>
            <th data-priority="" class="">Total Tagihan</th>
            <th data-priority="" class="">Status Pembayaran</th>
            <th data-priority="" class="">Bukti Bayar</th>
            <th data-priority="" class="">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $dt)
          <tr>
            <td>{{$loop->index+1}}. </td>
            <td>{{ $dt->kode_bayar ?? 'Belum ada pembayaran' }}</td>
            <td>{{$dt->name ?? '-'}}</td>
            <td>{{$dt->tipe_bayar ?? '-'}}</td>
            <td>{{$dt->nomor_kamar ?? '-'}} / {{$dt->nama_cabang}}</td>
            <td>
              @if($dt->jenis_pembayaran == 'kos')
              <span class="text text-primary">Pembayaran Kos</span>
              @else
              <span class="text text-success">Pembayaran Lain-Lain</span>
              @endif
            </td>
            <td>{{$dt->tanggal_bayar ?? '-'}}</td>
            <td>{{bulan_to_nama($dt->bulan_pembayaran)}} {{$dt->tahun_pembayaran}}</td>
            <td>Rp. {{number_format($dt->total_tagihan,0,",",".")}}</td>
            <td>{{$dt->status_bayar ?? 'Belum Bayar'}}</td>
            <td>
              @if($dt->tipe_bayar == 'Tunai')
              -
              @else
              @if($dt->bukti_bayar != NULL)
              <img src="{{asset('bukti_bayar')}}/{{$dt->bukti_bayar}}" width="45">
              @endif
              @endif
            </td>
            <td>{{$dt->keterangan_bayar}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>

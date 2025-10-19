@if($_GET['type']=="Excel")
<?php  
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename=Laporan Pengeluaran Kos.xls'); 
?>
@endif
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Pengeluaran Kos</title>

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
    REKAP LAPORAN PENGELUARAN KOS <br>
    @if(!empty($_GET['tanggal_awal']))
    Periode : {{$_GET['tanggal_awal']}} - {{$_GET['tanggal_akhir']}} <br>
    @else
    Semua Periode <br>
    @endif
    <sup><small>
      Laporan ini di Export dalam Aplikasi untuk melakukan rekap data pengeluaran {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}</small></sup>
    </center>
  </header>
  <main>
    <div class="card-body" style="font-family: times new roman;">
      <br>
      <label>Total Pengeluaran : Rp. {{number_format($total->total_pengeluaran,0,",",".")}}</label><br>
      <table class="table table-bordered" style="width: 100%;margin-top: 1rem;text-align: center;" cellpadding="6" cellspacing="0" border="1">
       <thead style="background: #aaa;">
        <tr>
          <th>No. </th>
          <th>Tanggal</th>
          <th>Nominal</th>
          <th>Bukti/Foto</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $dt)
        <tr>
          <td>{{$loop->index+1}}. </td>
          <td>{{tanggal_indonesia($dt->tanggal_pengeluaran)}}</td>
          <td>Rp. {{number_format($dt->nominal_pengeluaran,0,",",".")}}</td>
          <td><img src="{{asset('bukti_pengeluaran')}}/{{$dt->bukti_pengeluaran}}" width="45"></td>
          <td>{{$dt->keterangan_pengeluaran}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>
</body>
</html>

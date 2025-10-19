@if($_GET['type']=="Excel")
<?php  
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename=Laporan Keuangan Kos.xls'); 
?>
@endif
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Keuangan Kos</title>

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
      REKAP LAPORAN KEUANGAN KOS <br>
      @if(!empty($_GET['tanggal_awal']))
      Periode : {{bulan_to_nama($startMonth)}} {{$startYear}} - {{bulan_to_nama($endMonth)}} {{$endYear}} <br>
      @else
      Semua Periode <br>
      @endif
      <sup><small>
        Laporan ini di Export dalam Aplikasi untuk melakukan rekap data keuangan {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}</small></sup>
      </center>
    </header>
    <main>
      <div class="card-body" style="font-family: times new roman;">
        <br>
        <label>Pendapatan : Rp. {{number_format($total_pembayaran->total_pemasukan-$total_pengeluaran->total_pengeluaran,0,",",".")}}</label><br>
        <table class="table table-bordered datatable" style="width: 100%;margin-top: 1rem;" cellpadding="6" cellspacing="0" border="1">
         <thead style="background: #aaa;">
           <tr>
             <th data-priority="" class="">No. </th>
             <th data-priority="" class="">Tanggal</th>
             <th data-priority="" class="">Keterangan</th>
             <th data-priority="" class="">Pemasukan</th>
             <th data-priority="" class="">Pengeluaran</th>
           </tr>
         </thead>
         <tbody>
          <?php  
          $counter = 1;
          ?>
          @foreach($combined as $pem)
          <tr>
            <td>{{ $counter++ }}. </td>
            <td><?php echo tanggal_indonesia($pem['date']); ?></td>
            <td><?php echo $pem['keterangan']; ?></td>
            <td><?php echo $pem['pemasukan'] != 0 ? 'Rp. ' . number_format($pem['pemasukan'], 0, ",", ".") : ''; ?></td>
            <td><?php echo $pem['pengeluaran'] != 0 ? 'Rp. ' . number_format($pem['pengeluaran'], 0, ",", ".") : ''; ?></td>
          </tr>
          @endforeach
          <tr>
            <td colspan="3" align="center"><b>Jumlah :</b></td>
            <td><b>Rp. {{number_format($total_pembayaran->total_pemasukan,0,",",".")}}</b></td>
            <td><b>Rp. {{number_format($total_pengeluaran->total_pengeluaran,0,",",".")}}</b></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>

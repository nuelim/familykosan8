@if($_GET['type']=="Excel")
<?php  
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename=Laporan Penyewaan Kos.xls'); 
?>
@endif
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Penyewaan Kos</title>

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
  <header>
    <center>
      REKAP LAPORAN PENYEWAAN KOS <br>
      @if(!empty($_GET['tanggal_awal']))
      Periode : {{$_GET['tanggal_awal']}} - {{$_GET['tanggal_akhir']}} <br>
      @else
      Semua Periode <br>
      @endif
      <sup>
        <small>
          Laporan ini di Export dalam Aplikasi untuk melakukan rekap data penyewaan {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
        </small>
      </sup>
    </center>
  </header>
  <main>
    <div class="card-body" style="font-family: times new roman;">
      <table class="table table-bordered" style="width: 100%;margin-top: 1rem;" cellpadding="6" cellspacing="0" border="1">
       <thead style="background: #aaa;">
        <tr>
          <th data-priority="1">No. </th>
          <th data-priority="2">Kode Penyewaan</th>
          <th data-priority="3">Nama Penghuni</th>
          <th data-priority="4">Kamar</th>
          <th data-priority="5">Tarif</th>
          <th data-priority="6">Tanggal Mulai Sewa</th>
          <th data-priority="6">Tanggal Selesai Sewa</th>
          <th data-priority="6">Catatan/Keterangan</th>
          <th data-priority="7">Status Penyewaan</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0" >
        @foreach($data as $dt)
        <tr>
          <td>{{$loop->index+1}}</td>
          <td><b>{{$dt->kode_penyewaan}}</b></td>
          <td>{{$dt->name}}</td>
          <td>{{$dt->nama_cabang}} / {{$dt->nomor_kamar}}</td>
          <td>Rp. {{number_format($dt->harga_kamar,0,",",".")}} / bulan</td>
          <td>{{tanggal_indonesia($dt->tanggal_penyewaan)}}</td>
          <td>{{tanggal_indonesia($dt->tanggal_selesai)}}</td>
          <td>{{$dt->catatan_penyewaan}}</td>
          <td>
            @if($dt->status_penyewaan == 'A')
            <span class="badge bg-success text-white">Aktif</span>
            @else
            <span class="badge bg-danger text-white">Non Aktif/Selesai</span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>
</body>
</html>

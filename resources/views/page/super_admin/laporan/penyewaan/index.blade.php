  @extends('page/layout/app')

  @section('title','Laporan Penyewaan Kos')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading" id="pagePenyewaan">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Laporan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Penyewaan Kos</li>
            </ol>
          </nav>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-xl-6 pb-4" style="background: white;box-shadow:2px 2px grey;">
          <form method="GET">
            <div class="row mt-3">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Tanggal Awal</label>
              <div class="col-sm-8">
                <input type="date" value="{{request()->has('tanggal_awal') ? request()->input('tanggal_awal') : ''}}" class="form-control" name="tanggal_awal">
              </div>
            </div>
            <div class="row mt-3">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Tanggal Akhir</label>
              <div class="col-sm-8">
                <input type="date" value="{{request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : ''}}" class="form-control" name="tanggal_akhir">
              </div>
            </div>
            <input type="" hidden="" value="{{request()->has('access') ? request()->input('access') : null}}" name="access">
            <button class="btn btn-info mt-4"><i class="fa fa-filter"></i> Tampilkan</button>
            <a href="{{route('laporan.penyewaan',['access'=>request()->has('access') ? request()->input('access') : null])}}" class="btn btn-secondary mt-4"><i class="bx bx-refresh"></i></a>
            <a href="{{route('laporan.export',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'PDF','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;" class="btn btn-danger mt-4"><i class="bx bxs-file-pdf"></i></a>
            <a href="{{route('laporan.export',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'Excel','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;margin-right: 4px;" class="btn btn-success mt-4 text-white"><i class="bx bxs-file"></i></a>
          </form>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Laporan Penyewaan Kos {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap dt-responsive datatable" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th data-priority="3">Kode Penyewaan</th>
                  <th data-priority="4">Nama Penghuni</th>
                  <th data-priority="5">Kamar</th>
                  <th data-priority="6">Tarif</th>
                  <th data-priority="7">Tanggal Mulai Sewa</th>
                  <th data-priority="8">Tanggal Selesai Sewa</th>
                  <th data-priority="2">Status Penyewaan</th>
                  <!-- <th data-priority="8">Jumlah Tagihan belum bayar</th> -->
                  <!-- <th data-priority="9">Total Tagihan belum bayar</th> -->
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
                  <td>
                    @if($dt->status_penyewaan == 'A')
                    <span class="badge bg-success text-white">Aktif</span>
                    @else
                    <span class="badge bg-danger text-white">Non Aktif/Selesai</span>
                    @endif
                  </td>
                  <!-- <td>{{$dt->jumlah_tagihan}} bulan</td> -->
                  <!-- <td>Rp. {{number_format($dt->total_tagihan,0,",",".")}}</td> -->
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
  @endsection
  @section('scripts')
  <script type="text/javascript">
    $(function () {
      $('.datatable').DataTable({
        processing: true,
        pageLength: 10,
        responsive: true,
        colReorder: true
      });
    });
  </script>
  @endsection
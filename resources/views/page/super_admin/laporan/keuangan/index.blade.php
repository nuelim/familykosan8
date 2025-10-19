  @extends('page/layout/app')

  @section('title','Laporan Keuangan')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Laporan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Keuangan</li>
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
            <a href="{{route('laporan.keuangan',['access'=>request()->has('access') ? request()->input('access') : null])}}" class="btn btn-secondary mt-4"><i class="bx bx-refresh"></i></a>
            <a href="{{route('keuangan.export',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'PDF','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;" class="btn btn-danger mt-4"><i class="bx bxs-file-pdf"></i></a>
            <a href="{{route('keuangan.export',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'Excel','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;margin-right: 4px;" class="btn btn-success mt-4 text-white"><i class="bx bxs-file"></i></a>
          </form>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Laporan Keuangan {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
              <thead>
                <tr>
                 <th data-priority="1" class="">No. </th>
                 <th data-priority="" class="">Tanggal</th>
                 <th data-priority="" class="">Keterangan</th>
                 <th data-priority="" class="">Pemasukan</th>
                 <th data-priority="2" class="">Pengeluaran</th>
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
            </tbody>
            <tfoot>
             <tr>
              <td></td>
              <td></td>
              <td></td>
              <td><b>Rp. {{number_format($total_pembayaran->total_pemasukan,0,",",".")}}</b></td>
              <td><b>Rp. {{number_format($total_pengeluaran->total_pengeluaran,0,",",".")}}</b></td>
            </tr>
          </tfoot>
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
      // order: [[2, 'desc']],
      processing: true,
      pageLength: 10
    });
  });
</script>
@endsection
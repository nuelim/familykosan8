  @extends('page/layout/app')

  @section('title','Laporan Pembayaran')

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
              <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
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
            <a href="{{route('laporan.pembayaran',['access'=>request()->has('access') ? request()->input('access') : null])}}" class="btn btn-secondary mt-4"><i class="bx bx-refresh"></i></a>
            <a href="{{route('pembayaran.export',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'PDF','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;" class="btn btn-danger mt-4"><i class="bx bxs-file-pdf"></i></a>
            <a href="{{route('pembayaran.export',['tanggal_awal'=>request()->has('tanggal_awal') ? request()->input('tanggal_awal') : '','tanggal_akhir'=>request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : '','type'=>'Excel','access'=>request()->has('access') ? request()->input('access') : null])}}" target="_blank" style="float: right;margin-right: 4px;" class="btn btn-success mt-4 text-white"><i class="bx bxs-file"></i></a>
          </form>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Laporan Pembayaran (Kos & Lainnya) {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1" class="">No. </th>
                  <th data-priority="3" class="">Kode Pembayaran</th>
                  <th data-priority="4" class="">Nama</th>
                  <th data-priority="5" class="">Tipe</th>
                  <th data-priority="6" class="">Jenis</th>
                  <th data-priority="7" class="">Tanggal Bayar</th>
                  <th data-priority="" class="">Tagihan untuk</th>
                  <th data-priority="8" class="">Total Tagihan</th>
                  <th data-priority="9" class="">Status Pembayaran</th>
                  <th data-priority="10" class="">Bukti Bayar</th>
                  <th data-priority="2" class="">Keterangan </th>
                </tr>
              </thead>
              <tbody>
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
                @foreach($data as $dt)
                <tr>
                  <td>{{$loop->index+1}}. </td>
                  <td>{!! $dt->kode_bayar ?? '<b class="text-danger">Belum Bayar</b>' !!}</td>
                  <td>{{$dt->name ?? '-'}}</td>
                  <td>{{$dt->tipe_bayar ?? '-'}}</td>
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
  @extends('page/layout/app')

  @section('title','Data Pembayaran')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
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
              <li class="breadcrumb-item"><a href="">Pengelolaan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data Pembayaran</li>
            </ol>
          </nav>
        </div>
      </div>
      @if($type == 'pembayaran')
      <div class="row mb-4">
        <div class="col-xl-6 pb-4" style="background: white;box-shadow:2px 2px grey;">
          <form method="GET">
            <div class="row mt-2">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Tanggal Awal</label>
              <div class="col-sm-8">
                <input type="date" value="{{request()->has('tanggal_awal') ? request()->input('tanggal_awal') : ''}}" class="form-control" name="tanggal_awal">
              </div>
            </div>
            <div class="row mt-2">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Tanggal Akhir</label>
              <div class="col-sm-8">
                <input type="date" value="{{request()->has('tanggal_akhir') ? request()->input('tanggal_akhir') : ''}}" class="form-control" name="tanggal_akhir">
              </div>
            </div>
            <div class="row mt-2">
              <label class="col-sm-3 form-label mt-2" style="color: black;">Status Bayar</label>
              <div class="col-sm-8">
                <select class="form-control" name="keyword" id="keyword">
                  <option value="">:. STATUS PEMBAYARAN .:</option>
                  <option value="Belum Bayar" {{ request('keyword') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                  <option value="Sedang di cek" {{ request('keyword') =='Sedang di cek' ? 'selected' : '' }}>Belum di Konfirmasi</option>
                  <option value="Sudah Bayar" {{ request('keyword') == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
              </div>
            </div>
            <input type="" hidden="" value="{{request()->has('access') ? request()->input('access') : null}}" name="access">
            <input type="" hidden="" value="{{request()->has('type') ? request()->input('type') : null}}" name="type">
            <button class="btn btn-info mt-4"><i class="fa fa-filter"></i> Tampilkan</button>
            <a href="{{route('index.pembayaran',['access'=>request()->has('access') ? request()->input('access') : null,'type'=>$type])}}" class="btn btn-secondary mt-4"><i class="bx bx-refresh bx-sm"></i></a>
          </form>
        </div>
      </div>
      @endif
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            @if($type == 'pembayaran')
            Data Pembayaran (Kos & Lainnya)
            @else
            Tagihan Kos Bulan ini
            @endif
            {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped dt-responsive" id="table_pembayaran" cellpadding="0" cellspacing="0" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="" class="">No. </th>
                  @if($type == 'pembayaran')
                  <th data-priority="" class="">Kode Pembayaran</th>
                  <th data-priority="" class="">Tipe Pembayaran</th>
                  <th data-priority="" class="">Jenis Pembayaran</th>
                  <th data-priority="" class="">Tanggal Pembayaran</th>
                  <th data-priority="" class="">Total Tagihan</th>
                  <th data-priority="" class="">Status Pembayaran</th>
                  <th data-priority="" class="">Bukti Bayar</th>
                  <th data-priority="2" class="">Keterangan Pembayaran</th>
                  @elseif($type == 'tagihan')
                  <th data-priority="" class="">Penghuni</th>
                  <th data-priority="" class="">Kamar</th>
                  <th data-priority="" class="">Tagihan Bulan</th>
                  <th data-priority="" class="">Total Tagihan</th>
                  <th data-priority="" class="">Status</th>
                  <th data-priority="" class="">Action</th>
                  @endif
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
                @foreach($tagihan_sudah_bayar as $dt)
                <tr>
                  <td>{{$loop->index+1}}. </td>
                  @if($type == 'pembayaran')
                  <td>{!! $dt->kode_bayar ?? '<b class="text-danger">Belum Bayar</b>' !!}<br><a href="{{route('detail_penyewan',['kode_penyewaan'=>$dt->kode_penyewaan,'id_penyewaan'=>$dt->id_penyewaan,'access'=>request()->input('access', session('access', null))])}}">Lihat Rincian <i class="fa fa-eye"></i></a></td>
                  <td>{{$dt->tipe_bayar ?? '-'}}</td>
                  <td>
                    @if($dt->jenis_pembayaran == 'kos')
                    <span class="badge bg-primary">Pembayaran Kos</span>
                    @else
                    <span class="badge bg-success">Pembayaran Lain-Lain</span>
                    @endif
                  </td>
                  <td>
                    @if($dt->tanggal_bayar != NULL)
                    {{tanggal_indonesia($dt->tanggal_bayar) ?? '-'}}
                    @else
                    -
                    @endif
                  </td>
                  <td>Rp. {{number_format($dt->total_tagihan,0,",",".")}}</td>
                  <td>{{$dt->status_bayar ?? 'Belum Bayar'}}</td>
                  <td>
                    @if($dt->tipe_bayar == 'Tunai')
                    -
                    @else
                    @if($dt->bukti_bayar != NULL)
                    <img src="{{asset('bukti_bayar')}}/{{$dt->bukti_bayar}}" width="65">
                    @endif
                    @endif
                  </td>
                  <td>{{$dt->keterangan_bayar}}</td>
                  @else
                  <td>{{$dt->name}}</td>
                  <td>{{$dt->nomor_kamar}} ({{$dt->nama_cabang}})</td>
                  <td>{{bulan_to_nama($dt->bulan_pembayaran)}} {{$dt->tahun_pembayaran}}</td>
                  <td>Rp. {{number_format($dt->total_tagihan,0,",",".")}}</td>
                  <td><b>{{$dt->status_bayar ?? 'Belum Bayar'}}</b></td>
                  <td><a href="{{route('detail_penyewan',['kode_penyewaan'=>$dt->kode_penyewaan,'id_penyewaan'=>$dt->id_penyewaan,'access'=>request()->input('access', session('access', null))])}}">Lihat Rincian <i class="fa fa-eye"></i></a></td>
                  @endif
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
  @section('css')
  <style type="text/css">
   .lds-spinner,
   .lds-spinner div,
   .lds-spinner div:after {
    box-sizing: border-box;
  }
  .lds-spinner {
    color: #000;
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
  }
  .lds-spinner div {
    transform-origin: 40px 40px;
    animation: lds-spinner 1.2s linear infinite;
  }
  .lds-spinner div:after {
    content: " ";
    display: block;
    position: absolute;
    top: 3.2px;
    left: 36.8px;
    width: 6.4px;
    height: 17.6px;
    border-radius: 20%;
    background: #000;
  }
  .lds-spinner div:nth-child(1) {
    transform: rotate(0deg);
    animation-delay: -1.1s;
  }
  .lds-spinner div:nth-child(2) {
    transform: rotate(30deg);
    animation-delay: -1s;
  }
  .lds-spinner div:nth-child(3) {
    transform: rotate(60deg);
    animation-delay: -0.9s;
  }
  .lds-spinner div:nth-child(4) {
    transform: rotate(90deg);
    animation-delay: -0.8s;
  }
  .lds-spinner div:nth-child(5) {
    transform: rotate(120deg);
    animation-delay: -0.7s;
  }
  .lds-spinner div:nth-child(6) {
    transform: rotate(150deg);
    animation-delay: -0.6s;
  }
  .lds-spinner div:nth-child(7) {
    transform: rotate(180deg);
    animation-delay: -0.5s;
  }
  .lds-spinner div:nth-child(8) {
    transform: rotate(210deg);
    animation-delay: -0.4s;
  }
  .lds-spinner div:nth-child(9) {
    transform: rotate(240deg);
    animation-delay: -0.3s;
  }
  .lds-spinner div:nth-child(10) {
    transform: rotate(270deg);
    animation-delay: -0.2s;
  }
  .lds-spinner div:nth-child(11) {
    transform: rotate(300deg);
    animation-delay: -0.1s;
  }
  .lds-spinner div:nth-child(12) {
    transform: rotate(330deg);
    animation-delay: 0s;
  }
  @keyframes lds-spinner {
    0% {
      opacity: 1;
    }
    100% {
      opacity: 0;
    }
  }
</style>
@endsection
@section('scripts')
<script type="text/javascript">
  $(function () {
    $('#table_pembayaran').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
    });
  });
  $(function () {
    $('.datatable').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
    });
  });
  $(document).on('click', '.delete_pembayaran', function (event) {
    bayarID = $(this).attr('more_id');
    jenisBayar = $(this).attr('more_jenis');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Pembayaran akan dihapus secara Permanent!',
      icon: 'warning',
      type: 'warning',
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#loading").show();
        $.ajax({
          method: "GET",
          url: "{{url('page/pengelolaan/penyewaan/bayar_tagihan/destroy')}}"+"/"+bayarID+"?jenis_bayar="+jenisBayar,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              showToast('bg-success','Pembayaran Dihapus',response.message);
              setTimeout(function(){
                document.location.href=''
              }, 200);
            }else{
              showToast('bg-danger','Pembayaran Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Pembayaran Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection
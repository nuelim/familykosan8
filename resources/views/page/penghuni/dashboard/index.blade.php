  @extends('page/layout/app')

  @section('title','Dashboard')

  @foreach($data as $dt)
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
              <li class="breadcrumb-item"><a href="">Penghuni</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">Selamat Datang ! 🎉</h5>
                <p class="mb-4">
                  Hai <b>{{Auth::user()->name}}</b>,
                  Selamat bergabung di kos kami! Semoga Anda betah dan merasa nyaman di sini.
                  Jika ada yang perlu dibantu, jangan ragu untuk menghubungi kami.
                </p>
                <a href="{{route('penghuni.detail_penyewan',['kode_penyewaan'=>$dt->kode_penyewaan,'id_penyewaan'=>$dt->id_penyewaan])}}" class="btn btn-sm btn-outline-primary">Lihat Detail Penyewaan</a>
                @if($dt->tenggat != NULL)
                <br><span class="text-danger">Batas Waktu Pembayaran : <span id="countdown" class="countdown"></span></span>
                @endif
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img
                src="{{asset('panel/assets/img/illustrations/man-with-laptop-light.png')}}"
                height="140"
                alt="View Badge User"
                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                data-app-light-img="illustrations/man-with-laptop-light.png" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
          <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <div class="avatar flex-shrink-0">
                    <img
                    src="{{asset('panel/assets/img/icons/unicons/chart-success.png')}}"
                    alt="chart success"
                    class="rounded" />
                  </div>
                  <div class="dropdown">
                    <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt3"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                    <a class="dropdown-item" href="{{route('penghuni.detail_penyewan',['kode_penyewaan'=>$dt->kode_penyewaan,'id_penyewaan'=>$dt->id_penyewaan])}}#tagihan-sudah-bayar">Lihat Detail</a>
                  </div>
                </div>
              </div>
              <span class="fw-medium d-block mb-1">Tagihan Sudah Dibayar</span>
              <h4 class="card-title mb-2">Rp. {{number_format($dt->tagihan_sudah_bayar,0,",",".")}}</h4>
              <!-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> -->
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <img
                  src="{{asset('panel/assets/img/icons/unicons/wallet-info.png')}}"
                  alt="Credit Card"
                  class="rounded" />
                </div>
                <div class="dropdown">
                  <button
                  class="btn p-0"
                  type="button"
                  id="cardOpt6"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                  <a class="dropdown-item" href="{{route('penghuni.detail_penyewan',['kode_penyewaan'=>$dt->kode_penyewaan,'id_penyewaan'=>$dt->id_penyewaan])}}#tagihan-belum-bayar">Lihat Detail</a>
                </div>
              </div>
            </div>
            <span>Tagihan Belum Dibayar</span>
            <h4 class="card-title mb-1">Rp. {{number_format($dt->tagihan_belum_bayar,0,",",".")}}</h4>
            <!-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
@section('scripts')
@if($dt->tenggat != NULL )
<script type="text/javascript">
  const endDateString = "{{ $dt->tenggat }}";
  const endDate = new Date(endDateString).getTime();
  function updateCountdown() {
    const now = new Date().getTime();
    const timeLeft = endDate - now;
    if (timeLeft <= 0) {
      document.getElementById('countdown').innerHTML = 'Waktu Habis';
      $(".new_form_bayar").hide();
      clearInterval(countdownInterval);
      return;
    }
    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    document.getElementById('countdown').innerHTML =
    minutes + " menit " + seconds + " detik ";
  }
  const countdownInterval = setInterval(updateCountdown, 1000);
  updateCountdown();
</script>
@endif
@endsection
@endforeach
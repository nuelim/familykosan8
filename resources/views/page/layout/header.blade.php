<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="bx bx-menu bx-sm"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  <!-- Search -->
  <div class="navbar-nav align-items-center flex-row">
    <div class="nav-item d-flex align-items-center" style="font-family: Times New Roman;text-align: center;font-weight: bold;">
      <img src="{{asset('logo-true.png')}}" width="40" height="40">
      <marquee>
        Gion Kos | Solusi Mudah untuk Penyewaan dan Pembayaran Kos | Manajemen Kos yang Efisien dan Transparan | {{ tanggal_indonesia(date('Y-m-d')) }} <span id="clock"></span>
      </marquee>
    </div>
  </div>
  <!-- /Search -->

  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Place this tag where you want the button to render. -->
    @if(Auth::user()->level == 'Super Admin')
    <li class="nav-item dropdown-user navbar-dropdown dropdown me-2 me-xl-0">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class='bx bx-location-plus bx-sm'></i><small><sup class="badge bg-info rounded-pill" style="right: 10px;">{{count($cabang)}}</sup></small>
      </a>
      <div class="dropdown-menu dropdown-menu-end py-0" id="header_cabang">
        <div class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h5 class="text-body mb-0 me-auto">Akses Cabang</h5>
            <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip" data-bs-placement="top">{{count($cabang)}}</a>
          </div>
        </div>
        <div class="dropdown-shortcuts-list scrollable-container">
          <div class="row row-bordered text-center overflow-visible g-0">
            <div class="dropdown-shortcuts-item col-lg-12" style="padding: 30px;">
              {!! empty(session('access')) ? '<sup style="float: right;" class="text-success"><i class="fa fa-check-circle"></i></sup>' : '' !!}
              <span class="text-secondary fw-medium">
                <i class="bx bxs-arch" style="font-size: 30px;"></i>
              </span>
              <br>
              <a href="javascript:void(0)" class="stretched-link beralih" more_name="Akses Semua Cabang" more_id="0">Semua Cabang</a>
              <br>
              <small class="text-muted mb-0">Beralih <i class="bx bx-chevron-right"></i></small>
            </div>
            @foreach($cabang as $cbg)
            <div class="dropdown-shortcuts-item col-lg-6" style="padding: 30px;">
              {!! session('access') == $cbg->kode_cabang ? '<sup style="float: right;" class="text-success"><i class="fa fa-check-circle"></i></sup>' : '' !!}
              <span class="text-secondary fw-medium">
                <i class="bx bxs-flame" style="font-size: 30px;"></i>
              </span>
              <br>
              <a href="javascript:void(0)" class="stretched-link beralih" more_name="{{$cbg->nama_cabang}}" more_id="{{$cbg->id_cabang}}">{{$cbg->nama_cabang}}</a>
              <br>
              <small class="text-muted mb-0">Beralih <i class="bx bx-chevron-right"></i></small>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </li>
    @endif
    @if(Auth::user()->level == 'Super Admin' OR Auth::user()->level == 'Operator')
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="bx bx-bell bx-sm"></i><small><sup class="badge bg-danger rounded-pill" id="count_notifikasi" style="right: 8px;"></sup></small>
      </a>
      <ul class="dropdown-menu dropdown-menu-end py-0" id="header_notifikasi">
        <li class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h5 class="text-body mb-0 me-auto">Notifikasi</h5>
            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title=""><i class="bx fs-4 bx-envelope-open"></i></a>
          </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
          <ul class="list-group list-group-flush" id="content_notifikasi">

          </ul>
        </li>
        <li class="dropdown-menu-footer border-top p-3">
          <a href="{{ route('index.pembayaran',['access'=>request()->input('access', session('access', null)),'keyword'=>'Sedang di cek','type'=>'pembayaran']) }}" class="btn btn-primary text-uppercase w-100" >Lihat Semua Pembayaran</a>
        </li>
      </ul>
    </li>
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <span class="position-relative">
          <i class="fa fa-bullhorn fa-lg"></i>
          <span id="count_reminder"></span>
        </span>
        <!-- <small><sup class="badge bg-danger rounded-pill" id="count_reminder" style="right: 8px;"></sup></small> -->
      </a>
      <ul class="dropdown-menu dropdown-menu-end py-0" id="header_reminder">
        <li class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h5 class="text-body mb-0 me-auto">Reminder Sewa (H-30)</h5>
            <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title=""><i class="bx bx-info-circle text-info" style="float: right;" data-bs-toggle="popover"
              data-bs-offset="0,14"
              data-bs-placement="top"
              data-bs-html="true"
              data-bs-content="Data yang ditampilkan adalah Akhir Sewa H-30 yang belum melakukan Pembayaran Kos."
              title="Informasi"></i>
            </a>
          </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
          <ul class="list-group list-group-flush" id="content_reminder">

          </ul>
        </li>
        <li class="dropdown-menu-footer border-top p-3">
          <a href="{{ route('penyewaan_reminder',['access'=>request()->input('access', session('access', null))]) }}" class="btn btn-primary text-uppercase w-100" >Lihat Semua</a>
        </li>
      </ul>
    </li>
    @endif
    <!-- path foto -->
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          @foreach($userprofil as $usp)
          @if($usp->foto == NULL)
          <img src="{{asset('thumbnail.png')}}" alt class="w-px-40 h-40 rounded-circle" />
          @else
          <img src="{{asset($path_foto)}}/{{$usp->foto}}" alt class="w-px-40 h-40 rounded-circle" />
          @endif
          @endforeach
          <!-- <img src="{{asset('panel/assets/img/avatars/1.png')}}" alt class="w-px-40 h-auto rounded-circle" /> -->
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="#">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                  @foreach($userprofil as $usp)
                  @if($usp->foto == NULL)
                  <img src="{{asset('thumbnail.png')}}" alt class="w-px-40 h-40 rounded-circle" />
                  @else
                  <img src="{{asset($path_foto)}}/{{$usp->foto}}" alt class="w-px-40 h-40 rounded-circle" />
                  @endif
                  @endforeach
                </div>
              </div>
              <div class="flex-grow-1">
                <span class="fw-medium d-block">{{Auth::user()->name}}</span>
                <small class="text-muted">{{Auth::user()->level}}</small>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item" href="{{route('myprofil')}}">
            <i class="bx bx-user me-2"></i>
            <span class="align-middle">Profil Saya</span>
          </a>
        </li>
        <li>
          <div class="dropdown-divider"></div>
        </li>
        <li>
          <a class="dropdown-item" href="{{route('logout')}}">
            <i class="bx bx-power-off me-2"></i>
            <span class="align-middle">Log Out</span>
          </a>
        </li>
      </ul>
    </li>
    <!--/ User -->
  </ul>
</div>
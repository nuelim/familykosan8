<?php
$currentRoute = request()->route()->getName();
$isDataMaster = false;
$isPengelolaan = false;
$isUser = false;
$isUser = false;
$isLaporan = false;
$isReminder = false;

if (in_array($currentRoute, ['index.kamar','index.fasilitas','index.cabang'])) {
  $isDataMaster = true;
}
if (in_array($currentRoute, ['index.operator','index.penghuni'])) {
  $isUser = true;
}
if (in_array($currentRoute, ['index.penyewaan','index.pembayaran','index.pengeluaran'])) {
  $isPengelolaan = true;
}
if (in_array($currentRoute, ['laporan.penyewaan','laporan.pembayaran','laporan.pengeluaran','laporan.keuangan'])) {
  $isLaporan = true;
}
if (in_array($currentRoute, ['penyewaan_reminder','riwayat_reminder'])) {
  $isReminder = true;
}
?>
<div class="app-brand demo">
  <a href="javascript:void(0)" class="app-brand-link mb-2" style="display: flex; align-items: center;">
    <div class="app-brand-logo demo">
      @foreach($userprofil as $usp)
      @if($usp->foto == NULL)
      <img src="{{asset('thumbnail.png')}}" alt class="rounded-circle" style="width: 50px;height: 50px;" />
      @else
      <img src="{{asset($path_foto)}}/{{$usp->foto}}" alt class="rounded-circle" style="width: 50px;height: 50px;" />
      @endif
      @endforeach
    </div>
    <div class="app-brand-text menu-text">
      <span class="user-name">{{implode(" ", array_slice(explode(" ",Auth::user()->name),0,2))}}</span>
      <span class="user-email mt-2">{{ Auth::user()->level }}</span>
    </div>
  </a>

  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
    <i class="bx bx-chevron-left bx-sm align-middle"></i>
  </a>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
  @if(Auth::user()->level == 'Penghuni')
  <li class="menu-header small text-uppercase">
    <span class="menu-header-text">Dashboard</span>
  </li>
  <li class="menu-item {{ (route('penghuni.dashboard') == url()->current()) ? ' active' : '' }}">
    <a
    href="{{route('penghuni.dashboard')}}"
    class="menu-link">
    <i class="menu-icon tf-icons bx bx-home"></i>
    <div data-i18n="Email">Dashboard</div>
  </a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Kotak Saran</span>
</li>
<li class="menu-item {{ (route('penghuni.aduan') == url()->current()) ? ' active' : '' }}">
  <a
  href="{{route('penghuni.aduan')}}"
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-box"></i>
  <div data-i18n="Email">Kotak Saran</div>
</a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Halaman Depan</span>
</li>
<li class="menu-item">
  <a
  href="{{route('home')}}"
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-search"></i>
  <div data-i18n="Email">Cari Kos</div>
</a>
</li>
@endif
@if(Auth::user()->level == 'Operator')
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Dashboard</span>
</li>
<li class="menu-item {{ (route('operator.dashboard') == url()->current()) ? ' active' : '' }}">
  <a
  href="{{route('operator.dashboard',['access'=>request()->input('access', session('access', null))])}}"
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-home"></i>
  <div data-i18n="Email">Dashboard</div>
</a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Master</span>
</li>
@endif
@if(Auth::user()->level == 'Super Admin')
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Dashboard</span>
</li>
<li class="menu-item {{ (route('index.dashboard') == url()->current()) ? ' active' : '' }}">
  <a
  href="{{route('index.dashboard',['access'=>request()->input('access', session('access', null))])}}"
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-home"></i>
  <div data-i18n="Email">Dashboard</div>
</a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Master</span>
</li>
<li class="menu-item{{ $isDataMaster ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-data"></i>
    <div data-i18n="Dashboards">Data Master</div>
    <div class="badge bg-label-primary rounded-pill ms-auto">3</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ (route('index.cabang') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.cabang',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Cabang</div>
      </a>
    </li>
    <li class="menu-item {{ (route('index.fasilitas') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.fasilitas',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Fasilitas</div>
      </a>
    </li>
    <li class="menu-item {{ (route('index.kamar') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.kamar',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Kamar</div>
      </a>
    </li>
  </ul>
</li>
@endif
@if(Auth::user()->level == 'Super Admin' OR Auth::user()->level == 'Operator')
<li class="menu-item{{ request()->routeIs('index.user*') ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-group"></i>
    <div data-i18n="Dashboards">Data User</div>
    @if(Auth::user()->level == 'Super Admin')
    <div class="badge bg-label-primary rounded-pill ms-auto">2</div>
    @else
    <div class="badge bg-label-primary rounded-pill ms-auto">1</div>
    @endif
  </a>
  <ul class="menu-sub">
    @if(Auth::user()->level == 'Super Admin')
    <li class="menu-item {{ (route('index.user','operator') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.user',['level'=>'operator','access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Operator</div>
      </a>
    </li>
    @endif
    <li class="menu-item {{ (route('index.user','penghuni') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('index.user',['level'=>'penghuni','access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Penghuni</div>
      </a>
    </li>
  </ul>
</li>
@endif
@if(Auth::user()->level == 'Super Admin')
<li class="menu-item">
  <a
  href="javascript:void(0)"
  class="menu-link new_reminder">
  <i class="menu-icon tf-icons fa bx bx-message"></i>
  <div data-i18n="">Pesan Reminder</div>
</a>
</li>
@endif
@if(Auth::user()->level == 'Super Admin' OR Auth::user()->level == 'Operator')
<li class="menu-item{{ $isReminder ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons fa fa-bullhorn"></i>
    <div data-i18n="Dashboards">Reminder Sewa</div>
    <div class="badge bg-label-primary rounded-pill ms-auto">2</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ (route('penyewaan_reminder') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('penyewaan_reminder',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Reminder H-30</div>
      </a>
    </li>
    <li class="menu-item {{ (route('riwayat_reminder') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('riwayat_reminder',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="">Riwayat Reminder</div>
      </a>
    </li>
  </ul>
</li>
@endif
@if(Auth::user()->level == 'Super Admin' OR Auth::user()->level == 'Operator')
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Kelola Kos</span>
</li>
<li class="menu-item {{ (request('type') !== 'tagihan' && $isPengelolaan) ? 'active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-scatter-chart"></i>
    <div data-i18n="Front Pages">Pengelolaan</div>
    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">3</div>
  </a>
  <ul class="menu-sub">
    <!-- Penyewaan -->
    <li class="menu-item {{ request()->routeIs('index.penyewaan') ? 'active' : '' }}">
      <a href="{{ route('index.penyewaan', ['access' => request('access', session('access'))]) }}" class="menu-link">
        <div data-i18n="Landing">Penyewaan</div>
      </a>
    </li>
    <!-- Pembayaran -->
    <li class="menu-item {{ request()->routeIs('index.pembayaran') && request('type') === 'pembayaran' ? 'active' : '' }}">
      <a href="{{ route('index.pembayaran', ['type' => 'pembayaran', 'access' => request('access', session('access'))]) }}" class="menu-link">
        <div data-i18n="Pricing">Pembayaran</div>
      </a>
    </li>
    <!-- Pengeluaran -->
    <li class="menu-item {{ request()->routeIs('index.pengeluaran') ? 'active' : '' }}">
      <a href="{{ route('index.pengeluaran', ['access' => request('access', session('access'))]) }}" class="menu-link">
        <div data-i18n="Payment">Pengeluaran</div>
      </a>
    </li>
  </ul>
</li>

<!-- Tagihan Bulan Ini -->
<li class="menu-item {{ request()->routeIs('index.pembayaran') && request('type') === 'tagihan' ? 'active' : '' }}">
  <a href="{{ route('index.pembayaran', ['type' => 'tagihan', 'access' => request('access', session('access'))]) }}" class="menu-link">
    <i class="menu-icon tf-icons bx bx-calendar"></i>
    <div data-i18n="Email">Tagihan Bulan ini</div>
  </a>
</li>

<li class="menu-item {{ (route('penghuni.aduan') == url()->current()) ? ' active' : '' }}">
  <a
  href="{{route('penghuni.aduan',['access'=>request()->input('access', session('access', null))])}}"
  class="menu-link">
  <i class="menu-icon tf-icons bx bx-box"></i>
  <div data-i18n="Email">Kotak Saran</div>
</a>
</li>
<li class="menu-header small text-uppercase">
  <span class="menu-header-text">Laporan</span>
</li>
<li class="menu-item{{ $isLaporan ? ' active open' : '' }}">
  <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-file"></i>
    <div data-i18n="Account Settings">Laporan</div>
    <div class="badge bg-label-primary fs-tiny rounded-pill ms-auto">4</div>
  </a>
  <ul class="menu-sub">
    <li class="menu-item {{ (route('laporan.penyewaan') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.penyewaan',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="Account">Penyewaan</div>
      </a>
    </li>
    <li class="menu-item {{ (route('laporan.pembayaran') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.pembayaran',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="Notifications">Pembayaran</div>
      </a>
    </li>
    <li class="menu-item {{ (route('laporan.pengeluaran') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.pengeluaran',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="Connections">Pengeluaran</div>
      </a>
    </li>
    <li class="menu-item {{ (route('laporan.keuangan') == url()->current()) ? ' active' : '' }}">
      <a href="{{route('laporan.keuangan',['access'=>request()->input('access', session('access', null))])}}" class="menu-link">
        <div data-i18n="Connections">Keuangan</div>
      </a>
    </li>
  </ul>
</li>
@endif
</ul>

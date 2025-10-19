{{-- resources/views/landing/index.blade.php --}}

@extends('landing.layout.app')
@section('content')

<div class="container-fluid hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hero-text">
                    <h1 class="display-4 fw-bold">Temukan Kosan Ideal Untuk Tinggal Dengan Nyaman</h1>
                    <p class="lead my-4">Selamat datang di FAMILY Kos! Temukan berbagai pilihan kosan yang nyaman dan terjangkau. Kami siap membantu Anda menemukan tempat tinggal yang sesuai dengan kebutuhan Anda.</p>
                    <a href="{{ url('/kamar') }}" class="btn btn-success btn-lg">Lihat Kamar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid search-bar">
    <div class="container">
        <form class="row g-3 align-items-center">
            <div class="col-md">
                <input type="text" class="form-control" placeholder="Harga Awal...">
            </div>
            <div class="col-md">
                <input type="text" class="form-control" placeholder="Harga Akhir...">
            </div>
            <div class="col-md">
                <select class="form-select">
                    <option selected>: PILIH CABANG :</option>
                    <option value="1">Cikarang</option>
                    <option value="2">Karawang</option>
                </select>
            </div>
            <div class="col-md">
                <select class="form-select">
                    <option selected>: PILIH JENIS :</option>
                    <option value="1">Pria</option>
                    <option value="2">Wanita</option>
                    <option value="3">Campur</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-dark w-100">Tampilkan <i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
</div>
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">CABANG KOS</span></h2>
        <p>Jelajahi berbagai cabang kos yang kami tawarkan.</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="cabang-kos-card">
                <div class="icon mb-3">
                    <i class="fa fa-city"></i>
                </div>
                <h5>GION PUSPA CIKARANG</h5>
                <p>1 Kamar</p>
            </div>
        </div>
    </div>
</div>
@endsection
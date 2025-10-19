@extends('home/layout/app')

@section('title', 'FAMILY Kos - Temukan Kosan Ideal Anda')

@section('content')
<section id="beranda" class="container-fluid py-5">
    <div class="row px-xl-5 align-items-center">
        <div class="col-lg-6">
            <h1 class="display-4 font-weight-bold mb-4">Temukan Kosan Ideal Untuk Tinggal Dengan Nyaman</h1>
            <p class="mb-4">Jelajahi berbagai pilihan kamar kos berkualitas di lokasi strategis. Kami menyediakan fasilitas lengkap untuk menunjang kenyamanan Anda.</p>
            <a href="#daftar-kamar" class="btn btn-success btn-lg scroll-link">Lihat Kamar</a>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <div id="image-slider" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('carousel/1.jpg') }}" class="d-block w-100 rounded" alt="Gambar Kos 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('carousel/2.jpg') }}" class="d-block w-100 rounded" alt="Gambar Kos 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('carousel/3.jpg') }}" class="d-block w-100 rounded" alt="Gambar Kos 3">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#image-slider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#image-slider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</section>



<section id="cabang-kos" class="container-fluid py-5 fade-in-section">
    <div class="text-center mb-5">
        <h2 class="section-title">CABANG KOS</h2>
        <p>Temukan kami di berbagai lokasi strategis.</p>
    </div>
    <div class="row px-xl-5 justify-content-center">
        @foreach($cabang->take(3) as $cbg)
        <div class="col-md-3">
            <div class="card text-center shadow-sm mb-4">
                <div class="card-body">
                    <i class="fas fa-building fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">{{ strtoupper($cbg->nama_cabang) }}</h5>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
<section id="daftar-kamar" class="container-fluid py-5 fade-in-section">
    <div class="px-xl-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
             <h2 class="section-title mb-0">Daftar Kamar Kos</h2>
             <span><strong>{{ $kamar->firstItem() }} - {{ $kamar->lastItem() }}</strong> dari <strong>{{ $kamar->total() }}</strong> Kamar</span>
        </div>
        <div class="row">
            @foreach($kamar as $kmr)
            <?php
            $kamar_fasilitas = DB::table('fasilitas')
                ->leftJoin('kamar_fasilitas', 'kamar_fasilitas.id_fasilitas', '=', 'fasilitas.id_fasilitas')
                ->where('kamar_fasilitas.id_kamar', $kmr->id_kamar)
                ->get();
            ?>
            <div class="col-md-3 col-12 mb-4">
                <a href="{{ route('view', ['id_kamar' => Crypt::encryptString($kmr->id_kamar)]) }}" style="text-decoration: none;">
                    <div class="card kos-card h-100 border-0 shadow-sm">
                        <div class="img-container">
                            <img src="{{ asset('gambar_kamar/' . $kmr->foto) }}" class="card-img-top" alt="Foto Kamar">
                        </div>
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">{{$kmr->jenis_kamar}}</span>
                            <p class="text-dark">KAMAR {{$kmr->nomor_kamar}} <br>
                                <b>{{$kmr->nama_cabang}}</b>
                            </p>
                            <p class="small text-muted">
                                @foreach($kamar_fasilitas->take(3) as $kf)
                                    <span>{{ $kf->nama_fasilitas }}</span>@if (!$loop->last) · @endif
                                @endforeach
                            </p>
                            <h5 class="text-primary mt-3">
                                Rp{{ number_format($kmr->harga_kamar ?? 0, 0, ',', '.') }}<span class="small">/bulan</span>
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
         <nav class="mt-4" aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        @if ($kamar->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $kamar->previousPageUrl() }}#daftar-kamar" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($kamar->links()->elements[0] as $page => $url)
            @if ($page == $kamar->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $url }}#daftar-kamar">{{ $page }}</a></li>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($kamar->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $kamar->nextPageUrl() }}#daftar-kamar" rel="next">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
</nav>
    </div>
</section>
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .kos-card .img-container {
        height: 200px;
        overflow: hidden;
    }
    .kos-card img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    .section-title {
        font-weight: 700;
        text-transform: uppercase;
    }

    .section-title::before,
    .section-title::after {
        display: none; 
    }

    /* Penyesuaian untuk judul "Cabang Kos" agar tetap di tengah */
    #cabang-kos .section-title {
        text-align: center;
    }

    /* */
    /* */
    .fade-in-section {
        opacity: 0;
        /* Mulai dari 50px ke bawah DAN sedikit mengecil */
        transform: translateY(50px) scale(0.9); 
        /* Transisi kita perlambat jadi 1 detik */
        transition: opacity 1s ease-out, transform 1s ease-out;
    }

    .fade-in-section.is-visible {
        opacity: 1;
        /* Kembali ke posisi normal (0px) DAN ukuran normal (1) */
        transform: translateY(0) scale(1); 
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function(){

  // --- Skrip Smooth Scroll ---
  $(".scroll-link").on('click', function(event) {
    if (this.pathname.replace(/^\//, '') == location.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) {
            return false;
          } else {
            $target.attr('tabindex','-1');
            $target.focus();
          };
        });
      }
    }
  });

  // --- Skrip Intersection Observer (Fade-in) ---
  const sections = document.querySelectorAll('.fade-in-section');

  if (sections.length > 0) { // Cek jika 'sections' benar-benar ada
    const options = {
      root: null, 
      rootMargin: '0px',
      threshold: 0.0 
    };

    const observer = new IntersectionObserver(function(entries, observer) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
        } else {
          entry.target.classList.remove('is-visible');
        }
      });
    }, options);

    sections.forEach(section => {
      observer.observe(section);
    });
  }

}); // <-- Ini adalah penutup $(document).ready() yang benar
</script>
@endsection
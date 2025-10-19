{{-- resources/views/landing/layout/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>FAMILY Kos - Temukan Kosan Ideal</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('home/css/custom.css') }}" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('logo-true.png') }}" alt="FAMILY Kos Logo" style="height: 40px;">
                <h3 class="m-0 text-uppercase text-dark ms-2">FAMILY Kos</h3>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="{{ url('/') }}" class="nav-item nav-link active">Beranda</a>
                    <a href="{{ url('/kamar') }}" class="nav-item nav-link">Kamar</a>
                    <a href="#" class="nav-item nav-link">Populer</a>
                    <a href="#" class="nav-item nav-link">Cabang Kos</a>
                </div>
            </div>
            <a href="/login" class="btn btn-success px-4 d-none d-lg-block">Login</a>
        </div>
    </nav>
    @yield('content')

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-phone-alt me-2"></i> +012 345 67890</li>
                        <li><i class="fa fa-envelope me-2"></i> superadmin@gmail.com</li>
                    </ul>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Cabang Kos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Kamar</a></li>
                        <li><a href="#">Kamar Rekomendasi</a></li>
                        <li><a href="#">Cabang Kos</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Galeri Foto Kos</h5>
                    <div class="row g-2">
                        @for ($i = 0; $i < 6; $i++)
                        <div class="col-4">
                           <img class="img-fluid bg-light p-1" src="https://via.placeholder.com/150" alt="">
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Saran dan Masukan</h5>
                    <p>Berikan saran dan masukan anda untuk kami.</p>
                    <form action="">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Isi...">
                            <button class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-bottom text-center text-white py-3" style="background-color: #233140;">
        &copy; FAMILY Kos. All Right Reserved. Designed By FAMILY KOSAN
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
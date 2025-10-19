<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Family Kos | @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicon -->
    <!-- <link href="img/favicon.ico" rel="icon"> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('logo-true.png')}}" />
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <!-- Libraries Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('home/css/style.css')}}" rel="stylesheet">
</head>
@yield('css')
<style type="text/css">
    #loading {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      z-index: 9999;
      text-align: center;
  }
  .swal2-container {
    z-index: 99999;
}
@media (min-width: 801px) {
  #loading{
    padding-top: 20%;
}
}
@media (max-width: 800px) {
  #loading{
    padding-top: 80%;
}
}
.select2-hidden-accessible + .select2-container .select2-selection {
  height: 36px;
  padding-top: 2px;
}
.select2-hidden-accessible + .select2-container .select2-selection__arrow, .select2-hidden-accessible + .select2-container .select2-selection_clear{
    height: 35px;
}
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}
select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
  background: #e8ebed;
  box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection_clear {
  display: none;
}
/* Fixed navbar for larger screens */
/*@media (min-width: 992px) {*/
    .fixed-top {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        margin: 0 auto;
        width: 100%;
        box-shadow: 0 5px 8px rgba(0, 0, 0, 0.3);
        transition: background-color 0.6s ease, box-shadow 0.3s ease; /* Tambahkan transisi */

    }
    /*}*/
    /* Menghilangkan fixed-top pada mobile */
    @media (max-width: 991px) {
    /*.navbar_fix {
        width: 100%;
        position: relative;
        }*/
        .menu_navbar{
            display: none;
        }
    }
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: none;
    }

    .popup {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #fff;
        transform: translateY(100%);
        transition: transform 0.3s ease-in-out;
        border-top-right-radius: 25px;
        border-top-left-radius: 25px;
        z-index: 1000;
    }

    .popup.open {
        transform: translateY(0);
    }

    .popup-content {
        padding: 20px;
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
    }

    .close-btn {
        background-color: #ccc;
        padding: 8px 12px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .close-btn:hover {
        background-color: #999;
    }
</style>
<body style="font-family: 'Poppins', sans-serif;">
   <div class="container-fluid">
   <!--  <div class="row bg-secondary py-2 px-xl-5 d-none d-lg-block">
        <div class="col-lg-12 text-right">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark px-2" href="" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="text-dark px-2" href="" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a class="text-dark pl-2" href="" target="_blank">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div> -->
    <?php
    use App\Models\Cabang;
    $cabang = Cabang::where('status_cabang', 'A')->get();
    $cabangSearch = request('cabang_search', null);
    $label_cabang = Cabang::where('id_cabang', $cabangSearch)->first();
    ?>
    <div class="row align-items-center py-1 px-xl-5 bg-light menu_navbar navbar_desktop">
        <div class="col-lg-4 d-none d-lg-block">
            <a href=" {{route('home')}} " class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold">FAMILY </span>KOST</h1>
                {!! $label_cabang ? '<strong style="color: #000;"><i class="fa fa-map-marker-alt"></i> ' . $label_cabang->nama_cabang . '</strong>' : '' !!}
            </a>
        </div>
        <div class="col-lg-4 col-6 d-none d-lg-block text-left">
            <form action=" {{route('home')}}#list_kamar ">
                <div class="input-group">
                    <select class="form-control cabang_search" id="cabang_search" name="cabang_search">
                        @foreach($cabang as $cbg)
                        <option value="{{$cbg->id_cabang}}">{{$cbg->nama_cabang}}</option>
                        @endforeach
                    </select>
                    <!-- <input type="text" class="form-control" placeholder="Cari Cabang Kamar Kos sesuai keperluan anda... "> -->
                    <div class="input-group-append">
                        <button class="input-group-text bg-transparent text-primary">
                            <i class="fa fa-search"></i>
                        </button>
                        @if(!empty($_GET['cabang_search']))
                        <a class="input-group-text bg-transparent text-primary" href="{{route('home')}}#list_kamar">
                            <i class="fas fa-sync"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4 col-12 text-center">
            @if(Auth::user())
            <a href="{{route('myprofil')}}" class="btn border">
                <i class="fa fa-user text-primary"></i>
                <span class="badge">{{implode(" ", array_slice(explode(" ",Auth::user()->name),0,2))}}</span>
            </a>
            <a href="{{route('logout')}}" class="btn border">
                <i class="fas fa-sign-out-alt text-primary"></i>
                <span class="badge">Logout</span>
            </a>
            @else
            <a href=" {{route('login')}} " class="btn border">
                <i class="fa fa-key  text-primary"></i>
                <span class="badge">Login</span>
            </a>
            <a href=" {{route('register')}} " class="btn border">
                <i class="fa fa-user-plus text-primary"></i>
                <span class="badge">Register</span>
            </a>
            @endif
        </div>
    </div>
    <div class="row border-top px-xl-5 navbar_mobile">
       <div class="col-lg-12">
        <nav class="navbar navbar-expand-lg bg-light navbar-light py-2 py-lg-0 px-0">
            <a href=" {{route('home')}} " class="text-decoration-none d-block d-lg-none">
                <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold">FAMILY</span> KOST</h1>
                {!! $label_cabang ? '<strong style="color: #000;"><i class="fa fa-map-marker-alt"></i> ' . $label_cabang->nama_cabang . '</strong>' : '' !!}
            </a>
            <div class="input-group navbar-toggler" style="max-width: 52%;border: 0px;margin: 0;padding: 0;" id="search_mobile">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-transparent"><i class="fa fa-search"></i></span>
              </div>
              <input type="search" class="form-control popup-trigger" placeholder="Cari Kamar sesuai dengan keperluan anda ....">
          </div>
          <div class=" justify-content-between" id="navbarCollapse">
            <div class="navbar-nav mx-auto py-0">
                <a href="#beranda" class="nav-item nav-link scroll-link">BERANDA</a>
                <a href="#daftar-kamar" class="nav-item nav-link scroll-link">LIST KAMAR</a>
                <a href="#cabang-kos" class="nav-item nav-link scroll-link">CABANG KOS</a>
                
            </div>
            <div class="navbar-nav ml-auto py-0">
                @if(Auth::user() && Auth::user()->level == 'Penghuni')
                <a href=" {{route('penghuni.dashboard')}} " class="nav-item nav-link"><i class="fa fa-home"></i> Riwayat Penyewaan Saya</a>
                @elseif(Auth::user() && Auth::user()->level == 'Super Admin')
                <a href=" {{route('index.dashboard')}} " class="nav-item nav-link"><i class="fa fa-home"></i> Dashboard</a>
                @elseif(Auth::user() && Auth::user()->level == 'Operator')
                <a href=" {{route('operator.dashboard')}} " class="nav-item nav-link"><i class="fa fa-home"></i> Dashboard</a>
                @endif
            </div>
        </div>
    </nav>
</div>
</div>
</div>
<!-- Navbar End -->

@yield('content')
<!-- Featured Start -->

<!-- Footer Start -->
<div class="container-fluid bg-secondary text-dark mt-5 pt-4 d-none d-lg-block">
    <div class="row px-xl-5">
        <div class="col-lg-5 col-md-12 mb-5 pr-3 pr-xl-5">
            <a href="" class="text-decoration-none">
                <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold">FAMILY</span> KOST</h1>
            </a>
            <p>Aplikasi ini dirancang untuk mempermudah penyewaan kos, dengan fitur pencarian kos berdasarkan lokasi, harga, dan fasilitas yang sesuai kebutuhan Anda.</p>
            <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Jl. Jatisari No.26a, RT.01/RW.06, Legi, Pepelegi, Kec. Waru, Kabupaten Sidoarjo, Jawa Timur 61256</p>
            <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>kosdante@gmail.com</p>
            <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+62 857 4827 5403</p>
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="row">
                <div class="col-md-6 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Cabang Family Kos</h5>
                    <div class="d-flex flex-column justify-content-start">
                        @foreach($cabang as $cbg)
                        <a class="text-dark mb-2" href="{{$cbg->link_cabang}}"><i class="fa fa-angle-right mr-2"></i>{{$cbg->nama_cabang}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Quick Links</h5>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-dark mb-2" href=" {{route('home')}} "><i class="fa fa-angle-right mr-2"></i>Beranda</a>
                        <a class="text-dark mb-2" href="{{route('home')}}#list_kamar"><i class="fa fa-angle-right mr-2"></i>List Kamar</a>
                        <a class="text-dark mb-2" href=" {{route('login')}} "><i class="fa fa-angle-right mr-2"></i>Login</a>
                        <a class="text-dark mb-2" href=""><i class="fa fa-angle-right mr-2"></i>Register/Buat Akun</a>
                        <a class="text-dark mb-2" href=""><i class="fa fa-angle-right mr-2"></i>Riwayat Penyewaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row border-top border-light mx-xl-5 py-4">
        <div class="col-md-12 px-xl-0">
            <p class="mb-md-0 text-center text-md-left text-dark">
                &copy; <a class="text-dark font-weight-semi-bold" href="javascript:void(0)">Family Kost</a>. All Rights Reserved. Designed
                by
                <a class="text-dark font-weight-semi-bold" href=" {route('home')} ">FAMILY KOSAN</a>
            </p>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal_form_cari" class="btn btn-primary search_kos text-white"><i class="fa fa-search"></i> Cari Kos</a>
<div class="whatsapp-fixed">
    <a href="https://wa.me/6285748275403" target="_blank">
        <img src="https://djpb.kemenkeu.go.id/kppn/makassar2/images/ICON_WA.png">
    </a>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('home/lib/easing/easing.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('home/mail/jqBootstrapValidation.min.js')}}"></script>
<script src="{{asset('home/mail/contact.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="{{asset('home/js/main.js')}}"></script>
</body>
<script type="text/javascript">
 $(document).ready(function () {
    var navbar = $(".navbar_desktop");
    var navbarMobile = $(".navbar_mobile");
    var sticky = navbar.offset().top;

    $(window).scroll(function () {
        if (window.innerWidth > 991) {
            // Desktop logic
            if (window.pageYOffset > sticky) {
                navbar.addClass("fixed-top");
            } else {
                navbar.removeClass("fixed-top");
            }
        } else {
            // Mobile logic
            if (window.pageYOffset > sticky) {
                navbarMobile.addClass("fixed-top");
            } else {
                navbarMobile.removeClass("fixed-top");
            }
        }
    });
});
</script>

<script type="text/javascript">
  document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && (event.key === 'u' || event.key === 'U')) {
      event.preventDefault();
  }
});
  document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.shiftKey && (event.key === 'i' || event.key === 'I')) {
      event.preventDefault();
  }
});
  document.addEventListener("contextmenu",function(e) {
    e.preventDefault();
}, false)
</script>
@yield('scripts')
</html>
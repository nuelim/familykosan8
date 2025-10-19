
<!DOCTYPE html>

<html
lang="en"
class="light-style layout-wide customizer-hide"
dir="ltr"
data-theme="theme-default"
data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>GION Kos | LUPA PASSWORD</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('logo-true.png')}}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet" />

  <link rel="stylesheet" href="{{asset('panel/assets/vendor/fonts/boxicons.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/css/demo.css')}}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/pages/page-auth.css')}}" />

  <!-- Helpers -->
  <script src="{{asset('panel/assets/vendor/js/helpers.js')}}"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js')}} in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('panel/assets/js/config.js')}}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bold" style="text-transform: uppercase;">GION Kos</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Lupa Password? 🔒</h4>
              <p class="mb-4">Masukkan akun email anda, dan akan terkirim verifikasi akun</p>
              <form id="forgotForm" method="post">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input autocomplete="off" type="email"
                  required
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="Masukkan Email anda"
                  autofocus />
                  <small class="text-danger" id="validasi_email"></small>
                </div>
                <button class="btn btn-primary w-100" id="send">KIRIM</button>
              </form>
              <div class="text-center mt-3">
                <a href="{{route('login')}}" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  Kembali ke login
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js')}} -->

    <script src="{{asset('panel/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/js/menu.js')}}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{asset('panel/assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js')}}"></script>
  </body>
  <script type="text/javascript">
    $(function () {
      $('#forgotForm').submit(function (e) {
        e.preventDefault();
        $("#send").html('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>');
        let formData = $(this).serializeArray();
        $.ajax({
          method: "POST",
          headers: {
            Accept: "application/json"
          },
          url: "{{route('proses_forgot')}}",
          data: formData,
          success: function (response) {
            $("#send").html('KIRIM');
            $("#forgotForm")[0].reset();
            if (response.status == 'true') {
              Swal.fire({
                icon: "success",
                type: "success",
                title: 'Success',
                text: response.message
              }).then((result) => {
                if (result.isConfirmed) {
                  document.location.href=" {{route('login')}} ";
                }
              });
            }else if(response.status == 'warning'){
             Swal.fire({
              icon: "warning",
              type: "warning",
              title: 'Email Empty',
              text: response.message
            });
           }else{
            Swal.fire({
              icon: "error",
              type: "error",
              title: 'Error',
              text: 'Terjadi Kesalahan [Permintaan data tidak dikirim]'
            });
          }
        },
        error: function (response) {
          $("#send").html('KIRIM');
          Swal.fire({
            icon: "error",
            type: "error",
            title: 'Error',
            text: 'Terjadi Kesalahan [Permintaan data tidak dikirim]'
          });
        }
      });
      });
    });  
    // var email = document.getElementById('email');
    $(document).on('keyup','#email',function() {
      var mail=$('#email').val();
      var atps=mail.indexOf("@");
      var dots=mail.lastIndexOf(".");
      if (mail !== '') {
        if (atps<1 || dots<atps+2 || dots+2>=mail.length || !document.getElementById("email").checkValidity())
        {
          $("#send").attr('disabled',true);
          document.getElementById('validasi_email').innerHTML = "Alamat email tidak valid";
        }
        else
        {
          $("#send").attr('disabled',false);
          document.getElementById('validasi_email').innerHTML = "";
        }
      }else{
        $("#send").attr('disabled',false);
        document.getElementById('validasi_email').innerHTML = "";
      }
    });
// 
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
</html>

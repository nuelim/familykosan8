<!DOCTYPE html>
<html
lang="en"
class="light-style layout-wide customizer-hide"
dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Family Kost | Register Akun</title>

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">
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
      <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
          <div class="authentication-wrapper container-p-y">
            <div class="authentication-inner">
              <!-- Register -->
              <div class="card">
                <div class="card-body">
                  <!-- Logo -->
                  <div class="app-brand justify-content-center">
                    <a href="" class="app-brand-link gap-2">
                      <span class="app-brand-text demo text-body fw-bold" style="text-transform: uppercase;">Family Kost</span>
                    </a>
                  </div>
                  <!-- /Logo -->
                  <p class="mb-4 text-center mt-3">Buat Akun | Lengkapi form akun dibawah ini dengan benar.</p>

                  <form id="penghuniForm" method="post" enctype="multipart/form-data" class="mb-3">
                    @csrf
                    <div class="row">
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Nama <span class="text-danger">*</span></label>
                          <input type="" hidden="" id="id_user" name="id_user">
                          <input type="text" class="form-control input_view" id="name" name="name">
                          <span class="invalid-feedback" role="alert" id="nameError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Email <span class="text-danger">*</span></label>
                          <input type="email" class="form-control input_view" id="email" name="email">
                          <span class="invalid-feedback" role="alert" id="emailError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">NIK <span class="text-danger">*</span></label>
                          <input type="number" class="form-control input_view" id="nik" name="nik">
                          <span class="invalid-feedback" role="alert" id="nikError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Tempat Lahir <span class="text-danger">*</span></label>
                          <input type="text" class="form-control input_view" id="tempat_lahir" name="tempat_lahir">
                          <span class="invalid-feedback" role="alert" id="tempat_lahirError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Tanggal Lahir <span class="text-danger">*</span></label>
                          <input type="date" class="form-control change_view" id="tgl_lahir" name="tgl_lahir">
                          <span class="invalid-feedback" role="alert" id="tgl_lahirError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">No Telepon <span class="text-danger">*</span></label>
                          <input type="number" class="form-control input_view" id="ponsel" name="ponsel">
                          <span class="invalid-feedback" role="alert" id="ponselError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Foto KTP <span class="text-danger label_req_ktp">*</span></label>
                          <input type="file" accept="image/*" class="form-control change_view" id="foto" name="foto">
                          <input type="" hidden="" class="form-control change_view" id="fotoLama" name="fotoLama">
                          <span class="invalid-feedback" role="alert" id="fotoError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Alamat <span class="text-danger">*</span></label>
                          <input class="form-control input_view" rows="4" id="alamat" name="alamat">
                          <span class="invalid-feedback" role="alert" id="alamatError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">No. Darurat <span class="text-danger">*</span></label>
                          <input type="number" class="form-control input_view" id="no_darurat" name="no_darurat">
                          <span class="invalid-feedback" role="alert" id="no_daruratError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6 mb-2">
                        <div class="form-group">
                          <label class="">Password <span class="text-danger">*</span></label>
                          <input class="form-control input_view" id="password" name="password">
                          <span class="invalid-feedback" role="alert" id="passwordError">
                            <strong></strong>
                          </span>
                        </div>
                      </div>
                      <select style="width: 100%;" hidden="" class="form-control change_view" name="status_user" id="status_user">
                        <option value="Aktif">Aktif</option>
                        <option value="Non Aktif">Non Aktif</option>
                      </select>
                      <div class="">
                       <div class="d-flex justify-content-between">
                        <a href="{{route('login')}}">
                          <small>Sudah punya akun? Login</small>
                        </a>
                      </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                      <button class="btn btn-primary w-100 submit">
                        <i class="bx bx-check"></i> <span>Register</span>
                      </button>
                    </div>
                  </div>
                </form>

              </div>
            </div>
            <!-- /Register -->
          </div>
        </div>
      </div>
      <div class="col-lg-2"></div>
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

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="{{asset('panel/assets/js/main.js')}}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
<script type="text/javascript">
 $(function () {
  $('#penghuniForm').submit(function (e) {
    e.preventDefault();
    if ($(this).data('submitted') === true) {
      return;
    }
    $(this).data('submitted', true);
    let formData = new FormData(this);
    $(".invalid-feedback").children("strong").text("");
    $("#penghuniForm input").removeClass("is-invalid");
    $("#penghuniForm select").removeClass("is-invalid");
    $(".submit").attr('disabled',true);
    $.ajax({
      method: "POST",
      headers: {
        Accept: "application/json"
      },
      contentType: false,
      processData: false,
      url : "{{route('save.penghuni')}}",
      data: formData,
      success: function (response) {
        $(".submit").attr('disabled',false);
        $('#penghuniForm').data('submitted', false);
        if (response.status == 'true') {
          $("#penghuniForm")[0].reset();
          Swal.fire({
            title: 'Register Success',
            text: response.message,
            icon: 'success',
            type: 'success'
          }).then((result) => {
            if (result.isConfirmed) {
              document.location.href = "{{route('login')}}";
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            type: "error",
            title: 'Error',
            text: 'Terjadi Kesalahan [Permintaan data tidak dikirim]'
          });
        }
      },
      error: function (response) {
        $('#penghuniForm').data('submitted', false);
        $(".submit").attr('disabled',false);
        if (response.status === 422) {
          let errors = response.responseJSON.errors;
          Object.keys(errors).forEach(function (key) {
            $("#" + key).addClass("is-invalid");
            $("#" + key + "Error").children("strong").text(errors[key][0]);
          });
        } else {
          Swal.fire({
            icon: "error",
            type: "error",
            title: 'Error',
            text: 'Terjadi Kesalahan [Permintaan data tidak dikirim]'
          });
        }
      }
    });
  });
});
</script>
</html>

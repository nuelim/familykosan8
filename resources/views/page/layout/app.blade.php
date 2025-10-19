<!DOCTYPE html>
<html
lang="en"
class="light-style layout-menu-fixed layout-compact"
dir="ltr"
data-theme="theme-default">
<head>
  <meta charset="utf-8" />
  <meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') | Family Kos</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{asset('logo-true.png')}}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet" />

  <!-- datatable -->
  <link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  
  <!-- <link href="{{asset('panel/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet"> -->
  <!-- <link href="{{asset('panel/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet"> -->
  <!-- <link href="{{asset('panel/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet"> -->
  <link href="{{asset('panel/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
  <!-- <link href="{{asset('panel/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  <!--  -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/fonts/boxicons.css')}}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/custom.css')}}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{asset('panel/assets/css/demo.css')}}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
  <link rel="stylesheet" href="{{asset('panel/assets/vendor/libs/apex-charts/apex-charts.css')}}" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">

  <!-- Helpers -->
  <script src="{{asset('panel/assets/vendor/js/helpers.js')}}"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('panel/assets/js/config.js')}}"></script>
  </head>
  <style type="text/css">
    .badge.badge-dot{display:inline-block;margin:0;padding:0;width:.5rem;height:.5rem;border-radius:50%;vertical-align:middle}.badge.badge-so{position:absolute;top:auto;display:inline-block;margin:0;transform:translate(-50%, -30%)}[dir=rtl] .badge.badge-so{transform:translate(50%, -30%)}.badge.badge-so:not(.badge-dot){padding:.05rem .2rem;font-size:.582rem;line-height:.75rem}
    .app-brand-logo {
      margin-right: 15px;
    }

    .app-brand-text {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .user-name {
      font-weight: bold;
    }

    .user-email {
      font-size: 0.9em;
      color: #666; /* Adjust color as needed */
    }
    .modal-loading {
      display: flex;
      justify-content: center;
      align-items: center;
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 9999;
      visibility: hidden;
    }
    .modal-body {
      position: relative;
    }
    .modal.show .modal-loading {
      visibility: visible;
    }
    #loading {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.5);
      z-index: 9999;
      text-align: center;
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
    #loading_page {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.5);
      z-index: 9999;
      text-align: center;
    }
    @media (min-width: 801px) {
      #loading_page{
        padding-top: 20%;
      }
      #header_notifikasi{
        width: 350px;
      }
      #header_reminder{
        width: 350px;
      }
      #header_cabang{
        width: 400px;
      }

    }
    @media (max-width: 800px) {
      #loading_page{
        padding-top: 80%;
      }
    }
    .swal2-container {
      z-index: 99999; /* Sesuaikan dengan z-index yang Anda butuhkan */
    }
    .select2-hidden-accessible + .select2-container .select2-selection {
      height: 36px;
      padding-top: 2px;
    }
    .select2-hidden-accessible + .select2-container .select2-selection__arrow, .select2-hidden-accessible + .select2-container .select2-selection_clear{
      height: 40px;
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
    .is-invalid:valid + .select2 .select2-selection{
      border-color: #dc3545!important;
    }
    *:focus{
      outline:0px;    }

     /* li.menu-item, a.menu-link{
        color: white;
        }*/
        @media (min-width: 801px) {
          .responsive-table{
            width: 1300px;
          }
        }
        @media (max-width: 800px) {
          .responsive-table thead{
            display: none;
          }
          .responsive-table tr {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
          }

          .responsive-table td {
            /*width: 100%;*/
            box-sizing: border-box;
            display: inline-block;
            /*font-size: 17px;*/
          }
          .responsive-table td:before {
            content: attr(data-label);
            font-weight: bold;
            display: block;
            /*font-size: 17px;*/
            margin-right: 8px;
          }
        }

        .lds-ellipsis,
        .lds-ellipsis div {
          box-sizing: border-box;
        }
        .lds-ellipsis {
          display: inline-block;
          position: relative;
          width: 80px;
          height: 80px;
        }
        .lds-ellipsis div {
          position: absolute;
          top: 33.33333px;
          width: 13.33333px;
          height: 13.33333px;
          border-radius: 50%;
          background: currentColor;
          animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
          left: 8px;
          animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
          left: 8px;
          animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
          left: 32px;
          animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
          left: 56px;
          animation: lds-ellipsis3 0.6s infinite;
        }
        @keyframes lds-ellipsis1 {
          0% {
            transform: scale(0);
          }
          100% {
            transform: scale(1);
          }
        }
        @keyframes lds-ellipsis3 {
          0% {
            transform: scale(1);
          }
          100% {
            transform: scale(0);
          }
        }
        @keyframes lds-ellipsis2 {
          0% {
            transform: translate(0, 0);
          }
          100% {
            transform: translate(24px, 0);
          }
        }
      </style>
      @yield('css')
      <body>
        <div class="loading_page" id="loading_page" style="display: none;text-align: center;">
        </div>
        <div
        class="bs-toast toast toast-placement-ex m-2"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="2000">
        <div class="toast-header">
          <i class="bx bx-bell me-2"></i>
          <div class="me-auto fw-medium" id="titleText"></div>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="messageText"></div>
      </div>
      <select class="form-select placement-dropdown" hidden="" id="selectPlacement">
        <option value="top-0 end-0">Top right</option>
      </select>
      <!-- Layout wrapper -->
      <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
          <!-- Menu -->
          <?php 
          $cabang = App\Models\Cabang::whereIn('id_cabang',session('site'))->where('status_cabang','A')->get();
          $userprofil = App\Models\User::getMyProfil();
          if (Auth::user()->level == 'Penghuni') {
            $path_foto = 'foto_ktp';
          }else{
            $path_foto = 'foto_profil';
          }
          ?>
          <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            @include('page/layout/sidebar')
          </aside>
          <!-- / Menu -->

          <!-- Layout container -->
          <div class="layout-page">
            <!-- Navbar -->

            <nav class="layout-navbar container-xxl navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar" style="width: 100%;">
              @include('page.layout.header')
            </nav>

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
              <!-- Content -->

              <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                  <div class="col-lg-12 mb-4 order-0">
                    @yield('content')
                    <div class="modal text-left" data-bs-backdrop="static" id="modal_form_reminder" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-fullscreen" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="myModalLabel1"></h5>
                          <a
                          href=""
                          class="btn-close"
                          ></a>
                        </div>
                        <div class="modal-body">
                         <form method="post" id="reminderForm" enctype="multipart/form-data">
                          @csrf
                          <?php  
                          $reminder = DB::table('reminder')->first();
                          ?>
                          <div class="row">
                            <div class="col-12">
                              <div class="form-group">
                                <label>Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="25" name="pesan_reminder" id="pesan_reminder">{{ $reminder->pesan_reminder ?? '' }}</textarea>
                                <span class="invalid-feedback" role="alert" id="pesan_reminderError">
                                  <strong></strong>
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            @include('page.layout.footer')
          </footer>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

   <!--  <div class="buy-now">
      <a
      href="https://themeselection.com/item/sneat-bootstrap-html-admin-template/"
      target="_blank"
      class="btn btn-danger btn-buy-now"
      >Upgrade to Pro</a
      >
    </div> -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{asset('panel/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('panel/assets/vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('panel/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <script src="{{asset('panel/assets/js/ui-popover.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('panel/assets/js/extended-ui-perfect-scrollbar.js')}}"></script>
    <script src="{{asset('panel/assets/js/main.js')}}"></script>
    <script src="{{asset('panel/assets/js/custom.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page JS -->
    <script src="{{asset('panel/assets/js/dashboards-analytics.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- datatable -->
    <script src="{{asset('panel/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('panel/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('panel/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  </body>
  @if(Auth::user()->level == 'Super Admin')
  <script type="text/javascript">
    $(document).on('click', '.beralih', function (event) {
      cabangID = $(this).attr('more_id');
      cabangName = $(this).attr('more_name');
      event.preventDefault();
      $("#loading_page").show();
      $("#loading_page").html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div><h4>'+cabangName+'</h4>');
      let currentUrl = window.location.href;
      let url = new URL(currentUrl);
      url.searchParams.delete('access');
      $.ajax({
        method: "GET",
        url: "{{url('page/beralih/access')}}"+"/"+cabangID,
        success:function(response)
        {
          if (response.status == 'true') {
            showToast('bg-primary','Beralih Success','Mohon menunggu proses beralih.');
            setTimeout(function(){
              $("#loading_page").hide();
              if (response.message == null) {
                window.location.href = url.toString();
              }else{
                document.location.href="?access="+response.message
              }
            }, 1500);
          }else{
            $("#loading_page").hide();
            showToast('bg-danger','Beralih Error',response.message);
          }
        },
        error: function(response) {
          $("#loading_page").hide();
          showToast('bg-danger','Beralih Error',response.message);
        }
      });
    });
  </script>
  @endif
  @if(Auth::user()->level == 'Super Admin' OR Auth::user()->level == 'Operator')
  <script type="text/javascript">
    function get_notifikasi(accessKode) {
      $.ajax({
        type: "GET",
        url: "{{route('get_notifikasi')}}",
        data: { access_kode: accessKode },
        success: function(response) {
          let html = "";
          response.forEach(item => {
            // const timeAgo = formatTimeAgo(item.created_at);
            html += `
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
            <div class="d-flex">
            <div class="flex-shrink-0 me-3">
            <div class="avatar">
            <img src="${item.foto ? `{{asset('foto_ktp')}}/${item.foto}` : '{{asset('thumbnail.png')}}'}" alt class="w-px-40 h-auto rounded-circle">
            </div>
            </div>
            <div class="flex-grow-1">
            <h6 class="mb-1">${item.name}</h6>
            <p class="mb-0">${item.keterangan}</p>
            <small class="text-muted">${item.time}</small>
            </div>
            <div class="flex-shrink-0 dropdown-notifications-actions">
            <a href="javascript:void(0)" class="dropdown-notifications-read">
            <span class="badge badge-dot"></span>
            </a>
            </div>
            </div>
            </li>`;
          });
          $("#content_notifikasi").html(html);
          $("#count_notifikasi").html(response.length);
        },
        error: function(response) {
          console.error("Error get notifikasi:", response);
        },
        complete: function() {
          setTimeout(function() {
            get_notifikasi(accessKode);
          }, 8000);
        }
      });
    }

    function formatRupiahReminder(value) {
      let stringValue = value.toString();
      let parts = stringValue.split(".");
      let wholePart = parts[0];
      let decimalPart = parts.length > 1 ? "." + parts[1] : "";
      let formattedWholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      let formattedValue = "Rp. " + formattedWholePart + decimalPart;
      return formattedValue;
    }
    function tanggal_indonesia_reminder(dateString) {
      const hariDalamSeminggu = [
      'Minggu',
      'Senin',
      'Selasa',
      'Rabu',
      'Kamis',
      'Jumat',
      'Sabtu'
      ];
      const bulan = [
      'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
      ];
      const tanggal = dateString.split('-');
      const hari = tanggal[2];
      const bulanIndex = parseInt(tanggal[1]) - 1;
      const tahun = tanggal[0];
      const dateObj = new Date(tahun, bulanIndex, hari);
      const namaHari = hariDalamSeminggu[dateObj.getDay()];
      return `${namaHari}, ${hari} ${bulan[bulanIndex]} ${tahun}`;
    }
    function get_reminder(accessKode) {
      $.ajax({
        type: "GET",
        url: "{{route('get_reminder')}}",
        data: { access_kode: accessKode },
        success: function(response) {
          let html = "";
          response.forEach(item => {
            const hub_penghuni = item.hari_tersisa <= 2 ? ' <small>(Segera untuk menghubungi Penghuni)</small>' : '';
            html += `
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
            <div class="d-flex">
            <div class="flex-shrink-0 me-3">
            <div class="avatar">
            <img src="{{asset('foto_ktp')}}/${item.foto}" alt class="w-px-40 h-auto rounded-circle">
            </div>
            </div>
            <div class="flex-grow-1">
            <h6 class="mb-1">Reminder Akhir Sewa: Kamar ${item.nomor_kamar}</h6>
            <p class="mb-0">
            <a href="{{url('page/pengelolaan/penyewaan/view')}}/${item.kode_penyewaan}-${item.id_penyewaan}?access=${accessKode}" class="text-muted">
            Sisa hari : H-${item.hari_tersisa}${hub_penghuni}<br>
            Total Tagihan: ${formatRupiahReminder(item.tagihan_belum_bayar, 'Rp. ')}
            </a>
            </p>
            <small class="text-muted">
            Tanggal Akhir Sewa: <u>${tanggal_indonesia_reminder(item.tanggal_selesai)}</u>
            </small>
            </div>
            <div class="flex-shrink-0 dropdown-notifications-actions">
            <a href="javascript:void(0)" class="dropdown-notifications-read">
            <span class="badge badge-dot bg-danger"></span>
            </a>
            </div>
            </div>
            </li>`;
          });
          $("#content_reminder").html(html);
          const so_reminder = response.length > 0 
          ? '<span class="badge bg-danger rounded-pill badge-dot badge-so"></span>'
          : '';
          $("#count_reminder").html(so_reminder);
        },
        error: function(response) {
          console.error("Error get notifikasi:", response);
        },
        complete: function() {
          setTimeout(function() {
            get_reminder(accessKode);
          }, 8000);
        }
      });
    }

    $(document).ready(function() {
      var access_kode = "{{request()->has('access') ? request()->input('access') : null}}";
      get_notifikasi(access_kode);
      get_reminder(access_kode);  
    });
  </script>
  @endif
  @if(Auth::user()->level == 'Super Admin')
  <script type="text/javascript">
    $(document).ready(function() {
      $(".new_reminder").click(function() {
        $("#reminderForm")[0].reset();
        $(".invalid-feedback").children("strong").text("");
        $("#reminderForm textarea").removeClass("is-invalid");
        $(".modal-title").html('<i class="fa fa-bullhorn"></i> Ubah Teks Pesan Pengingat');
        $("#modal_form_reminder").modal('show');
      });
    });
    $(document).ready(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      let previousValue = '';
      $(document).on('keyup', '#pesan_reminder', function(e) {
        let currentValue = $(this).val();
        if (currentValue !== previousValue) {
          $(".modal-title").html('<i class="fa fa-bullhorn"></i> Form Pesan Pengingat <i class="fa fa-spinner fa-pulse fa-1x"></i>');
          previousValue = currentValue;
          $.ajax({
            method: "POST",
            url : "{{ route('save_reminder') }}",
            data: {
              'pesan_reminder': currentValue
            },
            success: function (response) {
              if (response.status == 'true') {
                $(".modal-title").html('<i class="fa fa-bullhorn"></i> Form Pesan Pengingat  <i class="fa fa-check text-success"></i>');
              } else {
                showToast('bg-danger','Reminder Error',response.message);
              }
            },
            error: function (response) {
              showToast('bg-danger','Reminder Error',response.message);
            }
          });
        }
      });
    });
  </script>
  @endif
  
<!-- <script type="text/javascript">
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
</script> -->
@yield('scripts')
</html>

 @extends('home/layout/app')

 @foreach($data as $dt)
 @section('title',"$dt->nomor_kamar - $dt->nama_cabang")

 @section('content')
 <div class="container-fluid">
   <div id="loading">
    Loading....
  </div>

  <div class="row px-xl-5">
    <div class="col-xl-12 text-left">
      <h4><a href="javascript:void(0)" onclick="window.history.back()" class="text text-primary" id="go_back"><i class="fa fa-arrow-left"></i></a></h4>
    </div>
    <div class="col-lg-12 pb-3">
      <div id="product-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner border">
          <div class="carousel-item active">
            <img class="carousel-img w-100" src="{{asset('gambar_kamar')}}/{{$dt->foto}}" alt="Image">
          </div>
          @foreach($foto as $img)
          <div class="carousel-item">
            <img class="carousel-img w-100" src="{{asset('gambar_kamar')}}/{{$img->foto}}" alt="Image">
          </div>
          @endforeach
        </div>
        <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
          <i class="fa fa-2x fa-angle-left text-dark"></i>
        </a>
        <a class="carousel-control-next" href="#product-carousel" data-slide="next">
          <i class="fa fa-2x fa-angle-right text-dark"></i>
        </a>
      </div>
    </div>

    <div class="col-xl-12">
      <div class="row">
       <div class="col-lg-8">
        <h3 class="font-weight-semi-bold mb-3">Kos Kamar {{$dt->nomor_kamar}}
         <div class="dropend" style="float: right;">
          <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-share-alt"></i>
          </button>
          <ul class="dropdown-menu">
            <?php  
            $storeURL = route('view',Crypt::encryptString($dt->id_kamar));
            $productImageURL = asset('gambar_kamar/'.$dt->foto);
            $pesan = "Temukan Kos Impian Anda - Nomor Kamar ".$dt->nomor_kamar."\n";
            $pesan .= "Harga hanya Rp. ".number_format($dt->harga_kamar,0,",",".")." per bulan.\n\n";
            $pesan .= "Nikmati kenyamanan dan fasilitas terbaik di Family KOS. Jangan lewatkan kesempatan ini!\n";
            $pesan .= "Klik untuk melihat detail lebih lanjut: $storeURL";
            // $pesan .= "Lihat foto kamar yang tersedia di bawah ini.";
            $whatsappURL = 'https://api.whatsapp.com/send?text=' . urlencode($pesan) . '&amp;image=' . urlencode($productImageURL);
            ?>
            <li><a class="dropdown-item" target="_blank" href="<?php echo $whatsappURL; ?>"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
          </ul>
        </div>
        <div style="display: flex; align-items: center;">
          <h3 style="margin: 0;" class="text d-block d-lg-none">Rp. {{number_format($dt->harga_kamar,0,",",".")}}</h3>
          <span class="text d-block d-lg-none" style="font-size: 1rem; line-height: 1;">/bulan</span>
        </div>
      </h3>
      <span class="badge bg-transparent border border-dark">{{$dt->jenis_kamar}}</span>
      <span class="text">&nbsp;&nbsp;<i class="fa fa-map-marker-alt"></i> {{$dt->nama_cabang}}</span>
      <p class="text mt-3">Keterangan Kamar: {{$dt->keterangan_kamar}}</p>
      <hr>
      <h3 class="font-weight-semi-bold mb-3">Fasilitas Kamar</h3>
      <p class="" style="color: #000;">
        <div class="row">
          <?php
          $items = [];
          foreach ($fasilitas as $fst) {
            $items[] = '<span class="text text-primary"><i class="fa fa-check"></i></span> ' . $fst->nama_fasilitas;
          }

          $chunks = array_chunk($items, 5);

          foreach ($chunks as $column) {
            echo '<div class="col-md-4">';
            foreach ($column as $content) {
              echo $content . '<br>';
            }
            echo '</div>';
          }
          ?>
        </div>
      </p>
      <hr>
      <h3 class="font-weight-semi-bold">Lokasi Kos dalam maps</h3>
      <span class="text"><i class="fa fa-map-marker-alt"></i> {{$dt->nama_cabang}} / {{$dt->alamat_cabang}}</span>
      <div class="iframe-container mt-2">
        <iframe 
        frameborder="0" 
        scrolling="no" 
        marginheight="0" 
        marginwidth="0" 
        src="https://maps.google.com/maps?width=720&amp;height=600&amp;hl=en&amp;q={{$dt->alamat_cabang}}+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed">
      </iframe>
    </div>
  </div>
  <div class="col-lg-4 d-none d-lg-block" style="color: #000; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
   <div class="row no-gutters">
    <div class="col-lg-12 text-center mb-3 mt-3">
      <div style="display: flex; align-items: center;">
        <h3 style="margin: 0;">Rp. {{number_format($dt->harga_kamar,0,",",".")}}</h3>
        <span style="font-size: 1rem; line-height: 1;">/bulan</span>
      </div>
    </div>
    <div class="col-lg-12">
      @if(Auth::user())
      @if(Auth::user()->level == 'Penghuni')
      <button class="btn btn-primary btn-lg w-100 mt-4 text-white checkout" style="border-radius: 8px;">Sewa</button>
      @endif
      @else
      <a href=" {{route('login')}} " class="btn btn-primary btn-lg w-100 mt-4 text-white" style="border-radius: 8px;">Sewa</a>
      @endif
      <!-- <button class="btn btn-primary btn-lg w-100 mt-4 text-white checkout" style="border-radius: 8px;">Sewa</button> -->
    </div>
  </div>
</div>
</div>
</div>

</div>
</div>
<!-- nav mobile -->
<div class="container-fluid">
  <div class="row border-top px-xl-5 d-block d-lg-none">
    <nav class="navbar navbar-expand bg-light navbar-light py-0 py-lg-0 fixed-bottom">
      <ul class="navbar-nav nav-justified w-100" id="menu">
        <li class="nav-item">
          <!-- <button class="btn btn-primary btn-lg w-100 mt-4 text-white checkout" style="border-radius: 8px;">Sewa</button> -->
          @if(Auth::user())
          @if(Auth::user()->level == 'Penghuni')
          <button class="btn btn-primary btn-lg w-100 text-white checkout" style="border-radius: 8px;">Sewa</button>
          @endif
          @else
          <a href=" {{route('login')}} " class="btn btn-primary btn-lg w-100 text-white" style="border-radius: 8px;">Sewa</a>
          @endif
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- end noav mobile -->
@include('home.view.form')
@endsection
@endforeach
@section('css')
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
  .carousel-inner {
    max-height: 400px; /* Tetapkan tinggi maksimal carousel */
    overflow: hidden;  /* Sembunyikan konten yang melebihi container */
  }

  .carousel-img {
    object-fit: contain; /* Gambar tampil penuh tanpa terpotong */
    height: 100%;        /* Isi tinggi carousel sepenuhnya */
    max-height: 400px;   /* Sesuaikan dengan tinggi carousel */
  }
  .iframe-container {
    position: relative;
    width: 100%; /* Menggunakan lebar penuh */
    padding-top: 56.25%; /* Rasio aspek 16:9 */
    overflow: hidden;
  }

  .iframe-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
  }
  .input-group {
    display: flex;
    align-items: center;
  }

  .input-group .icon {
    padding: 0.5rem;
    background: #e9ecef;
    border: 1px solid #ced4da;
    border-right: none;
    border-radius: 4px 0 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .input-group input {
    border-radius: 0 4px 4px 0;
    border-left: none;
  }
  /* Hanya aktifkan fullscreen pada perangkat mobile (layar <= 575.98px) */
  @media (max-width: 575.98px) {
    .modal-fullscreen {
      width: 100vw;
      max-width: none;
      height: 100%;
      margin: 0;
    }
    .modal-fullscreen .modal-content {
      height: 100%;
      border: 0;
      border-radius: 0;
    }
    .modal-fullscreen .modal-footer,
    .modal-fullscreen .modal-header {
      border-radius: 0;
    }
    .modal-fullscreen .modal-body {
      overflow-y: auto;
    }
  }

  /* Nonaktifkan fullscreen untuk layar lebih besar */
  @media (min-width: 576px) {
    .modal-fullscreen {
      width: auto;
      max-width: 500px; /* Sesuaikan ukuran modal untuk desktop */
      height: auto;
      margin: 1.75rem auto;
    }
    .modal-fullscreen .modal-content {
      height: auto;
      /*border: 1px solid #dee2e6;*/
      border-radius: 0.3rem;
    }
  }

</style>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/main.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
  function tanggal_indonesia(dateString) {
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
    return `${hari} ${bulan[bulanIndex]} ${tahun}`;
  }
  $("#search_mobile").hide();
  $(".checkout").click(function() {
    $(this).html('<span class="spinner-border spinner-border-md text-white" role="status"></span>');
    $(".checkout").attr('disabled',true);
    setTimeout(function() {
      $(".checkout").html('Sewa');
      $("#loading").show();
      setTimeout(function() {
        $("#penyewaanForm")[0].reset();
        $("#calender_sewa").datepicker("setDate", null);
        $("#label_tanggal").html('');
        $("#tanggal_penyewaan").val('');
        $("#loading").hide();
        $(".checkout").attr('disabled',false);
        $(".invalid-feedback").children("strong").text("");
        $("#penyewaanForm input").removeClass("is-invalid");
        $("#modal_form").removeClass('fade').modal('toggle');
      }, 1000);
    }, 1500);
  });
  $(document).ready(function () {
    $("#calender_sewa").datepicker({
    dateFormat: "yy-mm-dd", // Format tanggal
    minDate: 0,            // Tanggal minimal adalah hari ini
    maxDate: "+1M",        // Tanggal maksimal adalah 1 bulan dari hari ini
    onSelect: function(dateText) {
      // console.log("Tanggal yang dipilih: " + dateText); // Aksi saat tanggal dipilih
      $("#label_tanggal").html('Tanggal mulai sewa: '+tanggal_indonesia(dateText));
      $("#tanggal_penyewaan").val(dateText);
    }
  });
  });


  // $(document).ready(function () {
  //   $('#tanggal_penyewaan').daterangepicker({
  //   singleDatePicker: true, // Hanya memilih satu tanggal
  //   locale: {
  //     format: 'YYYY-MM-DD',
  //     applyLabel: 'Terapkan',
  //     cancelLabel: 'Batal',
  //     daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
  //     monthNames: [
  //     'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  //     'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  //     ]
  //   },
  //   opens: 'center',
  //   drops: 'up',
  //   minDate: moment().format('YYYY-MM-DD'), // Tanggal minimal
  //       maxDate: moment().add(1, 'months').format('YYYY-MM-DD') // Tanggal maksimal 1 bulan dari sekarang
  //     }).on('cancel.daterangepicker', function(ev, picker) {
  //       $(this).val('');
  //     });
  //     $('#tanggal_penyewaan').val('');
  //   });

  $(".search_kos").attr('hidden',true);
  $(".whatsapp-fixed").attr('hidden',true);
  $("#kamar").addClass('active');
  $(function () {
    $('#penyewaanForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#penyewaanForm input").removeClass("is-invalid");
      $("#loading").show();
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url : "{{route('save.penyewaan')}}",
        data: formData,
        success: function (response) {
          $('#penyewaanForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#penyewaanForm")[0].reset();
            Swal.fire({
              title: 'Penyewaan Berhasil',
              text: response.message,
              icon: 'success',
              type: 'success'
            });
            document.location.href = response.redirect;
          }else if(response.status == 'warning'){
            Swal.fire({
              title: 'Penyewaan Warning',
              text: response.message,
              icon: 'warning',
              type: 'warning'
            });
          }else {
            Swal.fire({
              title: 'Penyewaan Error',
              text: response.message,
              icon: 'error',
              type: 'error'
            });
          }
        },
        error: function (response) {
          $('#penyewaanForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              var key_temp = key.replaceAll(".", "_");
              $("#" + key_temp).addClass("is-invalid");
              $("#" + key_temp + "Error").children("strong").text(errors[key][0]);
            });
          } else {
           Swal.fire({
            title: 'Penyewaan Error',
            text: response.message,
            icon: 'error',
            type: 'error'
          });
         }
       }
     });
    });
  });
</script>
@endsection
  @extends('page/layout/app')

  @section('title','Penyewaan Kos')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading" id="pagePenyewaan">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Pengelolaan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Penyewaan Kos</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Penyewaan Kos {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
            {!! request()->has('exp') ? '<br><strong>(Akhir Sewa : ' . date('F Y') . ')</strong>' : '' !!}
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Penyewaan
            </button><a href="{{route('index.penyewaan',['access'=>request()->has('access') ? request()->input('access') : null,'exp'=>'exp'])}}" class="btn btn-secondary btn-sm rounded-pill" style="float: right;margin-right: 5px;"><i class="bx bx-refresh"></i> Tampilkan Penyewaan yang habis bulan sekarang</a>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap dt-responsive" id="table_penyewaan" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="2">No. </th>
                  <!-- <th data-priority="3">Kode Penyewaan</th> -->
                  <th data-priority="4">Nama Penghuni</th>
                  <th data-priority="5">Kamar</th>
                  <th data-priority="6">Tarif</th>
                  <th data-priority="7">Tanggal Mulai Sewa</th>
                  <th data-priority="8">Tanggal Selesai Sewa</th>
                  <th data-priority="9">Jumlah Tagihan belum bayar</th>
                  <th data-priority="10">Total Tagihan belum bayar</th>
                  <th data-priority="11">Catatan</th>
                  <th data-priority="1">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0" >
                @foreach($data as $dt)
                <tr>
                  <td>{{$loop->index+1}}</td>
                  <!-- <td><b>{{$dt->kode_penyewaan}}</b></td> -->
                  <td>{{$dt->name}}</td>
                  <td>{{$dt->nama_cabang}} / {{$dt->nomor_kamar}}</td>
                  <td>Rp. {{number_format($dt->harga_kamar,0,",",".")}} / bulan</td>
                  <td>{{tanggal_indonesia($dt->tanggal_penyewaan)}}</td>
                  <td>{{tanggal_indonesia($dt->tanggal_selesai)}}</td>
                  <td>{{$dt->jumlah_tagihan}} bulan</td>
                  <td>Rp. {{number_format($dt->total_tagihan,0,",",".")}}</td>
                  <td>{{$dt->catatan_penyewaan}}</td>
                  <td>
                    <a href="javascript:void(0)" more_id="{{$dt->id_penyewaan}}" class="btn edit btn-sm rounded-pill text-white btn-success"><i class="bx bx-edit"></i></a>
                    <a href=" {{route('detail_penyewan',['kode_penyewaan'=>$dt->kode_penyewaan,'id_penyewaan'=>$dt->id_penyewaan,'access'=>request()->input('access', session('access', null))])}} " class="btn btn-sm rounded-pill text-white btn-info"><i class="bx bx-detail"></i></a>
                    <a href="javascript" more_id="{{$dt->id_penyewaan}}" class="btn delete btn-sm btn-danger rounded-pill" title='Hapus Penyewaan'><i class="bx bx-trash"></i></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!--  -->
  @include('page.super_admin.penyewaan.form')
  @endsection
  @section('css')
  <style type="text/css">
    .lds-spinner,
    .lds-spinner div,
    .lds-spinner div:after {
      box-sizing: border-box;
    }
    .lds-spinner {
      color: #000;
      display: inline-block;
      position: relative;
      width: 80px;
      height: 80px;
    }
    .lds-spinner div {
      transform-origin: 40px 40px;
      animation: lds-spinner 1.2s linear infinite;
    }
    .lds-spinner div:after {
      content: " ";
      display: block;
      position: absolute;
      top: 3.2px;
      left: 36.8px;
      width: 6.4px;
      height: 17.6px;
      border-radius: 20%;
      background: #000;
    }
    .lds-spinner div:nth-child(1) {
      transform: rotate(0deg);
      animation-delay: -1.1s;
    }
    .lds-spinner div:nth-child(2) {
      transform: rotate(30deg);
      animation-delay: -1s;
    }
    .lds-spinner div:nth-child(3) {
      transform: rotate(60deg);
      animation-delay: -0.9s;
    }
    .lds-spinner div:nth-child(4) {
      transform: rotate(90deg);
      animation-delay: -0.8s;
    }
    .lds-spinner div:nth-child(5) {
      transform: rotate(120deg);
      animation-delay: -0.7s;
    }
    .lds-spinner div:nth-child(6) {
      transform: rotate(150deg);
      animation-delay: -0.6s;
    }
    .lds-spinner div:nth-child(7) {
      transform: rotate(180deg);
      animation-delay: -0.5s;
    }
    .lds-spinner div:nth-child(8) {
      transform: rotate(210deg);
      animation-delay: -0.4s;
    }
    .lds-spinner div:nth-child(9) {
      transform: rotate(240deg);
      animation-delay: -0.3s;
    }
    .lds-spinner div:nth-child(10) {
      transform: rotate(270deg);
      animation-delay: -0.2s;
    }
    .lds-spinner div:nth-child(11) {
      transform: rotate(300deg);
      animation-delay: -0.1s;
    }
    .lds-spinner div:nth-child(12) {
      transform: rotate(330deg);
      animation-delay: 0s;
    }
    @keyframes lds-spinner {
      0% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
  </style>
  @endsection
  @section('scripts')
  <script type="text/javascript">
    var access = "{{request()->has('access') ? request()->input('access') : null}}";
    function formatRupiah(angka, prefix) {
    // Pastikan angka tidak null atau undefined, jika iya set ke '0'
    if (!angka) angka = '0';

    let numberString = angka.toString().replace(/[^,\d]/g, ''),
    split = numberString.split(','),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
      let separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
  }

  $(function () {
    $('#table_penyewaan').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
    });
    $("#table_kamar").DataTable({
     processing: true,
     pageLength: 10,
     responsive: true
   });
  });
  var text_id_kamar;
  var text_cabang;
  var text_kamar;
  var text_jenis;
  var text_jml;
  var text_harga;
  var text_keterangan;
  var tipe_input;
  $(document).ready(function() {
    $(document).on('click','.pilih',function() {
      var more_id = $(this).attr('more_id');
      var cabang = $(this).attr('more_cabang');
      var cabang_id = $(this).attr('more_id_cabang');
      var kamar = $(this).attr('more_kamar');
      var jenis = $(this).attr('more_jenis');
      var jml = $(this).attr('more_jml');
      var harga = $(this).attr('more_harga');
      var keterangan = $(this).attr('more_keterangan');
      $("#pilih_"+more_id).html('<i class="fa fa-spinner fa-pulse fa-2x"></i>');
      setTimeout(function() {
        $(".pilih").attr('hidden',true);
        $(".pilih").html('<i class="bx bx-check"></i>');
        $("#done_change_"+more_id).attr('hidden',false);
        $(".cancel").attr('hidden',false);
        $("#id_kamar").val(more_id);
        if (tipe_input == 'edit') {
          $("#label_pindah").attr('hidden',false);
        }
          // $("#cabang_kamar").val(cabang_id);
      // 
      $("#id_cabang").val(cabang_id).trigger('change');
      $("#nomor_kamar").html(kamar);
      $("#harga_kamar").html(harga);
      $("#nama_cabang").html(cabang);
      $("#jenis_kamar").html(jenis);
      $("#jumlah_fasilitas").html(jml);
      $("#keterangan_kamar").html(keterangan);
    }, 300);
    });
  });
  $(".cancel").click(function() {
    $(this).attr('hidden',true);
    $(".pilih").attr('hidden',false);
    $(".done_change").attr('hidden',true);
    $("#id_kamar").val('');
    $("#id_cabang").val(null).trigger('change');
    $("#label_pindah").attr('hidden',true);
    $(".view_akun").html('');
    if (tipe_input == 'edit') {
     $("#id_kamar").val(text_id_kamar);
     $("#nomor_kamar").html(text_kamar);
     $("#harga_kamar").html(formatRupiah(text_harga,'Rp. '));

     $("#nama_cabang").html(text_cabang);
     $("#jenis_kamar").html(text_jenis);
     $("#jumlah_fasilitas").html(text_jml);
     $("#keterangan_kamar").html(text_keterangan);
   }
 });
  function get_penghuni() {
    $("#id_user").empty();
    $.ajax({
      type: "GET",
      url: "{{route('get_penghuni')}}",
      data: {access: access},
      success: function(response) {
        $.each(response, function(key, value) {
          $("#id_user").append('<option value="'+value.id+'" more_nik="'+value.nik+'" more_email="'+value.email+'" more_tgl_lahir="'+value.tgl_lahir+'" more_tempat_lahir="'+value.tempat_lahir+'" more_telepon="'+value.ponsel+'" more_alamat="'+value.alamat+'" more_no_darurat="'+value.no_darurat+'" more_id_cabang="'+value.id_cabang+'"">'+value.name+' (Cabang : '+value.nama_cabang+')'+'</option>');
        });
        $("#id_user").val(null).trigger('change');
      },
      error: function(response) {
        get_penghuni();
      }
    });
  }
  $("#id_user").select2({
    placeholder: ".: PILIH PENGHUNI .:"
  }).on('change',function() {
    var userID = $(this).val();
    if (userID) {
      const selectElement = document.getElementById('id_user');
      const selectedOption = selectElement.options[selectElement.selectedIndex];
      var more_nik = selectedOption.getAttribute('more_nik');
      var more_email = selectedOption.getAttribute('more_email');
      var more_tempat_lahir = selectedOption.getAttribute('more_tempat_lahir');
      var more_telepon = selectedOption.getAttribute('more_telepon');
      var more_tgl_lahir = selectedOption.getAttribute('more_tgl_lahir');
      var more_alamat = selectedOption.getAttribute('more_alamat');
      var more_no_darurat = selectedOption.getAttribute('more_no_darurat');
      var more_id_cabang = selectedOption.getAttribute('more_id_cabang');
      $("#nik_change").val(more_nik);
      $("#email_change").val(more_email);
      $("#tgl_lahir_change").val(more_tgl_lahir);
      $("#tempat_lahir_change").val(more_tempat_lahir);
      $("#ponsel_change").val(more_telepon);
      $("#alamat_change").val(more_alamat);
      $("#no_darurat_change").val(more_no_darurat);
        // $("#cabang_user").val(more_id_cabang)
      }else{
      }
    });
  let ajaxUrl;
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
      $("#penyewaanForm select").removeClass("is-invalid");
      $("#penyewaanForm textarea").removeClass("is-invalid");
      $(".error-tab").html("");
      $("#loading").show();
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url : ajaxUrl,
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
            setTimeout(function() {
              document.location.href=response.redirect;
            }, 100);
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
              var tab_id = $("#" + key_temp + "Error").closest(".tab-pane").attr("id");
              if (tab_id != undefined) {
                $("#tab_detail").find("[href$='#" + tab_id + "']").find(".error-tab").html("<i class='bx bx-info-circle'></i> Required");
              }
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

    return `${hari} ${bulan[bulanIndex]} ${tahun}`;
  }
  $("#status_user").select2({
    placeholder: ".: PILIH STATUS :.",
    dropdownParent: $("#modal_form_penghuni")
  });
  $("#id_cabang").select2({
    placeholder: ".: PILIHAN CABANG :.",
    dropdownParent: $("#modal_form_penghuni")
  });
  $("#status_penyewaan").select2({
    placeholder: ".: STATUS PENYEWAAN :."
  });
  $(document).ready(function() {
    $(".new_penghuni").click(function() {
      $("#loading").show();
      setTimeout(function() {
        $("#loading").hide();
        $("#penghuniForm")[0].reset();
        $(".invalid-feedback").children("strong").text("");
        $("#penghuniForm input").removeClass("is-invalid");
        $("#penghuniForm select").removeClass("is-invalid");
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Penghuni');
          // $(".view_akun").html('');
          // $(".img_view").css('display','none');
          $("#status_user").val(null).trigger('change');
          $("#id_cabang").val(null).trigger('change');
          $(".label_req_ktp").text('*');
          $(".cancel").trigger('click');
          $("#modal_form_penghuni").modal('show');
        }, 300);
    });
  });
    // $(".input_view").keyup(function() {
    //   var view = $(this).attr('id');
    //   $("#"+view+'_view').html($(this).val());
    // });
    $(".change_view").change(function() {
      var view = $(this).attr('id');
      // if (view == 'tgl_lahir' ) {
      //   $("#"+view+'_view').html(tanggal_indonesia($(this).val()));
      // }else if(view == 'status_user'){
      //   $("#"+view+'_view').html($(this).val());
      // }else if(view == 'id_cabang'){
      //   if ($(this).val()) {
      //     const selectElement = document.getElementById('id_cabang');
      //     const selectedOption = selectElement.options[selectElement.selectedIndex];
      //     var more_nama = selectedOption.getAttribute('more_id');
      //     $("#"+view+'_view').html(more_nama);
      //   }
      // }else{
        if (this.files && this.files[0]) {
          show_loading();
          var file = this.files[0];
          var allowedExtensions = ['png','jpg','jpeg'];
          var fileExtension = file.name.split('.').pop().toLowerCase();

          if (!allowedExtensions.includes(fileExtension)) {
            alert('File Dokumen tidak didukung.');
            $("#foto").val('');
            $("#foto").empty('');
            hide_loading();
            return;
          }
          $('.img_view').css('display','none');
          var reader = new FileReader();
          reader.onload = function (e) {
            setTimeout(function() {
              hide_loading();
              $('.img_view').css('display','block');
              $('.img_view').attr('src',e.target.result);
            }, 500);
          };
          reader.readAsDataURL(this.files[0]);
        } else {
          $('.img_view').css('display','none');
          $('#foto').val('');
          $('#foto').empty('');
        }
      // }
    });
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
        $("#loading").show();
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
            $('#penghuniForm').data('submitted', false);
            $("#loading").hide();
            if (response.status == 'true') {
              $("#penghuniForm")[0].reset();
              $('#modal_form_penghuni').modal('hide');
              showToast('bg-primary','Penghuni Success',response.message);
              $("#id_user").empty();
              setTimeout(function() {
                get_penghuni();
              }, 50);
            } else {
              showToast('bg-danger','Penghuni Error',response.message);
            }
          },
          error: function (response) {
            $('#penghuniForm').data('submitted', false);
            $("#loading").hide();
            if (response.status === 422) {
              let errors = response.responseJSON.errors;
              Object.keys(errors).forEach(function (key) {
                $("#" + key).addClass("is-invalid");
                $("#" + key + "Error").children("strong").text(errors[key][0]);
              });
            } else {
              showToast('bg-danger','Penghuni Error',response.message);
            }
          }
        });
      });
    });
    $(document).ready(function() {
      $(".new").click(function() {
        $("#loading").show();
        $("#pagePenyewaan").css('display','none');
        tipe_input = 'new';
        $(".cancel").trigger('click');
        $("#label_pindah").attr('hidden',true);
        setTimeout(function() {
          $("#loading").hide();
          $("#penyewaanForm")[0].reset();
          $(".invalid-feedback").children("strong").text("");
          $("#penyewaanForm input").removeClass("is-invalid");
          $("#penyewaanForm select").removeClass("is-invalid");
          $("#penyewaanForm textarea").removeClass("is-invalid");
          $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Penyewaan');
          $(".error-tab").html("");
          $("#col_status_penyewaan").hide();
          $("#status_penyewaan").val(null).trigger('change');
          $("#id_user").empty();
          $("#id_user").attr('readonly',false);
          $("#tanggal_penyewaan").attr('disabled',false);
          $(".new_penghuni").show();
          $("#col_table_kamar").show();
          get_penghuni();
          $("#pagePenyewaanForm").css('display','block');
          ajaxUrl = "{{route('save.penyewaan')}}";
          $("#id_user").val(null).trigger('change');
        }, 200);
      });
    });
    function get_edit(penyewaanID) {
      $.ajax({
        type: "GET",
        url: "{{url('page/pengelolaan/penyewaan/get_edit')}}"+"/"+penyewaanID,
        success: function(response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#id_user").append('<option value="'+value.name+'">'+value.name+' (Cabang : '+value.nama_cabang+')'+'</option>');
            $("#nik_change").val(value.nik);
            $("#email_change").val(value.email);
            $("#tgl_lahir_change").val(tanggal_indonesia(value.tgl_lahir));
            $("#tempat_lahir_change").val(value.tempat_lahir);
            $("#ponsel_change").val(value.ponsel);
            $("#alamat_change").val(value.alamat);
            $("#no_darurat_change").val(value.no_darurat);
            $("#tanggal_penyewaan").val(value.tanggal_penyewaan);
            $("#tanggal_selesai").val(value.tanggal_selesai);
            // $("#rentang_bayar").val(value.rentang_bayar).trigger('change');
            $("#status_penyewaan").val(value.status_penyewaan).trigger('change');
            $("#catatan_penyewaan").val(value.catatan_penyewaan);
            $("#id_penyewaan").val(value.id_penyewaan);
            // // 
            text_id_kamar = value.id_kamar;
            text_kamar = value.nomor_kamar;
            text_jenis = value.jenis_kamar;
            text_cabang = value.nama_cabang;
            text_harga = formatRupiah(value.harga_kamar,'Rp. ');
            text_jml = value.jumlah_fasilitas;
            text_keterangan = value.keterangan_kamar;
            // 
            $("#id_kamar").val(value.id_kamar);
            $("#nomor_kamar").html(value.nomor_kamar);
            $("#harga_kamar").html(formatRupiah(value.harga_kamar,'Rp. '));
            $("#nama_cabang").html(value.nama_cabang);
            $("#jenis_kamar").html(value.jenis_kamar);
            $("#jumlah_fasilitas").html(value.jumlah_fasilitas);
            $("#keterangan_kamar").html(value.keterangan_kamar);
          });
        },
        error: function(response) {
          get_edit(penyewaanID);
        }
      });
    }
    $(document).on('click','.edit',function() {
      var penyewaanID = $(this).attr('more_id');
      $("#loading").show();
      $("#pagePenyewaan").css('display','none');
      $("#penyewaanForm")[0].reset();
      tipe_input = 'edit';
      $(".cancel").trigger('click');
      $("#label_pindah").attr('hidden',true);
      $(".invalid-feedback").children("strong").text("");
      $("#penyewaanForm input").removeClass("is-invalid");
      $("#penyewaanForm select").removeClass("is-invalid");
      $("#penyewaanForm textarea").removeClass("is-invalid");
      $(".modal-title").html('<i class="bx bx-edit"></i> Form Edit Penyewaan');
      $(".error-tab").html("");
      $("#status_penyewaan").val(null).trigger('change');
      $("#pagePenyewaanForm").css('display','block');
      $("#col_status_penyewaan").show();
      $("#id_user").empty();
      $("#id_user").attr('readonly',true);
      $("#tanggal_penyewaan").attr('disabled',true);
        // $("#rentang_bayar").attr('disabled',true);
        $(".new_penghuni").hide();
        $("#col_table_kamar").show();
        ajaxUrl = "{{route('update.penyewaan')}}";
        if (penyewaanID) {
          get_edit(penyewaanID);
        }
      });
    $(".close").click(function() {
      $("#pagePenyewaan").css('display','block');
      $("#pagePenyewaanForm").css('display','none');
    });
    $(document).on('click', '.delete', function (event) {
      penyewaanID = $(this).attr('more_id');
      event.preventDefault();
      Swal.fire({
        title: 'Lanjut Hapus Data?',
        text: 'Data Penyewaan akan dihapus secara Permanent!',
        icon: 'warning',
        type: 'warning',
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: 'Lanjutkan'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#loading").show();
          $.ajax({
            method: "GET",
            url: "{{url('page/pengelolaan/penyewaan/destroy')}}"+"/"+penyewaanID,
            success:function(response)
            {
              $("#loading").hide();
              if (response.status == 'true') {
                showToast('bg-success','Penyewaan Dihapus',response.message);
                setTimeout(function(){
                  document.location.href=''
                }, 200);
              }else{
                showToast('bg-danger','Penyewaan Error',response.message);
              }
            },
            error: function(response) {
              $("#loading").hide();
              showToast('bg-danger','Penyewaan Error',response.message);
            }
          })
        }
      });
    });
  </script>
<!--   <script type="text/javascript">
    $(function () {
      $('#table_kamar').DataTable({
        processing: true,
        pageLength: 10,
        responsive: true,
        colReorder: true,
        responsive: true,
        ajax: {
          url: "{{ route('index.kamar') }}",
          error: function (jqXHR, textStatus, errorThrown) {
            $('#table_kamar').DataTable().ajax.reload();
          }
        },
        columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex'},
        { 
          data: 'nama_cabang', 
          name: 'nama_cabang', 
          render: function (data, type, row) {
            return data;
          }  
        },
        { 
          data: 'nomor_kamar', 
          name: 'nomor_kamar', 
          render: function (data, type, row) {
            return data;
          }  
        },
        { 
          data: 'harga_kamar', 
          name: 'harga_kamar', 
          render: function (data, type, row) {
            return formatRupiah(data,'Rp. ');
          }  
        },
        { 
          data: 'jenis_kamar', 
          name: 'jenis_kamar', 
          render: function (data, type, row) {
            return data;
          }  
        },
        { 
          data: 'jumlah_fasilitas', 
          name: 'jumlah_fasilitas', 
          render: function (data, type, row) {
            return data+' Fasilitas';
          }  
        },
        { 
          data: 'status_kamar', 
          name: 'status_kamar', 
          render: function (data, type, row) {
            return data;
          }  
        },
        { 
          data: 'keterangan_kamar', 
          name: 'keterangan_kamar', 
          render: function (data, type, row) {
            return data;
          }  
        },
        { data: 'action', name: 'action', orderable: false, className: 'space' }
        ]
      });
    });
    $("#id_cabang").select2({
      placeholder: ".: PILIH CABANG :."
    });
    $("#jenis_kamar").select2({
      placeholder: ".: PILIH JEN.IS KAMAR :."
    });
    function formatRupiah(angka, prefix) {
      let numberString = angka.replace(/[^,\d]/g, '').toString(),
      split = numberString.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
    $(document).on('keyup','#harga_kamar',function() {
      $(this).val(formatRupiah($(this).val(), 'Rp. '));
    });
    let global_id_fasilitas = 0;
    $(document).on('click', '#new_fasilitas', function () {
      var content = jQuery("#sample_table_fasilitas tr"),
      size = global_id_fasilitas++,
      element = null,
      element = content.clone();
      element.attr('id','rec-'+size);
      element.attr('class','rec');
      element.find('.delete-record').attr('data-id', size);

      element.find('.id_kamar_fasilitas_input').attr('id', 'fasilitas_' + size + '_id_kamar_fasilitas');
      element.find('.id_kamar_fasilitas_input').attr('name', 'fasilitas[' + size + '][id_kamar_fasilitas]');

      element.find('.id_fasilitas_input').attr('id', 'fasilitas_' + size + '_id_fasilitas');
      element.find('.id_fasilitas_input').attr('name', 'fasilitas[' + size + '][id_fasilitas]');
      element.find('.id_fasilitas_input_error').attr('id', 'fasilitas_' + size + '_id_fasilitasError');
      element.find('.id_fasilitas_input').select2({
        placeholder: ".: PILIH FASILITAS :."
      });
      element.find('.id_fasilitas_input').val(null).trigger('change');

      element.appendTo('#table_fasilitas');
      $('#table_fasilitas tr').each(function (index) {
        $(this).find('span.sn').html(index + 1);
      });
    });
    $(document).on('click', '.delete-record', function () {
      var id = jQuery(this).attr('data-id');
      var more_id = jQuery(this).attr('more_id');
      var currentValues = $("#id_fasilitas_del").val();
      if (currentValues) {
        if (more_id) {
          $("#id_fasilitas_del").val(currentValues + ',' + more_id);
        }
      } else {
        $("#id_fasilitas_del").val(more_id);
      }
      var targetDiv = jQuery(this).attr('targetDiv');
      jQuery('#rec-' + id).remove();
      $('#table_fasilitas tr').each(function (index) {
        $(this).find('span.sn').html(index + 1);
      });
      return true;
    });
    var ajaxUrl;
    $(document).ready(function() {
      $(".new").click(function() {
        $("#loading").show();
        $("#pageKamar").attr('hidden',true);
        setTimeout(function() {
          $("#loading").hide();
          $("#penyewaanForm")[0].reset();
          $(".invalid-feedback").children("strong").text("");
          $("#penyewaanForm input").removeClass("is-invalid");
          $("#penyewaanForm select").removeClass("is-invalid");
          $("#penyewaanForm textarea").removeClass("is-invalid");
          $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Kamar');
          jQuery('.rec').remove();
          $(".error-tab").html("");
          $("#pagepenyewaanForm").attr('hidden',false);
          ajaxUrl = "{{route('save.kamar')}}";
          $("#id_cabang").val(null).trigger('change');
          $("#jenis_kamar").val(null).trigger('change');
          global_id_fasilitas = 0;
        }, 200);
      });
    });
    $(".close").click(function() {
      $("#pagepenyewaanForm").attr('hidden',true);
      $("#pageKamar").attr('hidden',false);
    })
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
        $("#penyewaanForm select").removeClass("is-invalid");
        $("#penyewaanForm textarea").removeClass("is-invalid");
        $(".error-tab").html("");
        $("#loading").show();
        $.ajax({
          method: "POST",
          headers: {
            Accept: "application/json"
          },
          contentType: false,
          processData: false,
          url : ajaxUrl,
          data: formData,
          success: function (response) {
            $('#penyewaanForm').data('submitted', false);
            $("#loading").hide();
            if (response.status == 'true') {
              $("#pagepenyewaanForm").attr('hidden',true);
              $("#pageKamar").attr('hidden',false);
              $("#penyewaanForm")[0].reset();
              showToast('bg-primary','Kamar Success',response.message);
              $('#table_kamar').DataTable().ajax.reload();
            }else if(response.status == 'warning'){
              Swal.fire({
                title: 'Fasilitas Warning',
                text: response.message,
                icon: 'warning',
                type: 'warning'
              });
            }else {
              showToast('bg-danger','Kamar Error',response.message);
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
                var tab_id = $("#" + key_temp + "Error").closest(".tab-pane").attr("id");
                if (tab_id != undefined) {
                  $("#tab_detail").find("[href$='#" + tab_id + "']").find(".error-tab").html("<i class='bx bx-info-circle'></i> Required");
                }
              });
            } else {
              showToast('bg-danger','Kamar Error',response.message);
            }
          }
        });
      });
    });
    function get_edit(kamarID) {
      $.ajax({
        type: "GET",
        url: "{{url('page/master/kamar/get_edit')}}"+"/"+kamarID,
        success: function(response) {
          $("#loading").hide();
          $.each(response.data, function(key, value) {
            $("#id_kamar").val(value.id_kamar);
            $("#id_cabang").val(value.id_cabang).trigger('change');
            $("#nomor_kamar").val(value.nomor_kamar);
            $("#harga_kamar").val(formatRupiah(value.harga_kamar,'Rp. '));
            $("#jenis_kamar").val(value.jenis_kamar).trigger('change');
            $("#keterangan_kamar").val(value.keterangan_kamar);
          });
          $.each(response.kamar_fasilitas, function(key, value_fk) {
            $("#new_fasilitas").trigger('click');
          });
          setTimeout(function () {
            $('#table_fasilitas tr').each(function (index) {
              $(this).find('.id_fasilitas_input').val(response.kamar_fasilitas[index].id_fasilitas).trigger('change');
              $(this).find('span.sn').html(index + 1);
              $(this).find('.delete-record').attr('more_id', response.kamar_fasilitas[index].id_kamar_fasilitas);
              $(this).find('.id_kamar_fasilitas_input').val(response.kamar_fasilitas[index].id_kamar_fasilitas);
            }); 
          }, 500);
        },
        error: function(response) {
          get_edit(kamarID);
        }
      });
    }
    $(document).on('click','.edit',function() {
      var kamarID = $(this).attr('more_id');
      $("#loading").show();
      $("#pageKamar").attr('hidden',true);
      $("#penyewaanForm")[0].reset();
      $(".invalid-feedback").children("strong").text("");
      $("#penyewaanForm input").removeClass("is-invalid");
      $("#penyewaanForm select").removeClass("is-invalid");
      $("#penyewaanForm textarea").removeClass("is-invalid");
      $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Kamar');
      jQuery('.rec').remove();
      $(".error-tab").html("");
      $("#pagepenyewaanForm").attr('hidden',false);
      ajaxUrl = "{{route('update.kamar')}}";
      $("#id_cabang").val(null).trigger('change');
      $("#jenis_kamar").val(null).trigger('change');
      global_id_fasilitas = 0;
      if (kamarID) {
        get_edit(kamarID);
      }
    });
    $(document).on('click', '.delete', function (event) {
      kamarID = $(this).attr('more_id');
      event.preventDefault();
      Swal.fire({
        title: 'Lanjut Hapus Data?',
        text: 'Data Kamar akan dihapus secara Permanent!',
        icon: 'warning',
        type: 'warning',
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: 'Lanjutkan'
      }).then((result) => {
        if (result.isConfirmed) {
          $("#loading").show();
          $.ajax({
            method: "GET",
            url: "{{url('page/master/kamar/destroy')}}"+"/"+kamarID,
            success:function(response)
            {
              $("#loading").hide();
              if (response.status == 'true') {
                setTimeout(function(){
                  showToast('bg-success','Kamar Dihapus',response.message);
                  $('#table_kamar').DataTable().ajax.reload();         
                }, 50);
              }else{
                showToast('bg-danger','Kamar Error',response.message);
              }
            },
            error: function(response) {
              $("#loading").hide();
              showToast('bg-danger','Kamar Error',response.message);
            }
          })
        }
      });
    });
  </script> -->
  @endsection
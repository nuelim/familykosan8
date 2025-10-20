  @extends('page/layout/app')

  @section('title','Kamar')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading" id="pageKamar">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Master </a></li>
              <li class="breadcrumb-item active" aria-current="page">Fasilitas</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="row">
       <div class="col-lg-5 pb-4 mb-2" style="background: white;box-shadow:2px 2px grey;">
        <h5 class="text mt-3">STATUS KAMAR</h5>
        <select class="form-control" id="keyword" style="width: 100%;" name="keyword">
          <option value="Terpakai">Berpenghuni</option>
          <option value="Belum Terpakai">Belum Berpenghuni</option>
        </select>
        <button class="btn btn-info mt-2" type="button" id="filter"><i class="fa fa-filter"></i> Tampilkan</button>
        <a href="{{route('index.kamar',['access'=>request()->has('access') ? request()->input('access') : null])}}" class="btn btn-secondary mt-2"><i class="bx bx-refresh bx-sm"></i></a>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
           Kamar {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
          <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
            <i class="bx bx-plus"></i> Tambah Kamar
          </button>
        </h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped nowrap dt-responsive" id="table_kamar" style="width: 100%;">
            <thead>
              <tr>
                <th -priority="2">No. </th>
                <th -priority="3">Cabang</th>
                <th -priority="4">Nomor Kamar</th>
                <th -priority="5">Tarif per Bulan</th>
                <th -priority="6">Jenis</th>
                <th -priority="7">Jumlah Fasilitas</th>
                <th -priority="8">Status Kamar</th>
                <th -priority="9">Keterangan Kamar</th>
                <th -priority="1">Action</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
@include('page.super_admin.kamar.form')
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
  var keyword = "{{request()->has('keyword') ? request()->input('keyword') : null}}";
  function KamarTable(keyword=null,access=null) {
  // $(function () {
    $('#table_kamar').Table({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('index.kamar') }}",
        : {access: access, status_kamar: keyword},
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
          // return data;
          if (data == 'Terpakai') {
            return '<span class="badge bg-danger text-white">Berpenghuni</span>';
          }else{
            return '<span class="badge bg-success text-white">Belum Berpenghuni/Tersedia</span>';
          }
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
  // });
}
$(function () {
  KamarTable(keyword,access);
});
$("#keyword").val(keyword).trigger('change');
$(document).on('click', '#filter', function() {
  keyword = $("#keyword").val();
  if (keyword) {
    $('#table_kamar').DataTable().destroy();
    KamarTable(keyword,access);
  }else{
    alert('PILIH STATUS KAMAR');
  }
});
$("#keyword").select2({
  placeholder: ".: STATUS KAMAR :."
});
$("#id_cabang").select2({
  placeholder: ".: PILIH CABANG :."
});
$("#jenis_kamar").select2({
  placeholder: ".: PILIH JENIS KAMAR :."
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
      $("#kamarForm")[0].reset();
      $(".invalid-feedback").children("strong").text("");
      $("#kamarForm input").removeClass("is-invalid");
      $("#kamarForm select").removeClass("is-invalid");
      $("#kamarForm textarea").removeClass("is-invalid");
      $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Kamar');
      jQuery('.rec').remove();
      jQuery('.gam').remove();
      $(".error-tab").html("");
      $("#pageKamarForm").attr('hidden',false);
      ajaxUrl = "{{route('save.kamar')}}";
      $("#id_cabang").val(null).trigger('change');
      $("#jenis_kamar").val(null).trigger('change');
      global_id_fasilitas = 0;
      global_id_kamar_foto = 0;
    }, 200);
  });
});
$(".close").click(function() {
  $("#pageKamarForm").attr('hidden',true);
  $("#pageKamar").attr('hidden',false);
})
$(function () {
  $('#kamarForm').submit(function (e) {
    e.preventDefault();
    if ($(this).data('submitted') === true) {
      return;
    }
    $(this).data('submitted', true);
    let formData = new FormData(this);
    $(".invalid-feedback").children("strong").text("");
    $("#kamarForm input").removeClass("is-invalid");
    $("#kamarForm select").removeClass("is-invalid");
    $("#kamarForm textarea").removeClass("is-invalid");
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
        $('#kamarForm').data('submitted', false);
        $("#loading").hide();
        if (response.status == 'true') {
          $("#pageKamarForm").attr('hidden',true);
          $("#pageKamar").attr('hidden',false);
          $("#kamarForm")[0].reset();
          showToast('bg-primary','Kamar Success',response.message);
          $('#table_kamar').DataTable().ajax.reload();
        }else if(response.status == 'warning'){
          Swal.fire({
            title: 'Warning',
            text: response.message,
            icon: 'warning',
            type: 'warning'
          });
        }else {
          showToast('bg-danger','Kamar Error',response.message);
        }
      },
      error: function (response) {
        $('#kamarForm').data('submitted', false);
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
      $.each(response.kamar_foto, function(key, value_foto) {
        $("#new_gambar").trigger('click');
      });
      setTimeout(function () {
        $('#table_gambar tr').each(function (index) {
          $(this).find('.id_kamar_foto_input').val(response.kamar_foto[index].id_kamar_foto);
          $(this).find('.foto_input').attr('disabled',true);
          $(this).find('.tipe_input').val(response.kamar_foto[index].tipe).trigger('change');
          $(this).find('.lihat_foto_input').attr('src', '{{asset('gambar_kamar')}}/'+response.kamar_foto[index].foto);
          $(this).find('span.sn').html(index + 1);
          $(this).find('.delete-gambar').attr('more_id', response.kamar_foto[index].id_kamar_foto);
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
  $("#kamarForm")[0].reset();
  $(".invalid-feedback").children("strong").text("");
  $("#kamarForm input").removeClass("is-invalid");
  $("#kamarForm select").removeClass("is-invalid");
  $("#kamarForm textarea").removeClass("is-invalid");
  $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Kamar');
  jQuery('.rec').remove();
  jQuery('.gam').remove();
  $(".error-tab").html("");
  $("#pageKamarForm").attr('hidden',false);
  ajaxUrl = "{{route('update.kamar')}}";
  $("#id_cabang").val(null).trigger('change');
  $("#jenis_kamar").val(null).trigger('change');
  global_id_fasilitas = 0;
  global_id_kamar_foto = 0;
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
let global_id_kamar_foto = 0;
$(document).on('click', '#new_gambar', function () {
  var content = jQuery("#sample_table_gambar tr"),
  size = global_id_kamar_foto++,
  element = null,
  element = content.clone();
  element.attr('id','gam-'+size);
  element.attr('class','gam');
  element.find('.delete-gambar').attr('data-id', size);

  element.find('.id_kamar_foto_input').attr('id', 'gambar_' + size + '_id_kamar_foto');
  element.find('.id_kamar_foto_input').attr('name', 'gambar[' + size + '][id_kamar_foto]');

  element.find('.lihat_foto_input').attr('id', 'gambar_' + size + '_lihat_foto');
  element.find('.lihat_foto_input').attr('name', 'gambar[' + size + '][lihat_foto]');

  element.find('.tipe_input').attr('id', 'gambar_' + size + '_tipe');
  element.find('.tipe_input').attr('name', 'gambar[' + size + '][tipe]');
  element.find('.tipe_input_error').attr('id', 'gambar_' + size + '_tipeError');
  element.find('.tipe_input').select2({
    placeholder: ".: PILIH TIPE GAMBAR :."
  }).val(null).trigger('change');

  element.find('.foto_input').attr('id', 'gambar_' + size + '_foto');
  element.find('.foto_input').attr('name', 'gambar[' + size + '][foto]');
  element.find('.foto_input_error').attr('id', 'gambar_' + size + '_fotoError');
  element.find('.foto_input').val(null).trigger('change');
  element.find('.foto_input').on('change',function() {
    if (this.files && this.files[0]) {
      show_loading();
      var file = this.files[0];
      var allowedExtensions = ['png','jpg','jpeg'];
      var fileExtension = file.name.split('.').pop().toLowerCase();

      if (!allowedExtensions.includes(fileExtension)) {
        alert('File Dokumen tidak didukung.');
        element.find('.foto_input').val('');
        element.find('.foto_input').empty('');
        hide_loading();
        return;
      }
      element.find('.lihat_foto_input').css('display','none');
      var reader = new FileReader();
      reader.onload = function (e) {
        setTimeout(function() {
          hide_loading();
          element.find('.lihat_foto_input').css('display','block');
          element.find('.lihat_foto_input').attr('src', e.target.result);
        }, 500);
      };
      reader.readAsDataURL(this.files[0]);
    } else {
      element.find('.lihat_foto_input').css('display','none');
      element.find('.foto_input').val('');
      element.find('.foto_input').empty('');
    }
  });

  element.appendTo('#table_gambar');
  $('#table_gambar tr').each(function (index) {
    $(this).find('span.sn').html(index + 1);
  });
});
$(document).on('click', '.delete-gambar', function () {
  var id = jQuery(this).attr('data-id');
  var more_id = jQuery(this).attr('more_id');
  var currentValues = $("#id_foto_del").val();
  if (currentValues) {
    if (more_id) {
      $("#id_foto_del").val(currentValues + ',' + more_id);
    }
  } else {
    $("#id_foto_del").val(more_id);
  }
  var targetDiv = jQuery(this).attr('targetDiv');
  jQuery('#gam-' + id).remove();
  $('#table_gambar tr').each(function (index) {
    $(this).find('span.sn').html(index + 1);
  });
  return true;
});
</script>
@endsection

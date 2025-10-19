  @extends('page/layout/app')

  @section('title','Data Kotak Saran')

  @section('content')
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Page</a></li>
              <li class="breadcrumb-item active" aria-current="page">Kotak Saran</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Kotak Saran
            @if(Auth::user()->level != 'Penghuni')
            {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
            @endif
            @if(Auth::user()->level == 'Penghuni')
            <button type="button" style="float: right;" class="btn btn-sm btn-outline-primary block new" >
              <i class="bx bx-plus"></i>
            </button>
            @endif
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="table_ajuan" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th>Penyewaan</th>
                  <th>Nama Penghuni</th>
                  <th>Isi Saran</th>
                  <th data-priority="2">Action</th>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_ajuan" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel1"></h5>
        <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
       <form method="post" id="ajuanForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group">
              <label>Isi Saran <span class="text-danger">*</span></label>
              <input type="" hidden="" id="id_ajuan" name="id_ajuan">
              <textarea class="form-control" rows="4" id="isi" name="isi" placeholder="Masukkan Saran Anda ..."></textarea>
              <span class="invalid-feedback" role="alert" id="isiError">
                <strong></strong>
              </span>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-lg-12">
            <label class="form-label">Tambahkan Foto</label><br>
            <button type="button" id="new_ajuan_foto" class="btn btn-info text-white btn-sm mb-2">
              <i class="bx bx-plus"></i>
            </button>
            <div class="table-responsive">
              <table class="table table-hover table-bordered table-striped responsive-table" style="width: 100%;">
                <thead style="background: #aaa;">
                 <tr>
                  <th>No. </th>
                  <th>Foto</th>
                  <th>Preview</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="table_ajuan_foto">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn" data-bs-dismiss="modal">
        <span>Tutup</span>
      </button>
      <button class="btn btn-primary ml-1 submit">
        <i class="bx bx-save"></i> <span>Simpan</span>
      </button>
    </div>
  </form>
  <div style="display:none;">
    <table id="sample_table_ajuan_foto">
      <tr id="">
        <td data-label="No."><span class="sn" style="vertical-align:middle;"></span></td>   
        <td data-label="Isi">
          <input type="" hidden="" name="ajuan[0][id_ajuan_detail]" id="ajuan_0_id_ajuan_detail" class="form-control id_ajuan_detail_input">
          <input type="file" accept="image/*" name="ajuan[0][foto_ajuan]" id="ajuan_0_foto_ajuan" class="form-control foto_ajuan_input">
          <span class="invalid-feedback foto_ajuan_input_error" role="alert" id="ajuan_0_foto_ajuanError">
            <strong></strong>
          </span>
        </td>
        <td data-label="Preview Foto">
          <img src="" class="img rounded-pill lihat_foto_ajuan_input" id="ajuan_0_lihat_foto_ajuan" width="100">
          <!-- <a href="" target="_blank" class="text-primary lihat_foto_ajuan_input" id="ajuan_0_lihat_foto_ajuan">Lihat Foto <i class="bx bx-file-image"></i></a> -->
        </td>
        <td>
          <center>
            <button type="button" class="delete-record btn btn-sm btn-danger" data-id="0"><i class="bx bx-trash"></i></button>
          </center>
        </td>
      </tr>
    </table>
  </div>
</div>
</div>
</div>
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
  $(function () {
    $('#table_ajuan').DataTable({
      processing: true,
      pageLength: 10,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('penghuni.aduan') }}",
        data: {access: access},
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_ajuan').DataTable().ajax.reload();
        }
      },
      columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex'},
      { 
        data: 'kode_penyewaan', 
        name: 'kode_penyewaan', 
        render: function (data, type, row) {
          return '#'+data;
        }  
      },
      { 
        data: 'name', 
        name: 'name', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'isi', 
        name: 'isi', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  $("#status_ajuan").select2({
    placeholder: ".: PILIH STATUS :.",
    dropdownParent: $("#modal_form_ajuan")
  });
  var ajaxUrl = "";
  $(document).ready(function() {
    $(".new").click(function() {
      $("#loading").show();
      setTimeout(function() {
        $("#loading").hide();
        $("#ajuanForm")[0].reset();
        $(".invalid-feedback").children("strong").text("");
        $("#ajuanForm input").removeClass("is-invalid");
        $("#ajuanForm textarea").removeClass("is-invalid");
        global_id_ajuan_detail = 0;
        $(".rec").remove();
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Kotak Saran');
        // 
        $("#new_ajuan_foto").show();
        $("#ajuanForm textarea").attr('disabled',false);
        $(".foto_ajuan_input").attr('disabled',false);
        $(".delete-record").show();
        $(".submit").show();
        // 
        $("#modal_form_ajuan").modal('show');
        ajaxUrl = "{{route('save.ajuan')}}";
      }, 300);
    });
  });
  let global_id_ajuan_detail = 0;
  $(document).on('click', '#new_ajuan_foto', function () {
    var content = jQuery("#sample_table_ajuan_foto tr"),
    size = global_id_ajuan_detail++,
    element = null,
    element = content.clone();
    element.attr('id','rec-'+size);
    element.attr('class','rec');
    element.find('.delete-record').attr('data-id', size);

    element.find('.id_ajuan_detail_input').attr('id', 'ajuan_' + size + '_id_ajuan_detail');
    element.find('.id_ajuan_detail_input').attr('name', 'ajuan[' + size + '][id_ajuan_detail]');

    element.find('.lihat_foto_ajuan_input').attr('id', 'ajuan_' + size + '_lihat_foto_ajuan');
    element.find('.lihat_foto_ajuan_input').attr('name', 'ajuan[' + size + '][lihat_foto_ajuan]');

    element.find('.foto_ajuan_input').attr('id', 'ajuan_' + size + '_foto_ajuan');
    element.find('.foto_ajuan_input').attr('name', 'ajuan[' + size + '][foto_ajuan]');
    element.find('.foto_ajuan_input_error').attr('id', 'ajuan_' + size + '_foto_ajuanError');
    element.find('.foto_ajuan_input').val(null).trigger('change');
    element.find('.foto_ajuan_input').on('change',function() {
      if (this.files && this.files[0]) {
        show_loading();
        var file = this.files[0];
        var allowedExtensions = ['png','jpg','jpeg'];
        var fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
          alert('File Dokumen tidak didukung.');
          element.find('.foto_ajuan_input').val('');
          element.find('.foto_ajuan_input').empty('');
          hide_loading();
          return;
        }
        element.find('.lihat_foto_ajuan_input').css('display','none');
        var reader = new FileReader();
        reader.onload = function (e) {
          setTimeout(function() {
            hide_loading();
            element.find('.lihat_foto_ajuan_input').css('display','block');
            element.find('.lihat_foto_ajuan_input').attr('src', e.target.result);
          }, 500);
        };
        reader.readAsDataURL(this.files[0]);
      } else {
        element.find('.lihat_foto_ajuan_input').css('display','none');
        element.find('.foto_ajuan_input').val('');
        element.find('.foto_ajuan_input').empty('');
      }
    });

    element.appendTo('#table_ajuan_foto');
    $('#table_ajuan_foto tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
  });
  $(document).on('click', '.delete-record', function () {
    var id = jQuery(this).attr('data-id');
    var more_id = jQuery(this).attr('more_id');
    var currentValues = $("#id_ajuan_detail_del").val();
    if (currentValues) {
      if (more_id) {
        $("#id_ajuan_detail_del").val(currentValues + ',' + more_id);
      }
    } else {
      $("#id_ajuan_detail_del").val(more_id);
    }
    var targetDiv = jQuery(this).attr('targetDiv');
    jQuery('#rec-' + id).remove();
    $('#table_ajuan_foto tr').each(function (index) {
      $(this).find('span.sn').html(index + 1);
    });
    return true;
  });
  $(function () {
    $('#ajuanForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#ajuanForm input").removeClass("is-invalid");
      $("#ajuanForm textarea").removeClass("is-invalid");
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
          $('#ajuanForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#ajuanForm")[0].reset();
            $('#modal_form_ajuan').modal('hide');
            Swal.fire({
              title: 'Saran Success',
              text: response.message,
              icon: 'success',
              type: 'success'
            });
            $('#table_ajuan').DataTable().ajax.reload();
          } else {
            showToast('bg-danger','Saran Error',response.message);
          }
        },
        error: function (response) {
          $('#ajuanForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              var key_temp = key.replaceAll(".", "_");
              $("#" + key_temp).addClass("is-invalid");
              $("#" + key_temp + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            showToast('bg-danger','Saran Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(ajuanID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/aduan/get_edit')}}"+"/"+ajuanID,
      success: function(response) {
        $("#loading").hide();
        global_id_ajuan_detail = 0;
        $.each(response.data, function(key, value) {
          $("#id_ajuan").val(value.id_ajuan);
          $("#isi").val(value.isi);
          $("#status_ajuan").val(value.status_ajuan).trigger('change');
        });
        $.each(response.foto, function(key, value_foto) {
          $("#new_ajuan_foto").trigger('click');
        });
        setTimeout(function () {
          $('#table_ajuan_foto tr').each(function (index) {
            $(this).find('.id_ajuan_detail_input').val(response.foto[index].id_ajuan_detail);
            $(this).find('.lihat_foto_ajuan_input').attr('src', '{{asset('foto_ajuan')}}/'+response.foto[index].foto_ajuan);
            $(this).find('span.sn').html(index + 1);
            $(this).find('.delete-record').attr('more_id', response.foto[index].id_ajuan_detail);
          }); 
        }, 500);
      },
      error: function(response) {
        get_edit(ajuanID);
      }
    });
  }
  $(document).on('click','.view',function() {
    $("#loading").show();
    var ajuanID = $(this).attr('more_id');
    $("#ajuanForm")[0].reset();
    $("#status_ajuan").val(null).trigger('change')
    $(".invalid-feedback").children("strong").text("");
    $("#ajuanForm input").removeClass("is-invalid");
    $("#ajuanForm textarea").removeClass("is-invalid");
    $(".modal-title").html('<i class="fa fa-eye"></i> Lihat Kotak Saran');
    $(".rec").remove();
    // 
    $("#new_ajuan_foto").hide();
    $("#ajuanForm textarea").attr('disabled',true);
    $(".foto_ajuan_input").attr('disabled',true);
    $(".delete-record").hide();
    $(".submit").hide();
    // 
    $("#modal_form_ajuan").modal('show');
    ajaxUrl = "";
    if (ajuanID) {
      get_edit(ajuanID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    ajuanID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Kotak Saran akan dihapus secara Permanent!',
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
          url: "{{url('page/aduan/destroy')}}"+"/"+ajuanID,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              setTimeout(function(){
                showToast('bg-success','Saran Dihapus',response.message);
                $('#table_ajuan').DataTable().ajax.reload();         
              }, 50);
            }else{
              showToast('bg-danger','Saran Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Saran Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection
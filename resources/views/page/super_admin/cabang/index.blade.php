  @extends('page/layout/app')

  @section('title','Data Cabang')

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
              <li class="breadcrumb-item"><a href="">Data Master</a></li>
              <li class="breadcrumb-item active" aria-current="page">Cabang</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Cabang
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Cabang
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="table_cabang" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th>Nama Cabang</th>
                  <th>Alamat Cabang</th>
                  <th>Link Maps</th>
                  <th>Status</th>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_cabang" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
       <form method="post" id="cabangForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Nama Cabang <span class="text-danger">*</span></label>
              <input type="" hidden="" id="id_cabang_add" name="id_cabang_add">
              <input type="" hidden="" id="id_cabang" name="id_cabang">
              <input type="text" autocomplete="off" class="form-control" id="nama_cabang" name="nama_cabang">
              <span class="invalid-feedback" role="alert" id="nama_cabangError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Alamat Cabang <span class="text-danger">*</span></label>
              <input type="text" autocomplete="off" class="form-control" id="alamat_cabang" name="alamat_cabang">
              <span class="invalid-feedback" role="alert" id="alamat_cabangError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Url/Link Maps Cabang <span class="text-danger">*</span></label>
              <input type="url" autocomplete="off" class="form-control" id="link_cabang" name="link_cabang">
              <span class="invalid-feedback" role="alert" id="link_cabangError">
                <strong></strong>
              </span>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label class="col-form-label">Status <span class="text-danger">*</span></label>
              <select class="form-control" style="width: 100%;" id="status_cabang" name="status_cabang">
                <option value="A">Aktif</option>
                <option value="I">Non Aktif</option>
              </select>
              <span class="invalid-feedback" role="alert" id="status_cabangError">
                <strong></strong>
              </span>
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
  function get_id_cabang() {
    $("#id_cabang_add").val('');
    $.ajax({
      type: "GET",
      url: "{{route('get_id_cabang')}}",
      success: function(response) {
        var cabang = "";
        for (let x = 0; x < response.length; x++) {
          cabang += response[x].id_cabang;
          if (x < response.length - 1) {
            cabang += ',';
          }
        }        
        $("#id_cabang_add").val(cabang);
      },
      error: function(response) {
        get_id_cabang();
      }
    });
  }
  $(function () {
    $('#table_cabang').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('index.cabang') }}",
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_cabang').DataTable().ajax.reload();
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
        data: 'alamat_cabang', 
        name: 'alamat_cabang', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'link_cabang', 
        name: 'link_cabang', 
        render: function (data, type, row) {
          return '<a href="'+data+'" target="_blank"><i class="bx bx-current-location"></i> Lihat di Google Maps</a>';
        }  
      },
      { 
        data: 'status_cabang', 
        name: 'status_cabang', 
        render: function (data, type, row) {
          if (data == 'A') {
            return '<span class="badge bg-success text-white">Aktif</span>';
          }else{
            return '<span class="badge bg-danger text-white">Non Aktif</span>';
          }
          // return data;
        }  
      },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  $("#status_cabang").select2({
    placeholder: ".: PILIH STATUS :.",
    dropdownParent: $("#modal_form_cabang")
  });
  var ajaxUrl = "";
  $(document).ready(function() {
    $(".new").click(function() {
      $("#loading").show();
      get_id_cabang();
      setTimeout(function() {
        $("#loading").hide();
        $("#cabangForm")[0].reset();
        $("#status_cabang").val(null).trigger('change')
        $(".invalid-feedback").children("strong").text("");
        $("#cabangForm input").removeClass("is-invalid");
        $("#cabangForm select").removeClass("is-invalid");
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Cabang');
        $("#modal_form_cabang").modal('show');
        ajaxUrl = "{{route('save.cabang')}}";
      }, 300);
    });
  });
  $(function () {
    $('#cabangForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = $(this).serializeArray();
      $(".invalid-feedback").children("strong").text("");
      $("#cabangForm input").removeClass("is-invalid");
      $("#cabangForm select").removeClass("is-invalid");
      $("#loading").show();
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        url : ajaxUrl,
        data: formData,
        success: function (response) {
          $('#cabangForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#cabangForm")[0].reset();
            $('#modal_form_cabang').modal('hide');
            showToast('bg-primary','cabang Success',response.message);
            // $("#id_cabang_add").val('');
            // get_id_cabang();
            $('#table_cabang').DataTable().ajax.reload();
          } else {
            showToast('bg-danger','cabang Error',response.message);
          }
        },
        error: function (response) {
          $('#cabangForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              $("#" + key).addClass("is-invalid");
              $("#" + key + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            showToast('bg-danger','cabang Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(cabangID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/master/cabang/get_edit')}}"+"/"+cabangID,
      success: function(response) {
        if (response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#id_cabang").val(value.id_cabang);
            $("#nama_cabang").val(value.nama_cabang);
            $("#alamat_cabang").val(value.alamat_cabang);
            $("#link_cabang").val(value.link_cabang);
            $("#status_cabang").val(value.status_cabang).trigger('change');
          });
        }
      },
      error: function(response) {
        get_edit(cabangID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    get_id_cabang();
    var cabangID = $(this).attr('more_id');
    $("#cabangForm")[0].reset();
    $("#status_cabang").val(null).trigger('change')
    $(".invalid-feedback").children("strong").text("");
    $("#cabangForm input").removeClass("is-invalid");
    $("#cabangForm select").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah cabang');
    $("#modal_form_cabang").modal('show');
    ajaxUrl = "{{route('update.cabang')}}";
    if (cabangID) {
      get_edit(cabangID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    cabangID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Cabang akan dihapus secara Permanent!',
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
          url: "{{url('page/master/cabang/destroy')}}"+"/"+cabangID,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              setTimeout(function(){
                showToast('bg-success','Cabang Dihapus',response.message);
                $('#table_cabang').DataTable().ajax.reload();         
              }, 50);
            }else{
              showToast('bg-danger','Cabang Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Cabang Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection
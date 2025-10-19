  @extends('page/layout/app')

  @section('title','Penghuni')

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
              <li class="breadcrumb-item"><a href="">Master User</a></li>
              <li class="breadcrumb-item active" aria-current="page">Penghuni</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data User Penghuni {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Penghuni
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped dt-responseive nowrap" id="table_penghuni" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th data-priority="3">Nama</th>
                  <th data-priority="12">Cabang</th>
                  <th data-priority="5">Email</th>
                  <th data-priority="4">NIK</th>
                  <th data-priority="6">Tempat/Tgl Lahir</th>
                  <th data-priority="7">Telepon</th>
                  <th data-priority="11">Telepon Darurat</th>
                  <th data-priority="8">KTP</th>
                  <th data-priority="9">Status Akun</th>
                  <th data-priority="10">Alamat</th>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_penghuni" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel1"></h5>
        <buttonz
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        <form method="post" id="penghuniForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-lg-9">
              <div class="row">
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Cabang <span class="text-danger">*</span></label>
                    <select style="width: 100%;" class="form-control" id="id_cabang" name="id_cabang">
                      @foreach($cabang as $cbg)
                      <option value="{{$cbg->id_cabang}}" more_id="{{$cbg->nama_cabang}}">{{$cbg->nama_cabang}}</option>
                      @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert" id="id_cabangError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Nama <span class="text-danger">*</span></label>
                    <input type="" hidden="" id="id_user" name="id_user">
                    <input type="text" autocomplete="off" class="form-control input_view" id="name" name="name">
                    <span class="invalid-feedback" role="alert" id="nameError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Email</label>
                    <input type="email" class="form-control input_view" id="email" name="email">
                    <span class="invalid-feedback" role="alert" id="emailError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Password <span class="text-danger">*</span></label>
                    <input type="text" autocomplete="off" class="form-control input_view" id="password" name="password">
                    <span class="invalid-feedback" role="alert" id="passwordError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">NIK <span class="text-danger">*</span></label>
                    <input type="text" autocomplete="off" class="form-control input_view" id="nik" name="nik">
                    <span class="invalid-feedback" role="alert" id="nikError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" autocomplete="off" class="form-control input_view" id="tempat_lahir" name="tempat_lahir">
                    <span class="invalid-feedback" role="alert" id="tempat_lahirError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                    <span class="invalid-feedback" role="alert" id="tgl_lahirError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">No Telepon <span class="text-danger">*</span></label>
                    <input type="number" autocomplete="off" class="form-control input_view" id="ponsel" name="ponsel">
                    <span class="invalid-feedback" role="alert" id="ponselError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Foto KTP <span class="text-danger label_req_ktp">*</span></label>
                    <input type="file" accept="image/*" class="form-control change_view" id="foto" name="foto">
                    <input type="" hidden="" class="form-control" id="fotoLama" name="fotoLama">
                    <span class="invalid-feedback" role="alert" id="fotoError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Alamat <span class="text-danger">*</span></label>
                    <input class="form-control input_view" autocomplete="off" rows="4" id="alamat" name="alamat">
                    <span class="invalid-feedback" role="alert" id="alamatError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">No. Darurat <span class="text-danger">*</span></label>
                    <input type="number" autocomplete="off" class="form-control input_view" id="no_darurat" name="no_darurat">
                    <span class="invalid-feedback" role="alert" id="no_daruratError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
                <div class="col-lg-6 mb-2">
                  <div class="form-group">
                    <label class="col-form-label">Status <span class="text-danger">*</span></label>
                    <select style="width: 100%;" class="form-control" name="status_user" id="status_user">
                      <option value="Aktif">Aktif</option>
                      <option value="Non Aktif">Non Aktif</option>
                    </select>
                    <span class="invalid-feedback" role="alert" id="status_userError">
                      <strong></strong>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <label><i class="bx bx-info-circle"></i> Penghuni Info</label>
              <div class="card overflow-hidden">
                <div class="card-body" id="vertical-example">
                  <table class="table text-center" style="text-align: center;font-size: 13px;padding: 0;margin: 0;" cellpadding="0" cellspacing="0">
                    <tr align="center"><td id="foto_view"><img src="" style="display: none;" width="150" height="150" class="img rounded img_view"></td></tr>
                   <!--  <tr><td id="id_cabang_view" class="view_akun"></td></tr>
                    <tr><td id="name_view" class="view_akun"></td></tr>
                    <tr><td id="email_view" class="view_akun"></td></tr>
                    <tr><td id="nik_view" class="view_akun"></td></tr>
                    <tr><td id="tempat_lahir_view" class="view_akun"></td></tr>
                    <tr><td id="tgl_lahir_view" class="view_akun"></td></tr>
                    <tr><td id="ponsel_view" class="view_akun"></td></tr>
                    <tr><td id="alamat_view" class="view_akun"></td></tr>
                    <tr><td id="no_darurat_view" class="view_akun"></td></tr>
                    <tr><td id="status_user_view" class="view_akun"></td></tr> -->
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-loading" id="modal-loading" style="display: none;text-align: center;">
          <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
          <h5>Menunggu KTP</h5>
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
@endsection
@section('scripts')
<script type="text/javascript">
  var access = "{{request()->has('access') ? request()->input('access') : null}}";
  $(function () {
    $('#table_penghuni').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('index.user',$level) }}",
        data: {access: access},
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_penghuni').DataTable().ajax.reload();
        }
      },
      columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex'},
      { 
        data: 'name', 
        name: 'name', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'nama_cabang', 
        name: 'nama_cabang', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'email', 
        name: 'email', 
        render: function (data, type, row) {
          if (data != null) {
            return '<a href="mailto:'+data+'">'+data+'</a>';
          }else{
            return '-';
          }
        }  
      },
      { 
        data: 'nik', 
        name: 'nik', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'tempat_lahir', 
        name: 'tempat_lahir', 
        render: function (data, type, row) {
          return data+'/'+tanggal_indonesia(row.tgl_lahir);
        }  
      },
      { 
        data: 'ponsel', 
        name: 'ponsel', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'no_darurat', 
        name: 'no_darurat', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'foto', 
        name: 'foto', 
        render: function (data, type, row) {
          if (data ==  null) {
            return '<span class="badge bg-primary text-white">Belum ada foto</span>';
          }else{
            return '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><li data-bs-toggle="tooltip"data-popup="tooltip-custom"data-bs-placement="top" class="avatar avatar-xl pull-up" title="Foto Profil"><img src="{{asset('foto_ktp')}}/'+data+'" alt="Avatar" class="rounded-circle" /></li></ul>';
          }
        }  
      },
      { 
        data: 'status_user', 
        name: 'status_user', 
        render: function (data, type, row) {
          if (data == 'Aktif') {
            return '<span class="badge bg-success text-white">Aktif</span>';
          }else{
            return '<span class="badge bg-danger text-white">Non Aktif</span>';
          }
        }  
      },
      { 
        data: 'alamat', 
        name: 'alamat', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
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
    placeholder: ".: PILIH CABANG :.",
    dropdownParent: $("#modal_form_penghuni")
  });
  var ajaxUrl = "";
  $(document).ready(function() {
    $(".new").click(function() {
      $("#loading").show();
      setTimeout(function() {
        $("#loading").hide();
        $("#penghuniForm")[0].reset();
        $(".invalid-feedback").children("strong").text("");
        $("#penghuniForm input").removeClass("is-invalid");
        $("#penghuniForm select").removeClass("is-invalid");
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Penghuni');
        $(".view_akun").html('');
        $(".img_view").css('display','none');
        $("#status_user").val(null).trigger('change');
        $("#id_cabang").val(null).trigger('change');
        $(".label_req_ktp").text('*');
        $("#modal_form_penghuni").modal('show');
        ajaxUrl = "{{route('save.penghuni')}}";
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
        url : ajaxUrl,
        data: formData,
        success: function (response) {
          $('#penghuniForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#penghuniForm")[0].reset();
            $('#modal_form_penghuni').modal('hide');
            showToast('bg-primary','User Success',response.message);
            $('#table_penghuni').DataTable().ajax.reload();
          } else {
            showToast('bg-danger','User Error',response.message);
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
            showToast('bg-danger','User Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(userID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/user/penghuni/get_edit')}}"+"/"+userID,
      success: function(response) {
        if (response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#id_user").val(value.id);
            $("#name").val(value.name);
            $("#email").val(value.email);
            $("#nik").val(value.nik);
            $("#status_user").val(value.status_user).trigger('change');
            $("#id_cabang").val(value.id_cabang).trigger('change');
            $("#tempat_lahir").val(value.tempat_lahir);
            $("#tgl_lahir").val(value.tgl_lahir).trigger('change');
            $("#ponsel").val(value.ponsel);
            $("#no_darurat").val(value.no_darurat);
            $("#alamat").val(value.alamat);
            $("#fotoLama").val(value.foto);
            // 
            // $("#email_view").html(value.email);
            // $("#name_view").html(value.name);
            // $("#nik_view").html(value.nik);
            // $("#tempat_lahir_view").html(value.tempat_lahir);
            // $("#ponsel_view").html(value.ponsel);
            // $("#no_darurat_view").html(value.no_darurat);
            // $("#alamat_view").html(value.alamat);
            $('.img_view').attr("src","{{asset('foto_ktp')}}/"+value.foto);
            if (value.foto != null) {
              $(".img_view").css('display','block');
            }else{
              $(".img_view").css('display','none');
            }
          });
        }
      },
      error: function(response) {
        get_edit(userID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var userID = $(this).attr('more_id');
    $("#penghuniForm")[0].reset();
    $(".invalid-feedback").children("strong").text("");
    $("#penghuniForm input").removeClass("is-invalid");
    $("#penghuniForm select").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Penghuni');
    $(".label_req_ktp").text('');
    $("#modal_form_penghuni").modal('show');
    $(".view_akun").html('');
    $(".img_view").css('display','none');
    $("#status_user").val(null).trigger('change');
    $("#id_cabang").val(null).trigger('change');
    ajaxUrl = "{{route('update.penghuni')}}";
    if (userID) {
      get_edit(userID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    userID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data User Penghuni akan dihapus secara Permanent!',
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
          url: "{{url('page/user/destroy')}}"+"/"+userID,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              setTimeout(function(){
                showToast('bg-success','Penghuni Dihapus',response.message);
                $('#table_penghuni').DataTable().ajax.reload();         
              }, 50);
            }else{
              showToast('bg-danger','Penghuni Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Penghuni Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection
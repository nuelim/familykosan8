  @extends('page/layout/app')

  @section('title','Data Pengeluaran')

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
              <li class="breadcrumb-item"><a href="">Pengelolaan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Pengeluaran</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Data Pengeluaran {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
            <button type="button" style="float: right;" class="btn btn-sm rounded-pill btn-primary block new" >
              <i class="bx bx-plus"></i> Tambah Pengeluaran
            </button>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped" id="table_pengeluaran" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="1">No. </th>
                  <th>Tanggal</th>
                  <th>Nominal</th>
                  <th>Bukti/Foto</th>
                  <th>Keterangan</th>
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
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_pengeluaran" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-lg modal-dialog-scrollable" role="document">
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
        <form method="post" id="pengeluaranForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-lg-12 mb-2">
              <div class="form-group">
                <label class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                <input type="" hidden="" id="id_pengeluaran" name="id_pengeluaran">
                <select style="width: 100%;" class="form-control" id="id_cabang" name="id_cabang">
                  @foreach($cabang as $cbg)
                  <option value="{{$cbg->id_cabang}}">{{$cbg->nama_cabang}}</option>
                  @endforeach
                </select>
                <span class="invalid-feedback" role="alert" id="id_cabangError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <div class="col-lg-12 mb-2">
              <div class="form-group">
                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_pengeluaran" name="tanggal_pengeluaran">
                <span class="invalid-feedback" role="alert" id="tanggal_pengeluaranError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <div class="col-lg-12 mb-2">
              <div class="form-group">
                <label class="form-label">Nominal <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nominal_pengeluaran" name="nominal_pengeluaran">
                <span class="invalid-feedback" role="alert" id="nominal_pengeluaranError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <div class="col-lg-12 mb-2">
              <div class="form-group">
                <label class="form-label">Bukti <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="bukti_pengeluaran" name="bukti_pengeluaran">
                <input type="" hidden="" id="bukti_pengeluaranLama" name="bukti_pengeluaranLama">
                <span class="invalid-feedback" role="alert" id="bukti_pengeluaranError">
                  <strong></strong>
                </span>
              </div>
            </div>
            <div class="col-lg-12 mb-2">
              <div class="form-group">
                <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="4" id="keterangan_pengeluaran" name="keterangan_pengeluaran"></textarea>
                <span class="invalid-feedback" role="alert" id="keterangan_pengeluaranError">
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
  var access = "{{request()->has('access') ? request()->input('access') : null}}";
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
  $(function () {
    $('#table_pengeluaran').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('index.pengeluaran') }}",
        data: {access: access},
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_pengeluaran').DataTable().ajax.reload();
        }
      },
      columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex'},
      { 
        data: 'tanggal_pengeluaran', 
        name: 'tanggal_pengeluaran', 
        render: function (data, type, row) {
          return tanggal_indonesia(data);
        }  
      },
      { 
        data: 'nominal_pengeluaran', 
        name: 'nominal_pengeluaran', 
        render: function (data, type, row) {
          return formatRupiah(data,'Rp. ');
        }  
      },
      { 
        data: 'bukti_pengeluaran', 
        name: 'bukti_pengeluaran', 
        render: function (data, type, row) {
          return '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center"><li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-lg pull-up" title="Foto/Bukti"><img src="{{asset('bukti_pengeluaran')}}/'+data+'" class="rounded-circle" /></li></ul>';
        }  
      },
      { 
        data: 'keterangan_pengeluaran', 
        name: 'keterangan_pengeluaran', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  $("#id_cabang").select2({
    placeholder: ".: PILIH CABANG :.",
    dropdownParent: $("#modal_form_pengeluaran")
  });
  $("#nominal_pengeluaran").keyup(function() {
    $(this).val(formatRupiah($(this).val(),'Rp. '));
  });
  var ajaxUrl = "";
  $(document).ready(function() {
    $(".new").click(function() {
      $("#loading").show();
      setTimeout(function() {
        $("#loading").hide();
        $("#pengeluaranForm")[0].reset();
        $(".invalid-feedback").children("strong").text("");
        $("#pengeluaranForm input").removeClass("is-invalid");
        $("#pengeluaranForm select").removeClass("is-invalid");
        $("#pengeluaranForm textarea").removeClass("is-invalid");
        $(".modal-title").html('<i class="bx bx-plus"></i> Form Tambah Pengeluaran');
        $("#id_cabang").val(null).trigger('change')
        $("#modal_form_pengeluaran").modal('show');
        ajaxUrl = "{{route('save.pengeluaran')}}";
      }, 300);
    });
  });
  $(function () {
    $('#pengeluaranForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      // let formData = $(this).serializeArray();
      let formData = new FormData(this);
      $(".invalid-feedback").children("strong").text("");
      $("#pengeluaranForm input").removeClass("is-invalid");
      $("#pengeluaranForm select").removeClass("is-invalid");
      $("#pengeluaranForm textarea").removeClass("is-invalid");
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
          $('#pengeluaranForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#pengeluaranForm")[0].reset();
            $('#modal_form_pengeluaran').modal('hide');
            showToast('bg-primary','Pengeluaran Success',response.message);
            $('#table_pengeluaran').DataTable().ajax.reload();
          } else {
            showToast('bg-danger','Pengeluaran Error',response.message);
          }
        },
        error: function (response) {
          $('#pengeluaranForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              $("#" + key).addClass("is-invalid");
              $("#" + key + "Error").children("strong").text(errors[key][0]);
            });
          } else {
            showToast('bg-danger','Pengeluaran Error',response.message);
          }
        }
      });
    });
  });
  function get_edit(pengeluaranID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/pengelolaan/pengeluaran/get_edit')}}"+"/"+pengeluaranID,
      success: function(response) {
        if (response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#id_pengeluaran").val(value.id_pengeluaran);
            $("#tanggal_pengeluaran").val(value.tanggal_pengeluaran);
            $("#nominal_pengeluaran").val(formatRupiah(value.nominal_pengeluaran,'Rp. '));
            $("#keterangan_pengeluaran").val(value.keterangan_pengeluaran);
            $("#bukti_pengeluaranLama").val(value.bukti_pengeluaran);
            $("#id_cabang").val(value.id_cabang).trigger('change');
          });
        }
      },
      error: function(response) {
        get_edit(pengeluaranID);
      }
    });
  }
  $(document).on('click','.edit',function() {
    $("#loading").show();
    var pengeluaranID = $(this).attr('more_id');
    $("#pengeluaranForm")[0].reset();
    $("#id_cabang").val(null).trigger('change')
    $(".invalid-feedback").children("strong").text("");
    $("#pengeluaranForm input").removeClass("is-invalid");
    $("#pengeluaranForm select").removeClass("is-invalid");
    $("#pengeluaranForm textarea").removeClass("is-invalid");
    $(".modal-title").html('<i class="bx bx-edit"></i> Form Ubah Pengeluaran');
    $("#modal_form_pengeluaran").modal('show');
    ajaxUrl = "{{route('update.pengeluaran')}}";
    if (pengeluaranID) {
      get_edit(pengeluaranID);
    }
  });
  $(document).on('click', '.delete', function (event) {
    pengeluaranID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Data Pengeluaran akan dihapus secara Permanent!',
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
          url: "{{url('page/pengelolaan/pengeluaran/destroy')}}"+"/"+pengeluaranID,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              setTimeout(function(){
                showToast('bg-success','Pengeluaran Dihapus',response.message);
                $('#table_pengeluaran').DataTable().ajax.reload();         
              }, 50);
            }else{
              showToast('bg-danger','Pengeluaran Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Pengeluaran Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection
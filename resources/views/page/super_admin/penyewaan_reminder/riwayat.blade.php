  @extends('page/layout/app')

  @section('title','Riwayat Pesan Reminder')

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
              <li class="breadcrumb-item"><a href="">Page</a></li>
              <li class="breadcrumb-item active" aria-current="page">Riwayat Reminder</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section" id="contenIndex">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Riwayat Pesan Pengingat {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap dt-responsive" id="table_penyewaan" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="2">No. </th>
                  <th data-priority="3">Kode Penyewaan</th>
                  <th data-priority="4">Nama</th>
                  <th data-priority="5">Tanggal Reminder</th>
                  <th data-priority="6">Pesan</th>
                  <th data-priority="1">Action</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0" >
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    <div id="contenView" style="display: none;">
     <div class="card email-card-last mx-sm-6 mx-3">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap border-bottom">
        <div class="d-flex align-items-center mb-sm-0 mb-3">
          <i class="bx bx-chevron-left bx-md cursor-pointer back" data-bs-toggle="sidebar" data-target="#app-email-view"></i>
          <img src="" id="foto_pesan" alt="user-avatar" class="flex-shrink-0 rounded-circle me-4" height="38" width="38" />
          <div class="flex-grow-1 ms-1">
            <h6 class="m-0 fw-normal" id="nama_pesan"></h6>
            <small class="text-body" id="email_pesan"></small> / <small class="text-body" id="ponsel_pesan"></small>
          </div>
        </div>
        <div class="d-flex align-items-center">
          <p class="mb-0 me-4 text-muted" ><span id="tanggal_pesan"></span> / Riwayat Pesan Reminder</p>
          <div class="dropdown">
            <button class="btn btn-icon dropdown-toggle hide-arrow" id="dropdownEmailTwo" data-bs-toggle="dropdown" aria-expanded="true">
              <i class="bx bx-dots-vertical-rounded bx-sm"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownEmailTwo">
             <a class="dropdown-item" id="detail_sewa_pesan" href="">
              <i class="fa fa-eye me-1"></i>
              <span class="align-middle">Lihat Detail Sewa</span>
            </a>
            <a class="dropdown-item delete" id="delete_pesan" href="javascript:void(0)">
              <i class="bx bx-trash me-1"></i>
              <span class="align-middle">Hapus</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body pt-6">
      <p id="text_pesan" class="text mt-3">

      </p>
    </div>
  </div>
  <!-- Email View : Reply mail-->
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
<script>
  var access = "{{request()->has('access') ? request()->input('access') : null}}";
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
    $('#table_penyewaan').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('riwayat_reminder') }}",
        data: {access: access},
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_penyewaan').DataTable().ajax.reload();
        }
      },
      columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex'},
      { 
        data: 'kode_penyewaan', 
        name: 'kode_penyewaan', 
        render: function (data, type, row) {
          return data;
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
        data: 'tanggal_reminder', 
        name: 'tanggal_reminder', 
        render: function (data, type, row) {
          return tanggal_indonesia(data);
        }  
      },
      { 
        data: 'text_pesan', 
        name: 'text_pesan', 
        render: function (data, type, row) {
          var div = document.createElement("div");
          div.innerHTML = data;
          var textContent = div.textContent || div.innerText || "";
          var maxLength = 100;
          var truncatedText = textContent.length > maxLength ? textContent.substring(0, maxLength) + "..." : textContent;
          var readMoreButton = truncatedText+' <a href="javascript:void(0)" class="read-more view" more_id="'+row.id_penyewaan_reminder+'">Lihat Selengkapnya</a>';
          return readMoreButton;
        }  
      },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  function get_edit(reminderID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/riwayat_reminder/get_view')}}"+"/"+reminderID,
      success: function(response) {
        $("#loading").hide();
        $("#contenView").show();
        $.each(response, function(key, value) {
          var formattedText = value.text_pesan.replace(/\n/g, '<br>');
          $('#foto_pesan').attr("src","{{asset('foto_ktp')}}/"+value.foto);
          if (value.foto != null) {
            $("#foto_pesan").css('display','block');
          }else{
            $("#foto_pesan").css('display','none');
          }
          var route = "{{url('page/pengelolaan/penyewaan/view')}}/"+value.kode_penyewaan+'-'+value.id_penyewaan;
          $("#detail_sewa_pesan").attr('href',route);
          $("#text_pesan").html(formattedText);
          $("#delete_pesan").attr('more_id',value.id_penyewaan_reminder);
          $("#nama_pesan").html(value.name);
          $("#email_pesan").html(value.email);
          $("#ponsel_pesan").html(value.ponsel);
          $("#tanggal_pesan").html(tanggal_indonesia(value.tanggal_reminder));
        });
      },
      error: function(response) {
        get_edit(reminderID);
      }
    });
  }
  $(document).on('click','.view',function() {
    $("#loading").show();
    var reminderID = $(this).attr('more_id');
    $("#contenIndex").hide();
    if (reminderID) {
      get_edit(reminderID);
    }
  });
  $(".back").click(function() {
    $("#contenView").hide();
    $("#contenIndex").show();
  });
  $(document).on('click', '.delete', function (event) {
    reminderID = $(this).attr('more_id');
    event.preventDefault();
    Swal.fire({
      title: 'Lanjut Hapus Data?',
      text: 'Riwayat Reminder akan dihapus secara Permanent!',
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
          url: "{{url('page/riwayat_reminder/destroy')}}"+"/"+reminderID,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              $("#contenView").hide();
              setTimeout(function(){
                $("#contenIndex").show();
                showToast('bg-success','Reminder Dihapus',response.message);
                $('#table_penyewaan').DataTable().ajax.reload();         
              }, 50);
            }else{
              showToast('bg-danger','Reminder Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Reminder Error',response.message);
          }
        })
      }
    });
  });
</script>
@endsection
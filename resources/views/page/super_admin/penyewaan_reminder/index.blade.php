  @extends('page/layout/app')

  @section('title','Reminder Akhir Sewa Kos')

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
              <li class="breadcrumb-item active" aria-current="page">Reminder Penyewaan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            Reminder Penyewaan Akhir Sewa Kos (H-30) {!! $label_cabang ? '<strong>(Cabang : ' . $label_cabang->nama_cabang . ')</strong>' : '' !!}
            <i class="bx bx-info-circle text-info" style="float: right;" data-bs-toggle="popover"
            data-bs-offset="0,14"
            data-bs-placement="top"
            data-bs-html="true"
            data-bs-content="Di halaman ini sistem akan menampilkan Data Penyewaan yang minimal <b>H-30</b> dari Tanggal Selesai Sewa dan yang belum melakukan Pembayaran Tagihan Kos."
            title="Informasi"></i>
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap dt-responsive" id="table_penyewaan" style="width: 100%;">
              <thead>
                <tr>
                  <th data-priority="2">No. </th>
                  <th data-priority="3">Nama Penghuni</th>
                  <th data-priority="4">Kamar</th>
                  <th data-priority="5">Total Tagihan</th>
                  <th data-priority="6">Jumlah Tagihan</th>
                  <th data-priority="7">Tanggal Selesai Sewa</th>
                  <th data-priority="8">Hari Tersisa</th>
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
  </div>
  <?php  
  function bulan_to_nama($bulan) {
    $nama_bulan = array(
      '01' => 'January',
      '02' => 'February',
      '03' => 'March',
      '04' => 'April',
      '05' => 'May',
      '06' => 'June',
      '07' => 'July',
      '08' => 'August',
      '09' => 'September',
      '10' => 'October',
      '11' => 'November',
      '12' => 'December'
    );
    return $nama_bulan[$bulan];
  }
  ?>
  @foreach($data as $dt)
  <?php  
  $bulan_tagihan = App\Models\Penyewaan::join('users','users.id','=','penyewaan.id_user')
  ->leftJoin('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
  ->where('pembayaran.id_penyewaan',$dt->id_penyewaan)
  ->where('pembayaran.id_bayar',NULL)
  // ->where('pembayaran.status_reminder',NULL)
  ->where('pembayaran.jenis_pembayaran','kos')
  ->orderBy('pembayaran.bulan_pembayaran','ASC')
  ->orderBy('pembayaran.tahun_pembayaran','ASC')
  ->get();
  $periode_tagihan_string = [];
  $batas_akhir = "";
  foreach ($bulan_tagihan as $bt) {
    $periode_tagihan_string[] = bulan_to_nama($bt->bulan_pembayaran) . ' - ' . $bt->tahun_pembayaran;
    $last_element = $bulan_tagihan->last();
    $batas_akhir = substr($dt->tanggal_selesai, -2).' '.bulan_to_nama($last_element->bulan_pembayaran).' '.date('Y');
  }
  $periode_tagihan = implode(', ', $periode_tagihan_string);
  ?>
  <div class="modal text-left" data-bs-backdrop="static" id="modal_reminder{{$dt->id_penyewaan}}" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-body">
         <form method="post" id="reminderForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <input type="" value="{{$dt->id_penyewaan}}" name="id_penyewaan" hidden="">
            <div class="col-12">
             @foreach($bulan_tagihan as $tbb)
             <input type="checkbox" checked="" hidden="" more_bulan="{{bulan_to_nama($tbb->bulan_pembayaran)}} - {{$tbb->tahun_pembayaran}}" more_harga="{{$dt->harga_kamar}}" more_penyewaan="{{date('d', strtotime($dt->tanggal_penyewaan))}}" value="{{bulan_to_nama($tbb->bulan_pembayaran)}}{{$tbb->bulan_pembayaran}}" class="form-check-input pilih_tagihan" name="pilih_tagihan[]">
             <input type="hidden" value="{{bulan_to_nama($tbb->bulan_pembayaran)}}{{$tbb->bulan_pembayaran}}" name="bulan_pembayaran[]">
             <input type="hidden" value="{{$tbb->id_pembayaran}}" class="form-check-input ml-1" name="id_pembayaran[]">
             @endforeach
             @foreach($reminder as $rem)
             <div id="reminderText">
              @foreach($reminder as $index => $rem)
              <p id="reminderTextContent">
               {!! nl2br(e(str_replace(
                ['$name', '$kamar', '$cabang', '$mulai','$selesai','$nomor_admin','$periode','$total_tagihan','$batas_akhir_bayar'],
                [$dt->name, $dt->nomor_kamar, $dt->nama_cabang, tanggal_indonesia($dt->tanggal_penyewaan), tanggal_indonesia($dt->tanggal_selesai), $dt->ponsel_operator, $periode_tagihan, number_format($dt->total_tagihan,0,",","."), $batas_akhir],$rem->pesan_reminder
                ))) !!}
              </p>
              @endforeach
            </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">
          <span>Tutup</span>
        </button>
        <button class="btn btn-danger ml-1 submit">
          <i class="fa fa-paper-plane"></i> <span>Kirim Email Tagihan</span>
        </button>
      </div>
    </form>
  </div>
</div>
</div>
@endforeach
<!--  -->
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
  function formatRupiah(value) {
    let stringValue = value.toString();
    let parts = stringValue.split(".");
    let wholePart = parts[0];
    let decimalPart = parts.length > 1 ? "." + parts[1] : "";
    let formattedWholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    let formattedValue = "Rp. " + formattedWholePart + decimalPart;
    return formattedValue;
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
    $('#table_penyewaan').DataTable({
      processing: true,
      pageLength: 10,
      responsive: true,
      colReorder: true,
      responsive: true,
      ajax: {
        url: "{{ route('penyewaan_reminder') }}",
        data: {access: access},
        error: function (jqXHR, textStatus, errorThrown) {
          $('#table_penyewaan').DataTable().ajax.reload();
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
        data: 'nomor_kamar', 
        name: 'nomor_kamar', 
        render: function (data, type, row) {
          return data;
        }  
      },
      { 
        data: 'total_tagihan', 
        name: 'total_tagihan', 
        render: function (data, type, row) {
          return formatRupiah(data,'Rp. ');
        }  
      },
      { 
        data: 'jumlah_tagihan', 
        name: 'jumlah_tagihan', 
        render: function (data, type, row) {
          return data+' Bulan';
        }  
      },
      { 
        data: 'tanggal_selesai', 
        name: 'tanggal_selesai', 
        render: function (data, type, row) {
          return tanggal_indonesia(data);
        }  
      },
      { 
        data: 'hari_tersisa', 
        name: 'hari_tersisa', 
        render: function (data, type, row) {
          return data+' Hari';
        }  
      },
      { data: 'action', name: 'action', orderable: false, className: 'space' }
      ]
    });
  });
  $(function () {
    $('#reminderForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      let contentTextValue = $('#reminderTextContent').text().trim();
      formData.append('reminderTextContent', contentTextValue);
      $(".invalid-feedback").children("strong").text("");
      $("#reminderForm input").removeClass("is-invalid");
      $("#loading").show();
      $.ajax({
        method: "POST",
        headers: {
          Accept: "application/json"
        },
        contentType: false,
        processData: false,
        url : "{{ route('send.reminder') }}",
        data: formData,
        success: function (response) {
          $('#reminderForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#reminderForm")[0].reset();
            Swal.fire({
              title: 'Success',
              text: response.message,
              icon: 'success',
              type: 'success'
            });
            $(".modal").modal('hide');
            setTimeout(function() {
              $('#table_penyewaan').DataTable().ajax.reload();
            }, 150);
          }else if(response.status == 'warning'){
            Swal.fire({
              title: 'Warning',
              text: response.message,
              icon: 'warning',
              type: 'warning'
            });
          }else {
            Swal.fire({
              title: ' Error',
              text: response.message,
              icon: 'error',
              type: 'error'
            });
          }
        },
        error: function (response) {
          $('#reminderForm').data('submitted', false);
          $("#loading").hide();
          if (response.status === 422) {
            let errors = response.responseJSON.errors;
            Object.keys(errors).forEach(function (key) {
              var key_temp = key.replaceAll(".", "_");
              $("#" + key_temp).addClass("is-invalid");
              $("#" + key_temp + "Error").children("strong").text(errors[key][0]);
              var tab_id = $("#" + key_temp + "Error").closest(".tab-pane").attr("id");
            });
          } else {
            Swal.fire({
              title: 'Error',
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
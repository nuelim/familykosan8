  @extends('page/layout/app')

  @section('title','Dashboard')

  @section('content')
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-primary btn-sm"><i class="fa fa-bell"></i> Izinkan Notifikasi</button>
        <div id="notification-status" style="float: right;"></div>
      </div>
      <div class="col-12">
        <div class="row">
          <div class="col-lg-9 mt-2">
            <div class="card">
             <canvas id="bar-chart"></canvas>
           </div>
         </div>
         <div class="col-lg-3 mt-2">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0">
                  <i class="bx bx-user" style="font-size: 0.5in;"></i>
                </div>
                <div class="dropdown">
                  <button
                  class="btn p-0"
                  type="button"
                  id="cardOpt3"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  >
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                  <a class="dropdown-item" href=" {{route('index.user',['level'=>'penghuni','akses'=>request()->has('access') ? request()->input('access') : null])}} ">Lihat Detail</a>
                </div>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Penghuni</span>
            <h3 class="card-title mb-2">{{$penghuni}}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>
  $(function () {
    var cData = JSON.parse(`<?php echo $chart_data; ?>`);
    var ctx = $("#bar-chart");
    // ctx.height = 400;

    var datasets = [
    {
      label: "Pembayaran Kos",
      data: cData.data.kos,
      backgroundColor: "#696cff",
      borderColor: "#4CAF50",
      borderWidth: 1,
    },
    {
      label: "Pembayaran Lain-Lain",
      data: cData.data.lain,
      backgroundColor: "#03c3ec",
      borderColor: "#2196F3",
      borderWidth: 1,
    },
    ];

    var data = {
      labels: cData.label,
      datasets: datasets,
    };

    var options = {
      responsive: true,
      layout: {
        padding: {
          left: 20,
          right: 20,
          top: 20,
          bottom: 20
        }
      },
      plugins: {
        title: {
          display: true,
          text: "Chart Pembayaran Kos dan Lain-Lain Tahun {{date('Y')}}",
          font: {
            size: 18
          },
          padding: {
            top: 10,
            bottom: 30
          },
        },
      },
      // title: {
      //   display: true,
      //   position: "top",
      //   text: "Chart Data Pembayaran {{date('Y')}}",
      //   fontSize: 18,
      //   fontColor: "#111",
      // },
      legend: {
        display: true,
        position: "bottom",
        labels: {
          fontColor: "#333",
          fontSize: 16,
        },
      },
      scales: {
        x: {
          barPercentage: 0.4,
          categoryPercentage: 0.5,
          grid: {
            display: false
          },
        },
        y: {
          beginAtZero: true,
        },
      },
    };

    var chart1 = new Chart(ctx, {
      type: "bar",
      data: data,
      options: options,
    });
  });

  var firebaseConfig = {
    apiKey: "AIzaSyBusFlAp2ReDK-KGBqydb_9vpZdmM7jDZg",
    authDomain: "tes-push-90913.firebaseapp.com",
    projectId: "tes-push-90913",
    storageBucket: "tes-push-90913.firebaseapp.com",
    messagingSenderId: "159888530881",
    appId: "1:159888530881:web:78dad6f37974fff3d52340",
    measurementId: "G-4NCJPP2H29"
  };
  firebase.initializeApp(firebaseConfig);
  const messaging = firebase.messaging();
  document.addEventListener('DOMContentLoaded', (event) => {
    const notificationStatusDiv = document.getElementById('notification-status');
    if (Notification.permission === "granted") {
      $("#btn-nft-enable").attr('disabled',true);
      notificationStatusDiv.innerHTML = '<span class="badge bg-success">Notifikasi Diizinkan</span>';
    } else if (Notification.permission === "denied") {
      $("#btn-nft-enable").attr('disabled',false);
      notificationStatusDiv.innerHTML = '<span class="badge bg-danger">Notifikasi Ditolak</span>';
    } else {
      $("#btn-nft-enable").attr('disabled',false);
    }
  });
  function initFirebaseMessagingRegistration() {
    messaging
    .requestPermission()
    .then(function () {
      return messaging.getToken()
    })
    .then(function(token) {
      console.log(token);
      $("#loading").show();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: " {{route('save-token')}} ",
        type: 'POST',
        data: {
          token: token
        },
        dataType: 'JSON',
        success: function (response) {
          $("#btn-nft-enable").attr('disabled',true);
          $("#loading").hide();
          Swal.fire({
            icon: 'success',
            type: 'success',
            title: 'Notifikasi Aktif',
            text: 'Anda akan menerima Notifikasi jika terdapat Upload Pembayaran dari Penghuni.'
          });
        },
        error: function (err) {
          $("#btn-nft-enable").attr('disabled',false);
          // $("#btn-nft-enable").trigger('click');
          $("#loading").hide();
        // Swal.fire({
        //   icon: 'error',
        //   type: 'error',
        //   title: 'Error',
        //   text: err
        // });
      },
    });

    }).catch(function (err) {
      $("#btn-nft-enable").attr('disabled',false);
      // $("#btn-nft-enable").trigger('click');
      $("#loading").hide();
    // Swal.fire({
    //   icon: 'error',
    //   type: 'error',
    //   title: 'Error',
    //   text: err
    // });
  });
  }  

  messaging.onMessage(function(payload) {
    const noteTitle = payload.notification.title;
    const noteOptions = {
      body: payload.notification.body,
      icon: payload.notification.icon,
    };
    new Notification(noteTitle, noteOptions);
  });
</script>
@endsection
  @extends('page/layout/app')

  @section('title', 'Penyewaan Detail')

  @section('content')
  @foreach($data as $dt)
  <div class="loading" id="loading" style="display: none;">
    <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
    <h4>Loading</h4>
  </div>
  <div class="page-heading" id="pagePenyewaan">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-first">
          <a href="javascript:void(0)" onclick="window.history.back()" ><i class="bx bx-arrow-back"></i></a>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-last">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Pengelolaan</a></li>
              <li class="breadcrumb-item"><a href="">Penyewaan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Detail Penyewaan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <section class="section">
      <div class="card">
        <h5 class="card-header">Penyewaan <b>#{{$dt->kode_penyewaan}}</b></h5>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8">
              <span><i class="fa fa-info-circle"></i> Disini anda dapat melihat detail penyewaan mulai dari:</span>
              <ol start="1">
                <li>Informasi Penghuni</li>
                <li>Informasi Kos</li>
                <li>Total Tagihan</li>
                <li>Tagihan belum bayar</li>
                <li>Tagihan sudah bayar</li>
              </ol>
            </div>
            <div class="col-lg-4">
              <button type="button" class="btn btn-danger new_form_bayar" more_type="reminder"><i class="fa fa-bullhorn"></i> Kirim Pesan Reminder</button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="table-responsive">
              <table class="table table-striped table-borderless border-bottom">
                <tbody>
                  <tr>
                    <th class="text-nowrap">Nama Penghuni</th>
                    <td>:</td>
                    <td>
                     {{$dt->name}}
                   </td>
                 </tr>
                 <tr>
                  <th class="text-nowrap">Email</th>
                  <td>:</td>
                  <td>
                   <a href="mailto:{{$dt->email}}">{{$dt->email}}</a>
                 </td>
               </tr>
               <tr>
                <th class="text-nowrap">No. Telp/WA</th>
                <td>:</td>
                <td>
                  {{$dt->ponsel}} / <a href="https://wa.me/62{{substr($dt->ponsel,1)}}" target="_blank" class="btn btn-xs rounded-pill btn-success"><i class="bx bxl-whatsapp"></i> WhatsApp</a>
                </td>
              </tr>
              <tr>
                <th class="text-nowrap">No. Darurat</th>
                <td>:</td>
                <td>
                 {{$dt->no_darurat}}
               </td>
             </tr>
             <tr>
              <th class="text-nowrap">Alamat</th>
              <td>:</td>
              <td>
               {{$dt->alamat}}
             </td>
           </tr>
         </tbody>
       </table>
     </div>
   </div>
   <div class="col-lg-6">
    <div class="table-responsive">
      <table class="table table-striped table-borderless border-bottom">
        <tbody>
          <tr>
            <th class="text-nowrap">Kamar</th>
            <td>:</td>
            <td>
             {{$dt->nama_cabang}} / {{$dt->nomor_kamar}}
           </td>
         </tr>
         <tr>
          <th class="text-nowrap">Harga per Bulan</th>
          <td>:</td>
          <td>
            Rp. {{number_format($dt->harga_kamar,0,",",".")}}
          </td>
        </tr>
        <tr>
         <th class="text-nowrap">Fasilitas</th>
         <td>:</td>
         <td>
          {{$dt->jumlah_fasilitas}} Fasilitas
        </td>
      </tr>
      <tr>
        <th class="text-nowrap">Jenis</th>
        <td>:</td>
        <td>
          {{$dt->jenis_kamar}}
        </td>
      </tr>
      <tr>
        <th class="text-nowrap">Keterangan Kamar</th>
        <td>:</td>
        <td>
         {{$dt->keterangan_kamar}}
       </td>
     </tr>
   </tbody>
 </table>
</div>
</div>
</div>
</div>
<!--  -->

<div class="card mt-2">
  <h5 class="card-header">Rincian Penyewaan dan Pembayaran</h5>
  <div class="card-body">
    <div class="row">
      <div class="col-sm-6">
        <label class="form-label" style="color: #000;">Tagihan Kos Belum Dibayar : Rp. {{number_format($dt->tagihan_belum_bayar,0,",",".")}}</label><br>
        <label class="form-label" style="color: #000;">Tagihan Kos Sudah Dibayar : Rp. {{number_format($dt->tagihan_sudah_bayar,0,",",".")}}</label><br>
        <label class="form-label" style="color: #000;">Total Semua Tagihan Kos : Rp. {{number_format($dt->total_tagihan,0,",",".")}}</label>
      </div>
      <div class="col-sm-6">
        <label class="form-label" style="color: #000;">Catatan/Keterangan Penyewaan : {{$dt->catatan_penyewaan}}</label><br>
        <!-- <label class="form-label" style="color: #000;">Rentang Bayar : <u>{{$dt->rentang_bayar}} bulan sekali</u></label><br> -->
        <label class="form-label" style="color: #000;">Tanggal Mulai Sewa : {{tanggal_indonesia($dt->tanggal_penyewaan)}}</label><br>
        <label class="form-label" style="color: #000;">Tanggal Selesai Sewa : {{tanggal_indonesia($dt->tanggal_selesai)}}</label><br>
        <label class="form-label" style="color: #000;">Hari Tersisa : {{$dt->hari_tersisa}} Hari</label>
      </div>
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
      if (array_key_exists($bulan, $nama_bulan)) {
        return $nama_bulan[$bulan];
      }
      return '(Data Bulan Kosong)'; // Atau return '' 
    }
    ?>
    <div class="row">
      <!-- Custom content with heading -->
      <div class="col-lg-12">
        <div class="demo-inline-spacing mt-3">
          <div class="list-group list-group-horizontal-md text-md-center">
            <a
            class="list-group-item list-group-item-action active"
            id="home-list-item"
            data-bs-toggle="list"
            href="#tagihan-belum-bayar"
            >Tagihan Kos Belum Bayar ({{count($tagihan_belum_bayar)}})</a
            >
            <a
            class="list-group-item list-group-item-action"
            id="profile-list-item"
            data-bs-toggle="list"
            href="#tagihan-sudah-bayar"
            >Tagihan Kos Sudah Dibayar ({{count($tagihan_sudah_bayar)}})</a
            >
            <a
            class="list-group-item list-group-item-action"
            id="messages-list-item"
            data-bs-toggle="list"
            href="#pembayaran-kos-belum-dikofirmasi"
            >Pembayaran Kos Belum dikonfirmasi ({{count($pembayaran_belum_dikonfirmasi)}})
            @if(count($pembayaran_belum_dikonfirmasi) > 0)
            <sup>
              <div class="spinner-grow spinner-grow-sm text-success" role="status"></div>
            </sup>
            @endif
            </a
            >
            <a
            class="list-group-item list-group-item-action"
            id="settings-list-item"
            data-bs-toggle="list"
            href="#pembayaran-lainnya"
            >Pembayaran Lainnya ({{count($pembayaran_lainnya)}})</a
            >
          </div>
          <div class="tab-content px-0 mt-0">
            <div class="tab-pane fade show active" id="tagihan-belum-bayar">
              <div class="table-responsive">
                <button type="button" class="btn btn-primary new_form_bayar mb-1" more_type="kos"><i class="bx bx-up-arrow-circle"></i> &nbsp;Bayar Tagihan</button>
                <table class="table table-striped nowrap dt-responsive datatable" cellpadding="0" cellspacing="0" id="table_penyewaan" style="width: 100%;">
                  <thead class="bg-dark text-white">
                    <tr>
                      <th data-priority="" class="text-white">No. </th>
                      <th data-priority="" class="text-white">Bulan</th>
                      <th data-priority="" class="text-white">Tahun</th>
                      <th data-priority="" class="text-white">Tagihan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($tagihan_belum_bayar as $tbb)
                    <tr>
                      <td>{{$loop->index+1}}. </td>
                      <td>{{bulan_to_nama($tbb->bulan_pembayaran)}}</td>
                      <td>{{$tbb->tahun_pembayaran}}</td>
                      <td>Rp. {{number_format($tbb->harga_pembayaran,0,",",".")}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="tagihan-sudah-bayar">
             <div class="table-responsive">
              <table class="table table-striped dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
                <thead class="bg-dark text-white">
                  <tr>
                    <th data-priority="" class="text-white">No. </th>
                    <th data-priority="" class="text-white">Kode Pembayaran</th>
                    <th data-priority="" class="text-white">Tipe Pembayaran</th>
                    <th data-priority="" class="text-white">Tanggal Pembayaran</th>
                    <th data-priority="" class="text-white">Total Tagihan</th>
                    <th data-priority="" class="text-white">Bukti Bayar</th>
                    <th data-priority="" class="text-white">Keterangan Pembayaran</th>
                    <th data-priority="" class="text-white">Invoice</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tagihan_sudah_bayar as $tsb)
                  <tr>
                    <td>{{$loop->index+1}}. </td>
                    <td>{{$tsb->kode_bayar}}<br><a href="" more_id="{{$tsb->id_bayar}}" data-bs-toggle="modal" data-bs-target="#modal_rincian_pembayaran{{$tsb->id_bayar}}" class="">Lihat Rincian <i class="fa fa-eye"></i></a></td>
                    <td>{{$tsb->tipe_bayar}}</td>
                    <td>{{tanggal_indonesia($tsb->tanggal_bayar)}}</td>
                    <td>Rp. {{number_format($tsb->total_tagihan,0,",",".")}}</td>
                    <td>
                      @if($tsb->tipe_bayar == 'Tunai')
                      -
                      @else
                      <img src="{{asset('bukti_bayar')}}/{{$tsb->bukti_bayar}}" width="65">
                      @endif
                    </td>
                    <td>{{$tsb->keterangan_bayar}}</td>
                    <td align="center"><a href="javascript:void(0)" more_id="{{$tsb->id_bayar}}" class="btn btn-info btn-sm rounded-pill btn_invoice"><i class='bx bx-receipt'></i></a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="pembayaran-kos-belum-dikofirmasi">
            <div class="table-responsive">
              <table class="table table-striped nowrap dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
                <thead class="bg-dark text-white">
                  <tr>
                    <th data-priority="" class="text-white">No. </th>
                    <th data-priority="" class="text-white">Kode Pembayaran</th>
                    <th data-priority="" class="text-white">Tanggal Pembayaran</th>
                    <th data-priority="" class="text-white">Total Tagihan</th>
                    <th data-priority="" class="text-white">Bukti Bayar</th>
                    <th data-priority="" class="text-white">Keterangan Pembayaran</th>
                    <th data-priority="" class="text-white">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pembayaran_belum_dikonfirmasi as $pbk)
                  <tr>
                    <td>{{$loop->index+1}}. </td>
                    <td>{{$pbk->kode_bayar}}</td>
                    <td>{{tanggal_indonesia($pbk->tanggal_bayar)}}</td>
                    <td>Rp. {{number_format($pbk->total_tagihan,0,",",".")}}</td>
                    <td>
                      <img src="{{asset('bukti_bayar')}}/{{$pbk->bukti_bayar}}" width="65">
                    </td>
                    <td>{{$pbk->keterangan_bayar}}</td>
                    <td><a href="javascript:void(0)" data-bs-toggle="modal" more_id="{{$pbk->id_bayar}}" data-bs-target="#modal_rincian_pembayaran{{$pbk->id_bayar}}"  class="btn btn-sm btn-success text-white">Lihat</a></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="pembayaran-lainnya">
            <div class="table-responsive">
              <button type="button" class="btn btn-primary new_form_bayar mb-1" more_type="lain-lain"><i class="fa fa-plus"></i> &nbsp;Pembayaran Lain</button>
              <table class="table table-striped dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
                <thead class="bg-dark text-white">
                  <tr>
                    <th data-priority="" class="text-white">No. </th>
                    <th data-priority="" class="text-white">Kode Pembayaran</th>
                    <th data-priority="" class="text-white">Tanggal Pembayaran</th>
                    <th data-priority="" class="text-white">Tipe Pembayaran</th>
                    <th data-priority="" class="text-white">Nominal</th>
                    <th data-priority="" class="text-white">Bukti Bayar</th>
                    <th data-priority="" class="text-white">Keterangan Pembayaran</th>
                    <th data-priority="" class="text-white">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pembayaran_lainnya as $pl)
                  <tr>
                    <td>{{$loop->index+1}}. </td>
                    <td>{{$pl->kode_bayar}}</td>
                    <td>{{tanggal_indonesia($pl->tanggal_bayar)}}</td>
                    <td>{{$pl->tipe_bayar}}</td>
                    <td>Rp. {{number_format($pl->harga_pembayaran,0,",",".")}}</td>
                    <td>
                      @if($pl->tipe_bayar == 'Tunai')
                      -
                      @else
                      <img src="{{asset('bukti_bayar')}}/{{$pl->bukti_bayar}}" width="65">
                      @endif
                    </td>
                    <td>{{$pl->keterangan_bayar}}</td>
                    <td>
                      <!-- <a href="javascript:void(0)" class="btn btn-sm btn-success text-white edit_bayar" more_id="{{$pl->id_bayar}}">
                        <i class="bx bx-edit"></i>
                      </a> -->
                      <a href="" class="btn btn-danger btn-sm rounded-pill action_pembayaran" more_action="delete" more_id="{{$pl->id_bayar}}" more_jenis="lain-lain"><i class="bx bx-trash"></i></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @include('page.super_admin.penyewaan.pembayaran.rincian_pembayaran')
        @include('page.super_admin.penyewaan.pembayaran.form')
      </div>
    </div>
  </div>
</div>
</div>
</section>
</div>
@endforeach
<!--  -->
@endsection
@section('css')
<style type="text/css">
  /* The Modal (background) */
  .zoom-modal {
    display: none;
    position: fixed;
    z-index: 100;
    padding-top: 80px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.9);
  }

  /* Modal Content (image) */
  .zoom-modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    transition: transform 0.25s ease;
  }

  /* The Close Button */
  .zoom-modal .close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
  }

  .zoom-modal .close:hover,
  .zoom-modal .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
  }

  .zoom-container img:hover {
    cursor: pointer;
  }

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
  $(function () {
    $('#table_tagihan_belum_bayar').DataTable({
      processing: true,
      pageLength: 100,
      responsive: true,
      colReorder: true,
      responsive: true,
    });
  });
  $(function () {
    $('.datatable').DataTable({
      processing: true,
      pageLength: 100,
      responsive: true,
      colReorder: true,
      responsive: true,
    });
  });
  let ajaxUrl;
  let newType;
  $(document).ready(function() {
    $(".new_form_bayar").click(function() {
      newType = $(this).attr('more_type');
      if (newType == 'reminder') {
        ajaxUrl = "{{ route('send.reminder') }}";
      }else{
        ajaxUrl = "{{ route('save.pembayaran') }}";
      }
      $("#loading").hide();
      $("#bayarForm")[0].reset();
      $(".invalid-feedback").children("strong").text("");
      $("#bayarForm input").removeClass("is-invalid");
      $("#bayarForm select").removeClass("is-invalid");
      $("#tipe_bayar").val(null).trigger('change');
      $("#semua_tagihan").prop('checked',false).trigger('change');
      if (newType == 'kos') {
        $("#row_reminder").hide();
        $("#content_form_bayar").show();
        $("#jenis_bayar").val(newType);
        $(".modal-title").html('<i class="bx bx-up-arrow-circle"></i> Form Bayar Tagihan Kos');
        $("#label_submit_bayar").html('<i class="bx bx-save"></i> BAYAR');
        $("#row_total_tagihan").show();
        $("#row_table_tagihan").show();
        $("#row_nominal_bayar").hide();
      }else if(newType == 'lain-lain'){
        $("#row_reminder").hide();
        $("#content_form_bayar").show();
        $("#jenis_bayar").val(newType);
        $(".modal-title").html('<i class="fa fa-plus"></i> Form Pembayaran Lainnya');
        $("#row_total_tagihan").hide();
        $("#row_table_tagihan").hide();
        $("#row_nominal_bayar").show();
        $("#label_submit_bayar").html('<i class="bx bx-save"></i> BAYAR');
      }else{
        $("#row_reminder").show();
        $("#content_form_bayar").hide();
        $("#jenis_bayar").val(newType);
        $(".modal-title").html('<i class="fa fa-bullhorn"></i> Pesan Pengingat');
        $("#row_total_tagihan").show();
        $("#row_table_tagihan").show();
        $("#row_nominal_bayar").hide();
        $("#label_submit_bayar").html('<i class="bx bx-save"></i> KIRIM PESAN');
      }
      $("#modal_form_bayar").modal('show');
    });
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
  $(document).on('keyup','#harga_pembayaran',function() {
    $(this).val(formatRupiah($(this).val(), 'Rp. '));
  });
  // $(document).ready(function() {
  //   function updateTotal() {
  //     let total = 0;
  //     $('.pilih_tagihan:checked').each(function() {
  //       total += parseFloat($(this).attr('more_harga'));
  //     });
  //     $('.label_total_tagihan').text('Rp ' + total.toLocaleString());
  //   }
  //   $('.pilih_tagihan').on('change', function() {
  //     updateTotal()
  //   });
  //   $('#semua_tagihan').on('change', function() {
  //     let isChecked = $(this).is(':checked');
  //     $('.pilih_tagihan').prop('checked', isChecked);
  //     updateTotal();
  //   });
  // });
  document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.pilih_tagihan');
    const reminderTextContents = document.querySelectorAll('[id^="reminderTextContent"]');
    const selectAllCheckbox = document.getElementById('semua_tagihan');
    
    // Simpan teks asli dari setiap elemen reminderTextContents
    const originalTexts = [];
    reminderTextContents.forEach(element => {
      originalTexts.push(element.innerHTML.trim());
    });

    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        updateReminderText();
      });
    });
    
    selectAllCheckbox.addEventListener('change', function () {
      checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
      });
      updateReminderText();
    });
    
    function updateReminderText() {
      let selectedTagihan = [];
      let totalTagihan = 0;
      let batasAkhirBayar = null;
      let lastSelectedBulan = null;

      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          let harga = parseFloat(checkbox.getAttribute('more_harga'));
          totalTagihan += harga;
          selectedTagihan.push(checkbox.getAttribute('more_bulan'));
          lastSelectedBulan = checkbox.getAttribute('more_bulan');
          if (!batasAkhirBayar) {
            batasAkhirBayar = checkbox.getAttribute('more_penyewaan');
          }
        }
      });

      $('.label_total_tagihan').text('Rp ' + totalTagihan.toLocaleString());

      reminderTextContents.forEach((element, index) => {
        if (selectedTagihan.length > 0) {
          let originalText = originalTexts[index];
          originalText = originalText.replace('$periode', '('+selectedTagihan.join(', ')+')');
          originalText = originalText.replace('$total_tagihan', 'Rp ' + totalTagihan.toLocaleString());
          originalText = originalText.replace('$batas_akhir_bayar', batasAkhirBayar+' '+lastSelectedBulan);
          element.innerHTML = originalText;
        } else {
          element.innerHTML = originalTexts[index];
        }
      });
    }

    // Pemanggilan fungsi updateReminderText() saat halaman pertama kali dimuat
    updateReminderText();
  });





  $("#tipe_bayar").change(function() {
    var tipeBayar = $(this).val();
    if (tipeBayar) {
      if (tipeBayar == 'Transfer') {
        $('.img_preview_bukti').css('display','block');
        $("#row_bukti_bayar").show();
      }else{
        $('.img_preview_bukti').css('display','none');
        $('#bukti_bayar').val('');
        $('#bukti_bayar').empty('');
        $('.img_preview_bukti').attr('src','');
        $("#row_bukti_bayar").hide();
      }
    }else{
      $('.img_preview_bukti').css('display','none');
      $('#bukti_bayar').val('');
      $('#bukti_bayar').empty('');
      $('.img_preview_bukti').attr('src','');
      $("#row_bukti_bayar").hide();
    }
  });
  $("#bukti_bayar").change(function() {
    if (this.files && this.files[0]) {
      show_loading();
      var file = this.files[0];
      var allowedExtensions = ['png','jpg','jpeg'];
      var fileExtension = file.name.split('.').pop().toLowerCase();

      if (!allowedExtensions.includes(fileExtension)) {
        alert('File Dokumen tidak didukung.');
        $("#bukti_bayar").val('');
        $("#bukti_bayar").empty('');
        hide_loading();
        return;
      }
      $('.img_preview_bukti').css('display','none');
      var reader = new FileReader();
      reader.onload = function (e) {
        setTimeout(function() {
          hide_loading();
          $('.img_preview_bukti').css('display','block');
          $('.img_preview_bukti').attr('src',e.target.result);
        }, 500);
      };
      reader.readAsDataURL(this.files[0]);
    } else {
      $('.img_preview_bukti').css('display','none');
      $('#bukti_bayar').val('');
      $('#bukti_bayar').empty('');
    }
  });
  $(function () {
    $('#bayarForm').submit(function (e) {
      e.preventDefault();
      if ($(this).data('submitted') === true) {
        return;
      }
      $(this).data('submitted', true);
      let formData = new FormData(this);
      if (newType == 'reminder') {
        let contentTextValue = $('#reminderTextContent').text().trim();
        formData.append('reminderTextContent', contentTextValue);
      }
      $(".invalid-feedback").children("strong").text("");
      $("#bayarForm input").removeClass("is-invalid");
      $("#bayarForm select").removeClass("is-invalid");
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
          $('#bayarForm').data('submitted', false);
          $("#loading").hide();
          if (response.status == 'true') {
            $("#bayarForm")[0].reset();
            Swal.fire({
              title: 'Success',
              text: response.message,
              icon: 'success',
              type: 'success'
            });
            setTimeout(function() {
              document.location.href = '';
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
          $('#bayarForm').data('submitted', false);
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
  function get_edit_bayar(bayarID) {
    $.ajax({
      type: "GET",
      url: "{{url('page/pengelolaan/penyewaan/bayar_tagihan/get_edit')}}"+"/"+bayarID,
      success: function(response) {
        if (response) {
          $("#loading").hide();
          $.each(response, function(key, value) {
            $("#tipe_bayar").val(value.tipe_bayar).trigger('change');
            $("#tanggal_bayar").val(value.tanggal_bayar);
            $("#harga_pembayaran").val(formatRupiah(value.harga_pembayaran,'Rp. '));
            $("#id_bayar").val(value.id_bayar);
            $("#keterangan_bayar").val(value.keterangan_bayar);
            $("#bukti_bayarLama").val(value.bukti_bayar);
            if (value.tipe_bayar == 'Transfer') {
              $('.img_preview_bukti').attr("src","{{asset('bukti_bayar')}}/"+value.bukti_bayar);
              $(".img_preview_bukti").css('display','block');
              $("#row_bukti_bayar").show();
            }else{
              $('.img_preview_bukti').attr("src",'');
              $(".img_preview_bukti").css('display','none');
              $("#row_bukti_bayar").hide();
            }
          });
        }
      },
      error: function(response) {
        get_edit_bayar(bayarID);
      }
    });
  }
  // $(document).on('click','.edit_bayar',function() {
  //   var newType = 'lain-lain';
  //   ajaxUrl = "  ";
  //   var bayarID = $(this).attr('more_id');
  //   $("#loading").show();
  //   $("#bayarForm")[0].reset();
  //   $(".invalid-feedback").children("strong").text("");
  //   $("#bayarForm input").removeClass("is-invalid");
  //   $("#bayarForm select").removeClass("is-invalid");
  //   $("#bayarForm textarea").removeClass("is-invalid");
  //   $("#tipe_bayar").val(null).trigger('change');
  //   $("#semua_tagihan").prop('checked',false).trigger('change');
  //   $("#jenis_bayar").val(newType);
  //   $(".modal-title").html('<i class="fa fa-edit"></i> Form Edit Pembayaran Lainnya');
  //   $("#row_total_tagihan").hide();
  //   $("#row_table_tagihan").hide();
  //   $("#row_nominal_bayar").show();
  //   $("#modal_form_bayar").modal('show');
  //   get_edit_bayar(bayarID);
  // });
  $(document).on('click', '.action_pembayaran', function (event) {
    var bayarID = $(this).attr('more_id');
    var jenisBayar = $(this).attr('more_jenis');
    var actionBayar = $(this).attr('more_action');
    var penyewaanID = $(this).attr('more_id_penyewaan');
    event.preventDefault();
    let title, text, icon, type;
    if (actionBayar == 'delete') {
      title = 'Lanjut Hapus Data?';
      text = 'Data Pembayaran akan dihapus secara Permanent!?';
      icon = 'warning';
      type = 'warning';
    }else{
      title = 'Konfirmasi Pembayaran';
      text = 'Terima Pembayaran Kos';
      icon = 'info';
      type = 'info';
    }
    Swal.fire({
      title: title,
      text: text,
      icon: icon,
      type: type,
      showCancelButton: !0,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: 'Lanjutkan'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#loading").show();
        $.ajax({
          method: "GET",
          // url: "{{url('page/pengelolaan/penyewaan/bayar_tagihan/destroy')}}"+"/"+bayarID+"?jenis_bayar="+jenisBayar+"&action_bayar="+actionBayar,
          url: "{{url('page/pengelolaan/penyewaan/bayar_tagihan/destroy')}}"+"/"+bayarID+"?jenis_bayar="+jenisBayar+"&action_bayar="+actionBayar+"&id_penyewaan="+penyewaanID,
          success:function(response)
          {
            $("#loading").hide();
            if (response.status == 'true') {
              showToast('bg-success','Pembayaran Success',response.message);
              setTimeout(function(){
                document.location.href=''
              }, 200);
            }else{
              showToast('bg-danger','Pembayaran Error',response.message);
            }
          },
          error: function(response) {
            $("#loading").hide();
            showToast('bg-danger','Pembayaran Error',response.message);
          }
        })
      }
    });
  });
  // script.js
  $(document).on('click', '.zoom-image', function() {
    var bayarID = $(this).attr('more_id');
    var src = $(this).attr('src');
    var modal = document.getElementById('zoom-modal-'+bayarID);
    var modalImg = document.getElementById('modal-zoom-image-'+bayarID);
    // var span = document.getElementsByClassName('close')[0];

    modal.style.display = "block";
    modalImg.src = src;

    // span.onclick = function() { 
    //   modal.style.display = "none";
    // }

    $(document).on('click','.close',function() {
      modal.style.display = "none";
    });

    let isZoomed = false;

    modalImg.onclick = function(e) {
      if (!isZoomed) {
        const rect = modalImg.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const xPercent = x / rect.width * 100;
        const yPercent = y / rect.height * 100;

        modalImg.style.transformOrigin = `${xPercent}% ${yPercent}%`;
        modalImg.style.transform = 'scale(2)';
        isZoomed = true;
      } else {
        modalImg.style.transform = 'scale(1)';
        isZoomed = false;
      }
    }
  });
  $(".btn_invoice").click(function(event){
    event.preventDefault();
    var invoiceID = $(this).attr('more_id');
    var route = "{{url('page/invoice')}}/"+invoiceID;
    $("#loading").show();
    setTimeout(function() {
      $("#loading").hide();
      document.location.href = route;
    }, 2000);
  });
</script>
@endsection
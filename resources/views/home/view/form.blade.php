<div class="modal" id="modal_form" data-bs-backdrop="static" tabindex="-1" role="dialog" style="transition: none;">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Form Sewa Kamar | {{$dt->nomor_kamar}}</h5>
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times" aria-hidden="true"></span></button>
      </div>
      <div class="modal-body">
        <form method="post" id="penyewaanForm" enctype="multipart/form-data">
          @csrf
          <div class="row no-gutters">
            <input type="checked" id="checklist" value="true" name="checklist" checked="" hidden="">
            <div class="col-lg-12 mb-3 border text-center border-primary" style="border-radius: 55px 55px 55px 55px;padding: 5px;">
              <h3>Kamar Kos {{$dt->nomor_kamar}}</h3>
            </div>
            <div class="col-xl-12">
              <div class='form-group'>
                <label><i class="fa fa-map-marker-alt"></i> Alamat Kos</label>
                <br><b>Gion Kos</b> | <span>{{$dt->alamat_cabang}}</span><br>
              </div>
            </div>
            @if(Auth::user())
            <div class="col-lg-12 text-center">
              <hr>
              <input type="" hidden="" id="id_kamar" name="id_kamar" value="{{$dt->id_kamar}}">
              <input type="" hidden="" id="id_user" name="id_user" value="{{Auth::user()->id}}">
              <span id="label_tanggal" style="color: #000;"></span>
              <div class="input-group" style="display: flex;
              justify-content: center; /* Pusatkan secara horizontal */
              align-items: center;    /* Pusatkan secara vertikal */
              margin: 0;">
                <!-- <div class="icon">
                  <i class="fa fa-calendar"></i>
                </div> -->
                <div id="calender_sewa"></div>
                <input type="text" id="tanggal_penyewaan" hidden="" name="tanggal_penyewaan" placeholder="Tentukan Tanggal Sewa ..." class="form-control">
                <span class="invalid-feedback" role="alert" id="tanggal_penyewaanError">
                  <strong></strong>
                </span>
              </div>
              <small><i class="fa fa-info-circle mt-2"> Pastikan tanggal yang kamu masukkan benar, karena penyewaan kamu akan aktif setelah pembayaran kos disetujui Admin.</i></small>
            </div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <div style="width: 100%;margin: auto;">
           <div class="row">
            <div class="col-8 btn-transparent text-right">
              <span class="text">Harga Kamar<br>Rp. {{number_format($dt->harga_kamar,0,",",".")}} / bulan</span>
            </div>
            <div class="col-4 btn-primary text-center">
              @if(Auth::user())
              <button class="btn text-white btn-transparent submit"><span>Ajukan Sewa</span></button>  
              @else
              <a href=" {{route('login')}} " class="btn text-white btn-transparent">Ajukan Sewa</a>
              @endif
            </div>
          </div> 
        </div>
      </div>
    </form>
  </div>
</div>
</div>
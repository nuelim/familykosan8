  <div class="modal text-left" data-bs-backdrop="static" id="modal_form_bayar" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white" style="margin-bottom:12px;" id="myModalLabel1"></h5>
        <button
        type="button"
        class="btn-close"
        data-bs-dismiss="modal"
        aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
       <form method="post" id="bayarForm" enctype="multipart/form-data">
        @csrf
        <input type="" hidden="" id="jenis_bayar" name="jenis_bayar">
        <input type="" hidden="" id="id_bayar" name="id_bayar">
        <input type="" hidden="" id="bukti_bayarLama" name="bukti_bayarLama">
        <div class="row">
          <input type="" hidden="" value="{{$dt->id_penyewaan}}" name="id_penyewaan">
          <h5 class="col-5">Tipe Pembayaran <span class="text-danger">*</span></h5>
          <div class="col-7">
            <div class="form-group">
              <select class="form-control" style="width: 100%;" id="tipe_bayar" name="tipe_bayar">
                <option value="">:. TIPE PEMBAYARAN .:</option>
                <option value="Transfer">Transfer</option>
              </select>
              <span class="invalid-feedback" role="alert" id="tipe_bayarError">
                <strong></strong>
              </span>
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <h5 class="col-5">Tanggal Pembayaran <span class="text-danger">*</span></h5>
          <div class="col-7">
            <div class="form-group">
              <input type="date" class="form-control" name="tanggal_bayar" id="tanggal_bayar">
              <span class="invalid-feedback" role="alert" id="tanggal_bayarError">
                <strong></strong>
              </span>
            </div>
          </div>
        </div>
        <div class="row mt-2" id="row_nominal_bayar">
          <h5 class="col-5">Nominal Bayar <span class="text-danger">*</span></h5>
          <div class="col-7">
            <div class="form-group">
              <input type="text" class="form-control" name="harga_pembayaran" id="harga_pembayaran">
              <span class="invalid-feedback" role="alert" id="harga_pembayaranError">
                <strong></strong>
              </span>
            </div>
          </div>
        </div>
        <div class="row mt-2" id="row_bukti_bayar">
          <h5 class="col-5">Bukti Pembayaran <span class="text-danger">*</span></h5>
          <div class="col-7">
            <div class="form-group">
              <input type="file" accept="image/*" class="form-control" name="bukti_bayar" id="bukti_bayar">
              <span class="invalid-feedback" role="alert" id="bukti_bayarError">
                <strong></strong>
              </span>
            </div>
          </div>
        </div>
        <div class="row mt-2" id="row_bukti_bayar_view">
          <h5 class="col-5"></h5>
          <div class="col-7">
           <img src="" width="150" class="img_preview_bukti">
         </div>
       </div>
       <div class="row mt-2">
        <h5 class="col-5">Keterangan Pembayaran</h5>
        <div class="col-7">
          <div class="form-group">
            <textarea class="form-control" rows="4" name="keterangan_bayar" id="keterangan_bayar"></textarea>
            <span class="invalid-feedback" role="alert" id="keterangan_bayarError">
              <strong></strong>
            </span>
          </div>
        </div>
      </div>
      <div class="row mt-2" id="row_total_tagihan">
        <h5 class="col-5">Total Tagihan :</h5>
        <h5 class="col-5 label_total_tagihan"></h5>
      </div>
      <div class="row mt-4" id="row_table_tagihan">
        <div class="col-12">
         <div class="table-responsive">
          <table class="table table-striped nowrap dt-responsive" id="table_tagihan_belum_bayar" cellpadding="0" cellspacing="0" style="width: 100%;">
           <thead class="bg-dark text-white">
            <tr>
              <th data-priority="" class="text-white"><input type="checkbox" class="form-check-input pilih_semua" name="semua_tagihan" id="semua_tagihan"></th>
              <th data-priority="" class="text-white">Bulan</th>
              <th data-priority="" class="text-white">Tahun</th>
              <th data-priority="" class="text-white">Tagihan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tagihan_belum_bayar as $tbb)
            <tr>
              <td>
                <input type="checkbox" more_harga="{{$tbb->harga_pembayaran}}" value="{{bulan_to_nama($tbb->bulan_pembayaran)}}{{$tbb->bulan_pembayaran}}" class="form-check-input pilih_tagihan" name="pilih_tagihan[]">
                <input type="hidden" value="{{bulan_to_nama($tbb->bulan_pembayaran)}}{{$tbb->bulan_pembayaran}}" name="bulan_pembayaran[]">
                <input type="hidden" value="{{$tbb->id_pembayaran}}" class="form-check-input ml-1" name="id_pembayaran[]">
              </td>
              <td>{{bulan_to_nama($tbb->bulan_pembayaran)}}</td>
              <td>{{$tbb->tahun_pembayaran}}</td>
              <td>Rp. {{number_format($tbb->harga_pembayaran,0,",",".")}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
   <!--  <button type="button" class="btn" data-bs-dismiss="modal">
      <span>Tutup</span>
    </button> -->
    <button class="btn btn-primary w-100 submit">
      <span><i class="bx bx-save"></i> PEMBAYARAN</span>
    </button>
  </div>
</form>
</div>
</div>
</div>
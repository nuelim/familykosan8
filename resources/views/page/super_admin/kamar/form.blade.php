   <div class="page-heading" hidden="" id="pageKamarForm">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Master Data</a></li>
              <li class="breadcrumb-item active" aria-current="page">Form Kamar</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 modal-title"></h5>
        <div class="col">
          <i class="bx bx-info-circle text-warning" style="float: right;" data-bs-toggle="popover"
          data-bs-offset="0,14"
          data-bs-placement="top"
          data-bs-html="true"
          data-bs-content="Dalam form ini anda dapat menambahkan kamar dan fasilitas per kamar. Form Kamar dengan klik Tab <b>Kamar</b> dan Form Fasilitas Kamar Kos dengan klik Tab <b>Fasilitas Kamar</b>."
          title="Informasi"></i>
        </div>
      </div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data" id="kamarForm">
          @csrf
          <div class="row">
            <div class="col-lg-12" id="tab_detail">
              <div class="nav nav-pills">
                <a class="nav-item nav-link active" style="width: 30%;" data-bs-toggle="tab" href="#tab-pane-1"><i class="bx bx-bed"></i> Kamar
                  <span class="error-tab text-danger">Error</span>
                </a>
                <a class="nav-item nav-link" style="width: 30%;" data-bs-toggle="tab" href="#tab-pane-2"><i class="bx bx-bath"></i> Fasilitas Kamar
                  <span class="error-tab text-danger">Error</span>
                </a>
                <a class="nav-item nav-link" style="width: 30%;" data-bs-toggle="tab" href="#tab-pane-3"><i class="bx bx-image-add"></i> Gambar Kamar
                  <span class="error-tab text-danger">Error</span>
                </a>
                <input type="" id="id_foto_del" name="id_foto_del" hidden="">
                <input type="" id="id_fasilitas_del" name="id_fasilitas_del" hidden="">
              </div>
              <div class="tab-content">
                <!-- gambar -->
                <div class="tab-pane" id="tab-pane-3">
                  <button type="button" id="new_gambar" class="btn btn-info text-white btn-sm mb-2">
                    <i class="bx bx-plus"></i>
                  </button>
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped responsive-table" style="width: 100%;">
                      <thead style="background: #aaa;">
                       <tr>
                        <th>No. </th>
                        <th>Gambar</th>
                        <th>Tipe Gambar</th>
                        <th>Preview</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="table_gambar">
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- end gambar -->
              <!-- fasilitas -->
              <div class="tab-pane" id="tab-pane-2">
                <button type="button" id="new_fasilitas" class="btn btn-info text-white btn-sm mb-2">
                  <i class="bx bx-plus"></i>
                </button>
                <div class="table-responsive">
                  <table class="table table-hover table-bordered table-striped responsive-table" style="width: 100%;">
                    <thead style="background: #aaa;">
                     <tr>
                      <th>No. </th>
                      <th>Fasilitas</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="table_fasilitas">
                  </tbody>
                </table>
              </div>
            </div>
            <!-- end fasilitas -->
            <!-- kamar -->
            <div class="tab-pane show active" id="tab-pane-1">
             <div class="row">
               <div class="col-lg-6 mb-2">
                <label class="col-form-label">Cabang <span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-control" style="width: 100%;" id="id_cabang" name="id_cabang">
                    @foreach($cabang as $cbg)
                    <option value="{{$cbg->id_cabang}}">{{$cbg->nama_cabang}}</option>
                    @endforeach
                  </select>
                  <input type="" hidden="" id="id_kamar" name="id_kamar">
                  <span class="invalid-feedback" role="alert" id="id_cabangError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <label class="col-form-label">Nama Kamar <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" id="nama_kamar" autocomplete="off" name="nama_kamar" class="form-control" placeholder="Nama Kamar ..."/>
                  <span class="invalid-feedback" role="alert" id="nama_kamarError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <label class="col-form-label">Nomor Kamar <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" id="nomor_kamar" autocomplete="off" name="nomor_kamar" class="form-control" placeholder="Nomor Kamar ..."/>
                  <span class="invalid-feedback" role="alert" id="nomor_kamarError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <label class="col-form-label">Tarif/Harga Kamar per bulan <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" autocomplete="off" class="form-control" id="harga_kamar" name="harga_kamar" placeholder="Harga Kamar ..."/>
                  <span class="invalid-feedback" role="alert" id="harga_kamarError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <label class="col-form-label">Jenis Kamar <span class="text-danger">*</span></label>
                <div class="input-group">
                  <select class="form-control" style="width: 100%;" id="jenis_kamar" name="jenis_kamar">
                    <option value="Pria">Kos Putra</option>
                    <option value="Wanita">Kos Putri</option>
                    <option value="Campur">Campur</option>
                  </select>
                  <span class="invalid-feedback" role="alert" id="jenis_kamarError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-12">
                <label class="col-form-label">Keterangan</label>
                <div class="input-group">
                  <textarea class="form-control" placeholder="Keterangan ..." rows="4" id="keterangan_kamar" name="keterangan_kamar"></textarea>
                  <span class="invalid-feedback" role="alert" id="keterangan_kamarError">
                    <strong></strong>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <!-- end kamar -->
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
    <button type="button" class="btn close"> Kembali</button>
  </form>
  <div style="display:none;">
    <table id="sample_table_fasilitas">
      <tr id="">
        <td data-label="No."><span class="sn" style="vertical-align:middle;"></span></td>   
        <td data-label="Fasilitas">
          <input type="" hidden="" name="fasilitas[0][id_kamar_fasilitas]" id="fasilitas_0_id_kamar_fasilitas" class="form-control form-control-sm id_kamar_fasilitas_input">
          <select name="fasilitas[0][id_fasilitas]" style="width: 100%;" id="fasilitas_0_id_fasilitas" class="form-control form-control-sm id_fasilitas_input">
            @foreach($fasilitas as $fst)
            <option value="{{$fst->id_fasilitas}}">{{$fst->nama_fasilitas}}</option>
            @endforeach
          </select>
          <span class="invalid-feedback id_fasilitas_input_error" role="alert" id="fasilitas_0_id_fasilitasError">
            <strong></strong>
          </span>
        </td>
        <td>
          <center>
            <button type="button" class="delete-record btn btn-sm btn-danger" data-id="0"><i class="bx bx-trash"></i></button>
          </center>
        </td>
      </tr>
    </table>
  </div>
  <div style="display:none;">
    <table id="sample_table_gambar">
      <tr id="">
        <td data-label="No."><span class="sn" style="vertical-align:middle;"></span></td>   
        <td data-label="Isi">
          <input type="" hidden="" name="gambar[0][id_kamar_foto]" id="gambar_0_id_kamar_foto" class="form-control id_kamar_foto_input">
          <input type="file" name="gambar[0][foto]" id="gambar_0_foto" class="form-control foto_input" accept=".jpg, .jpeg, .png">
          <span class="invalid-feedback foto_input_error" role="alert" id="gambar_0_fotoError">
            <strong></strong>
          </span>
        </td>
        <td data-label="Tipe Gambar">
          <select name="gambar[0][tipe]" style="width: 100%;" id="gambar_0_tipe" class="form-control form-control-sm tipe_input">
            <option value="utama">Jadikan Utama</option>
            <option value="lainnya">Jadikan Lainnya</option>
          </select>
          <span class="invalid-feedback tipe_input_error" role="alert" id="gambar_0_tipeError">
            <strong></strong>
          </span>
        </td>
        <td data-label="Preview Foto">
          <img src="" class="img rounded-pill lihat_foto_input" id="gambar_0_lihat_foto" width="100">
        </td>
        <td>
          <center>
            <button type="button" class="delete-gambar btn btn-sm btn-danger" data-id="0"><i class="bx bx-trash"></i></button>
          </center>
        </td>
      </tr>
    </table>
  </div>
</div>
</div>
</div>
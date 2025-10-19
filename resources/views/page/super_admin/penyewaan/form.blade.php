  <div class="page-heading" id="pagePenyewaanForm" style="display: none;">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="">Pengelolaan</a></li>
              <li class="breadcrumb-item"><a href="">Penyewaan</a></li>
              <li class="breadcrumb-item active" aria-current="page">Form Penyewaan</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 modal-title"></h5>
      </div>
      <div class="card-body">
        <form method="post" enctype="multipart/form-data" id="penyewaanForm">
          @csrf
          <!-- kamar -->
          <div class="row">
            <div class="col-lg-12" id="tab_detail">
              <div class="nav nav-pills">
                <a class="nav-item nav-link active" id="tab-sewa" style="width: 50%;" data-bs-toggle="tab" href="#tab-pane-1"><i class="bx bx-sitemap"></i> Penghuni & Penyewaan
                  <span class="error-tab text-danger">Error</span>
                </a>
                <a class="nav-item nav-link" id="tab-kamar" style="width: 50%;" data-bs-toggle="tab" href="#tab-pane-2"><i class="bx bx-bed"></i> Pilih Kamar Kos
                  <span class="error-tab text-danger">Error</span>
                </a>
              </div>
              <div class="tab-content">
                <div class="tab-pane show active" id="tab-pane-1">
                  <div class="row">
                    <div class="col-lg-12">
                      <h5><i class="bx bx-customize"></i> Detail Penyewaan</h5>
                    </div>
                    <div class="col-lg-6 mb-2">
                      <label class="">Tanggal Mulai Sewa <span class="text-danger">*</span></label>
                      <div class="form-group">
                        <input type="" hidden="" id="id_penyewaan" name="id_penyewaan">
                        <input type="date" class="form-control" id="tanggal_penyewaan" name="tanggal_penyewaan">
                        <span class="invalid-feedback" role="alert" id="tanggal_penyewaanError">
                          <strong></strong>
                        </span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                      <label class="">Tanggal Akhir Sewa <span class="text-danger">*</span></label>
                      <div class="form-group">
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                        <span class="invalid-feedback" role="alert" id="tanggal_selesaiError">
                          <strong></strong>
                        </span>
                      </div>
                    </div>
                    <!-- <div class="col-lg-6 mb-2">
                      <label class="">Rentang Pembayaran <span class="text-danger">*</span></label>
                      <div class="form-group">
                        <select class="form-control" id="rentang_bayar" name="rentang_bayar">
                          <option value="1">1 bulan sekali</option>
                          <option value="3">3 bulan sekali</option>
                        </select>
                        <span class="invalid-feedback" role="alert" id="rentang_bayarError">
                          <strong></strong>
                        </span>
                      </div>
                    </div> -->
                    <div class="col-lg-6 mb-2" id="col_status_penyewaan">
                      <label class="">Status Penyewaan <span class="text-danger">*</span></label>
                      <div class="form-group">
                        <select class="form-control" style="width: 100%;" id="status_penyewaan" name="status_penyewaan">
                          <option value="P">Pending</option>
                          <option value="A">Aktif</option>
                          <option value="I">Selesai</option>
                        </select>
                        <span class="invalid-feedback" role="alert" id="status_penyewaanError">
                          <strong></strong>
                        </span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-2">
                      <label class="">Catatan/Keterangan Lain</label>
                      <div class="form-group">
                        <textarea class="form-control" rows="4" id="catatan_penyewaan" name="catatan_penyewaan"></textarea>
                        <span class="invalid-feedback" role="alert" id="catatan_penyewaanError">
                          <strong></strong>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 mt-2">
                      <h5><i class="bx bx-user"></i> Detail Penghuni 
                        / &nbsp;<button type="button" class="btn btn-xs btn-outline-primary new_penghuni" >
                          <i class="bx bx-plus"></i>
                        </button>
                        <button style="float: right;"
                        type="button"
                        class="btn text-nowrap text-primary"
                        data-bs-toggle="popover"
                        data-bs-offset="0,14"
                        data-bs-placement="top"
                        data-bs-html="true"
                        data-bs-content="Anda dapat menambahkan Akun Penghuni baru jika belum terdaftar dalam list Penghuni, dengan cara klik -> <b>+ Penghuni Baru</b>."
                        title="Informasi">
                        <i class="bx bx-info-circle"></i>
                      </button>
                    </h5>
                  </div>
                  <div class="col-lg-6 mb-2">
                    <label class="">Nama Penghuni <span class="text-danger">*</span> </label>
                    <div class="form-group">
                      <select class="form-control" style="width: 100%;" id="id_user" name="id_user"></select>
                      <span class="invalid-feedback" role="alert" id="id_userError">
                        <strong></strong>
                      </span>
                    </div>
                  </div>
                  <div class="col-lg-6 mb-2">
                    <label class="">Email <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="email" readonly="" disabled="" id="email_change" class="form-control">
                    </div>
                  </div>  
                  <div class="col-lg-6 mb-2">
                    <label class="">Tanggal Lahir <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="text" readonly="" disabled="" id="tgl_lahir_change" class="form-control">
                    </div>
                  </div>  
                  <div class="col-lg-6 mb-2">
                    <label class="">Tempat Lahir <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="text" readonly="" disabled="" id="tempat_lahir_change" class="form-control">
                    </div>
                  </div>  
                  <div class="col-lg-6 mb-2">
                    <label class="">NIK <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="number" readonly="" disabled="" id="nik_change" class="form-control">
                    </div>
                  </div>  
                  <div class="col-lg-6 mb-2">
                    <label class="">Nomor Telepon <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="number" readonly="" disabled="" id="ponsel_change" class="form-control">
                    </div>
                  </div>  
                  <div class="col-lg-6 mb-2">
                    <label class="">Alamat <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="text" readonly="" disabled="" id="alamat_change" class="form-control">
                    </div>
                  </div> 
                  <div class="col-lg-6 mb-2">
                    <label class="">Nomor Darurat <span class="text-danger">*</span></label>
                    <div class="form-group">
                      <input type="number" readonly="" disabled="" id="no_darurat_change" class="form-control">
                      <!-- <input type="" hidden="" id="cabang_user" name="cabang_user"> -->
                    </div>
                  </div> 
                </div>
              </div>
              <div class="tab-pane" id="tab-pane-2">
                <a href="javascript:void(0)" class="btn btn-danger cancel mb-3" hidden="">Batalkan Pilihan Kamar</a>
                <input type="" class="" hidden="" id="id_kamar" name="id_kamar">
                <!-- <input type="" id="cabang_kamar" hidden="" name="cabang_kamar"> -->
                <span class="invalid-feedback" role="alert" id="id_kamarError">
                  <strong></strong>
                </span>
                <div class="row">
                  <div class="col-lg-8" id="col_table_kamar">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="table-responsive text-nowrap">
                          <table class="table table-striped nowrap dt-responsive" id="table_kamar" style="width: 100%;">
                            <thead>
                              <tr>
                                <th data-priority="2">No. </th>
                                <th data-priority="3">Cabang</th>
                                <th data-priority="4">Nomor Kamar</th>
                                <th data-priority="5">Tarif per Bulan</th>
                                <th data-priority="6">Jenis</th>
                                <th data-priority="7">Jumlah Fasilitas</th>
                                <th data-priority="1">Pilih Kamar</th>
                              </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                              @foreach($kamar as $km)
                              <?php  
                              $jml = DB::table('kamar_fasilitas')->join('fasilitas','fasilitas.id_fasilitas','=','kamar_fasilitas.id_fasilitas')
                              ->where('kamar_fasilitas.id_kamar',$km->id_kamar)->get();
                              ?>
                              <tr>
                                <td>{{$loop->index+1}}. </td>
                                <td>{{$km->nama_cabang}}</td>
                                <td>{{$km->nomor_kamar}}</td>
                                <td>Rp {{number_format($km->harga_kamar,0,",",".")}}</td>
                                <td>{{$km->jenis_kamar}}</td>
                                <td>
                                  <a href="" data-bs-toggle="modal" data-bs-target="#detail{{$km->id_kamar}}" class="text-primary btn-fasilitas">
                                    <i class="fa fa-eye"></i> {{$km->jumlah_fasilitas}} Fasilitas
                                  </a>
                                </td>
                                <td align="center">
                                  <button type="button" more_kamar="{{$km->nomor_kamar}}" more_id_cabang="{{$km->id_cabang}}" more_cabang="{{$km->nama_cabang}}" more_harga="Rp. {{number_format($km->harga_kamar,0,",",".")}}" more_jenis="{{$km->jenis_kamar}}" more_jml="{{count($jml)}}" more_keterangan="{{$km->keterangan_kamar}}" id="pilih_{{$km->id_kamar}}" class="btn btn-success btn-sm rounded-pill pilih" more_id="{{$km->id_kamar}}"><i class="bx bx-check"></i></button>
                                  <i class="bx bx-check-circle done_change" style="font-size: 50px;" id="done_change_{{$km->id_kamar}}" hidden=""></i>
                                </td>
                              </tr>
                              @include('page.super_admin.penyewaan.view_fasilitas')
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="row">
                      <div class="col-lg-12">
                        <h5><i class="bx bx-bed"></i> Informasi Kamar</h5>
                      </div>
                      <div class="col-lg-12">
                        <div class="card overflow-hidden" style="height: 350px">
                          <div class="card-body scroll_info_kamar">
                            <table class="table" style="font-size: 13px;padding: 0;margin: 0;" cellpadding="0" cellspacing="0">
                              <tr><td>Cabang : <span id="nama_cabang" class="view_akun"><span></td></tr>
                                <tr><td>Kamar : <span id="nomor_kamar" class="view_akun"></span></td></tr>
                                <tr><td>Harga : <span id="harga_kamar" class="view_akun"></span></td></tr>
                                <tr><td>Fasilitas : <span id="jumlah_fasilitas" class="view_akun"></span></td></tr>
                                <tr><td>Jenis : <span id="jenis_kamar" class="view_akun"></span></td></tr>
                                <tr><td>Keterangan : <span id="keterangan_kamar" class="view_akun"></span></td></tr>
                              </table>
                              <span class="text text-success" id="label_pindah" hidden=""><b><i class="bx bx-check"></i> Anda melakukan perubahan/pindah kamar.</b></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <b><input type="checkbox" class="form-check-input" name="checklist" id="checklist"> Saya meyakini bahwa data yang saya masukkan telah sesuai.</b>
                  <span class="invalid-feedback d-block" role="alert" id="checklistError">
                    <strong></strong>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <!-- end kamar -->
          <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
          <button type="button" class="btn close"> Kembali</button>
        </form>
      </div>
    </div>
  </div>
  <!--  -->
  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_form_penghuni" tabindex="-1" role="dialog"
  aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
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
        <form method="post" id="penghuniForm" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-lg-9">
              <div class="row">
               <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Cabang <span class="text-danger">*</span></label>
                  <select style="width: 100%;" class="form-control change_view" id="id_cabang" name="id_cabang">
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
                  <label class="">Nama <span class="text-danger">*</span></label>
                  <input type="text" class="form-control input_view" id="name" name="name">
                  <span class="invalid-feedback" role="alert" id="nameError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Email </label>
                  <input type="email" class="form-control input_view" id="email" name="email">
                  <span class="invalid-feedback" role="alert" id="emailError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Password <span class="text-danger">*</span></label>
                  <input type="text" autocomplete="off" class="form-control input_view" id="password" name="password">
                  <span class="invalid-feedback" role="alert" id="passwordError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">NIK <span class="text-danger">*</span></label>
                  <input type="text" class="form-control input_view" id="nik" name="nik">
                  <span class="invalid-feedback" role="alert" id="nikError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Tempat Lahir <span class="text-danger">*</span></label>
                  <input type="text" class="form-control input_view" id="tempat_lahir" name="tempat_lahir">
                  <span class="invalid-feedback" role="alert" id="tempat_lahirError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Tanggal Lahir <span class="text-danger">*</span></label>
                  <input type="date" class="form-control change_view" id="tgl_lahir" name="tgl_lahir">
                  <span class="invalid-feedback" role="alert" id="tgl_lahirError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">No Telepon <span class="text-danger">*</span></label>
                  <input type="number" class="form-control input_view" id="ponsel" name="ponsel">
                  <span class="invalid-feedback" role="alert" id="ponselError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Foto KTP <span class="text-danger label_req_ktp">*</span></label>
                  <input type="file" accept="image/*" class="form-control change_view" id="foto" name="foto">
                  <input type="" hidden="" class="form-control change_view" id="fotoLama" name="fotoLama">
                  <span class="invalid-feedback" role="alert" id="fotoError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Alamat <span class="text-danger">*</span></label>
                  <input class="form-control input_view" rows="4" id="alamat" name="alamat">
                  <span class="invalid-feedback" role="alert" id="alamatError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">No. Darurat <span class="text-danger">*</span></label>
                  <input type="number" class="form-control input_view" id="no_darurat" name="no_darurat">
                  <span class="invalid-feedback" role="alert" id="no_daruratError">
                    <strong></strong>
                  </span>
                </div>
              </div>
              <div class="col-lg-6 mb-2">
                <div class="form-group">
                  <label class="">Status <span class="text-danger">*</span></label>
                  <select style="width: 100%;" class="form-control change_view" name="status_user" id="status_user">
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
              <div class="card-body scroll_info_penghuni" id="vertical-example">
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
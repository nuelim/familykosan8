                  @foreach($tagihan_sudah_bayar as $tsb)
                  <div class="modal fade text-left" data-bs-backdrop="static" id="modal_rincian_pembayaran{{$tsb->id_bayar}}" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" style="margin-bottom:12px;" id="myModalLabel1">Pembayaran #{{$tsb->kode_bayar}}</h5>
                          <button
                          type="button"
                          class="btn-close"
                          data-bs-dismiss="modal"
                          aria-label="Close"
                          ></button>
                        </div>
                        <?php  
                        $rincian_bayar = App\Models\Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
                        ->join('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
                        ->where('bayar.id_bayar',$tsb->id_bayar)
                        ->where('bayar.status_bayar','Sudah Bayar')
                        // ->where('pembayaran.jenis_pembayaran','kos')
                        ->where('pembayaran.id_bayar','!=',NULL)
                        ->orderBy('pembayaran.bulan_pembayaran','DESC')
                        ->orderBy('pembayaran.tahun_pembayaran','DESC')
                        ->get();
                        ?>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-12">
                              <a href="" class="btn btn-danger mb-4 action_pembayaran" more_action="delete" more_id="{{$tsb->id_bayar}}" more_id_penyewaan="{{$dt->id_penyewaan}}" more_jenis="kos"><i class="bx bx-trash"></i> Hapus Pembayaran</a>
                              @if($tsb->tipe_bayar == 'Transfer')
                              <br>
                              <div class="zoom-container">
                                <img class="zoom-image" id="zoom-image" more_id="{{$tsb->id_bayar}}" src="{{asset('bukti_bayar')}}/{{$tsb->bukti_bayar}}" width="100">
                              </div>
                              <div id="zoom-modal-{{$tsb->id_bayar}}" class="zoom-modal">
                              <span class="close">&times;</span>
                              <img class="zoom-modal-content" id="modal-zoom-image-{{$tsb->id_bayar}}">
                            </div>
                              @endif
                              <div class="table-responsive">
                                <table class="table table-striped nowrap dt-responsive datatable mt-2" cellpadding="0" cellspacing="0" style="width: 100%;">
                                 <thead class="bg-dark">
                                  <tr>
                                    <th data-priority="" class="text-white">No. </th>
                                    <th data-priority="" class="text-white">Tanggal</th>
                                    <th data-priority="" class="text-white">Bulan</th>
                                    <th data-priority="" class="text-white">Tahun</th>
                                    <th data-priority="" class="text-white">Keterangan Pembayaran</th>
                                    <th data-priority="" class="text-white">Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($rincian_bayar as $rb)
                                  <tr>
                                    <td>{{$loop->index+1}}. </td>
                                    <td>{{tanggal_indonesia($rb->tanggal_bayar)}}</td>
                                    <td>{{bulan_to_nama($rb->bulan_pembayaran)}}</td>
                                    <td>{{$rb->tahun_pembayaran}}</td>
                                    <td>{{$rb->keterangan_bayar}}</td>
                                    <td>{{$rb->status_bayar}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
                @foreach($pembayaran_belum_dikonfirmasi as $pbk)
                <div class="modal fade text-left" data-bs-backdrop="static" id="modal_rincian_pembayaran{{$pbk->id_bayar}}" tabindex="-1" role="dialog"
                  aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" style="margin-bottom:12px;" id="myModalLabel1">Pembayaran #{{$pbk->kode_bayar}}</h5>
                        <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        ></button>
                      </div>
                      <?php  
                      $rincian_bayar = App\Models\Penyewaan::join('pembayaran','pembayaran.id_penyewaan','=','penyewaan.id_penyewaan')
                      ->join('bayar','bayar.id_bayar','=','pembayaran.id_bayar')
                      ->where('bayar.id_bayar',$pbk->id_bayar)
                      ->where('bayar.status_bayar','Sedang di cek')
                        // ->where('pembayaran.jenis_pembayaran','kos')
                      ->where('pembayaran.id_bayar','!=',NULL)
                      ->orderBy('pembayaran.bulan_pembayaran','DESC')
                      ->orderBy('pembayaran.tahun_pembayaran','DESC')
                      ->get();
                      ?>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-12">
                            <a href="" class="btn btn-danger mb-4 action_pembayaran" more_action="delete" more_id="{{$pbk->id_bayar}}" more_jenis="kos" more_id_penyewaan="{{$dt->id_penyewaan}}"><i class="bx bx-trash"></i> Hapus Pembayaran</a>
                            <a href="" class="btn btn-success mb-4 action_pembayaran" more_action="confirm" more_id="{{$pbk->id_bayar}}" more_jenis="kos" more_id_penyewaan="{{$dt->id_penyewaan}}"><i class="bx bx-check"></i> Konfirmasi Pembayaran</a>
                            @if($pbk->tipe_bayar == 'Transfer')
                            <br>
                            <div class="zoom-container">
                              <img class="zoom-image" id="zoom-image" more_id="{{$pbk->id_bayar}}" src="{{asset('bukti_bayar')}}/{{$pbk->bukti_bayar}}" width="100">
                            </div>
                            <div id="zoom-modal-{{$pbk->id_bayar}}" class="zoom-modal">
                              <span class="close">&times;</span>
                              <img class="zoom-modal-content" id="modal-zoom-image-{{$pbk->id_bayar}}">
                            </div>
                            @endif
                            <div class="table-responsive">
                              <table class="table table-striped nowrap dt-responsive datatable mt-2" cellpadding="0" cellspacing="0" style="width: 100%;">
                               <thead class="bg-dark">
                                <tr>
                                  <th data-priority="" class="text-white">No. </th>
                                  <th data-priority="" class="text-white">Tanggal</th>
                                  <th data-priority="" class="text-white">Bulan</th>
                                  <th data-priority="" class="text-white">Tahun</th>
                                  <th data-priority="" class="text-white">Keterangan Pembayaran</th>
                                  <th data-priority="" class="text-white">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($rincian_bayar as $rb)
                                <tr>
                                  <td>{{$loop->index+1}}. </td>
                                  <td>{{$rb->tanggal_bayar}}</td>
                                  <td>{{bulan_to_nama($rb->bulan_pembayaran)}}</td>
                                  <td>{{$rb->tahun_pembayaran}}</td>
                                  <td>{{$rb->keterangan_bayar}}</td>
                                  <td>{{$rb->status_bayar}}</td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
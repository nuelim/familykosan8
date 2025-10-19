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
                        // ->where('bayar.status_bayar','Sudah Bayar')
                        // ->where('pembayaran.jenis_pembayaran','kos')
                        ->where('pembayaran.id_bayar','!=',NULL)
                        ->orderBy('pembayaran.bulan_pembayaran','DESC')
                        ->orderBy('pembayaran.tahun_pembayaran','DESC')
                        ->get();
                        ?>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-12">
                              @if($tsb->tipe_bayar == 'Transfer')
                              <div class="zoom-container">
                                <img class="zoom-image" id="zoom-image" more_id="{{$tsb->id_bayar}}" src="{{asset('bukti_bayar')}}/{{$tsb->bukti_bayar}}" width="100">
                              </div>
                              <div id="zoom-modal-{{$tsb->id_bayar}}" class="zoom-modal">
                                <span class="close">&times;</span>
                                <img class="zoom-modal-content" id="modal-zoom-image-{{$tsb->id_bayar}}">
                              </div>
                              @endif
                              <div class="table-responsive">
                                <table class="table table-striped nowrap dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
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
                @foreach($pembayaran_belum_dikonfirmasi as $bk)
                <div class="modal fade text-left" data-bs-backdrop="static" id="modal_rincian_pembayaran{{$bk->id_bayar}}" tabindex="-1" role="dialog"
                  aria-labelledby="myModalLabel1" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" style="margin-bottom:12px;" id="myModalLabel1">Pembayaran #{{$bk->kode_bayar}}</h5>
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
                      ->where('bayar.id_bayar',$bk->id_bayar)
                      // ->where('bayar.status_bayar','Sedang di cek')
                        // ->where('pembayaran.jenis_pembayaran','kos')
                      ->where('pembayaran.id_bayar','!=',NULL)
                      ->orderBy('pembayaran.bulan_pembayaran','DESC')
                      ->orderBy('pembayaran.tahun_pembayaran','DESC')
                      ->get();
                      ?>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-12">
                            @if($bk->tipe_bayar == 'Transfer')
                            <div class="zoom-container">
                              <img class="zoom-image" id="zoom-image" more_id="{{$bk->id_bayar}}" src="{{asset('bukti_bayar')}}/{{$bk->bukti_bayar}}" width="100">
                            </div>
                            <div id="zoom-modal-{{$bk->id_bayar}}" class="zoom-modal">
                              <span class="close">&times;</span>
                              <img class="zoom-modal-content" id="modal-zoom-image-{{$bk->id_bayar}}">
                            </div>
                            @endif
                            <div class="table-responsive">
                              <table class="table table-striped nowrap dt-responsive datatable" cellpadding="0" cellspacing="0" style="width: 100%;">
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
<div class="modal" id="detail{{$km->id_kamar}}" data-bs-backdrop="static">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="">Detail Kamar | #{{$km->nomor_kamar}}</h5>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-4">
						<label>Cabang</label>
					</div>
					<div class="col-2">:</div>
					<div class="col-6">
						<label>{{$km->nama_cabang}}</label>
					</div>
					<div class="col-4">
						<label>Nomor Kamar</label>
					</div>
					<div class="col-2">:</div>
					<div class="col-6">
						<label>{{$km->nomor_kamar}}</label>
					</div>
					<div class="col-4">
						<label>Jenis Kamar</label>
					</div>
					<div class="col-2">:</div>
					<div class="col-6">
						<label>{{$km->jenis_kamar}}</label>
					</div>
					<div class="col-4">
						<label>Tarif per Bulan</label>
					</div>
					<div class="col-2">:</div>
					<div class="col-6">
						<label>{{number_format($km->harga_kamar,0,",",".")}}</label>
					</div>
					<div class="col-4">
						<label>Jumlah Fasilitas</label>
					</div>
					<div class="col-2">:</div>
					<div class="col-6">
						<label>{{count($jml)}}</label>
					</div>
					<div class="col-4">
						<label>Keterangan</label>
					</div>
					<div class="col-2">:</div>
					<div class="col-6">
						<label>
							<?php
							$array = explode(PHP_EOL, $km->keterangan_kamar);
							$total = count($array);
							foreach($array as $item) {
								echo "<span>". $item . "</span><br>";
							}
							?>
						</label>
					</div>
					<div class="col-12">
						<hr>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						@foreach($jml as $jm)
						{{$jm->nama_fasilitas}}, 
						@endforeach
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal">
					<span>Tutup</span>
				</button>
			</div>
		</div>
	</div>
</div>
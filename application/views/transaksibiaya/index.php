<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user() || is_keuangan()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#tambahTransaksiBiaya">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Transaksi</span>
		    </a> 
	    <?php endif; ?>
	</div>

  	<div class="row clearfix">
	  	<div class="col-lg-12">
			
			<?= form_error('dataakun', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

			<?= $this->session->flashdata('message'); ?>
			
		</div>
	</div>


	<div class="row">
	  	<div class="col-12">
	    	<div class="card border-bottom-primary shadow mb-4">
	      		<div class="card-header py-3">
	        		<h6 class="m-0 font-weight-bold text-primary">Tabel <?= $title;  ?></h6>
	      		</div>
	      		<div class="card-body">

	        		<form action="" method="post">
	          			<div class="form-row align-items-end">

							<!-- Tanggal Awal -->
							<div class="col-md-3">
								<label for="tglAwal">Tanggal Awal</label>
								<div class="datepicker-wrapper">
									<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
									<i class="fa fa-calendar datepicker-icon" data-toggle></i>
								</div>	
							</div>

	            			<!-- Tanggal Akhir -->
	            			<div class="col-md-3">
	              				<label for="tglAkhir">Tanggal Akhir</label>
								<div class="datepicker-wrapper">
	              					<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input required>
									<i class="fa fa-calendar datepicker-icon" data-toggle></i>
								</div>	
							</div>

							<!-- Button -->
							<div class="col-md-3 d-flex align-items-end">
							  	<button type="submit" class="btn btn-success mr-2">
							    	<i class="fas fa-fw fa-filter"></i> Filter
							  	</button>
								<a href="" class="btn btn-secondary" data-toggle="modal" data-target="#printBiaya">
									<i class="fas fa-fw fa-print"></i> Cetak
								</a>
							</div>

	          			</div>
	        		</form>

	        		<hr>

					<div class="table-responsive">
				  		<table class="table table-dataAkun table-striped table-bordered table-sm" id="tabel-data">
						  	<thead class="text-center">
						  		<tr>
									<th scope="col">#</th>
									<th scope="col">Tanggal</th>
									<th scope="col">No Transaksi</th>
									<th scope="col">Keterangan</th>
									<th scope="col">Nominal</th>
									<th scope="col">Akun Kas / Bank</th>
									<th scope="col">Akun Biaya</th>
									<?php if (is_user() || is_keuangan()) : ?>
										<th scope="col">Action</th>
									<?php endif; ?>
						  		</tr>
						  	</thead>
						  	<tbody>
						  		<?php $i = 1; ?>
				  				<?php foreach ($dtAll as $row) : 
				  				?>
						    	<tr>
									<th scope="row"><?= $i++; ?></th>
									<td><?= convertDbdateToDate($row['tgl']); ?></td>
									<td><?= $row['no_trans']; ?></td>
									<td><?= $row['keterangan']; ?></td>
									<td class="text-right"><?= number_format($row['nilai'], 0, ',', '.'); ?></td>
									<td><?= $row['nm_kas']; ?></td>
									<td><?= $row['nm_biaya']; ?></td>
							      	<?php if (is_user() || is_keuangan()) : ?>
								      	<td class="text-center">
											<?php
												$file_name   = $row['bukti'];
												$file_path   = FCPATH . 'assets/file/bukti/' . $file_name;
												$file_url    = base_url('assets/file/bukti/' . $file_name);
												$file_exists = (!empty($file_name) && file_exists($file_path));
											?>

											<!-- BUTTON VIEW BUKTI -->
											<?php if ($file_exists) : ?>
												<a href="#" 
												class="badge badge-info btn-bukti"
												data-file="<?= $file_url; ?>"
												data-toggle="tooltip"
												title="Lihat Bukti">
													<i class="fas fa-file-alt"></i> Bukti
												</a>
											<?php else : ?>
												<span class="badge badge-secondary"
													style="opacity:0.6; cursor:not-allowed;"
													title="File bukti tidak tersedia">
													<i class="fas fa-file-alt"></i> Bukti
												</span>
											<?php endif; ?>

											<!-- EDIT -->									      	
											<a href="javascript:void(0)" 
												class="badge badge-success editTransaksiBiaya"
												data-toggle="modal" 
												data-target="#editTransaksiBiaya"
												data-id_trans_biaya="<?= $row['id_trans_biaya'] ?>"
												data-tgl="<?= convertDbdateToDate($row['tgl']) ?>"
												data-keterangan="<?= $row['keterangan'] ?>"
												data-nilai="<?= $row['nilai'] ?>"
												data-kas_id="<?= $row['kas_id'] ?>"
												data-biaya_id="<?= $row['biaya_id'] ?>"
												data-bukti="<?= $row['bukti'] ?>"
												data-bukti_url="<?= base_url('assets/file/bukti/'.$row['bukti']) ?>">
												<i class="fas fa-fw fa-edit"></i> Edit
											</a>

											<!-- DELETE -->
											<a href="#" class="badge badge-danger btn-delete" data-id="<?= $row['id_trans_biaya']; ?>" data-tgl="<?= $row['tgl']; ?>">
												<span class="icon text-white-50">
													<i class="fas fa-fw fa-trash"></i>
												</span> 
												<span class="text">Delete</span>
											</a>
								      	</td>
								  	<?php endif; ?>
						    	</tr>
						    	<?php 
								endforeach; ?>
						  	</tbody>
						</table>
					</div>
				</div>
			</div>
	  	</div>
	</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal View -->
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Preview Bukti Transaksi</h5>

        <div>
          <button type="button" class="btn btn-sm btn-secondary" id="toggleSize">
            <i class="fas fa-search-plus"></i> Actual Size
          </button>
          <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
        </div>
      </div>

      <div class="modal-body p-0 text-center" style="height:80vh; overflow:auto;">

        <!-- IMAGE PREVIEW -->
        <img id="imgPreview" src="" style="display:none; max-width:100%; height:auto;" />

        <!-- PDF PREVIEW -->
        <iframe id="pdfPreview"
                src=""
                style="display:none; width:100%; height:100%; border:none;">
        </iframe>

      </div>

    </div>
  </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="tambahTransaksiBiaya" tabindex="-1" role="dialog" aria-labelledby="tambahTransaksiBiayaLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tambahTransaksiBiayaLabel">Tambah Transaksi Biaya</h5>
			</div>
			<form action="<?= base_url('transaksibiaya/insert'); ?>" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm">
							<div class="form-group row">
								<label for="id_kas" class="col-sm-3 col-form-label">Akun Kas/Bank</label>
								<div class="col-sm-9">
									<select name="id_kas" id="id_kas" class="form-control bootstrap-select" title="-- Pilih Akun Kas/Bank--" data-width="100%" data-live-search="true" required="">
										<?php foreach($dtAkunKas as $row_) : ?>
											<option value="<?= $row_['id_sak']; ?>">(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="id_biaya" class="col-sm-3 col-form-label">Akun Biaya</label>
								<div class="col-sm-9">
									<select name="id_biaya" id="id_biaya" class="form-control bootstrap-select" title="-- Pilih Akun Biaya --" data-width="100%" data-live-search="true" required="">
										<?php foreach($dtAkunBiaya as $row_) : ?>
											<option value="<?= $row_['id_biaya']; ?>">(<?= $row_['kode']; ?>) <?= $row_['nm']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
								
							<div class="form-group row">
								<label for="nominal" class="col-sm-3 col-form-label">Nominal</label>
								<div class="col-sm-9">
									<input type="text" class="form-control nominal" id="nominal" name="nominal" placeholder="Nominal" required="">
								</div>
							</div>

							<div class="form-group row">
								<label for="tgl" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
								<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tgl" name="tgl" placeholder="dd-mm-yyyy" data-input required="">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
									</div>	
								</div>
							</div>

							<div class="form-group row">
					    		<label for="keterangan" class="col-sm-3 col-form-label">
					    			Upload Bukti (Opsional)
					    		</label>
					    		<div class="col-sm-9">
					    			<div class="custom-file">
									  <input type="file" class="custom-file-input col-sm-12" id="bukti" name="bukti">
									  <label class="custom-file-label" for="bukti">Choose file</label>
									</div>
					    		</div>
								<br>
								<div class="col-sm-3">
								</div>	
								<label for="info" class="col-sm-9 col-form-label">
									<small class="text-primary">*format : pdf/jpg/jpeg/png. maksimal: 5mb</small>
								</label>
							</div>

							<div class="form-group row">
								<label for="desk" class="col-sm-3 col-form-label">Deskripsi</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="deskripsi" name="desk" rows="3" cols="1" placeholder="Deskripsi" required=""></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">
						<span class="icon text-white-50">
							<i class="fas fa-fw fa-save"></i>
							</span>
						<span class="text">Simpan</span>
					</button>
					<button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
						<span class="icon text-white-50">
							<i class="fas fa-fw fa-window-close"></i>
						</span>
						<span class="text">Close</span>
					</button>
				</div>
			</form>
		</div>
  	</div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="editTransaksiBiaya" tabindex="-1" role="dialog" aria-labelledby="editTransaksiBiayaLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
      			<h5 class="modal-title" id="editTransaksiBiayaLabel">Edit Transaksi Biaya</h5>
    		</div>
    		<form action="<?= base_url('transaksibiaya/update'); ?>" method="post" enctype="multipart/form-data">
	    		<div class="modal-body">
	        		<div class="row">
						<div class="col-sm">

							<input type="hidden" class="form-control" id="id_trans_biaya_update" name="id_trans_biaya" required>
			      			<div class="form-group row">
			      				<label for="id_kas" class="col-sm-3 col-form-label">Akun Kas/Bank</label>
				    			<div class="col-sm-9">
							    	<select id="id_kas_update" name="id_kas" class="form-control bootstrap-select" title="-- Pilih Akun Kas/Bank--" data-width="100%" data-live-search="true" required="">
							    		<?php foreach($dtAkunKas as $row_) : ?>
											<option value="<?= $row_['id_sak']; ?>">(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
										<?php endforeach; ?>
							    	</select>
							    </div>
							</div>
			      			<div class="form-group row">
			      				<label for="id_biaya" class="col-sm-3 col-form-label">Akun Biaya</label>
				    			<div class="col-sm-9">
							    	<select id="id_biaya_update" name="id_biaya" class="form-control bootstrap-select" title="-- Pilih Akun Biaya --" data-width="100%" data-live-search="true" required="">
							    		<?php foreach($dtAkunBiaya as $row_) : ?>
											<option value="<?= $row_['id_biaya']; ?>">(<?= $row_['kode']; ?>) <?= $row_['nm']; ?></option>
										<?php endforeach; ?>
							    	</select>
							    </div>
							</div>
							<div class="form-group row">
								<label for="nominal" class="col-sm-3 col-form-label">Nominal</label>
			    				<div class="col-sm-9">
									<input type="text" class="form-control nominal" id="nilai_update" name="nominal" placeholder="Nominal Piutang" required="">
								</div>
							</div>

							<div class="form-group row">
								<label for="tgl" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
			    				<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tgl_update" name="tgl" data-input required="">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>
									</div>	
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3 col-form-label">File Bukti</label>
								<div class="col-sm-9">

									<!-- AREA JIKA FILE SUDAH ADA -->
									<div id="file-exists-area" style="display:none;">
										<div class="mb-2">
											<button type="button" class="btn btn-info btn-sm" id="btn-preview-file">
												<i class="fas fa-eye"></i> Preview
											</button>

											<button type="button" class="btn btn-danger btn-sm" id="btn-delete-file">
												<i class="fas fa-trash"></i> Hapus File
											</button>
										</div>
										<small class="text-success">File bukti sudah diupload</small>
									</div>

									<!-- AREA UPLOAD FILE BARU -->
									<div id="file-upload-area">
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="bukti" name="bukti">
											<label class="custom-file-label" for="bukti">Choose file</label>
										</div>
										<small class="text-primary">Format: pdf/jpg/jpeg/png. Maks: 5MB</small>
									</div>

									<input type="hidden" name="old_bukti" id="old_bukti">
								</div>
							</div>

			      			<div class="form-group row">
			      				<label for="desk" class="col-sm-3 col-form-label">Deskripsi</label>
			    				<div class="col-sm-9">
						    		<textarea class="form-control" id="keterangan_update" name="desk" rows="3" cols="1" placeholder="Deskripsi" required=""></textarea>
						    	</div>
							</div>
						</div>
					</div>
				</div>
		    	<div class="modal-footer">
		    		<button type="submit" class="btn btn-primary">
		    			<span class="icon text-white-50">
					  		<i class="fas fa-fw fa-save"></i>
							</span>
			    		<span class="text">Simpan</span>
			    	</button>
	        		<button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
		        		<span class="icon text-white-50">
					  		<i class="fas fa-fw fa-window-close"></i>
						</span>
						<span class="text">Close</span>
					</button>
		    	</div>
	    	</form>
    	</div>
  	</div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="hapusTransaksiBiaya" tabindex="-1" role="dialog" aria-labelledby="hapusTransaksiBiayaLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-md" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="hapusTransaksiBiayaLabel">Konfirmasi Hapus Data</h5>
      		</div>
      		<form action="<?= base_url('transaksibiaya/delete'); ?>" method="post">
      			<div class="modal-body">
	        		<div class="row">
						<div class="col-sm">
							<input type="hidden" class="form-control" id="id_transaksi_biaya_delete" name="id_transaksibiaya">
							<h5>Apakah ingin menghapus data?</h5>
						</div>
					</div>
				</div>
	    		<div class="modal-footer">
	    			<button type="submit" class="btn btn-danger">
	    				<span class="icon text-white-50">
				  			<i class="fas fa-fw fa-save"></i>
						</span>
		    			<span class="text">Ya</span>
		    		</button>
	        		<button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
		        		<span class="icon text-white-50">
				  			<i class="fas fa-fw fa-window-close"></i>
						</span>
						<span class="text">Tidak</span>
					</button>
	    		</div>
	    	</form>
    	</div>
  	</div>
</div>

<!-- Modal Laporan -->
<div class="modal fade" id="printBiaya" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="printLabel"></h5>
			</div>
			<form action="<?= base_url('transaksibiaya/print'); ?>" method="post" target="_BLANK">
				<div class="modal-body">				
					<input type="hidden" class="form-control" id="id_sak" name="id_sak" required>	
					<div class="form-group row">
						<label for="tglAwal" class="col-sm-5 col-form-label">Tanggal Awal</label>
						<div class="col-sm-7">
							<div class="datepicker-wrapper">
								<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
								<i class="fa fa-calendar datepicker-icon" data-toggle></i>
							</div>	
						</div>
					</div>
					<div class="form-group row">
						<label for="tglAkhir" class="col-sm-5 col-form-label">Tanggal Akhir</label>
						<div class="col-sm-7">
							<div class="datepicker-wrapper">
								<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input required>
								<i class="fa fa-calendar datepicker-icon" data-toggle></i>
							</div>
						</div>	
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" name="pdf"><i class="fas fa-fw fa-print"></i> PDF</button>
					<button type="submit" class="btn btn-success" name="excel"><i class="fas fa-fw fa-download"></i> EXCEL</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
    		
  			</form>
  		</div>
	</div>	
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();

    let id  = $(this).data('id');
    let tgl = $(this).data('tgl');

    if (!tgl) {
        Swal.fire('Error', 'Tanggal transaksi tidak ditemukan', 'error');
        return;
    }

    // 1️⃣ CEK PERIODE DULU
    $.ajax({
        url: APP.base_url + 'periode/cek_periode',
        type: 'POST',
        dataType: 'json',
        data: { tgl: tgl },
        success: function (res) {

            if (res.closed) {
                Swal.fire({
                    icon: 'error',
                    title: 'Periode Ditutup',
                    text: 'Transaksi ini berada di periode yang sudah ditutup dan tidak bisa dihapus'
                });
                return;
            }

            // 2️⃣ KONFIRMASI HAPUS
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data Transaksi Biaya yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // 3️⃣ REDIRECT DELETE
                    window.location.href = APP.base_url + 'transaksibiaya/delete/' + id;
                }
            });

        },
        error: function () {
            Swal.fire('Error', 'Gagal cek periode', 'error');
        }
    });
});
</script>

<script>
function formatRupiah(angka, prefix){
    let number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   	 = number_string.split(','),
        sisa     	 = split[0].length % 3,
        rupiah     	 = split[0].substr(0, sisa),
        ribuan     	 = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
}
</script>

<!-- Preview File from Index -->
<script>
let isActualSize = false;
let currentType = ""; // 'image' or 'pdf'

$(document).on('click', '.btn-bukti', function(e){
    e.preventDefault();

    let fileUrl = $(this).data('file');
    let ext = fileUrl.split('.').pop().toLowerCase();

    resetPreview();

    if (['jpg','jpeg','png'].includes(ext)) {
        currentType = 'image';
        $('#imgPreview').attr('src', fileUrl).show();
    } 
    else if (ext === 'pdf') {
        currentType = 'pdf';
        $('#pdfPreview').attr('src', fileUrl).show();
    }

    $('#modalBukti').modal('show');
});

$('#toggleSize').on('click', function(){
    if (currentType === 'image') {
        if (!isActualSize) {
            $('#imgPreview').css({
                'max-width': 'none',
                'width': 'auto'
            });
            $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
        } else {
            $('#imgPreview').css({
                'max-width': '100%',
                'width': '100%'
            });
            $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
        }
    }

    if (currentType === 'pdf') {
        if (!isActualSize) {
            $('#pdfPreview').css({
                'width': '1200px',
                'height': '1600px'
            });
            $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
        } else {
            $('#pdfPreview').css({
                'width': '100%',
                'height': '100%'
            });
            $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
        }
    }

    isActualSize = !isActualSize;
});

function resetPreview() {
    isActualSize = false;
    currentType = "";

    $('#imgPreview').hide().attr('src','').css({
        'max-width':'100%',
        'width':'100%'
    });

    $('#pdfPreview').hide().attr('src','').css({
        'width':'100%',
        'height':'100%'
    });

    $('#toggleSize').html('<i class="fas fa-search-plus"></i> Actual Size');
}

$('#modalBukti').on('hidden.bs.modal', function () {
    resetPreview();
});
</script>

<!-- Edit Transaksi -->
<script>
$(document).on('click', '.editTransaksiBiaya', function (e) {
    e.preventDefault(); // STOP PAGE RELOAD

    const id_trans_biaya = $(this).data('id_trans_biaya');
    const tgl           = $(this).data('tgl');
    const keterangan    = $(this).data('keterangan');
    const nilai         = $(this).data('nilai');
    const kas_id        = $(this).data('kas_id');
    const biaya_id      = $(this).data('biaya_id');
    const bukti         = $(this).data('bukti');
    const bukti_url     = $(this).data('bukti_url');

    $("#id_trans_biaya_update").val(id_trans_biaya);
    $("#tgl_update").val(tgl);
    $("#keterangan_update").val(keterangan);
    $("#nilai_update").val(formatRupiah(nilai.toString()));
    $("#id_kas_update").val(kas_id).trigger('change');
    $("#id_biaya_update").val(biaya_id).trigger('change');

    $("#old_bukti").val(bukti);

    if (bukti) {
        $("#file-exists-area").show();
        $("#file-upload-area").hide();
        $("#btn-preview-file").data("url", bukti_url);
        $("#btn-delete-file").data("file", bukti);
    } else {
        $("#file-exists-area").hide();
        $("#file-upload-area").show();
    }
});
</script>

<!-- Preview File from Edit -->
<script>
$(document).on('click', '#btn-preview-file', function () {

    let fileUrl = $(this).data('url');
    if (!fileUrl) return;

    let ext = fileUrl.split('.').pop().toLowerCase();
    let content = '';

    if (ext === 'pdf') {
        content = `<iframe src="${fileUrl}" width="100%" height="500px" style="border:none;"></iframe>`;
    } else {
        content = `<img src="${fileUrl}" class="img-fluid">`;
    }

    Swal.fire({
        title: 'Preview Bukti',
        html: content,
        width: 900,
        showCloseButton: true,
        showConfirmButton: false
    });
});
</script>

<!-- Delete File from Edit -->
<script>
$(document).on('click', '#btn-delete-file', function () {

    let fileName = $(this).data('file');

    Swal.fire({
        title: 'Hapus file bukti?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus'
    }).then((result) => {
        if (result.isConfirmed) {

            $.post("<?= base_url('transaksibiaya/delete_bukti') ?>", { file: fileName }, function () {

                $("#file-exists-area").hide();
                $("#file-upload-area").show();
                $("#old_bukti").val('');

                Swal.fire('Berhasil', 'File dihapus', 'success');
            });

        }
    });
});
</script>

<script type="text/javascript">

	$('#tambahTransaksiBiaya form').on('submit', function (e) {
	    e.preventDefault();

	    let form = this;
	    let tgl_text = document.getElementById('tgl').value;
		let tgl = convertTextToDate(tgl_text);

	    if (!tgl) {
	        Swal.fire('Error', 'Tanggal transaksi wajib diisi', 'error');
	        return;
	    }

	    $.ajax({
	        url: APP.base_url + 'periode/cek_periode',
	        type: 'POST',
	        dataType: 'json',
	        data: { tgl: tgl },
	        success: function (res) {

	            if (res.closed) {
	                Swal.fire({
	                    icon: 'error',
	                    title: 'Periode Ditutup',
	                    text: 'Tanggal transaksi berada di periode yang sudah ditutup'
	                });
	                return;
	            }

	            // jika lolos → submit
	            form.submit();
	        },
	        error: function () {
	            Swal.fire('Error', 'Gagal cek periode', 'error');
	        }
	    });
	});

	$('#editTransaksiBiaya form').on('submit', function (e) {
	    e.preventDefault();

	    let form = this;
	    let tgl  = $('#tgl_update').val();

	    if (!tgl) {
	        Swal.fire('Error', 'Tanggal transaksi wajib diisi', 'error');
	        return;
	    }

	    $.ajax({
	        url: APP.base_url + 'periode/cek_periode',
	        type: 'POST',
	        dataType: 'json',
	        data: { tgl: tgl },
	        success: function (res) {

	            if (res.closed) {
	                Swal.fire({
	                    icon: 'error',
	                    title: 'Periode Ditutup',
	                    text: 'Tanggal transaksi berada di periode yang sudah ditutup'
	                });
	                return;
	            }

	            // jika lolos → submit
	            form.submit();
	        },
	        error: function () {
	            Swal.fire('Error', 'Gagal cek periode', 'error');
	        }
	    });
	});

	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		function formatRupiah(angka) {
	        let number_string = angka.replace(/[^,\d]/g, '');
	        let split = number_string.split(',');
	        let sisa = split[0].length % 3;
	        let rupiah = split[0].substr(0, sisa);
	        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	        if (ribuan) {
	            let separator = sisa ? '.' : '';
	            rupiah += separator + ribuan.join('.');
	        }

	        return rupiah ? 'Rp. ' + rupiah : '';
	    }

	    $('.nominal').on('input', function () {
	        let value = $(this).val();
	        $(this).val(formatRupiah(value));
	    });


		$('.editTransaksiBiaya').on('click',function(){
			const id_trans_biaya = $(this).data('id_trans_biaya');
			const tgl = $(this).data('tgl');
			const keterangan = $(this).data('keterangan');
			const nilai = $(this).data('nilai');
			const kas_id = $(this).data('kas_id');
			const biaya_id = $(this).data('biaya_id');

			$("#id_trans_biaya_update").val(id_trans_biaya);
			$("#tgl_update").val(tgl);
			$("#keterangan_update").val(keterangan);
			$("#nilai_update").val(formatRupiah(nilai.toString()));
			$("#id_kas_update option[value='" + kas_id + "']").prop("selected", true).trigger('change');
			$("#id_biaya_update option[value='" + biaya_id + "']").prop("selected", true).trigger('change');
		});

		$('.hapusTransaksiBiaya').on('click',function(){
			const id_transaksibiaya = $(this).data('id_transaksibiaya');

			$("#id_transaksibiaya_delete").val(id_transaksibiaya);
		});

	});
</script>
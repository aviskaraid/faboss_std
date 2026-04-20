<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

	<?= $this->session->flashdata('message'); ?>
	
	<div class="row" id="tambah-data-transaksi">
	    <div class="col-lg">
	      <div class="card border-bottom-primary shadow mb-4">
	         <div class="card-header py-3">
	          <div class="row">
	            <div class="col-sm-9">
	              <h6 class="m-0 font-weight-bold text-primary mt-2"><?= $title;  ?></h6>
	            </div>
				<div class="col-sm-3 text-right">
					<a href="<?= base_url('journal'); ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-arrow-rotate-left"></i> Kembali</a>
				</div>
	          </div>
	        </div>
	        <div class="card-body">

	          <div class="col-lg">
	                <div class="row">
						<div class="col-sm">
							<div class="form-group row">
			                  <input type="hidden" class="form-control" id="id" name="id" value="<?= $dt_byID['id_jurnal']; ?>">
			                </div>
							<div class="form-group row">
								<label for="tglTransaksi" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
				    			<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tglTransaksi" name="tglTransaksi" value="<?= convertDbdateToDate($dt_byID['tgl']); ?>" data-input required readonly>
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>		
									</div>	
								</div>
							</div>
					      	<div class="form-group row">
					      		<label for="noTransaksi" class="col-sm-3 col-form-label">Nomor Transaksi</label>
				    			<div class="col-sm-9">
							    	<input type="text" class="form-control" id="noTransaksi" name="noTransaksi" value="<?= $dt_byID['no_trans']; ?>" readonly>
							    </div>
							</div>
							<div class="form-group row">
								<label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
				    			<div class="col-sm-9">
							    	<input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $dt_byID['keterangan']; ?>" autocomplete="off" readonly>
							    </div>
							</div>
							<div class="form-group row">
					    		<label for="keterangan" class="col-sm-3 col-form-label">
					    			Upload Bukti (Opsional) <br>
									<small class="text-primary">*format : pdf/jpg/jpeg/png. maksimal: 5mb</small>
					    		</label>
					    		
								<div class="col-sm-9">
									<label>File :</label><br>

									<?php if (!empty($dt_byID['bukti'])): ?>
										<div class="d-flex align-items-center">
											<button type="button"
													class="btn btn-info btn-sm btn-preview-bukti mr-2"
													data-file="<?= base_url('assets/file/bukti/' . $dt_byID['bukti']); ?>">
												<i class="fas fa-eye"></i> Lihat File
											</button>

											<span class="text-muted">
												<?= htmlspecialchars($dt_byID['bukti']); ?>
											</span>
										</div>
									<?php else: ?>
										<span class="text-muted font-italic">Tidak ada file bukti</span>
									<?php endif; ?>
								</div>


							</div>
						</div>
					</div>
					<div class="row">
						<div class="table-responsive">
							<table class="table table-hover table-sm table-bordered">
							  <thead>
							    <tr>
							      <th scope="col">Nomor Akun</th>
							      <th scope="col">Nama Akun</th>
							      <th scope="col">Nominal</th>
							      <th scope="col">Perkiraan</th>
							    </tr>
							  </thead>
							  <tbody id="insert-form">
							  	<?php 
							  	$total_debit = 0;
							  	$total_kredit = 0;
							  	foreach ($dt_detail as $row) : ?>	
							  	<tr id="clone">
							  		<td>
										<select type="text" class="form-control bootstrap-select" id="idAkun" name="idAkun[]" title="Nomor Akun" data-container="body" data-live-search="true" required disabled="">
											<?php foreach ($akun as $ak) : ?>
												<?php $selected = in_array($row['nama'], $ak) ? " selected " : null;?>
												<option value="<?= $ak['id_akun']; ?>" <?=$selected?>><?= $ak['noakun']; ?></option>
											<?php endforeach; ?>
							  			</select>
							  		</td>
							  		<td>
							  			<select type="text" class="form-control bootstrap-select" id="namaAkunJurnal" name="namaAkun[]" data-container="body" title="Pilih Nama Akun" data-live-search="true" required disabled="">
							  				<?php foreach ($akun as $ak) : ?>
							  					<?php $selected = in_array($row['nama'], $ak) ? " selected " : null;?>
												<option value="<?= $ak['nama']; ?>" <?=$selected?> ><?= $ak['nama']; ?></option>
											<?php endforeach; ?>
							  			</select>
							  		</td>
							  		<td>
							  			<input type="text" class="form-control nominalJurnal" id="nilai_debit" name="nilai_debit[]" value="<?= ($row['nilai_debit'] != "") ? 'Rp. '.number_format($row['nilai_debit'],0,',','.') : 'Rp. 0'; ?>" placeholder="Nominal" autocomplete="off" readonly>
							  		</td>
							  		<td>
							  			<input type="text" class="form-control nominalJurnal" id="nilai_kredit" name="nilai_kredit[]" value="<?= ($row['nilai_kredit'] != "") ? 'Rp. '.number_format($row['nilai_kredit'],0,',','.') : 'Rp. 0'; ?>" placeholder="Nominal" autocomplete="off" readonly>
							  		</td>
							  	</tr>
							  	<?php 

							  	$total_debit += ($row['nilai_debit'] != "") ? $row['nilai_debit'] : 0;
							  	$total_kredit += ($row['nilai_kredit'] != "") ? $row['nilai_kredit'] : 0;
							  	
								  endforeach; ?>
							  </tbody>
							  <tfoot>
									<tr>
										<td colspan="2" class="text-right">Total</td>
										<td><span id="total_debit"><?= 'Rp. '.number_format($total_debit,0,',','.'); ?></span></td>
										<td><span id="total_kredit"><?= 'Rp. '.number_format($total_kredit,0,',','.'); ?></span></td>
									</tr>
							  </tfoot>
							</table>
						</div>
					</div>
	            </div>
	          </div>

	        </div>
	     </div>
    </div>

  </div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal Preview Bukti -->


<div class="modal fade" id="modalPreviewBukti" tabindex="-1" role="dialog">
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

<script>
$(document).ready(function () {

    let fitMode = true;
    let currentType = null; // 'img' or 'pdf'

    // When preview button is clicked
    $(document).on('click', '.btn-preview-bukti', function () {

        let fileUrl = $(this).data('file');
        let ext = fileUrl.split('.').pop().toLowerCase();

        let img  = $('#imgPreview');
        let pdf  = $('#pdfPreview');

        // Reset both
        img.hide().attr('src', '');
        pdf.hide().attr('src', '');

        fitMode = true;
        $('#toggleSize').html('<i class="fas fa-search-plus"></i> Actual Size');

        // IMAGE FILE
        if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') {
            currentType = 'img';
            img.attr('src', fileUrl).css({
                'max-width': '100%',
                'height': 'auto'
            }).show();
        }
        // PDF FILE
        else if (ext === 'pdf') {
            currentType = 'pdf';
            pdf.attr('src', fileUrl).css({
                width: '100%',
                height: '100%'
            }).show();
        }
        else {
            alert('Format file tidak didukung untuk preview');
            return;
        }

        $('#modalPreviewBukti').modal('show');
    });

    // Toggle Fit / Actual size
    $('#toggleSize').on('click', function () {

        if (currentType === 'img') {
            let img = $('#imgPreview');

            if (fitMode) {
                img.css({
                    'max-width': 'none',
                    'width': 'auto'
                });
                $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
                fitMode = false;
            } else {
                img.css({
                    'max-width': '100%',
                    'width': '100%'
                });
                $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
                fitMode = true;
            }
        }

        if (currentType === 'pdf') {
            let pdf = $('#pdfPreview');

            if (fitMode) {
                pdf.css({
                    width: 'auto',
                    height: 'auto'
                });
                $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
                fitMode = false;
            } else {
                pdf.css({
                    width: '100%',
                    height: '100%'
                });
                $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
                fitMode = true;
            }
        }

    });

    // Clean when modal closes
    $('#modalPreviewBukti').on('hidden.bs.modal', function () {
        $('#imgPreview').attr('src', '').hide();
        $('#pdfPreview').attr('src', '').hide();
        fitMode = true;
        currentType = null;
    });

});
</script>


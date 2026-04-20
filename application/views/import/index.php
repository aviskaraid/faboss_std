<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addImport">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Import</span>
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
		<div class="col-sm-12">
	  		<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	              <h6 class="m-0 font-weight-bold text-primary">Tabel <?= $title;  ?></h6>
	            </div>
				<div class="card-body">
					<div class="table-responsive">
				  		<table class="table table-dataAkun table-striped table-bordered table-sm" id="tabel-data">
						  <thead class="text-center">
						  	<tr>
						      <th scope="col">#</th>
						      <th scope="col">Tanggal</th>
						      <th scope="col">Keterangan</th>
						      <?php if (is_user()) : ?>
							      <th scope="col">Action</th>
							  <?php endif; ?>
						  	</tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
				  			<?php foreach ($dt_import as $row) : ?>
						    <tr>
						      	<th width="10px" scope="row"><?= $i++; ?></th>
						      	<td><?= date('d F Y H:i:s', $row['date_created']); ?></td>
						      	<td><?= $row['ket']; ?></td>
						      <?php if (is_user()) : ?>
							      <td class="text-center" width="15%">
							      		<a href="" class="badge badge-danger deleteImport" data-toggle="modal" data-target="#deleteImport"
							      			data-id_import="<?= $row['id_import']; ?>">
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


<!-- Modal -->
<div class="modal fade" id="addImport" tabindex="-1" role="dialog" aria-labelledby="addImportLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="addImportLabel">Tambah Import Baru</h5>
      	</div>
      	<?= form_open_multipart('import/import_excel'); ?>
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<div class="form-group row">
						<label for="import" class="col-sm-3 col-form-label">Upload file</label>
		    			<div class="col-sm-9">
		    				<div class="custom-file">
							  <input type="file" class="custom-file-input" id="import" name="import">
							  <label class="custom-file-label" for="import">Choose file</label>
							</div>
							<small class="text-primary">Catatan : format file harus excel</small>
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="desk" class="col-sm-3 col-form-label">Deskripsi</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="desk" name="desk" placeholder="Deskripsi" required="">
					    </div>
					</div>
					<hr>
			      	<div class="form-group row">
			      		<label for="desk" class="col-sm-3 col-form-label">Panduan & File Import</label>
		    			<div class="col-sm-9">
					    	<a href="<?= base_url('assets/file/format/cara_melakukan_import_transaksi.xlsx'); ?>">Download Panduan Import Transaksi</a><br>
					    	<a href="<?= base_url('assets/file/format/pengolahan_transaksi.xlsx'); ?>">Download Pengolahan Transaksi</a><br>
					    	<a href="<?= base_url('assets/file/format/dt_transaksi.xls'); ?>">Download Format File Import</a><br>
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
		    	<span class="text">Impor</span>
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



<!-- Modal -->
<div class="modal fade" id="deleteImport" tabindex="-1" role="dialog" aria-labelledby="deleteImportLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="deleteImportLabel">Konfirmasi Hapus Data</h5>
      	</div>
      	<form action="<?= base_url('import/delete_import'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_import_delete" name="id_import">
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

<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		$('.deleteImport').on('click',function(){
			const id_import = $(this).data('id_import');

			$("#id_import_delete").val(id_import);
		});

	});
</script>
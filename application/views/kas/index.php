<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addKas">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Data Kas</span>
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
						      <th scope="col">ID Akun</th>
						      <th scope="col">Nama Kas</th>
						      <th scope="col">Deskripsi</th>
						      <th scope="col">Saldo Awal</th>
						      <th scope="col">Saldo Akhir</th>
						      <?php if (is_user()) : ?>
							      <th scope="col">Action</th>
							  <?php endif; ?>
						  	</tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
						  	<?php $tot_saldo_awal = 0; ?>
						  	<?php $tot_saldo_akhir = 0; ?>
				  			<?php foreach ($dt_kas as $row) : ?>
						    <tr>
						      	<th width="10px" scope="row"><?= $i++; ?></th>
						      	<td width="10%"><?= $row['noakun']; ?></td>
						      	<td width="20%"><?= $row['nm']; ?></td>
						      	<td ><?= $row['desk']; ?></td>
						      	<td width="20%" class="text-right"><?= number_format($row['saldo_awal']); ?></td>
						      	<td width="20%" class="text-right"><?= number_format($row['saldo_akhir']); ?></td>
						      <?php if (is_user()) : ?>
							      <td class="text-center" width="15%">
							      		<a href="" class="badge badge-primary update-kas" data-toggle="modal" data-target="#ubahKas"
							      			data-id_sak="<?= $row['id_sak']; ?>"
							      			data-nm="<?= $row['nm']; ?>"
							      			data-desk="<?= $row['desk']; ?>"
							      			data-id_akun="<?= $row['id_akun']; ?>"
							      		>
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-edit"></i>
											</span>
											<span class="text">Edit</span>
										</a>
							      		<a href="" class="badge badge-danger delete-kas" data-toggle="modal" data-target="#deleteKas"
							      			data-id_sak="<?= $row['id_sak']; ?>">
							      			<span class="icon text-white-50">
													  	<i class="fas fa-fw fa-trash"></i>
													</span>
													<span class="text">Delete</span>
							      		</a>
							      </td>
							  <?php endif; ?>
						    </tr>
						    <?php 
						    	$tot_saldo_awal += $row['saldo_awal'];
						    	$tot_saldo_akhir += $row['saldo_akhir'];
							endforeach; ?>
						  </tbody>
						  <tfoot>
						  	<tr class="font-weight-bold">
						  		<td colspan="4" class="text-right">Total Saldo Awal</td>
						  		<td class="text-right"><?= number_format($tot_saldo_awal); ?></td>
						  		<td class="text-right"><?= number_format($tot_saldo_akhir); ?></td>
						  		<?php if (is_user()) : ?>
						  		<td></td>
						  		<?php endif; ?>
						  	</tr>
						  </tfoot>
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
<div class="modal fade" id="addKas" tabindex="-1" role="dialog" aria-labelledby="addKasLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="addKasLabel">Tambah Data Kas</h5>
      	</div>
      	<form action="<?= base_url('kas/insert'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
			      	<div class="form-group row">
			      		<label for="id_akun" class="col-sm-3 col-form-label">Akun Kas</label>
		    			<div class="col-sm-9">
					    	<select name="id_akun" id="id_akun" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
					    	</select>
					    </div>
					</div>
					<div class="form-group row">
						<label for="nm_kas" class="col-sm-3 col-form-label">Nama Kas</label>
		    			<div class="col-sm-9">
							<input type="text" class="form-control" id="nm_kas" name="nm_kas" placeholder="Nama Kas" required="">
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="desk" class="col-sm-3 col-form-label">Deskripsi</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="desk" name="desk" placeholder="Deskripsi Kas" required="">
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

<!-- Modal -->
<div class="modal fade" id="ubahKas" tabindex="-1" role="dialog" aria-labelledby="ubahKasLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="ubahKasLabel">Ubah Data Kas</h5>
      	</div>
      	<form action="<?= base_url('kas/update'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
			      	<div class="form-group row">
			      		<label for="id_akun" class="col-sm-3 col-form-label">Akun Kas</label>
		    			<div class="col-sm-9">
					    	<select name="id_akun" id="id_akun_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
					    	</select>
					    </div>
					</div>
					<div class="form-group row">
						<label for="nm_kas" class="col-sm-3 col-form-label">Nama Kas</label>
		    			<div class="col-sm-9">
							<input type="hidden" class="form-control" id="id_kas_update" name="id_sak">
							<input type="text" class="form-control" id="nm_kas_update" name="nm" placeholder="Nama Kas" required="">
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="desk" class="col-sm-3 col-form-label">Deskripsi</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="desk_update" name="desk" placeholder="Deskripsi Kas" required="">
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


<!-- Modal -->
<div class="modal fade" id="deleteKas" tabindex="-1" role="dialog" aria-labelledby="ubahKasLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="ubahKasLabel">Konfirmasi Hapus Data</h5>
      	</div>
      	<form action="<?= base_url('kas/delete'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_kas_delete" name="id_sak">
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

		$('.update-kas').on('click',function(){
			const id_sak = $(this).data('id_sak');
			const nm = $(this).data('nm');
			const desk = $(this).data('desk');
			const id_akun = $(this).data('id_akun');

			$("#id_kas_update").val(id_sak);
			$("#nm_kas_update").val(nm);
			$("#desk_update").val(desk);
			$("#id_akun_update option[value='" + id_akun + "']").prop("selected", true).trigger('change');
		});

		$('.delete-kas').on('click',function(){
			const id_sak = $(this).data('id_sak');

			$("#id_kas_delete").val(id_sak);
		});

	});
</script>
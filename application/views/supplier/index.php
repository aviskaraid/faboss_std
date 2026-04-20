<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addSupplier">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Data Supplier</span>
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
									<th scope="col">Nama Supplier</th>
									<th scope="col">Alamat</th>
									<th scope="col">Hutang Belum Lunas</th>						      
									<?php if (is_user()) : ?>
										<th scope="col">Action</th>
									<?php endif; ?>
								</tr>
						  	</thead>
						  	<tbody>
								<?php $i = 1; ?>
								<?php $tot_hutang = 0; ?>
								<?php foreach ($dt_supplier as $row) : ?>
								<tr>
									<th width="10px" scope="row"><?= $i++; ?></th>
									<td width="10%"><?= $row['noakun']; ?></td>
									<td width="20%"><?= $row['nama_supplier']; ?></td>
									<td ><?= $row['alamat_supplier']; ?></td>
									<td width="20%" class="text-right"><?= number_format($row['saldo_hutang']); ?></td>
						      		<?php if (is_user()) : ?>
										<td class="text-center" width="15%">
											<a href="" class="badge badge-primary update-supplier" data-toggle="modal" data-target="#ubahSupplier"
												data-id_supplier="<?= $row['id_supplier']; ?>"
												data-nama_supplier="<?= $row['nama_supplier']; ?>"
												data-alamat_supplier="<?= $row['alamat_supplier']; ?>"
												data-id_akun="<?= $row['id_akun']; ?>"
											>
												<span class="icon text-white-50">
													<i class="fas fa-fw fa-edit"></i>
												</span>
												<span class="text">Edit</span>
											</a>
											<a href="" class="badge badge-danger delete-supplier" data-toggle="modal" data-target="#deleteSupplier"
												data-id_supplier="<?= $row['id_supplier']; ?>">
												<span class="icon text-white-50">
													<i class="fas fa-fw fa-trash"></i>
												</span>
												<span class="text">Delete</span>
											</a>
										</td>
							  		<?php endif; ?>
						    	</tr>
						    	<?php 
									$tot_hutang += $row['saldo_hutang'];
								
								endforeach; ?>
						  </tbody>
						  <tfoot>
						  	<tr class="font-weight-bold">
						  		<td colspan="4" class="text-right">Total Saldo Hutang</td>
								
						  		<td class="text-right"><?= number_format($tot_hutang); ?></td>
						  	
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


<!-- Modal Tambah Supplier -->
<div class="modal fade" id="addSupplier" tabindex="-1" role="dialog" aria-labelledby="addSupplierLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="addSupplierLabel">Tambah Data Supplier</h5>
      	</div>
      	<form action="<?= base_url('supplier/insert'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
			      	<div class="form-group row">
			      		<label for="id_akun" class="col-sm-3 col-form-label">Akun Supplier</label>
		    			<div class="col-sm-9">
					    	<select name="id_akun" id="id_akun" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
					    	</select>
					    </div>
					</div>
					<div class="form-group row">
						<label for="nama_supplier" class="col-sm-3 col-form-label">Nama Supplier</label>
		    			<div class="col-sm-9">
							<input type="text" class="form-control" id="nama_supplier" name="nama_supplier" placeholder="Nama Supplier" required="">
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="alamat_supplier" class="col-sm-3 col-form-label">Alamat Supplier</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="alamat_supplier" name="alamat_supplier" placeholder="Alamat Supplier" required="">
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

<!-- Modal Edit Supplier -->
<div class="modal fade" id="ubahSupplier" tabindex="-1" role="dialog" aria-labelledby="ubahSupplierLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="ubahSupplierLabel">Ubah Data Supplier</h5>
      	</div>
      	<form action="<?= base_url('supplier/update'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
			      	<div class="form-group row">
			      		<label for="id_akun" class="col-sm-3 col-form-label">Akun Supplier</label>
		    			<div class="col-sm-9">
					    	<select name="id_akun" id="id_akun_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
					    	</select>
					    </div>
					</div>
					<div class="form-group row">
						<label for="nama_supplier" class="col-sm-3 col-form-label">Nama Supplier</label>
		    			<div class="col-sm-9">
							<input type="hidden" class="form-control" id="id_supplier_update" name="id_supplier">
							<input type="text" class="form-control" id="nama_supplier_update" name="nama_supplier" placeholder="Nama Supplier" required="">
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="alamat_supplier" class="col-sm-3 col-form-label">Alamat Supplier</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="alamat_supplier_update" name="alamat_supplier" placeholder="Alamat Supplier" required="">
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


<!-- Modal Delete Supplier -->
<div class="modal fade" id="deleteSupplier" tabindex="-1" role="dialog" aria-labelledby="deleteSupplierLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="deleteSupplierLabel">Konfirmasi Hapus Data</h5>
      	</div>
      	<form action="<?= base_url('supplier/delete'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_supplier_delete" name="id_supplier">
			      	<div class="alert alert-danger" role="alert">
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
			const id_supplier = $(this).data('id_supplier');
			const nama_supplier = $(this).data('nama_supplier');
			const alamat_supplier = $(this).data('alamat_supplier');
			const id_akun = $(this).data('id_akun');

			$("#id_supplier_update").val(id_supplier);
			$("#nama_supplier_update").val(nama_supplier);
			$("#alamat_supplier_update").val(alamat_supplier);
			$("#id_akun_update option[value='" + id_akun + "']").prop("selected", true).trigger('change');
		});

		$('.delete-kas').on('click',function(){
			const id_supplier = $(this).data('id_supplier');

			$("#id_supplier_delete").val(id_supplier);
		});

	});
</script>
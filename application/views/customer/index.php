<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addCustomer">
 		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Data Customer</span>
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
									<th scope="col">Kode</th>
									<th scope="col">Nama Customer</th>
									<th scope="col">Alamat</th>
									<th scope="col">Piutang Belum Lunas</th>						      
									<?php if (is_user()) : ?>
										<th scope="col">Action</th>
									<?php endif; ?>
								</tr>
						  	</thead>
						  	<tbody>
								<?php $i = 1; ?>
								<?php $tot_piutang = 0; ?>
								<?php foreach ($dt_customer as $row) : ?>
								<tr>
									<th width="10px" scope="row"><?= $i++; ?></th>
									<td width="10%"><?= $row['noakun']; ?></td>
									<td width="10%"><?= $row['kode']; ?></td>
									<td width="20%"><?= $row['nama_customer']; ?></td>
									<td ><?= $row['alamat_customer']; ?></td>
									<td width="20%" class="text-right"><?= number_format($row['saldo_piutang']); ?></td>
						      		<?php if (is_user()) : ?>
										<td class="text-center" width="15%">
											<a href="" class="badge badge-primary update-customer" data-toggle="modal" data-target="#ubahCustomer"
												data-id_customer="<?= $row['id_customer']; ?>"
												data-kode="<?= $row['kode']; ?>"
												data-nama_customer="<?= $row['nama_customer']; ?>"
												data-alamat_customer="<?= $row['alamat_customer']; ?>"
												data-id_akun="<?= $row['id_akun']; ?>"
											>
												<span class="icon text-white-50">
													<i class="fas fa-fw fa-edit"></i>
												</span>
												<span class="text">Edit</span>
											</a>
											<a href="" class="badge badge-danger delete-customer" data-toggle="modal" data-target="#deleteCustomer"
												data-id_customer="<?= $row['id_customer']; ?>">
												<span class="icon text-white-50">
													<i class="fas fa-fw fa-trash"></i>
												</span>
												<span class="text">Delete</span>
											</a>
										</td>
							  		<?php endif; ?>
						    	</tr>
						    	<?php 
									$tot_piutang += $row['saldo_piutang'];
								
								endforeach; ?>
						  </tbody>
						  <tfoot>
						  	<tr class="font-weight-bold">
						  		<td colspan="4" class="text-right">Total Saldo Piutang</td>
								
						  		<td class="text-right"><?= number_format($tot_piutang); ?></td>
						  	
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


<!-- Modal Tambah Customer -->
<div class="modal fade" id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="addCustomerLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="addCustomerLabel">Tambah Data Customer</h5>
      	</div>
      	<form action="<?= base_url('customer/insert'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
			      	<div class="form-group row">
			      		<label for="id_akun" class="col-sm-3 col-form-label">Akun Customer</label>
		    			<div class="col-sm-9">
					    	<select name="id_akun" id="id_akun" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
					    	</select>
					    </div>
					</div>
					<div class="form-group row">
						<label for="kode" class="col-sm-3 col-form-label">Kode</label>
		    			<div class="col-sm-9">
							<input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Customer" required="">
						</div>
					</div>
					<div class="form-group row">
						<label for="nama_customer" class="col-sm-3 col-form-label">Nama Customer</label>
		    			<div class="col-sm-9">
							<input type="text" class="form-control" id="nama_customer" name="nama_customer" placeholder="Nama Customer" required="">
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="alamat_customer" class="col-sm-3 col-form-label">Alamat Customer</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="alamat_customer" name="alamat_customer" placeholder="Alamat Customer" required="">
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

<!-- Modal Edit Customer -->
<div class="modal fade" id="ubahCustomer" tabindex="-1" role="dialog" aria-labelledby="ubahCustomerLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="ubahCustomerLabel">Ubah Data Customer</h5>
      	</div>
      	<form action="<?= base_url('customer/update'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
			      	<div class="form-group row">
			      		<label for="id_akun" class="col-sm-3 col-form-label">Akun Customer</label>
		    			<div class="col-sm-9">
					    	<select name="id_akun" id="id_akun_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
					    	</select>
					    </div>
					</div>
					<div class="form-group row">
						<label for="kode" class="col-sm-3 col-form-label">Kode</label>
		    			<div class="col-sm-9">
							<input type="text" class="form-control" id="kode_update" name="kode" placeholder="Kode Customer" required="">
						</div>
					</div>
					<div class="form-group row">
						<label for="nama_customer" class="col-sm-3 col-form-label">Nama Customer</label>
		    			<div class="col-sm-9">
							<input type="hidden" class="form-control" id="id_customer_update" name="id_customer">
							<input type="text" class="form-control" id="nama_customer_update" name="nama_customer" placeholder="Nama Customer" required="">
						</div>
					</div>
			      	<div class="form-group row">
			      		<label for="alamat_customer" class="col-sm-3 col-form-label">Alamat Customer</label>
		    			<div class="col-sm-9">
					    	<input type="text" class="form-control" id="alamat_customer_update" name="alamat_customer" placeholder="Alamat Customer" required="">
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


<!-- Modal Delete Customer -->
<div class="modal fade" id="deleteCustomer" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="deleteCustomerLabel">Konfirmasi Hapus Data</h5>
      	</div>
      	<form action="<?= base_url('customer/delete'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_customer_delete" name="id_customer">
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

		$('.update-customer').on('click',function(){
			const id_customer = $(this).data('id_customer');
			const kode = $(this).data('kode');
			const nama_customer = $(this).data('nama_customer');
			const alamat_customer = $(this).data('alamat_customer');
			const id_akun = $(this).data('id_akun');

			$("#id_customer_update").val(id_customer);
			$("#kode_update").val(kode);
			$("#nama_customer_update").val(nama_customer);
			$("#alamat_customer_update").val(alamat_customer);
			$("#id_akun_update option[value='" + id_akun + "']").prop("selected", true).trigger('change');
		});

		$('.delete-customer').on('click',function(){
			const id_customer = $(this).data('id_customer');

			$("#id_customer_delete").val(id_customer);
		});

	});
</script>
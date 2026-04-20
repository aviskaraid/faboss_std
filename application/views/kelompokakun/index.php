<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#tambahKelompokakun">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Kelompok</span>
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
						      <th scope="col">Kode Kel. Akun</th>						      
						      <th scope="col">Nama Kel. Akun</th>
							  <th scope="col">Tipe</th>
						      <?php if (is_user()) : ?>
							      <th scope="col">Action</th>
							  <?php endif; ?>
						  	</tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
				  			<?php foreach ($dtKelompokakun as $row) : ?>
						    <tr>
						      	<th scope="row"><?= $i++; ?></th>
						      	<td><?= $row['kel_akun']; ?></td>						      	
						      	<td><?= $row['nama_kel_akun']; ?></td>
								<td><?= $row['nama_tipe']; ?></td>
						      	<?php if (is_user()) : ?>
							     	 <td class="text-center" width="15%">
							      		<a href="" class="badge badge-primary update-kelompokakun" data-toggle="modal" data-target="#ubahKelompokakun"
							      			data-id_kelompok_akun="<?= $row['id_kelompok_akun']; ?>"
							      			data-kel_akun="<?= $row['kel_akun']; ?>"
							      			data-tipe="<?= $row['tipe']; ?>"
							      			data-nama_kel_akun="<?= $row['nama_kel_akun']; ?>"
							      		>
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-edit"></i>
											</span>
											<span class="text">Ubah</span>
										</a>
							      		<a href="" class="badge badge-danger delete-kelompokakun" data-toggle="modal" data-target="#deleteKelompokakun"
							      			data-id_kelompok_akun="<?= $row['id_kelompok_akun']; ?>">
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-trash"></i>
											</span>
											<span class="text">Hapus</span>
							      		</a>
							      	</td>
							  	<?php endif; ?>
						    </tr>
						    <?php endforeach; ?>
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


<!-- Modal Tambah -->
<div class="modal fade" id="tambahKelompokakun" tabindex="-1" role="dialog" aria-labelledby="tambahKelompokakunLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
    	<div class="modal-header">
      	<h5 class="modal-title" id="tambahKelompokakunLabel">Tambah Kelompok Akun</h5>
    	</div>
    	<form action="<?= base_url('kelompokakun/insert'); ?>" method="post">
    		<div class="modal-body">
        		<div class="row">
					<div class="col-sm">
						<div class="form-group row">
							<label for="kel_akun" class="col-sm-5 col-form-label">Kode Kelompok Akun</label>
			    			<div class="col-sm-7">
								<input type="text" class="form-control" id="kel_akun" name="kel_akun" placeholder="Contoh: 110" required="">
							</div>
						</div>
						<div class="form-group row">
							<label for="nama_kel_akun" class="col-sm-5 col-form-label">Nama Kelompok Akun</label>
			    			<div class="col-sm-7">
								<input type="text" class="form-control" id="nama_kel_akun" name="nama_kel_akun" placeholder="Contoh: Aktiva" required="">
							</div>
						</div>
		      			<div class="form-group row">
		      				<label for="tipe" class="col-sm-5 col-form-label">Tipe Kelompok Akun</label>
		    				<div class="col-sm-7">
					    		<select name="tipe" id="tipe" class="form-control bootstrap-select" title="-- Pilih Tipe Kel. Akun --" data-width="100%" data-live-search="true" required="">
					    			<option value="A">Aktiva</option>
									<option value="P">Pasiva</option>
									<option value="L">Pendapatan</option>
									<option value="B">Biaya</option>
					    		</select>
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

<!-- Modal Ubah -->
<div class="modal fade" id="ubahKelompokakun" tabindex="-1" role="dialog" aria-labelledby="ubahKelompokakunLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-md" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
      			<h5 class="modal-title" id="ubahKelompokakunLabel">Ubah Kelompok Akun</h5>
    		</div>
    		<form action="<?= base_url('kelompokakun/update'); ?>" method="post">
      		<div class="modal-body">
	        	<div class="row">
					<div class="col-sm">
						<input type="hidden" class="form-control" id="id_kelompok_akun_u" name="id_kelompok_akun">
							<div class="form-group row">
								<label for="kel_akun" class="col-sm-5 col-form-label">Kode Kelompok Akun</label>
				    			<div class="col-sm-7">
									<input type="text" class="form-control" id="kel_akun_u" name="kel_akun" placeholder="Contoh: 110" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="nama_kel_akun" class="col-sm-5 col-form-label">Nama kelompok Akun</label>
				    			<div class="col-sm-7">
									<input type="text" class="form-control" id="nama_kel_akun_u" name="nama_kel_akun" placeholder="Contoh: Aktiva" required="">
								</div>
							</div>
			      			<div class="form-group row">
			      				<label for="tipe" class="col-sm-5 col-form-label">Tipe Kelompok Akun</label>
			    				<div class="col-sm-7">
						    		<select name="tipe" id="tipe_u" class="form-control bootstrap-select" title="-- Pilih Tipe Kel. Akun --" data-width="100%" data-live-search="true" required="">
						    			<option value="A">Aktiva</option>
										<option value="P">Pasiva</option>
										<option value="L">Pendapatan</option>
										<option value="B">Biaya</option>
						    		</select>
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


<!-- Modal Hapus -->
<div class="modal fade" id="deleteKelompokakun" tabindex="-1" role="dialog" aria-labelledby="deleteKelompokakunLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="deleteKelompokakunLabel">Konfirmasi Hapus Data</h5>
      	</div>
      	<form action="<?= base_url('kelompokakun/delete'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_kelompok_akun_d" name="id_kelompok_akun">
					<h5>Apakah ingin menghapus data?</h5>
				</div>
			</div>
		</div>
	    <div class="modal-footer">
	        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
		        <span class="icon text-white-50">
						  	<i class="fas fa-fw fa-window-close"></i>
						</span>
						<span class="text">Tidak</span>
					</button>
		    	<button type="submit" class="btn btn-danger">
		    		<span class="icon text-white-50">
					  	<i class="fas fa-fw fa-save"></i>
						</span>
			    	<span class="text">Ya</span>
			    </button>
	    </div>
	    </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		// ===============================
		// SWEET ALERT WARNING SAAT PAGE LOAD
		// ===============================
		if (!sessionStorage.getItem('warningKelompokAkunShown')) {
		Swal.fire({
			icon: 'warning',
			title: 'Perhatian!',
			html: `
				<div style="font-size:14px; line-height:1.6;">
					Disarankan untuk <b>tidak mengubah atau menghapus</b> data 
					<b>Kelompok Akun</b> jika tidak diperlukan,<br><br>
					karena dapat mengganggu <b>struktur akun</b> dan 
					<b>laporan-laporan keuangan</b>.
				</div>
			`,
			confirmButtonText: 'Saya Mengerti',
			confirmButtonColor: '#3085d6',
			backdrop: true,
			allowOutsideClick: false
		}).then(() => {
			sessionStorage.setItem('warningKelompokAkunShown', 'true');
		});
}

		// ===============================
		// MODAL UBAH
		// ===============================
		$('.update-kelompokakun').on('click',function(){
			const id_kelompok_akun = $(this).data('id_kelompok_akun');
			const kel_akun = $(this).data('kel_akun');
			const nama_kel_akun = $(this).data('nama_kel_akun');
			const tipe = $(this).data('tipe');

			$("#id_kelompok_akun_u").val(id_kelompok_akun);
			$("#kel_akun_u").val(kel_akun);
			$("#nama_kel_akun_u").val(nama_kel_akun);
			$("#tipe_u option[value='" + tipe + "']").prop("selected", true).trigger('change');
		});

		// ===============================
		// MODAL HAPUS
		// ===============================		
		$('.delete-kelompokakun').on('click', function(){
			const id_kelompok_akun = $(this).data('id_kelompok_akun');
			$("#id_kelompok_akun_d").val(id_kelompok_akun);
		});


	});
</script>
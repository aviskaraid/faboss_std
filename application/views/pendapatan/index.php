<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#tambahPendapatan">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Pendapatan</span>
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
						      <th scope="col">Kode Pendapatan</th>
						      <th scope="col">Nama Pendapatan</th>
						      <th scope="col">Akun Keuangan Pendapatan</th>
						      <?php if (is_user()) : ?>
							      <th scope="col">Action</th>
							  <?php endif; ?>
						  	</tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
				  			<?php foreach ($dtPendapatan as $row) : ?>
						    <tr>
						      	<th scope="row"><?= $i++; ?></th>
						      	<td><?= $row['kode']; ?></td>
						      	<td><?= $row['nm']; ?></td>
						      	<td><?= "(".$row['noakun'].") ".$row['nama']; ?></td>
						      <?php if (is_user()) : ?>
							      <td class="text-center" width="15%">
							      		<a href="" class="badge badge-primary update-pendapatan" data-toggle="modal" data-target="#ubahPendapatan"
							      			data-id_pendapatan="<?= $row['id_pendapatan']; ?>"
							      			data-kode="<?= $row['kode']; ?>"
							      			data-nama="<?= $row['nama']; ?>"
							      			data-akun_id="<?= $row['akun_id']; ?>"
							      		>
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-edit"></i>
											</span>
											<span class="text">Ubah</span>
										</a>
							      		<a href="" class="badge badge-danger delete-pendapatan" data-toggle="modal" data-target="#deletePendapatan"
							      			data-id_pendapatan="<?= $row['id_pendapatan']; ?>">
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-trash"></i>
											</span>
											<span class="text">Hapus</span>
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
<div class="modal fade" id="tambahPendapatan" tabindex="-1" role="dialog" aria-labelledby="tambahPendapatanLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
    	<div class="modal-header">
      	<h5 class="modal-title" id="tambahPendapatanLabel">Tambah Pendapatan</h5>
    	</div>
    	<form action="<?= base_url('pendapatan/insert'); ?>" method="post">
    	<div class="modal-body">
        <div class="row">
					<div class="col-sm">
						<div class="form-group row">
							<label for="kode" class="col-sm-3 col-form-label">Kode Pendapatan</label>
			    			<div class="col-sm-9">
								<input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh : PEN_001" required="">
							</div>
						</div>
						<div class="form-group row">
							<label for="nama" class="col-sm-3 col-form-label">Nama Pendapatan</label>
			    			<div class="col-sm-9">
								<input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh : Pendapatan Bunga" required="">
							</div>
						</div>
		      	<div class="form-group row">
		      		<label for="akun_id" class="col-sm-3 col-form-label">Akun Pendapatan</label>
		    			<div class="col-sm-9">
					    	<select name="akun_id" id="akun_id" class="form-control bootstrap-select" title="-- Pilih Akun Pendapatan--" data-width="100%" data-live-search="true" required="">
					    		<?php foreach($dt_akun as $row_) : ?>
									<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
								<?php endforeach; ?>
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

<!-- Modal -->
<div class="modal fade" id="ubahPendapatan" tabindex="-1" role="dialog" aria-labelledby="ubahPendapatanLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
    	<div class="modal-header">
      	<h5 class="modal-title" id="ubahPendapatanLabel">Ubah Pendapatan</h5>
    	</div>
    	<form action="<?= base_url('pendapatan/update'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
						<div class="col-sm">

							<input type="hidden" class="form-control" id="id_pendapatan_update" name="id_pendapatan">
							<div class="form-group row">
								<label for="kode" class="col-sm-3 col-form-label">Kode Pendapatan</label>
				    			<div class="col-sm-9">
									<input type="text" class="form-control" id="kode_update" name="kode" placeholder="Contoh : PEN_001" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="nama" class="col-sm-3 col-form-label">Nama Pendapatan</label>
				    			<div class="col-sm-9">
									<input type="text" class="form-control" id="nama_update" name="nama" placeholder="Contoh : Pendapatan Bunga" required="">
								</div>
							</div>
			      	<div class="form-group row">
			      		<label for="akun_id" class="col-sm-3 col-form-label">Akun Pendapatan</label>
			    			<div class="col-sm-9">
						    	<select name="akun_id" id="akun_id_update" class="form-control bootstrap-select" title="-- Pilih Akun Pendapatan--" data-width="100%" data-live-search="true" required="">
						    		<?php foreach($dt_akun as $row_) : ?>
										<option value="<?= $row_['id_akun']; ?>"><?= $row_['nama']; ?></option>
									<?php endforeach; ?>
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


<!-- Modal -->
<div class="modal fade" id="deletePendapatan" tabindex="-1" role="dialog" aria-labelledby="deletePendapatanLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="deletePendapatanLabel">Konfirmasi Hapus Data</h5>
      	</div>
      	<form action="<?= base_url('pendapatan/delete'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_pendapatan_delete" name="id_pendapatan">
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

<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		$('.update-pendapatan').on('click',function(){
			const id_pendapatan = $(this).data('id_pendapatan');
			const kode = $(this).data('kode');
			const nama = $(this).data('nama');
			const akun_id = $(this).data('akun_id');

			$("#id_pendapatan_update").val(id_pendapatan);
			$("#kode_update").val(kode);
			$("#nama_update").val(nama);
			$("#akun_id_update option[value='" + akun_id + "']").prop("selected", true).trigger('change');
		});

		$('.delete-pendapatan').on('click',function(){
			const id_pendapatan = $(this).data('id_pendapatan');

			$("#id_pendapatan_delete").val(id_pendapatan);
		});

	});
</script>
<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		    <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#tambahBiaya">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Biaya</span>
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
						      <th scope="col">Kode Biaya</th>
						      <th scope="col">Nama Biaya</th>
						      <th scope="col">Akun Keuangan Biaya</th>
						      <?php if (is_user()) : ?>
							      <th scope="col">Action</th>
							  <?php endif; ?>
						  	</tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
				  			<?php foreach ($dtBiaya as $row) : ?>
						    <tr>
						      	<th scope="row"><?= $i++; ?></th>
						      	<td><?= $row['kode']; ?></td>
						      	<td><?= $row['nm']; ?></td>
						      	<td><?= "(".$row['noakun'].") ".$row['nama']; ?></td>
						      <?php if (is_user()) : ?>
							      <td class="text-center" width="15%">
							      		<a href="" class="badge badge-primary update-biaya" data-toggle="modal" data-target="#ubahBiaya"
							      			data-id_biaya="<?= $row['id_biaya']; ?>"
							      			data-kode="<?= $row['kode']; ?>"
							      			data-nama="<?= $row['nama']; ?>"
							      			data-akun_id="<?= $row['akun_id']; ?>"
							      		>
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-edit"></i>
											</span>
											<span class="text">Ubah</span>
										</a>
							      		<a href="" class="badge badge-danger delete-biaya" data-toggle="modal" data-target="#deleteBiaya"
							      			data-id_biaya="<?= $row['id_biaya']; ?>">
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


<!-- Modal Tambah -->
<div class="modal fade" id="tambahBiaya" tabindex="-1" role="dialog" aria-labelledby="tambahBiayaLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tambahBiayaLabel">Tambah Biaya</h5>
			</div>
			<form action="<?= base_url('biaya/insert'); ?>" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm">
							<div class="form-group row">
								<label for="kode" class="col-sm-3 col-form-label">Kode Biaya</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh : BYA_001" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="nama" class="col-sm-3 col-form-label">Nama Biaya</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh : Biaya Listrik" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="akun_id" class="col-sm-3 col-form-label">Akun Biaya</label>
								<div class="col-sm-9">
									<select name="akun_id" id="akun_id" class="form-control bootstrap-select" title="-- Pilih Akun Biaya--" data-width="100%" data-live-search="true" required="">
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

<!-- Modal Ubah -->
<div class="modal fade" id="ubahBiaya" tabindex="-1" role="dialog" aria-labelledby="ubahBiayaLabel" aria-hidden="true" >
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ubahBiayaLabel">Ubah Biaya</h5>
			</div>
			<form action="<?= base_url('biaya/update'); ?>" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm">
							<input type="hidden" class="form-control" id="id_biaya_update" name="id_biaya">
							<div class="form-group row">
								<label for="kode" class="col-sm-3 col-form-label">Kode Biaya</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="kode_update" name="kode" placeholder="Contoh : BYA_001" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="nama" class="col-sm-3 col-form-label">Nama Biaya</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nama_update" name="nama" placeholder="Contoh : Biaya Listrik" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="akun_id" class="col-sm-3 col-form-label">Akun Biaya</label>
								<div class="col-sm-9">
									<select name="akun_id" id="akun_id_update" class="form-control bootstrap-select" title="-- Pilih Akun Biaya--" data-width="100%" data-live-search="true" required="">
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

<!-- Modal Hapus -->
<div class="modal fade" id="deleteBiaya" tabindex="-1" role="dialog" aria-labelledby="deleteKasLabel" aria-hidden="true" >
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deleteKasLabel">Konfirmasi Hapus Data</h5>
			</div>
			<form action="<?= base_url('biaya/delete'); ?>" method="post">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm">
							<input type="hidden" class="form-control" id="id_biaya_delete" name="id_biaya">
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

		$('.update-biaya').on('click',function(){
			const id_biaya = $(this).data('id_biaya');
			const kode = $(this).data('kode');
			const nama = $(this).data('nama');
			const akun_id = $(this).data('akun_id');

			$("#id_biaya_update").val(id_biaya);
			$("#kode_update").val(kode);
			$("#nama_update").val(nama);
			$("#akun_id_update option[value='" + akun_id + "']").prop("selected", true).trigger('change');
		});

		$('.delete-biaya').on('click',function(){
			const id_biaya = $(this).data('id_biaya');

			$("#id_biaya_delete").val(id_biaya);
		});

	});
</script>
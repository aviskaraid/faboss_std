<!-- Begin Page Content -->
<div class="container-fluid">
  	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800"><?= $title;  ?><span style="font-size: 20px;"> (Metode Langsung)</span></h1>
	 </div>
  <!-- Page Heading -->
  

  	<div class="row">
	  	<div class="col-sm-12">
			<?= form_error('dataakun', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

			<?= $this->session->flashdata('message'); ?>
		</div>
  	</div>

	<div class="row mt-3 mb-4" >
		<div class="col-sm">
			<div class="card border-bottom-primary shadow-sm mb-4">
	            <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-3">
				  			<h6 class="m-0 font-weight-bold text-primary mb-3">Setting Laporan Arus Kas <span>(Metode Langsung)</span></h6>
				  		</div>
				  		<div class="col-sm-9 text-right">
				  			<a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambahKatagori">
				  				<i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
	        					<span class="text">Tambah Katagori</span>
				  			</a>
				  		</div>
				  	</div>
	             </div>
			  	<div class="card-body">
				  	<div class="row">
				  		<div class="col-sm">
				  			<div class="table-responsive">
					  			<table class="table table-striped table-sm" style="width:100%">
								  <tbody>
									<tr>
								      <th scope="row" colspan="3">Aktivitas Operasi</th>
								    </tr>
								    <!-- looping data aktivitas operasi -->
								  	<?php $i=1; ?>
								  	<?php $j=1; ?>
								  	<?php $k=1; ?>
								    <?php foreach ($activitas_operasi as $act_op) : ?>
									    <tr>
									    	<td width="5%" class="text-center"><?= $i++; ?></td>
									    	<td ><?= $act_op['nama_kategori']; ?>
									    		<a href="" class="update-kategori" 
									    			data-toggle="modal" 
									    			data-target="#editKatagori" 
									    			data-id_kategori="<?= $act_op['id_kategori']; ?>" 
									    			data-nama_kategori="<?= $act_op['nama_kategori']; ?>"
									    			style="text-decoration: none;">
									    				<i class="fas fa-fw fa-edit fa-sm text-secondary-50"></i>
									    		</a>
									    		<a href="<?= base_url(); ?>report/delete/<?= $act_op['id_kategori']; ?>"><i class="fas fa-fw fa-trash fa-sm text-secondary-50"></i></a>
									    	</td>
									    </tr>

									 <?php endforeach; ?>
								    <!-- tutup looping data aktivitas operasi -->
									<tr>
								      <th scope="row" colspan="3">Aktivitas Investasi</th>
								    </tr>
								    <!-- looping data aktivitas operasi -->
								    <?php foreach ($activitas_investasi as $act_inv) : ?>
									    <tr>
									    	<td width="5%" class="text-center"><?= $j++; ?></td>
									    	<td><?= $act_inv['nama_kategori']; ?>
									    		<a href="" class="update-kategori" 
									    			data-toggle="modal" 
									    			data-target="#editKatagori" 
									    			data-id_kategori="<?= $act_inv['id_kategori']; ?>" 
									    			data-nama_kategori="<?= $act_inv['nama_kategori']; ?>"
									    			style="text-decoration: none;">
									    				<i class="fas fa-fw fa-edit fa-sm text-secondary-50"></i>
									    		</a>
									    		<a href="<?= base_url(); ?>report/delete/<?= $act_inv['id_kategori']; ?>"><i class="fas fa-fw fa-trash fa-sm text-secondary-50"></i></a>
									    	</td>
									    </tr>
									    
									<?php endforeach; ?>
								    <!-- Tutup looping data aktivitas operasi -->
									<tr>
								      <th scope="row" colspan="3">Aktivitas Pendanaan</th>
								    </tr>
								    <!-- looping data aktivitas operasi -->
								   	<?php foreach ($activitas_pendanaan as $act_pend) : ?>
									    <tr>
									    	<td width="5%" class="text-center"><?= $k++; ?></td>
									    	<td><?= $act_pend['nama_kategori']; ?>
									    		<a href="" class="update-kategori" 
									    			data-toggle="modal" 
									    			data-target="#editKatagori" 
									    			data-id_kategori="<?= $act_pend['id_kategori']; ?>" 
									    			data-nama_kategori="<?= $act_pend['nama_kategori']; ?>"
									    			style="text-decoration: none;">
									    				<i class="fas fa-fw fa-edit fa-sm text-secondary-50"></i>
									    		</a>
									    		<a href="<?= base_url(); ?>report/delete/<?= $act_pend['id_kategori']; ?>"><i class="fas fa-fw fa-trash fa-sm text-secondary-50"></i></a>
									    	</td>
									    </tr>
									<?php endforeach; ?>
								    <!-- Tutup looping data aktivitas operasi -->
								  </tbody>
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

<!-- Modal -->
<div class="modal fade" id="tambahKatagori" tabindex="-1" role="dialog" aria-labelledby="tambahKatagoriLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="tambahKatagoriLabel">Menambahkan Katagori Baru</h5>
		      </div>
		      <form action="<?= base_url('report/setting'); ?>" method="post">
		      	<input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
		      	<div class="modal-body">
					<div class="form-group row">
						<label for="nama-kategori" class="col-sm-5 col-form-label">Nama Katagori</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="nama-kategori" name="nama-kategori" placeholder="Nama Katagori" required="">
						</div>
					</div>
			      	<div class="form-group row">
				      	<label for="tipe-aktivitas" class="col-sm-5 col-form-label">Tipe Aktivitas</label>
				      	<div class="col-sm-7">
							<select name="tipe-aktivitas" id="tipe-aktivitas" class="bootstrap-select" data-width="100%" title="-- Pilih Aktivitas --">
								<?php foreach($activitas as $ac) : ?>
									<option value="<?= $ac['id_kelompok_aktivitas']; ?>"><?= $ac['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
			      	<div class="form-group row">
				      	<label for="akun-sumber" class="col-sm-5 col-form-label">Akun Sumber</label>
				      	<div class="col-sm-7">
							<select class="bootstrap-select" name="akun-sumber[]" id="akun-sumber" title="-- Pilih Akun --" data-width="100%" data-live-search="true" multiple>
								<?php foreach($masterdata as $md) : ?>
									<option value="<?= $md['id_akun']; ?>"><?= $md['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
				      	<label for="akun-sumber" class="col-sm-5 col-form-label">Keterangan</label>
				      	<div class="col-sm-7">
							<select class="bootstrap-select" name="keterangan" id="akun-sumber" title="-- Pilih Ketarangan --" data-width="100%">
								<option value="1">Penambah Kas</option>
								<option value="0">Pengurang Kas</option>
							</select>
						</div>
					</div>
				</div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary">Simpan</button>
			    </div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="editKatagori" tabindex="-1" role="dialog" aria-labelledby="editKatagoriLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="editKatagoriLabel">Mengedit Katagori</h5>
		      </div>
		      <form action="<?= base_url('report/update_katagori'); ?>" method="post">
		      	<input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
		      	<div class="modal-body">
		      		<input type="text" class="form-control" id="id" name="id" required="" hidden="">
					<div class="form-group row">
						<label for="nama-kategori" class="col-sm-5 col-form-label">Nama Katagori</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="nama-kategori" name="nama-kategori" placeholder="Nama Katagori" required="">
						</div>
					</div>
			      	<div class="form-group row">
				      	<label for="tipe-aktivitas" class="col-sm-5 col-form-label">Tipe Aktivitas</label>
				      	<div class="col-sm-7">
							<select name="tipe-aktivitas" id="tipe-aktivitas" class="bootstrap-select tipe-aktivitas" data-width="100%" title="-- Pilih Aktivitas --">
								<?php foreach($activitas as $ac) : ?>
									<option value="<?= $ac['id_kelompok_aktivitas']; ?>"><?= $ac['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
			      	<div class="form-group row">
				      	<label for="akun-sumber" class="col-sm-5 col-form-label">Akun Sumber</label>
				      	<div class="col-sm-7">
							<select class="bootstrap-select strings" name="akun-sumber[]" id="akun-sumber" title="-- Pilih Akun --" data-width="100%" data-live-search="true" multiple >
								<?php foreach($masterdata as $md) : ?>
									<option value="<?= $md['id_akun']; ?>"><?= $md['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
				      	<label for="akun-sumber" class="col-sm-5 col-form-label">Keterangan</label>
				      	<div class="col-sm-7">
							<select class="bootstrap-select keterangan" name="keterangan" id="akun-sumber" title="-- Pilih Ketarangan --" data-width="100%">
								<option value="1">Penambah Kas</option>
								<option value="0">Pengurang Kas</option>
							</select>
						</div>
					</div>
				</div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary">Simpan</button>
			    </div>
			</form>
		</div>
	</div>
</div>




<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		$('.update-kategori').on('click',function(){
			const id_kategori = $(this).data('id_kategori');
			const nama_kategori = $(this).data('nama_kategori');
			$(".strings").val('');
			$('[name="id"]').val(id_kategori);
			$('[name="nama-kategori"]').val(nama_kategori);

			$.ajax({
	            url : "<?= base_url('report/data_aktivitas'); ?>",
	            method : "POST",
	            data :{id:id_kategori},
	            async : true,
		    	dataType : 'json',
	            success : function(data){
                    $.each(data, function(i,e){
                        $(".tipe-aktivitas option[value='" + e.id_kelompok_aktivitas + "']").prop("selected", true).trigger('change');
                        $(".keterangan option[value='" + e.keterangan + "']").prop("selected", true).trigger('change');
                        $(".strings option[value='" + e.id_akun + "']").prop("selected", true).trigger('change');
                        $(".strings").selectpicker('refresh');
                    });
	            }
			});
			
		});

	});
</script>
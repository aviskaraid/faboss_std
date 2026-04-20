<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
  		<h1 class="h3 text-gray-800"><?= $title;  ?></h1>
  	</div>
	
	<div class="row">
	  	<div class="col-sm-12">
			<?= $this->session->flashdata('message'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<a href="" class="btn btn-primary btn-sm mb-3 shadow-sm" data-toggle="modal" data-target="#newPeriodeAk">
	  			<i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
			    <span class="text">Tambah Periode Akuntansi</span>
		  	</a>
		</div>
	</div>

	<div class="row">
		<div class="col-sm">
	  		<div class="card border-bottom-primary shadow mb-4">
	             <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-9">
				  			<h6 class="m-0 font-weight-bold text-primary">Tabel Periode Akuntansi</h6>
				  		</div>
				  	</div>
	             </div>
	             <div class="card-body">
			  		<div class="table-responsive">
				  		<table class="table table-menu table-hover table-sm" id="tabel-data">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Buka</th>
						      <th scope="col">Tahun</th>
						      <th scope="col">Tanggal Tutup</th>
						      <th scope="col">Aksi</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
						  	<?php foreach ($periode as $row) : ?>
						    <tr>
						      	<th scope="row" width="40px"><?= $i++; ?></th>
						      	<td width="80px">
						      		<?php if($row['tahun'] == $user['id_periode']) { ?>
						      			<span class="badge badge-success">Periode Sedang Dibuka</span>
						      		<?php } else { ?>
							      		<a href="<?= base_url(); ?>periode/open_periode/<?= $row['id_periode']; ?>" class="badge badge-primary">Buka Periode</a>
						      		<?php } ?>
						      	</td>
						      	<td>
						      		<?= $row['tahun']; ?>

						      		<?php if($row['tahun'] == $user['id_periode']) { ?>
							      		<a href="<?= base_url(); ?>periode/detail/<?= $row['id_periode']; ?>" class="badge badge-success">Detail</a>
						      		<?php } ?>
						      		<?php if(is_null($row['tgl_tutup'])) { ?>
							      		<a href="<?= base_url(); ?>periode/hapus_periode_akuntansi/<?= $row['id_periode']; ?>" class="badge badge-danger" onclick="return confirm('yakin?');">Delete</a>
							      	<?php } ?>

						      	</td>
						      	<td width="400px"><?= (!is_null($row['tgl_tutup'])) ? $row['tgl_tutup'] : 'Periode Tahunan Belum Ditutup'; ?></td>
						      	<td width="150px">
						      	<?php if($row['stts'] == 0) { ?>


						      		<a href="" class="badge badge-danger tutup-buku" data-toggle="modal" data-target="#deleteKas"
						      			data-id_periode="<?= $row['id_periode']; ?>">
						      			<span class="icon text-white-50">
												  	<i class="fas fa-fw fa-trash"></i>
												</span>
												<span class="text">Tutup Buku</span>
						      		</a>


						      	<?php } else { ?>
						      		<a href="<?= base_url(); ?>periode/buka_periode_tahunan/<?= $row['id_periode']; ?>" class="badge badge-success">Buka</a>
					      		<?php } ?>
						      	</td>
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

<!-- Modal -->
<div class="modal fade" id="newPeriodeAk" tabindex="-1" role="dialog" aria-labelledby="newPeriodeAkLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newPeriodeAkLabel">Tambah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('periode/tambah_periode_akuntansi'); ?>" method="post">
	      <div class="modal-body">
	        <div class="form-group">
			    <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Tahun">
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Tambah</button>
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
        	<h5 class="modal-title" id="ubahKasLabel">Tutup Tahun</h5>
      	</div>
      	<form action="<?= base_url('periode/tutup_periode_tahunan'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_periode_delete" name="id_periode">
					<div class="row">
						<table class="m-3" width="100%">
							<tbody>
								<tr>
									<td>Tahun Buku</td>
									<td class="text-right">2025</td>
								</tr>
								<tr>
									<td>Tanggal Tutup Buku</td>
									<td class="text-right">20 Desember 2025</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="row p-2 bg-light">
						<ul>
							<li>Seluruh Transaksi periode berjalan akan difinalisasi</li>
							<li>Saldo laba tahun berjalan akan ditutup</li>
							<li>Laba atau rugi akan dipindahkan ke Laba Ditahan</li>
							<li>Tahun buku akan dikunci setelah proses selesai</li>
						</ul>
					</div>

					<div class="row mt-3">
						<div class="col-sm">
							<div class="card bg-warning text-dark">
							  <div class="card-body">
							    <b>Peringatan</b>
							    <p>Proses tutup tahun bersifat final untuk tahun buku ini. Setelah tutup tahun dilakukan, seluruh periode dalam tahun tersebut tidak dapat dilakukan untuk transaksi baru.</p>
							  </div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
	        <span class="icon text-white-50">
					  	<i class="fas fa-fw fa-window-close"></i>
					</span>
					<span class="text">Batal</span>
				</button>
	    	<button type="submit" class="btn btn-danger">
	    		<span class="icon text-white-50">
				  	<i class="fas fa-fw fa-save"></i>
					</span>
		    	<span class="text">Tutup Tahun</span>
		    </button>
	    </div>
	    </form>
    </div>
  </div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		$('.tutup-buku').on('click',function(){
			const id_periode = $(this).data('id_periode');

			$("#id_periode_delete").val(id_periode);
		});

	});
</script>
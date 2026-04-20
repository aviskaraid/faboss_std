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
		<div class="col-sm">
	  		<div class="card border-bottom-primary shadow mb-4">
	             <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-9">
				  			<h6 class="m-0 font-weight-bold text-primary">Tabel Periode Akuntansi (Tahun : <?= $dtPeriodeAkun['tahun']; ?>)</h6>
				  		</div>
				  		<div class="col-sm-3 text-right">
							<a href="<?= base_url('periode'); ?>" class="btn btn-sm btn-secondary">Kembali</a>
				  		</div>
				  	</div>
	             </div>
	             <div class="card-body">
			  		<div class="table-responsive">
				  		<table class="table table-menu table-hover table-sm">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Bulan</th>
						      <th scope="col">Status</th>
						      <th scope="col">Tanggal Tutup Buku</th>
						      <th scope="col">Aksi</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
						  	<?php foreach ($dtPeriodeAkunDetail as $row) : ?>
						    <tr>
						      <th scope="row" width="40px"><?= $i++; ?></th>
						      <td><?= getBulan($row['bln']); ?></td>
						      <td width="40px"><?= ($row['stts'] == 0) ? '<span class="badge badge-success">Buka</span>' : '<span class="badge badge-danger">Tutup</span>'; ?></td>
						      <td width="400px"><?= (!is_null($row['tgl_tutup'])) ? $row['tgl_tutup'] : 'Periode Bulan Belum Ditutup'; ?></td>
						      <td width="80px">
						      	<?php if($row['stts'] == 0) { ?>

							      		<a href="" class="badge badge-danger tutup-buku" data-toggle="modal" data-target="#deleteKas"
							      			data-id_periode_tutup="<?= $row['id_periode_tutup']; ?>">
							      			<span class="icon text-white-50">
													  	<i class="fas fa-fw fa-trash"></i>
													</span>
													<span class="text">Tutup Buku</span>
							      		</a>

						      	<?php } else { ?>
						      		<a href="<?= base_url(); ?>periode/buka_periode_bulanan/<?= $row['id_periode_tutup']; ?>" class="badge badge-success">Buka</a>
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
<div class="modal fade" id="deleteKas" tabindex="-1" role="dialog" aria-labelledby="ubahKasLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="ubahKasLabel">Closing Bulanan</h5>
      	</div>
      	<form action="<?= base_url('periode/tutup_periode_bulanan'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_periode_tutup" name="id_periode">
					<div class="row">
						<table class="m-3" width="100%">
							<tbody>
								<tr>
									<td>Periode Akuntansi</td>
									<td class="text-right">November 2025</td>
								</tr>
								<tr>
									<td>Tanggal Closing</td>
									<td class="text-right"><?= tgl_indo($tgl_tutup); ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="row p-2 bg-light">
						<ul>
							<li>Seluruh Transaksi periode berjalan akan difinalisasi</li>
							<li>Periode akuntansi akan dikunci</li>
							<li>Transaksi baru tidak dapat dilakukan pada periode ini</li>
						</ul>
					</div>

					<div class="row mt-3">
						<div class="col-sm">
							<div class="card bg-warning text-dark">
							  <div class="card-body">
							    <b>Peringatan</b>
							    <p>Proses closing bersifat final untuk periode ini. Perubahan hanya ada dilakukan melalui jurnal penyesuaian.</p>
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
		    	<span class="text">Tutup Periode</span>
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
			const id_periode_tutup = $(this).data('id_periode_tutup');

			$("#id_periode_tutup").val(id_periode_tutup);
		});

	});
</script>
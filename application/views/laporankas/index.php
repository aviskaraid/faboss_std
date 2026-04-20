<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    
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
						      	<th width="5%" scope="row"><?= $i++; ?></th>
						      	<td width="10%"><?= $row['noakun']; ?></td>
						      	<td width="20%"><?= $row['nm']; ?></td>
						      	<td width="20%"><?= $row['desk']; ?></td>
						      	<td width="15%" class="text-right"><?= number_format($row['saldo_awal']); ?></td>
						      	<td width="15%" class="text-right"><?= number_format($row['saldo_akhir']); ?></td>
						      <?php if (is_user()) : ?>
							      <td class="text-center" width="15%">
									<a href="" class="badge badge-info delete-kas" data-toggle="modal" data-target="#printKas"
										data-id_sak="<?= $row['id_sak']; ?>">
										<span class="icon text-white-50">
											<i class="fas fa-fw fa-print"></i>
										</span>
										<span class="text">Laporan</span>
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

<!-- Modal Laporan -->
<div class="modal fade" id="printKas" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="printLabel"></h5>
			</div>
			<form action="<?= base_url('laporankas/print'); ?>" method="post" target="_BLANK">
				<div class="modal-body">				
					<input type="hidden" class="form-control" id="id_sak" name="id_sak" required>	
					<div class="form-group row">
						<label for="tglAwal" class="col-sm-5 col-form-label">Tanggal Awal</label>
						<div class="col-sm-7">
							<div class="datepicker-wrapper">
								<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($t_awal); ?>" data-input required>
								<i class="fa fa-calendar datepicker-icon" data-toggle></i>
							</div>	
						</div>
					</div>
					<div class="form-group row">
						<label for="tglAkhir" class="col-sm-5 col-form-label">Tanggal Akhir</label>
						<div class="col-sm-7">
							<div class="datepicker-wrapper">
								<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($t_akhir); ?>" data-input required>
								<i class="fa fa-calendar datepicker-icon" data-toggle></i>
							</div>
						</div>	
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" name="pdf"><i class="fas fa-fw fa-print"></i> PDF</button>
					<button type="submit" class="btn btn-success" name="excel"><i class="fas fa-fw fa-download"></i> EXCEL</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

<script>
$(document).on('click', '.delete-kas', function (e) {
    e.preventDefault(); // prevent empty href from jumping page

    var id_sak = $(this).data('id_sak');   // get value from button
    $('#id_sak').val(id_sak);              // put into hidden input

    // Optional: show account name in modal title
    var namaKas = $(this).closest('tr').find('td:eq(1)').text();
    $('#printLabel').text('Laporan ' + namaKas);
});
</script>

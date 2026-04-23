<!-- Begin Page Content -->

<style>
	.no-wrap {
    	white-space: nowrap;
	}
</style>
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
	  	<div class="col-12">
	    	<div class="card border-bottom-primary shadow mb-4">
	      		<div class="card-header py-3">
	        		<h6 class="m-0 font-weight-bold text-primary">Tabel <?= $title;  ?></h6>
	      		</div>
	      	<div class="card-body">

	        <form action="" method="post">
	          	<div class="form-row align-items-end">	            	
	            	<!-- Tanggal Awal -->
	            	<div class="col-md-3">
	              		<label for="tglAwal">Tanggal Awal</label>
						<div class="datepicker-wrapper">
	              			<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
							<i class="fa fa-calendar datepicker-icon" data-toggle></i>
						</div>
					</div>	

					<!-- Tanggal Akhir -->
					<div class="col-md-3">
						<label for="tglAkhir">Tanggal Akhir</label>
						<div class="datepicker-wrapper">
							<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input required>
							<i class="fa fa-calendar datepicker-icon" data-toggle></i>
						</div>
					</div>	

					<!-- Button -->
					<div class="col-md-3 d-flex align-items-end">
						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-fw fa-filter"></i> Filter
						</button>

						<a href="" class="btn btn-secondary" data-toggle="modal" data-target="#print">
							<i class="fas fa-fw fa-print"></i> Cetak
						</a>
					</div>

	          	</div>
	        </form>

	        <hr>

			<div class="table-responsive">
				<table class="table table-dataAkun table-striped table-bordered table-sm" id="tabel-data">
					<thead class="text-center">
						<tr>
							<th scope="col">#</th>
							<th scope="col">Tanggal</th>
							<th scope="col">Invoice</th>
							<th scope="col">Customer</th>
							<th scope="col">Jt. Tempo</th>
							<th scope="col">Nominal</th>							
							<th scope="col">Dibayar</th>
							<th scope="col"><= 30 Hari</th>
							<th scope="col">31-60 Hari</th>
							<th scope="col">> 60 Hari</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$i = 1; 
					date_default_timezone_set('Asia/Jakarta'); // ensure correct timezone
					$today = new DateTime();
					?>

					<?php foreach ($dtPiutang as $row) : ?>

					<?php
						// Convert jt_tempo to DateTime
						$jtTempo = new DateTime($row['jt_tempo']);

						// Calculate interval
						$interval = $jtTempo->diff($today);

						// Total days
						$days = $interval->days;

						// Remaining amount
						$sisa = $row['nilai'] - $row['dibayar'];

						// Initialize buckets
						$hari30 = 0;
						$hari60 = 0;
						$hariLebih60 = 0;


						if ($days <= 30) {
							$hari30 = $sisa;
						} elseif ($days <= 60) {
							$hari60 = $sisa;
						} else {
							$hariLebih60 = $sisa;
						}
						
					?>

					<tr>
						<th scope="row"><?= $i++; ?></th>
						<td class="text-nowrap"><?= convertDbdateToDate($row['tgl_invoice']); ?></td>
						<td><?= $row['no_ref']; ?></td>
						<td><?= $row['nama_customer']; ?></td>
						<td class="text-nowrap"><?= convertDbdateToDate($row['jt_tempo']); ?></td>
						<td class="text-right"><?= number_format($row['nilai'], 0, ',', '.'); ?></td>                                
						<td class="text-right"><?= number_format($row['dibayar'], 0, ',', '.'); ?></td>      
						
						<td class="text-right"><?= number_format($hari30, 0, ',', '.'); ?></td>            
						<td class="text-right"><?= number_format($hari60, 0, ',', '.'); ?></td>            
						<td class="text-right"><?= number_format($hariLebih60, 0, ',', '.'); ?></td>              
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



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Modal Print -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="printLabel"></h5>
			</div>
			<form action="<?= base_url('umurpiutang/print'); ?>" method="post" target="_BLANK">
				<div class="modal-body">					
					<div class="form-group row">
						<label for="tglAwal" class="col-sm-5 col-form-label">Tanggal Awal</label>
						<div class="col-sm-7">
							<div class="datepicker-wrapper">
								<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
								<i class="fa fa-calendar datepicker-icon" data-toggle></i>
							</div>	
						</div>
					</div>
					<div class="form-group row">
						<label for="tglAkhir" class="col-sm-5 col-form-label">Tanggal Akhir</label>
						<div class="col-sm-7">
							<div class="datepicker-wrapper">
								<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input required>
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


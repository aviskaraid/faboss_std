
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

	<div class="card border-bottom-primary shadow mb-4">
        <div class="card-body">
            <form action="<?php base_url('') ?>" method="post" target="">
                <div class="form-row align-items-end">

                    <!-- AKUN -->
                    <div class="col-md-3 mb-2">
                        <label for="namaAkun" class="col-sm-8 col-form-label">Pilih Akun</label>
						<div class="col-sm-12">
							<select id="namaAkun" name="namaAkun" class="form-control bootstrap-select" title="Pilih Akun" data-live-search="true" required>
								<?php foreach ($akun as $ak) : ?>
									<option value="<?= $ak['id_akun']; ?>"><?= $ak['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
                    </div>

                    <!-- TGL AWAL -->
                    <div class="col-md-3 mb-2">
                        <label for="tglAwal">Tanggal Awal</label>
						<div class="datepicker-wrapper">
							<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
							<i class="fa fa-calendar datepicker-icon" data-toggle></i>
						</div>
                    </div>

                    <!-- TGL AKHIR -->
                    <div class="col-md-3 mb-2">
                        <label for="tglAkhir" class="col-sm-8 col-form-label">Tanggal Akhir</label>
						<div class="datepicker-wrapper">
							<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input required>
							<i class="fa fa-calendar datepicker-icon" data-toggle></i>
						</div>
                    </div>

                    <!-- TOMBOL -->
                    <div class="col-md-3 mb-2 text-md-right text-left">
                        <button type="submit" class="btn btn-success mr-3">
							<span class="icon text-white-50">
								<i class="fas fa-fw fa-filter"></i>
							</span>
							<span class="text">Filter</span>
						</button>
						<a href="" class="btn btn-secondary" data-toggle="modal" data-target="#cetakledger">
							<span class="icon text-white-50">
								<i class="fas fa-fw fa-print"></i>
							</span>
							<span class="text">Cetak</span>
						</a>
                    </div>

                </div>
            </form>

        </div>
    </div>
  	
	<div class="row">
		<div class="col-lg">
			<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-9">
				  			<h6 class="m-0 font-weight-bold text-primary nameAccount">
				  				Nama Akun : <?php if($status == 1) : ?> <?= $account_data['nama']; ?> <?php endif; ?>				  					
				  			</h6>
				  		</div>
				  		<div class="col-sm-3 text-right">
				  			<h6 class="m-0 font-weight-bold text-success accountNumber">
				  				Nomor Akun : <?php if($status == 1) : ?> <?= $account_data['noakun']; ?> <?php endif; ?>
				  			</h6>
				  		</div>
				  	</div>
	            </div>
			  	<div class="card-body">
				  	<div class="row">
				  		<div class="col-sm">
				  			<div class="table-responsive">
					  			<table class="table table-striped table-sm" style="width:100%" id="tabel-data">
								  	<thead align="center">
								    	<tr>
											<th scope="col" rowspan="2" width="100px">Tanggal</th>
											<th scope="col" rowspan="2" width="110px">No. Transaksi</th>
											<th scope="col" rowspan="2" width="220px;">Keterangan</th>
											<th scope="col" rowspan="2">Debit</th>
											<th scope="col" rowspan="2">Kredit</th>
											<th scope="col" colspan="2">Saldo Akhir</th>
						            	</tr>
						            	<tr>
											<th scope="col">Debit</th>
											<th scope="col">Kredit</th>
						            	</tr>
								  	</thead>
								  	<tbody>
								  		<?php if($status == 1) : ?>
							            	<!-- saldo awal -->
											<?php 
												$totalDebit = 0;
												$totalKredit = 0;
												$sum = 0;
												if ($account_data) :
													$sum = intval($account_data['saldo_awal']);
											?>
							            		<tr>
							                	<td scope="row"></td>
							                	<td></td>
							                	<td>Saldo Awal</td>
							                	<?php if($account_data['id_perkiraan'] == 1) {?>
													<td align="right"><?= 'Rp. '.number_format($sum,0,',','.') ?></td>
													<td></td>
													<?php $totalDebit += $sum;?>
							                	<?php } else { ?>
							                    	<td></td>
							                    	<td align="right"><?= 'Rp. '.number_format($sum,0,',','.') ?></td>
							                    	<?php $totalKredit += $sum;?>
							                	<?php } ?>

							                	<?php if($account_data['id_perkiraan'] == 1) {?>
							                    	<td align="right"><?= 'Rp. '.number_format($sum,0,',','.') ?></td>
							                    	<td></td>
							            			</tr>
							                	<?php } else { ?>
							                  		<?php $sum = $sum*(-1);  ?>
							                    	<td></td>
							                    	<td align="right"><?= 'Rp. '.number_format(abs($sum),0,',','.') ?></td>
							            			</tr>
							                	<?php } ?>
							              	<?php endif; ?>
							            	<!-- Tutup saldo awal -->

							            	<!-- isi buku besaar -->
							            	<?php foreach ($journal_data as $data): ?>
							            		<tr>
							              		<!-- <td>Penerimaan Kas</td> -->
							              		<td align="center"><?= $data['tgl']; ?></td>
							              		<td align="center"><?= $data['no_trans_format']; ?></td>
							              		<td><?= $data['keterangan']; ?></td>

							                  	<?php if($data['id_perkiraan'] == 1){ ?>							                      
							                  		<td align="right">
							                    		<?= 'Rp. '.number_format($data['nilai'],0,',','.') ?>
							                  		</td>
							                  		<td></td>
							                  		<?php $sum += intval($data['nilai']); ?>
							                  		<?php $totalDebit += intval($data['nilai']);?>
							                  	<?php } else { ?>
							                  		<td></td>
							                  		<td align="right">
							                    		<?= 'Rp. '.number_format($data['nilai'],0,',','.') ?>
							                  		</td>
							                  		<?php $sum -= intval($data['nilai']); ?>
							                  		<?php $totalKredit += intval($data['nilai']);?>
							                  	<?php } ?>
							                  
							                    <?php if($data['id_perkiraan'] == 1) { ?>
							                      	<?php if($sum >= 0) { ?>
							                        	<td align="right"><?= 'Rp. '.number_format($sum,0,',','.') ?></td>
							                        	<td></td>
							                      		</tr>
							                      	<?php } else { ?>
							                          	<td></td>
							                          	<td align="right"><?= 'Rp. '.number_format(abs($sum),0,',','.') ?></td>
							                      		</tr>
							                      	<?php } ?>
							                    <?php } else {?>
							                      	<?php if($sum >= 0) { ?>
							                        	<td align="right"><?= 'Rp. '.number_format($sum,0,',','.') ?></td>
							                        	<td></td>
							                      		</tr>
							                      	<?php } else { ?>
							                          	<td></td>
							                          	<td align="right"><?= 'Rp. '.number_format(abs($sum),0,',','.') ?></td>
							                      		</tr>
							                      	<?php } ?>
							                    <?php } ?>

							            	<?php endforeach ?>
							            <!-- Penutup -->
							            <?php endif; ?>
								  	</tbody>
								  	<tfoot>
								  		<?php if($status == 1) : ?>
							            	<!-- Total -->
							            	<tr>
							              	<td colspan="3" align="right"><b>[<?= $account_data['noakun']; ?>] <?= $account_data['nama']; ?> | Saldo Akhir</b></td>							              
							              	<td align="right"><b><?= 'Rp. '.number_format($totalDebit,0,',','.') ?></b></td>
							              	<td align="right"><b><?= 'Rp. '.number_format($totalKredit,0,',','.') ?></b></td>
							              	<?php if($sum >= 0) { ?>
							                  	<td align="right"><b><?= 'Rp. '.number_format($sum,0,',','.') ?></b></td>
							                  	<td></td>
							              		</tr>
							              	<?php }else{ ?>
							                  	<td></td>
							                  	<td align="right"><b><?= 'Rp. '.number_format(abs($sum),0,',','.') ?></b></td>
							              		</tr>
							              	<?php } ?>
							            		</tr>
							        	<?php endif; ?>
								  	</tfoot>
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
<div class="modal fade" id="cetakledger" tabindex="-1" role="dialog" aria-labelledby="cetakledgerLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="cetakledgerLabel"></h5>
      		</div>
      		<form action="<?= base_url('report/printledger'); ?>" method="post" target="_BLANK">
      			<div class="modal-body">
      				<div class="form-group row">
						<label for="namaAkun" class="col-sm-4 col-form-label">Pilih Akun</label>
						<div class="col-sm-8">
							<select id="namaAkun" name="namaAkun" class="form-control bootstrap-select" title="Pilih Akun" data-live-search="true" required>
								<?php foreach ($akun as $ak) : ?>
									<option value="<?= $ak['id_akun']; ?>"><?= $ak['nama']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
      				<div class="form-group row">
        				<label for="tglTransaksi" class="col-sm-4 col-form-label">Tanggal Awal</label>
        				<div class="col-sm-8">
							<div class="datepicker-wrapper">				
								<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
								<i class="fa fa-calendar datepicker-icon" data-toggle></i>
							</div>	
						</div>	
      				</div>
        			<div class="form-group row">
            			<label for="noTransaksi" class="col-sm-4 col-form-label">Tanggal Akhir</label>
						<div class="col-sm-8">
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
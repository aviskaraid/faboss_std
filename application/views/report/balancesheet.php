<!-- Begin Page Content -->
<div class="container-fluid">

  	<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>
    <div class="row clearfix">
        <div class="col-lg-10">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>
    <div class="row mt-3 mb-4">
        <div class="col-sm">
            <div class="card border-bottom-primary shadow">
                <div class="col-sm">
                    <div class="mt-2 mb-2">
                        <div class="card-body">
                            <form action="<?php base_url('') ?>" method="post" class="form-inline">
                                <div class="form-group col-sm-4">
                                    <label for="bln" class="col-sm-5 col-form-label">Pilih Bulan</label>
                                    <div class="col-sm-7">
                                    	<select name="bln" id="bln" class="bootstrap-select" data-width="100%" title="-- Pilih Bulan --">
                                    		<?php foreach($bulan as $row) : 
                                    			if($bulan_sekarang == $row['id']) {
                                    				$selected = 'selected';
                                    			} else { $selected = null; }
                                    			?>
                                    		<option value="<?= $row['id']; ?>" <?= $selected; ?> ><?= $row['nm']; ?></option>
                                    		<?php endforeach; ?>
                                    	</select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="tahun" class="col-sm-5 col-form-label">Pilih Tahun</label>
                                    <div class="col-sm-7">
                                    	<select name="tahun" id="tahun" class="bootstrap-select" data-width="100%" title="-- Pilih Tahun --">
                                    		<?php foreach($tahun as $row) : 
                                    			if($tahun_sekarang == $row) {
                                    				$selected = 'selected';
                                    			} else { $selected = null; }
                                    			?>
                                    		<option value="<?= $row; ?>" <?= $selected; ?> ><?= $row; ?></option>
                                    		<?php endforeach; ?>
                                    	</select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4 justify-content-center">
                                    <button type="submit" class="btn btn-success mr-3">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-fw fa-filter"></i>
                                        </span>
                                        <span class="text">Filter</span>
                                    </button>
                                    <a href="" class="btn btn-secondary" data-toggle="modal" data-target="#cetakBalanceSheet">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-fw fa-print"></i>
                                        </span>
                                        <span class="text">Cetak</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<div class="row mt-3 mb-4">
		<div class="col-sm">
			<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	              <h6 class="m-0 font-weight-bold text-primary">Tabel Laporan Neraca</h6>
	            </div>
			  	<div class="card-body">
				  	<div class="row">
				  		<div class="col-sm">
				  			<div class="table-responsive">
					  			<table class="table table-hover table-sm" style="width:100%">
								  <thead>
								    <tr>
						              	<th scope="col" colspan="4"></th>
						            </tr>
								  </thead>
								  <tbody>
								  	<?php if($status == 0) : ?>
								  		<tr style="font-weight:bold;">
							              	<td scope="row" colspan="4">Aset</td>
							             </tr>
								         <tr style="font-weight:bold;">
								              <td scope="row" colspan="4" style="text-indent: 0.2in;">Aset Lancar</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Aset Lancar</td>
								             <td class="text-right">Rp. 0</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="4" style="text-indent: 0.2in;">Aset Tetap</td>
								         </tr>
									         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Aset Tetap</td>
								             <td class="text-right">Rp. 0</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.4in;">Total Aset</td>
								             <td class="text-right">Rp. 0</td>
								         </tr>
								         <tr style="font-weight:bold;">
								            <th scope="col" colspan="4"></th>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="4" >Liabilitas dan Ekuitas</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="4">Liabilitas</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="4" style="text-indent: 0.2in;">Liabilitas Jangka Pendek</td>
								         </tr>
								         <tr style="font-weight:bold;">
								              <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Liabilitas Jangka Pendek</td>
								              <td class="text-right">Rp. 0</td>
								            </tr>
								         <tr style="font-weight:bold;">
								              <td scope="row" colspan="4" style="text-indent: 0.2in;">Liabilitas Jangka Panjang</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Liabilitas Jangka Panjang</td>
								             <td class="text-right">Rp. 0</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.6in;">Total Liabilitas</td>
								             <td class="text-right">Rp. 0</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.2in;">Ekuitas</td>
								             <td></td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.6in;">Total Ekuitas</td>
								             <td class="text-right">Rp. 0</td>
								         </tr>
								         <tr style="font-weight:bold;">
								             <td scope="row" colspan="3" style="text-indent: 0.6in;">Total Liabilitas dan Ekuitas</td>
								             <td class="text-right">Rp. 0</td>
							           	</tr>
								  	<?php endif; ?>

								  	<?php if($status == 1) : ?>

									 <tr style="font-weight:bold;">
						              	<td scope="row" colspan="4">Aset</td>
						             </tr>
							         <tr style="font-weight:bold;">
							              <td scope="row" colspan="4" style="text-indent: 0.2in;">Aset Lancar</td>
							         </tr>
							         <!-- Aset Lancar -->
                                      <?php 
                                            $sum_aset_lancar_debit = 0;
                                            $sum_aset_lancar_kredit = 0;
                                            if(isset($neraca_saldo_data[0][110]))
                                            {
                                                foreach ($neraca_saldo_data[0][110] as $key => $row)
                                                {
                                        ?>
                                                    <tr>
                                                        <td align="center"><?= $row['noakun'] ?></td>
                                                        <td><?= $row['nama'] ?></td>
                                                      <?php if($row['saldo'] >= 0) { ?>
                                                          <td align="right"><?= 'Rp. '.number_format($row['saldo'],0,',','.') ?></td>
                                                          <td></td>
                                                          <?php $sum_aset_lancar_debit += $row['saldo']; ?>
                                                      <?php } else { ?>
                                                          <td align="right">(<?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?>)</td>
                                                          <td></td>
                                                          <?php $sum_aset_lancar_kredit += $row['saldo']; ?>
                                                      <?php } ?>
                                                    </tr>
                                        <?php 
                                                }
                                            }
                                            $sum_aset_lancar = $sum_aset_lancar_debit + $sum_aset_lancar_kredit;
                                         ?>
                                         <!-- Tutup Aset Lancar -->
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Aset Lancar</td>
							             <?php if($sum_aset_lancar >= 0) { ?>
			                          		<td class="text-right" align="right"><?= 'Rp. '.number_format($sum_aset_lancar,0,',','.') ?></td>
			                          	<?php } else { ?>
			                          		<td class="text-right" align="right">(<?= 'Rp. '.number_format(abs($sum_aset_lancar),0,',','.') ?>)</td>
			                          	<?php } ?>
							         </tr>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="4" style="text-indent: 0.2in;">Aset Tetap</td>
							         </tr>
							          <!-- Aset Tetap -->
							         <?php 
							              $sum_aset_tetap_debit = 0;
							              $sum_aset_tetap_kredit = 0;
							              if(isset($neraca_saldo_data[0][120]))
							              {
							                  foreach ($neraca_saldo_data[0][120] as $key => $row)
							                  {
							         ?>
							                      <tr>
							                          <td class="text-center"><?= $row['noakun'] ?></td>
							                          <td><?= $row['nama'] ?></td>
							                          <?php if($row['saldo'] >= 0) { 
    							                          $sum_aset_tetap_debit += $row['saldo'];
							                          ?>
							                          	<td align="right"><?= 'Rp. '.number_format($row['saldo'],0,',','.') ?></td>
							                          <?php } else { 
    							                          $sum_aset_tetap_kredit += $row['saldo'];
    							                          ?>
							                          	<td align="right">(<?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?>)</td>
							                          <?php } ?>
							                          <td></td>
							                      </tr>
							         <?php 
							                      $sum_aset_tetap = $sum_aset_tetap_debit + $sum_aset_tetap_kredit;
							                  }
							              }
							         ?>
							           <!-- tutup aset tetap -->
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Aset Tetap</td>
							             <?php if($sum_aset_tetap >= 0) { ?>
				                          	<td class="text-right" align="right"><?= 'Rp. '.number_format($sum_aset_tetap,0,',','.') ?></td>
				                         <?php } else { ?>
				                          	<td class="text-right" align="right">(<?= 'Rp. '.number_format(abs($sum_aset_tetap),0,',','.') ?>)</td>
				                         <?php } ?>
							         </tr>
							          <!-- Total Aset -->
							         <?php $total_aset = $sum_aset_lancar + $sum_aset_tetap; ?>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.4in;">Total Aset</td>
							             <?php if($total_aset >= 0) { ?>
				                          	<td class="text-right" align="right"><?= 'Rp. '.number_format($total_aset,0,',','.') ?></td>
				                         <?php } else { ?>
				                          	<td class="text-right" align="right">(<?= 'Rp. '.number_format(abs($total_aset),0,',','.') ?>)</td>
				                         <?php } ?>
							         </tr>
							         <tr style="font-weight:bold;">
							            <th scope="col" colspan="4"></th>
							         </tr>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="4" >Liabilitas dan Ekuitas</td>
							         </tr>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="4">Liabilitas</td>
							         </tr>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="4" style="text-indent: 0.2in;">Liabilitas Jangka Pendek</td>
							         </tr>
							         <!-- liabilitas jangka pendek -->
                                         <?php 
                                            $sum_liabilitas_jangka_pendek_debit = 0;
                                            $sum_liabilitas_jangka_pendek_kredit = 0;
                                            if(isset($neraca_saldo_data[0][210]))
                                            {
                                                foreach ($neraca_saldo_data[0][210] as $key => $row)
                                                {
                                        ?>
                                                    <tr>
                                                        <td align="center"><?= $row['noakun'] ?></td>
                                                        <td><?= $row['nama'] ?></td>
                                                      <?php if($row['saldo'] <= 0) { ?>
                                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                                          <?php $sum_liabilitas_jangka_pendek_kredit += $row['saldo']; ?>
                                                      <?php } else { ?>
                                                          <td align="right">(<?= 'Rp. '.number_format($row['saldo'],0,',','.') ?>)</td>
                                                          <?php $sum_liabilitas_jangka_pendek_debit += $row['saldo']; ?>
                                                      <?php } ?>
                                                      	<td></td>
                                                    </tr>
                                        <?php 
                                                }
                                            }
                                            $sum_liabilitas_jangka_pendek = $sum_liabilitas_jangka_pendek_kredit + $sum_liabilitas_jangka_pendek_debit;
                                         ?>
                                         <!-- tutup liabilitas jangka pendek -->
							         <tr style="font-weight:bold;">
							              <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Liabilitas Jangka Pendek</td>
							              <?php if($sum_liabilitas_jangka_pendek >= 0) { ?>
				                          <td class="text-right" align="right">(<?= 'Rp. '.number_format($sum_liabilitas_jangka_pendek,0,',','.') ?>)</td>
				                         <?php } else { ?>
				                          <td class="text-right" align="right"><?= 'Rp. '.number_format(abs($sum_liabilitas_jangka_pendek),0,',','.') ?></td>
				                         <?php } ?>
							            </tr>
							         <tr style="font-weight:bold;">
							              <td scope="row" colspan="4" style="text-indent: 0.2in;">Liabilitas Jangka Panjang</td>
							         </tr>
							         <!-- liabilitas jangka panjang -->
                                        <?php 
                                            $sum_liabilitas_jangka_panjang_debit = 0;
                                            $sum_liabilitas_jangka_panjang_kredit = 0;
                                            if(isset($neraca_saldo_data[0][220]))
                                            {
                                                foreach ($neraca_saldo_data[0][220] as $key => $row)
                                                {
                                        ?>
                                                    <tr>
                                                        <td align="center"><?= $row['noakun'] ?></td>
                                                        <td><?= $row['nama'] ?></td>
                                                      <?php if($row['saldo'] <= 0) { ?>
                                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                                          <?php $sum_liabilitas_jangka_panjang_kredit += $row['saldo']; ?>
                                                      <?php } else { ?>
                                                          <td align="right">(<?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?>)</td>
                                                          <?php $sum_liabilitas_jangka_panjang_debit += $row['saldo']; ?>
                                                      <?php } ?>
                                                      <td></td>
                                                    </tr>
                                        <?php 
                                                }
                                            }
                                            $sum_liabilitas_jangka_panjang = $sum_liabilitas_jangka_panjang_kredit + $sum_liabilitas_jangka_panjang_debit;
                                        ?>
                                         <!-- tutup liabilitas jngaka panjang -->
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.2in;">Total Liabilitas Jangka Panjang</td>
							             <?php if($sum_liabilitas_jangka_panjang >= 0) { ?>
				                          <td class="text-right" align="right">(<?= 'Rp. '.number_format($sum_liabilitas_jangka_panjang,0,',','.') ?>)</td>
				                         <?php } else { ?>
				                          <td class="text-right" align="right"><?= 'Rp. '.number_format(abs($sum_liabilitas_jangka_panjang),0,',','.') ?></td>
				                         <?php } ?>
							         </tr>
							         <?php $total_liabilitas = $sum_liabilitas_jangka_pendek + $sum_liabilitas_jangka_panjang; ?>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.6in;">Total Liabilitas</td>
							             <?php if($total_liabilitas >= 0) { ?>
				                          <td class="text-right" align="right">(<?= 'Rp. '.number_format($total_liabilitas,0,',','.') ?>)</td>
				                         <?php } else { ?>
				                          <td class="text-right" align="right"><?= 'Rp. '.number_format(abs($total_liabilitas),0,',','.') ?></td>
				                         <?php } ?>
							         </tr>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.2in;">Ekuitas</td>
							             <td></td>
							         </tr>
							         <?php 
                                        $sum_ekuitas_debit = 0;
                                        $sum_ekuitas_kredit = 0;
                                        if(isset($neraca_saldo_data[0][310]))
                                        {
                                            foreach ($neraca_saldo_data[0][310] as $key => $row)
                                            {	
                                            	$nilai = $row['saldo'];
                                            	if ($row['noakun'] == $akun_laba_ditahan['noakun']) {
                                            		// laba ditahan
                                            		$nilai += (-1)*$laba_ditahan;
                                            	} else if ($row['noakun'] == $akun_laba_sebelumnya['noakun']) {
                                            		// laba ditahan
                                            		$nilai += (-1)*$laba_tahun_sebelumnya;
                                            	} 
                                    ?>
                                                <tr>
                                                    <td align="center"><?= $row['noakun'] ?></td>
                                                    <td><?= $row['nama'] ?></td>
                                                  <?php if($nilai <= 0) { ?>
                                                      <td align="right"><?= 'Rp. '.number_format(abs($nilai),0,',','.') ?></td>
                                                      <?php $sum_ekuitas_kredit += $nilai; ?>
                                                  <?php } else { ?>
                                                      <td align="right">(<?= 'Rp. '.number_format(abs($nilai),0,',','.') ?>)</td>
                                                      <?php $sum_ekuitas_debit += $nilai; ?>
                                                  <?php } ?>
                                                  <td></td>
                                                </tr>
                                    <?php 
                                            }
                                        }
                                        $sum_ekuitas = $sum_ekuitas_kredit + $sum_ekuitas_debit;
                                    ?>

							         <?php $total_liabilitas_and_ekuitas = $total_liabilitas + $sum_ekuitas; ?>
							         <tr style="font-weight:bold;">
							             <td scope="row" colspan="3" style="text-indent: 0.6in;">Total Liabilitas dan Ekuitas</td>
							             <?php if($total_liabilitas_and_ekuitas >= 0) { ?>
								             <td class="text-right" align="right">(<?= 'Rp. '.number_format($total_liabilitas_and_ekuitas,0,',','.') ?>)</td>
				                         <?php } else { ?>
				                          	<td class="text-right" align="right"><?= 'Rp. '.number_format(abs($total_liabilitas_and_ekuitas),0,',','.') ?></td>
				                         <?php } ?>
						           	</tr>
								  <?php endif; ?>
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
<div class="modal fade" id="cetakBalanceSheet" tabindex="-1" role="dialog" aria-labelledby="cetakBalanceSheetLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cetakBalanceSheetLabel"></h5>
      </div>
      <form action="<?= base_url('report/printbalancesheet'); ?>" method="post" target="_BLANK">
      	<div class="modal-body">
      		<div class="form-group row">
                <label for="bln" class="col-sm-5 col-form-label">Pilih Bulan</label>
                <div class="col-sm-7">
                	<select name="bln" id="bln" class="bootstrap-select" data-width="100%" title="-- Pilih Bulan --">
                		<?php foreach($bulan as $row) : 
                			if($bulan_sekarang == $row['id']) {
                				$selected = 'selected';
                			} else { $selected = null; }
                			?>
                		<option value="<?= $row['id']; ?>" <?= $selected; ?> ><?= $row['nm']; ?></option>
                		<?php endforeach; ?>
                	</select>
                </div>
            </div>
            <div class="form-group row">
                <label for="tahun" class="col-sm-5 col-form-label">Pilih Tahun</label>
                <div class="col-sm-7">
                	<select name="tahun" id="tahun" class="bootstrap-select" data-width="100%" title="-- Pilih Tahun --">
                		<?php foreach($tahun as $row) : 
                			if($tahun_sekarang == $row) {
                				$selected = 'selected';
                			} else { $selected = null; }
                			?>
                		<option value="<?= $row; ?>" <?= $selected; ?> ><?= $row; ?></option>
                		<?php endforeach; ?>
                	</select>
                </div>
            </div>
    	</div>
      <div class="modal-footer">        
        <button type="submit" class="btn btn-danger" name="pdf"><i class="fas fa-fw fa-print"></i> PDF</button>
        <button type="submit" class="btn btn-success" name="excel"><i class="fas fa-fw fa-download"></i> EXCEL</button>
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       </div>
    </div>
  </form>
  </div>
</div>
<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between">
  	<!-- Page Heading -->
		<h1 class="h3 mb-4 text-gray-800"><?= $title;  ?><span style="font-size: 20px;"> (Metode Langsung)</span></h1>
	  	<?php if (is_user()) : ?>
			<a href="<?= base_url('report/setting'); ?>" class="btn btn-sm btn-secondary shadow-sm pl-4 pr-4 mb-3">
				<i class="fa fa-fw fa-cogs fa-sm text-white-50"></i>
				<span class="text">Setting</span>
			</a>
		<?php endif; ?>
	</div>
  	
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
                                    <a href="" class="btn btn-secondary" data-toggle="modal" data-target="#cetakCashFlowStatement">
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
		<div class="col-lg-12">
			<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
		  			<h6 class="m-0 font-weight-bold text-primary">Tabel Laporan Arus Kas</h6>
	            </div>
			  	<div class="card-body">
				  	<div class="row">
				  		<div class="col-sm">
				  			<div class="table-responsive">
					  			<table class="table table-striped table-sm" style="width:100%">
								  <thead>
								    <tr>
						              <th scope="col" colspan="5"></th>
						            </tr>
								  </thead>
								  <tbody>
								  	<?php if($status == 0) : ?>
								  		<tr style="font-weight:bold;">
							                <td scope="row" colspan="5">Aktivitas Operasi</td>
							            </tr>
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Total Kas Dari Aktivitas Operasi</td>
							                <td class="text-right">Rp. 0</td>
							            </tr>
							            <tr style="font-weight:bold;">
							              <td scope="row" colspan="5">Aktivitas Investasi</td>
							            </tr>
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Total Kas Dari Aktivitas Investasi</td>
							                 <td class="text-right">Rp. 0</td>
							            </tr>
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="5">Aktivitas Pendanaan</td>
							            </tr>
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Total Kas Dari Aktivitas Pendanaan</td>
							                <td class="text-right">Rp. 0</td>
							            </tr>

							            <!-- Hasil Akhir Arus Kas Periode Berjalan -->
						                <tr style="font-weight:bold;">
						                  <td scope="row" colspan="4">Penurunan Kas</td>
						                  <td class="text-right">Rp. 0</td>
						                </tr>

							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Saldo Kas/Bank Awal</td>
							                <td class="text-right">Rp. 0</td>
							            </tr>
							            <!-- Perhitungan saldo akhir -->
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Saldo Kas/Bank Akhir</td>
							                <td class="text-right">Rp. 0</td>
							            </tr>
							  		<?php endif; ?>
								  	<?php if($status == 1) : ?>
										<tr style="font-weight:bold;">
							                <td scope="row" colspan="5">Aktivitas Operasi</td>
							            </tr>
							            <!-- looping data aktivitas operasi -->
							            <?php $i=1; ?>
							            <?php $total_aktivitas_operasi = 0; ?>
							            <?php if($activitas_operasi) : ?>
								            <?php foreach ($activitas_operasi as $act_op) : ?>
								                <tr>
								                    <td><?= $i++; ?></td>
								                    <td colspan="2"><?= $act_op['nama_kategori']; ?></td>
								                    <?php 
								                    $result = 0;
								                    foreach ($act_op['akun_sumber'] as $nilai) : 
								                        $result += $nilai['nilai'];
								                    endforeach; ?>
								                    <?php if($result < 0) { ?>
								                        <td align="right">( <?= 'Rp. '.number_format(abs($result),0,',','.') ?> )</td>
								                    <?php } else { ?>
								                        <td align="right"><?= 'Rp. '.number_format($result,0,',','.') ?></td>
								                    <?php } ?>
								                    <td></td>
								                </tr>
								                <?php $total_aktivitas_operasi += $result; ?>
								            <?php endforeach; ?>
								        <?php endif; ?>

							            <!-- tutup looping data aktivitas operasi -->
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Total Kas Dari Aktivitas Operasi</td>
							                <?php if($total_aktivitas_operasi < 0) { ?>
							                    <td align="right">( <?= 'Rp. '.number_format(abs($total_aktivitas_operasi),0,',','.') ?> )</td>
							                <?php } else { ?>
							                    <td align="right"><?= 'Rp. '.number_format($total_aktivitas_operasi,0,',','.') ?></td>
							                <?php } ?>
							            </tr>
							            
							            <tr style="font-weight:bold;">
							              <td scope="row" colspan="5">Aktivitas Investasi</td>
							            </tr>
							             <!-- looping data aktivitas operasi -->
							            <?php $j=1; ?>
							            <?php $total_activitas_investasi = 0; ?>
							            <?php if($activitas_investasi) : ?>
								            <?php foreach ($activitas_investasi as $act_inv) : ?>
								                <tr>
								                    <td><?= $j++; ?></td>
								                    <td colspan="2"><?= $act_inv['nama_kategori']; ?></td>
								                    <?php 
								                    $result = 0;
								                    foreach ($act_inv['akun_sumber'] as $nilai) : 
								                        $result += $nilai['nilai'];
								                    endforeach; ?>
								                    <?php if($result < 0) { ?>
								                        <td align="right">( <?= 'Rp. '.number_format(abs($result),0,',','.') ?> )</td>
								                    <?php } else { ?>
								                        <td align="right"><?= 'Rp. '.number_format($result,0,',','.') ?></td>
								                    <?php } ?>
								                    <td></td>
								                </tr>
								                <?php $total_activitas_investasi += $result; ?>
								            <?php endforeach; ?>
							            <?php endif; ?>

							            <!-- Tutup looping data aktivitas operasi -->
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Total Kas Dari Aktivitas Investasi</td>
							                    <?php if($total_activitas_investasi < 0) { ?>
							                        <td align="right">( <?= 'Rp. '.number_format(abs($total_activitas_investasi),0,',','.') ?> )</td>
							                    <?php } else { ?>
							                        <td align="right"><?= 'Rp. '.number_format($total_activitas_investasi,0,',','.') ?></td>
							                    <?php } ?>
							                </tr>
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="5">Aktivitas Pendanaan</td>
							            </tr>
							            <!-- looping data aktivitas pendanaan -->
							            <?php $k=1; ?>
							            <?php $total_activitas_pendanaan = 0; ?>
							            <?php if($activitas_pendanaan) : ?>
								            <?php foreach ($activitas_pendanaan as $act_pend) : ?>
								                <tr>
								                    <td><?= $k++; ?></td>
								                    <td colspan="2"><?= $act_pend['nama_kategori']; ?></td>
								                    <?php 
								                    $result = 0;
								                    foreach ($act_pend['akun_sumber'] as $nilai) : 
								                        $result += $nilai['nilai'];
								                    endforeach; ?>
								                    <?php if($result < 0) { ?>
								                        <td align="right">( <?= 'Rp. '.number_format(abs($result),0,',','.') ?> )</td>
								                    <?php } else { ?>
								                        <td align="right"><?= 'Rp. '.number_format($result,0,',','.') ?></td>
								                    <?php } ?>
								                    <td></td>
								                </tr>
								                <?php $total_activitas_pendanaan += $result; ?>
								            <?php endforeach; ?>
								         <?php endif; ?>

							            <!-- Tutup looping data aktivitas pendanaan -->

							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Total Kas Dari Aktivitas Pendanaan</td>
							                <?php if($total_activitas_pendanaan < 0) { ?>
							                    <td align="right">( <?= 'Rp. '.number_format(abs($total_activitas_pendanaan),0,',','.') ?> )</td>
							                <?php } else { ?>
							                    <td align="right"><?= 'Rp. '.number_format($total_activitas_pendanaan,0,',','.') ?></td>
							                <?php } ?>
							            </tr>

							            <!-- Perhitungan arus kas berjalan -->
							            <?php $arus_kas = $total_aktivitas_operasi + $total_activitas_investasi + $total_activitas_pendanaan; ?>

							            <!-- Hasil Akhir Arus Kas Periode Berjalan -->
							            <?php if($arus_kas < 0) { ?>
							                <tr style="font-weight:bold;">
							                  <td scope="row" colspan="4">Penurunan Kas</td>
							                  <td align="right">( <?= 'Rp. '.number_format(abs($arus_kas),0,',','.') ?> )</td>
							                </tr>
							            <?php } else { ?>
							                <tr style="font-weight:bold;">
							                  <td scope="row" colspan="4">Kenaikan Kas</td>
							                  <td align="right"><?= 'Rp. '.number_format($arus_kas,0,',','.') ?></td>
							                </tr>
							            <?php } ?>

							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Saldo Kas/Bank Awal</td>
					                        <td align="right"><?= 'Rp. '.number_format($saldo_awal_tahun,0,',','.') ?></td>
							            </tr>
							            <!-- Perhitungan saldo akhir -->
							            <?php $saldo_akhir =  $saldo_awal_tahun + $arus_kas;  ?>
							            <tr style="font-weight:bold;">
							                <td scope="row" colspan="4">Saldo Kas/Bank Akhir</td>
							                <?php if($saldo_akhir < 0) { ?>
							                    <td align="right">( <?= 'Rp. '.number_format(abs($saldo_akhir),0,',','.') ?> )</td>
							                <?php } else { ?>
							                    <td align="right"><?= 'Rp. '.number_format($saldo_akhir,0,',','.') ?></td>
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
<div class="modal fade" id="cetakCashFlowStatement" tabindex="-1" role="dialog" aria-labelledby="cetakCashFlowStatementLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cetakCashFlowStatementLabel"></h5>
      </div>
      <form action="<?= base_url('report/printcashflowstatement'); ?>" method="post" target="_BLANK">
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
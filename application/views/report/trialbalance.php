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
                                    <a href="" class="btn btn-secondary" data-toggle="modal" data-target="#cetakTrialbalance">
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
	              <h6 class="m-0 font-weight-bold text-primary">Tabel Neraca Saldo</h6>
	            </div>
			  	<div class="card-body">
				  	<div class="row">
				  		<div class="col-sm">
				  			<div class="table-responsive">
					  			<table class="table table-striped table-sm" style="width:100%" id="tabel-data">
								  <thead>
								    <tr>
								      <th scope="col" width="80px">No. Akun</th>
                      <th scope="col">Nama Akun</th>
                      <th scope="col" width="150px">Debit</th>
                      <th scope="col" width="150px">Kredit</th>
								    </tr>
								  </thead>
								  <tbody>
                    <?php if($status == 1) : ?>
                    <!-- Aset Lancar -->
                      <?php 
                            $sum_debit = 0;
                            $sum_kredit = 0;
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
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- Tutup Aset Lancar -->
                         <!-- Aset Tetap -->
                         <?php 
                            if(isset($neraca_saldo_data[0][120]))
                            {
                                foreach ($neraca_saldo_data[0][120] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                          <?php if($row['saldo'] >= 0) { ?>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                              <td></td>
                                              <?php $sum_debit += $row['saldo']; ?>
                                          <?php } else { ?>
                                              <td></td>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                              <?php $sum_kredit += $row['saldo']; ?>
                                          <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup aset tetap -->
                         <!-- liabilitas jangka pendek -->
                         <?php 
                            if(isset($neraca_saldo_data[0][210]))
                            {
                                foreach ($neraca_saldo_data[0][210] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo'] >= 0) { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup liabilitas jangka pendek -->
                         <!-- liabilitas jangka panjang -->
                         <?php 
                            if(isset($neraca_saldo_data[0][220]))
                            {
                                foreach ($neraca_saldo_data[0][220] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup liabilitas jngaka panjang -->
                         <!-- ekuitas -->
                         <?php 
                            if(isset($neraca_saldo_data[0][310]))
                            {
                                foreach ($neraca_saldo_data[0][310] as $key => $row)
                                {
                        ?>
                                  <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php
                                }
                            }
                         ?>
                         <!-- tutup ekuitas -->
                         <!-- pendapatan operasional -->
                         <?php 
                            if(isset($neraca_saldo_data[0][410]))
                            {
                                foreach ($neraca_saldo_data[0][410] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup pendapatan operasional -->
                         <!-- pendapatan non-operasional -->
                         <?php 
                            if(isset($neraca_saldo_data[0][510]))
                            {
                                foreach ($neraca_saldo_data[0][510] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup pendapatan non-operasional -->
                         <!-- beban operasional -->
                         <?php 
                            if(isset($neraca_saldo_data[0][610]))
                            {
                                foreach ($neraca_saldo_data[0][610] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit += $row['saldo']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                          <?php $sum_kredit += $row['saldo']; ?>
                                      <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup beban operasional -->
                         <!-- beban non operasional -->
                         <?php 
                            if(isset($neraca_saldo_data[0][710]))
                            {
                                foreach ($neraca_saldo_data[0][710] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                        <?php if($row['saldo'] >= 0) { ?>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                              <td></td>
                                              <?php $sum_debit += $row['saldo']; ?>
                                        <?php } else { ?>
                                              <td></td>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                              <?php $sum_kredit += $row['saldo']; ?>
                                        <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup beban non operasional -->
                         <!-- beban non operasional -->
                         <?php 
                            if(isset($neraca_saldo_data[0][810]))
                            {
                                foreach ($neraca_saldo_data[0][810] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                        <?php if($row['saldo'] >= 0) { ?>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                              <td></td>
                                              <?php $sum_debit += $row['saldo']; ?>
                                        <?php } else { ?>
                                              <td></td>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                              <?php $sum_kredit += $row['saldo']; ?>
                                        <?php } ?>
                                    </tr>
                        <?php 
                                }
                            }
                         ?>
                         <!-- tutup beban non operasional -->
                         <?php endif; ?>
                  </tbody>
                  <tfoot>
                    <?php if($status == 1) : ?>
                         <!-- jumlah -->
                         <tr style="font-weight:bold;">
                            <td colspan="2" align="center">Jumlah</td>
                            <td class="text-success" align="right"><?= 'Rp. '.number_format($sum_debit,0,',','.') ?></td>
                            <td class="text-success" align="right"><?= 'Rp. '.number_format(abs($sum_kredit),0,',','.') ?></td>
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
<div class="modal fade" id="cetakTrialbalance" tabindex="-1" role="dialog" aria-labelledby="cetakTrialbalanceLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cetakTrialbalanceLabel"></h5>
      </div>
      <form action="<?= base_url('report/printtrialbalance'); ?>" method="post" target="_BLANK">
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
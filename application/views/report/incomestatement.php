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
                                        <option value="">Semua</option>
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
                                    <a href="" class="btn btn-secondary" data-toggle="modal" data-target="#cetakIncomeStatement">
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
	              <h6 class="m-0 font-weight-bold text-primary">Laporan Laba Rugi</h6>
	            </div>
			  	<div class="card-body">
				  	<div class="row">
				  		<div class="col-sm">
				  			<div class="table-responsive">
					  			<table class="table table-striped table-sm" style="width:100%">
								  <thead>
								    <tr>
								      <th scope="col"></th>
								      <th scope="col"></th>
								      <th scope="col"></th>
								      <th scope="col"></th>
								    </tr>
								  </thead>
								  <tbody>

                    <tr style="font-weight:bold;">
                          <td scope="row" colspan="4">Penjualan</td>
                        </tr>
                      <!-- pendapatan operasional -->
                                         <?php 
                                          $sum_penjualan_debit = 0;
                                          $sum_penjualan_kredit = 0;
                                            if(isset($laba_rugi_data[0][410]))
                                            {
                                                foreach ($laba_rugi_data[0][410] as $key => $row)
                                                { 
                                        ?>  
                                                      <?php if($row['saldo'] > 0) { ?>
                                                        <tr>
                                                          <td align="center"><?= $row['noakun'] ?></td>
                                                          <td><?= $row['nama'] ?></td>
                                                          <td align="right">(<?= 'Rp. '.number_format($row['saldo'],0,',','.') ?>)</td>
                                                          <td></td>
                                                        </tr>
                                                          <?php $sum_penjualan_debit += $row['saldo']; ?>
                                                      <?php } else if($row['saldo'] <= 0) { ?>
                                                        <tr>
                                                          <td align="center"><?= $row['noakun'] ?></td>
                                                          <td><?= $row['nama'] ?></td>
                                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                                          <td></td>
                                                        </tr>
                                                          <?php $sum_penjualan_kredit += abs($row['saldo']); ?>
                                                      <?php } ?>
                                        <?php 
                                                }
                                            }
                                         ?>
                                         <!-- tutup pendapatan operasional -->
                                         <!-- tutup pendapatan non-operasional -->
                         <?php $total_pendapatan = $sum_penjualan_kredit - $sum_penjualan_debit; ?>
                        <!-- Total Pendapatan -->
                        <tr style="font-weight:bold;">
                            <td colspan="3" style="text-indent: 0.3in;">Total Penjualan</td>
                            <?php if($total_pendapatan >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($total_pendapatan,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($total_pendapatan,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>
                        <!-- End Looping Data Pendapatan -->



                        <!-- HARGA POKOK PENJUALAN -->
                        <tr style="font-weight:bold;">
                          <td scope="row" colspan="4">Harga Pokok Penjualan</td>
                        </tr>
                      <!-- pendapatan operasional -->
                                         <?php 
                                          $sum_hpp_debit = 0;
                                          $sum_hpp_kredit = 0;
                                            if(isset($laba_rugi_data[0][510]))
                                            {
                                                foreach ($laba_rugi_data[0][510] as $key => $row)
                                                {
                                        ?>
                                                      <?php if($row['saldo'] >= 0) { ?>
                                                        <tr>
                                                          <td align="center"><?= $row['noakun'] ?></td>
                                                          <td><?= $row['nama'] ?></td>
                                                          <td align="right"><?= 'Rp. '.number_format($row['saldo'],0,',','.') ?></td>
                                                          <td></td>
                                                        </tr>
                                                          <?php $sum_hpp_debit += $row['saldo']; ?>
                                                      <?php } else if($row['saldo'] < 0) { ?>
                                                        <tr>
                                                          <td align="center"><?= $row['noakun'] ?></td>
                                                          <td><?= $row['nama'] ?></td>
                                                          <td align="right">(<?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?>)</td>
                                                          <td></td>
                                                        </tr>
                                                          <?php $sum_hpp_kredit += abs($row['saldo']); ?>
                                                      <?php } ?>
                                        <?php 
                                                }
                                            }
                                         ?>
                                         <!-- tutup pendapatan operasional -->
                                         <!-- tutup pendapatan non-operasional -->
                         <?php $total_hpp = $sum_hpp_debit - $sum_hpp_kredit; ?>
                        <!-- Total Pendapatan -->
                        <tr style="font-weight:bold;">
                            <td colspan="3" style="text-indent: 0.3in;">Total Harga Pokok Penjualan</td>
                            <?php if($total_hpp >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($total_hpp,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($total_hpp,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>
                        <!-- End Looping Data Pendapatan -->


                        <!-- LABA BRUTO/GROSS PRFIT --> 
                        <?php $laba_kotor = $total_pendapatan - $total_hpp; ?>
                        <tr style="font-weight:bold;">
                            <td colspan="3">Laba (Rugi) Bruto (Gross Profit)</td>
                            <?php if($laba_kotor >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($laba_kotor,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($laba_kotor,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>


						            <!-- Looping Data Beban -->
						            <tr style="font-weight:bold;">
						              <td scope="row" colspan="4">Beban Operasional</td>
						            </tr>
						            <!-- beban operasional -->
                                         <?php 
                                            $sum_beban_operasional_debit = 0;
                                            $sum_beban_operasional_kredit = 0;
                                            if(isset($laba_rugi_data[0][610]))
                                            {
                                                foreach ($laba_rugi_data[0][610] as $key => $row)
                                                {
                                        ?>
                                                    <?php if($row['saldo'] >= 0) { ?>
                                                        <tr>
                                                          <td align="center"><?= $row['noakun'] ?></td>
                                                          <td><?= $row['nama'] ?></td>
                                                          <td align="right"><?= 'Rp. '.number_format($row['saldo'],0,',','.') ?></td>
                                                          <td></td>
                                                        </tr>
                                                        <?php $sum_beban_operasional_debit += $row['saldo']; ?>
                                                    <?php } else if($row['saldo'] < 0) { ?>
                                                        <tr>
                                                          <td align="center"><?= $row['noakun'] ?></td>
                                                          <td><?= $row['nama'] ?></td>
                                                          <td></td>
                                                          <td align="right">(<?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?>)</td>
                                                        </tr>
                                                        <?php $sum_beban_operasional_kredit += abs($row['saldo']); ?>
                                                    <?php } ?>
                                        <?php 
                                                }
                                            }
                                         ?>
                                         <!-- tutup beban operasional -->
                                         <!-- tutup beban non operasional -->
						             <?php $total_beban = $sum_beban_operasional_debit - $sum_beban_operasional_kredit; ?>
						            <!-- End Looping Data Beban -->
						            <!-- Total Beban -->
						            <tr style="font-weight:bold;">
						                <td colspan="3" style="text-indent: 0.3in;">Total Beban Operasional</td>
                            <?php if($total_beban >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($total_beban,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($total_beban,0,',','.') ?>)</td>
                            <?php } ?>
						            </tr>


                        <!-- LABA BERSIH--> 
                         <?php $laba_rugi_operasional = $laba_kotor - $total_beban; ?>

                        <tr style="font-weight:bold;">
                            <td colspan="3">Laba (Rugi) Operasional</td>
                            <?php if($laba_rugi_operasional >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($laba_rugi_operasional,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($laba_rugi_operasional,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>




                        <!-- Looping Data Beban -->
                        <tr style="font-weight:bold;">
                          <td scope="row" colspan="4">Pendapatan (Beban) Diluar Usaha</td>
                        </tr>
                        <!-- beban operasional -->
                                         <?php 
                                            $sum_pend_beban_lain_debit = 0;
                                            $sum_pend_beban_lain_kredit = 0;
                                            if(isset($laba_rugi_data[0][710]))
                                            {
                                                foreach ($laba_rugi_data[0][710] as $key => $row)
                                                {
                                        ?>
                                                      <?php if($row['saldo'] >= 0) { ?>
                                                          <tr>
                                                            <td align="center"><?= $row['noakun'] ?></td>
                                                            <td><?= $row['nama'] ?></td>
                                                            <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                                            <td></td>
                                                          </tr>
                                                          <?php $sum_pend_beban_lain_debit += $row['saldo']; ?>
                                                      <?php } else if($row['saldo'] < 0) { ?>
                                                          <tr>
                                                            <td align="center"><?= $row['noakun'] ?></td>
                                                            <td><?= $row['nama'] ?></td>
                                                            <td></td>
                                                            <td align="right">(<?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?>)</td>
                                                          </tr>
                                                          <?php $sum_pend_beban_lain_kredit += abs($row['saldo']); ?>
                                                      <?php } ?>
                                        <?php 
                                                }
                                            }
                                         ?>
                                         <!-- tutup beban operasional -->
                                         <!-- tutup beban non operasional -->
                         <?php $total_pend_beban_lain = $sum_pend_beban_lain_kredit - $sum_pend_beban_lain_debit; ?>
                        <!-- End Looping Data Beban -->
                        <!-- Total Beban -->
                        <tr style="font-weight:bold;">
                            <td colspan="3" style="text-indent: 0.3in;">Total Pendapatan (Beban) Diluar Usaha</td>
                            <?php if($total_pend_beban_lain >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($total_pend_beban_lain,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($total_pend_beban_lain,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>



                        <!-- LABA BERSIH--> 
                        <?php $laba_rugi_sebelum_pajak = $laba_rugi_operasional + $total_pend_beban_lain; ?>
                        <tr style="font-weight:bold;">
                            <td colspan="3">Laba (Rugi) Sebelum Pajak</td>
                            <?php if($laba_rugi_sebelum_pajak >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($laba_rugi_sebelum_pajak,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($laba_rugi_sebelum_pajak,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>



                        <!-- Looping Data Beban -->
                        <tr style="font-weight:bold;">
                          <td scope="row" colspan="4">Pajak</td>
                        </tr>
                        <!-- beban operasional -->
                                         <?php 
                                            $sum_beban_pajak_debit = 0;
                                            $sum_beban_pajak_kredit = 0;
                                            if(isset($laba_rugi_data[0][810]))
                                            {
                                                foreach ($laba_rugi_data[0][810] as $key => $row)
                                                {
                                        ?>
                                                      <?php if($row['saldo'] >= 0) { ?>
                                                          <tr>
                                                            <td align="center"><?= $row['noakun'] ?></td>
                                                            <td><?= $row['nama'] ?></td>
                                                            <td align="right"><?= 'Rp. '.number_format($row['saldo'],0,',','.') ?></td>
                                                            <td></td>
                                                          </tr>
                                                          <?php $sum_beban_pajak_debit += $row['saldo']; ?>
                                                      <?php } else if($row['saldo'] < 0) { ?>
                                                          <tr>
                                                            <td align="center"><?= $row['noakun'] ?></td>
                                                            <td><?= $row['nama'] ?></td>
                                                            <td></td>
                                                            <td align="right"><?= 'Rp. '.number_format(abs($row['saldo']),0,',','.') ?></td>
                                                          </tr>
                                                          <?php $sum_beban_pajak_kredit += abs($row['saldo']); ?>
                                                      <?php } ?>
                                        <?php 
                                                }
                                            }
                                         ?>
                                         <!-- tutup beban operasional -->
                                         <!-- tutup beban non operasional -->
                         <?php $total_pajak = $sum_beban_pajak_debit-$sum_beban_pajak_kredit; ?>
                        <!-- End Looping Data Beban -->
                        <!-- Total Beban -->
                        <tr style="font-weight:bold;">
                            <td colspan="3" style="text-indent: 0.3in;">Total Pajak</td>
                            <?php if($total_pajak >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($total_pajak,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($total_pajak,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>

                        <!-- LABA BERSIH--> 
                        <?php $laba_rugi_setelah_pajak = $laba_rugi_sebelum_pajak - $total_pajak; ?>
                        <tr style="font-weight:bold;">
                            <td colspan="3">Laba (Rugi) Setelah Pajak</td>
                            <?php if($laba_rugi_setelah_pajak >= 0) { ?>
                              <td align="right"><?= 'Rp. '.number_format($laba_rugi_setelah_pajak,0,',','.') ?></td>
                            <?php } else { ?>
                              <td align="right">(<?= 'Rp. '.number_format($laba_rugi_setelah_pajak,0,',','.') ?>)</td>
                            <?php } ?>
                        </tr>

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
<div class="modal fade" id="cetakIncomeStatement" tabindex="-1" role="dialog" aria-labelledby="cetakIncomeStatementLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cetakIncomeStatementLabel"></h5>
      </div>
      <form action="<?= base_url('report/printincomestatement'); ?>" method="post" target="_BLANK">
      <div class="modal-body">
        <div class="form-group row">
          <label for="bln" class="col-sm-5 col-form-label">Pilih Bulan</label>
          <div class="col-sm-7">
            <select name="bln" id="bln" class="bootstrap-select" data-width="100%" title="-- Pilih Bulan --">
              <option value="">Semua</option>
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
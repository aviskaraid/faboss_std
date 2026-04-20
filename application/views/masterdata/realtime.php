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
                      <th scope="col" width="80px" rowspan="2">No. Akun</th>
                      <th scope="col" rowspan="2">Nama Akun</th>
                      <th scope="col" colspan="2" class="text-center">Saldo Awal</th>
                      <th scope="col" colspan="2" class="text-center">Saldo Akhir</th>
                    </tr>
								    <tr>
                      <th scope="col" width="150px">Debit</th>
                      <th scope="col" width="150px">Kredit</th>
                      <th scope="col" width="150px">Debit</th>
                      <th scope="col" width="150px">Kredit</th>
								    </tr>
								  </thead>
								  <tbody>
 
                    <!-- Aset Lancar -->
                      <?php 
                            $sum_debit_awal = 0;
                            $sum_kredit_awal = 0;
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
                                      <?php if($row['saldo_awal'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format($row['saldo_awal'],0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>

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
                                          <?php if($row['saldo_awal'] >= 0) { ?>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                              <td></td>
                                              <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                          <?php } else { ?>
                                              <td></td>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                              <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                          <?php } ?>
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
                                      <?php if($row['saldo_awal'] <= 0) { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>
                                      <?php if($row['saldo'] <= 0) { ?>
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
                                      <?php if($row['saldo_awal'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>
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
                                      <?php if($row['saldo_awal'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>
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
                                      <?php if($row['saldo_awal'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>
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
                         <!-- hpp -->
                         <?php 
                            if(isset($neraca_saldo_data[0][510]))
                            {
                                foreach ($neraca_saldo_data[0][510] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                      <?php if($row['saldo_awal'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>
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
                         <!-- tutup hpp -->
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
                                      <?php if($row['saldo_awal'] >= 0) { ?>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <td></td>
                                          <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                      <?php } else { ?>
                                          <td></td>
                                          <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                          <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                      <?php } ?>
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
                         <!-- pend beban diluar usaha -->
                         <?php 
                            if(isset($neraca_saldo_data[0][710]))
                            {
                                foreach ($neraca_saldo_data[0][710] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                        <?php if($row['saldo_awal'] >= 0) { ?>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                              <td></td>
                                              <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                        <?php } else { ?>
                                              <td></td>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                              <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                        <?php } ?>
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
                         <!-- tutup pend beban diluar usaha -->
                         <!-- pend beban diluar usaha -->
                         <?php 
                            if(isset($neraca_saldo_data[0][810]))
                            {
                                foreach ($neraca_saldo_data[0][810] as $key => $row)
                                {
                        ?>
                                    <tr>
                                        <td align="center"><?= $row['noakun'] ?></td>
                                        <td><?= $row['nama'] ?></td>
                                        <?php if($row['saldo_awal'] >= 0) { ?>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                              <td></td>
                                              <?php $sum_debit_awal += $row['saldo_awal']; ?>
                                        <?php } else { ?>
                                              <td></td>
                                              <td align="right"><?= 'Rp. '.number_format(abs($row['saldo_awal']),0,',','.') ?></td>
                                              <?php $sum_kredit_awal += $row['saldo_awal']; ?>
                                        <?php } ?>
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
                         <!-- tutup pend beban diluar usaha -->
               
                  </tbody>
                  <tfoot>
            
                         <tr style="font-weight:bold;">
                            <td colspan="2" align="center">Jumlah</td>
                            <td class="text-primary" align="right"><?= 'Rp. '.number_format($sum_debit_awal,0,',','.') ?></td>
                            <td class="text-primary" align="right"><?= 'Rp. '.number_format(abs($sum_kredit_awal),0,',','.') ?></td>
                            <td class="text-primary" align="right"><?= 'Rp. '.number_format($sum_debit,0,',','.') ?></td>
                            <td class="text-primary" align="right"><?= 'Rp. '.number_format(abs($sum_kredit),0,',','.') ?></td>
                        </tr>
           

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

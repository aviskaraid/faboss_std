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
                    <a href="" class="btn btn-secondary" data-toggle="modal" data-target="#cetakCapitalStatement">
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
					<h6 class="m-0 font-weight-bold text-primary">Laporan Perubahan Ekuitas</h6>
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
											<th scope="col" width="170px"></th>
											<th scope="col" width="170px"></th>
										</tr>
									</thead>
									<tbody>
										<?php if ($status == 0) : ?>
											<tr style="font-weight:bold;">
												<td scope="row" colspan="4">Saldo Per 1/<?= $bulan_sekarang."/". $tahun_sekarang; ?></td>
												<td><b>Rp. 0</b></td>
											</tr>
											<tr style="font-weight:bold;">
												<td scope="row" colspan="5">Penambahan</td>
											</tr>
											<tr style="font-weight:bold;">
												<td colspan="4" style="text-indent: 0.3in;">Total Penambahan</td>
												<td>Rp. 0</td>
											</tr>
											<tr style="font-weight:bold;">
												<td scope="row" colspan="5">Pengurangan</td>
											</tr>
											<tr style="font-weight:bold;">
												<td colspan="4" style="text-indent: 0.3in;">Total Pengurangan</td>
												<td>Rp. 0</td>
											</tr>
										<?php endif; ?>
										<?php if ($status == 1) : ?>

											<tr style="font-weight:bold;">
												<td scope="row" colspan="4">Saldo Per 1/<?= $bulan_sekarang."/". $tahun_sekarang; ?></td>
												<!-- Menangani Saldo Awal Modal -->
												<?php
												$modal_awal = $saldo_awal;
												$total_laba_rugi = $labarugi;
												?>
												<td align="right"><b><?= 'Rp. ' . number_format(abs($saldo_awal), 0, ',', '.') ?></b></td>
											</tr>

											<!-- Menangani Penambahan -->
											<?php
											$sum_ekuitas_debit = 0;
											// cek apakah ada data di perubahan ekuitas
											if (isset($perubahan_ekuitas_data[0][310])) {
												// jika total laba rugi >= 0, maka jalankan perintah dibawah
												if ($total_laba_rugi >= 0) {
											?>
													<tr style="font-weight:bold;">
														<td scope="row" colspan="5">Penambahan</td>
													</tr>
													<tr>
														<td></td>
														<td colspan="2">Laba Bersih</td>
														<td align="right"><b><?= 'Rp. ' . number_format(abs($total_laba_rugi), 0, ',', '.') ?></b></td>
														<td></td>
													</tr>
													<?php
													// Mendapatkan noakun 11 
													foreach ($perubahan_ekuitas_data[0][310] as $key => $row) {
														if ($row['saldo'] < 0) {
													?>
															<tr>
																<td align="center"><?= $row['noakun'] ?></td>
																<td colspan="2"><?= $row['nama'] ?></td>
																<td align="right"><?= 'Rp. ' . number_format(abs($row['saldo']), 0, ',', '.') ?></td>
																<td></td>
															</tr>
													<?php $sum_ekuitas_debit += abs($row['saldo']);
														}
													}
													// jumlah penambahan ekuitas
													$sum_ekuitas_debit = $sum_ekuitas_debit + abs($total_laba_rugi);
													?>

												<?php  } else { ?>
													<!-- jika kondisi false maka, jalankan perintah dibawah -->
													<tr style="font-weight:bold;">
														<td scope="row" colspan="5">Penambahan</td>
													</tr>
													<?php
													// Mendapatkan noakun 11 
													foreach ($perubahan_ekuitas_data[0][310] as $key => $row) {
														if ($row['saldo'] < 0) {
													?>
															<tr>
																<td align="center"><?= $row['noakun'] ?></td>
																<td colspan="2"><?= $row['nama'] ?></td>
																<td align="right"><?= 'Rp. ' . number_format(abs($row['saldo']), 0, ',', '.') ?></td>
																<td></td>
															</tr>
											<?php $sum_ekuitas_debit += abs($row['saldo']);
														}
													}
													// jumlah penambahan ekuitas
													$sum_ekuitas_debit = $sum_ekuitas_debit;
												}
											}
											?>

											<tr style="font-weight:bold;">
												<td colspan="4" style="text-indent: 0.3in;">Total Penambahan</td>
												<td align="right"><?= 'Rp. ' . number_format($sum_ekuitas_debit, 0, ',', '.') ?></td>
											</tr>

											<!-- Menangani Pengurangan -->
											<?php
											$sum_ekuitas_kredit = 0;
											// Jika terdapat data di $perubahan_ekuitas_data
											if (isset($perubahan_ekuitas_data[0][310])) {
												//jika totdal laba rugi kurang dari 0
												if ($total_laba_rugi < 0) {
											?>
													<tr style="font-weight:bold;">
														<td scope="row" colspan="5">Pengurangan</td>
													</tr>
													<tr>
														<td></td>
														<td colspan="2">Rugi Bersih</td>
														<td align="right"><b><?= 'Rp. ' . number_format(abs($total_laba_rugi), 0, ',', '.') ?></b></td>
														<td></td>
													</tr>
													<?php
													// Menampilkan akun selain modal
													foreach ($perubahan_ekuitas_data[0][310] as $key => $row) {
														if ($row['saldo'] > 0) {
													?>
															<tr>
																<td align="center"><?= $row['noakun'] ?></td>
																<td colspan="2"><?= $row['nama'] ?></td>
																<td align="right"><?= 'Rp. ' . number_format($row['saldo'], 0, ',', '.') ?></td>
																<td></td>
															</tr>
													<?php $sum_ekuitas_kredit += $row['saldo'];
														}
													}
													// jumlah pengurangan ekuitas
													$sum_ekuitas_kredit = $sum_ekuitas_kredit + abs($total_laba_rugi);
												}
												// Tutup ada total laba rugi
												else
												//Jika tidak ada total laba rugi
												{
													?>
													<tr style="font-weight:bold;">
														<td scope="row" colspan="5">Pengurangan</td>
													</tr>
													<?php
													foreach ($perubahan_ekuitas_data[0][310] as $key => $row) {
														if ($row['saldo'] > 0) {
													?>
															<tr>
																<td align="center"><?= $row['noakun'] ?></td>
																<td colspan="2"><?= $row['nama'] ?></td>
																<td align="right"><?= 'Rp. ' . number_format($row['saldo'], 0, ',', '.') ?></td>
																<td></td>
															</tr>

											<?php $sum_ekuitas_kredit += $row['saldo'];
														}
													}
													// jumlah pengurangan ekuitas
													$sum_ekuitas_kredit = $sum_ekuitas_kredit;
												}
											}
											?>
											<!-- Tutup Jika tidak ada total laba rugi -->
											<!-- Total Modal -->
											<tr style="font-weight:bold;">
												<td colspan="4" style="text-indent: 0.3in;">Total Pengurangan</td>
												<td align="right"><?= 'Rp. ' . number_format($sum_ekuitas_kredit, 0, ',', '.') ?></td>
											</tr>
											<!-- Tutup Total Modal -->

										<?php endif; ?>
									</tbody>
									<tfoot>
										<?php if ($status == 0) : ?>
											<tr style="font-weight:bold;">
												<td scope="row" colspan="4">Saldo Akhir</td>
												<td><b>Rp. 0</b></td>
											</tr>
										<?php endif; ?>
										<?php if ($status == 1) : ?>
											<!-- Modal Akhir -->
											<?php $modal_akhir = $modal_awal + $sum_ekuitas_debit - $sum_ekuitas_kredit; ?>
											<tr style="font-weight:bold;">
												<td scope="row" colspan="4">Saldo Per <?= date("d/n/Y", strtotime($tglAkhir)); ?></td>
												<td align="right"><b><?= 'Rp. ' . number_format($modal_akhir, 0, ',', '.') ?></b></td>
											</tr>
											<!-- Tutup Modal Akhir -->
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
<div class="modal fade" id="cetakCapitalStatement" tabindex="-1" role="dialog" aria-labelledby="cetakCapitalStatementLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cetakCapitalStatementLabel"></h5>
			</div>
			<form action="<?= base_url('report/printcapitalstatement'); ?>" method="post" target="_BLANK">
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
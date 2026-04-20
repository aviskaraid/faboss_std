<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	</div>

  	<div class="row clearfix">
	  	<div class="col-lg-12">
			<?= $this->session->flashdata('message'); ?>			
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
  			<div class="card border-bottom-primary shadow mb-4">
        		<div class="card-header py-3">
          			<h6 class="m-0 font-weight-bold text-primary">Tabel <?= $title;  ?></h6>
        		</div>
				<form action="<?= base_url('setting/update'); ?>" method="post">
      				<div class="modal-body">
						<div class="row">
							<div class="col-sm">

								<div class="form-group row">
									<label for="id_modal" class="col-sm-3 col-form-label">Akun Ekuitas</label>
									<div class="col-sm-9">
										<select name="id_modal" id="id_modal_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true">
											<?php foreach($dt_akun as $row_) : ?>
												<option value="<?= $row_['id_akun']; ?>" <?= ($row_['id_akun'] == $dt_setting['id_modal']) ? "selected" : null; ?>><?= $row_['nama']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label for="id_lb_ditahan" class="col-sm-3 col-form-label">Akun Laba/Rugi Ditahan</label>
									<div class="col-sm-9">
										<select name="id_lb_ditahan" id="id_lb_ditahan_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true">
											<?php foreach($dt_akun as $row_) : ?>
												<option value="<?= $row_['id_akun']; ?>" <?= ($row_['id_akun'] == $dt_setting['id_lb_ditahan']) ? "selected" : null; ?>><?= $row_['nama']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label for="id_lb_sebelum" class="col-sm-3 col-form-label">Akun Laba/Rugi Tahun Berjalan</label>
									<div class="col-sm-9">
										<select name="id_lb_sebelum" id="id_lb_sebelum_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true">
											<?php foreach($dt_akun as $row_) : ?>
												<option value="<?= $row_['id_akun']; ?>" <?= ($row_['id_akun'] == $dt_setting['id_lb_sebelum']) ? "selected" : null; ?>><?= $row_['nama']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label for="id_modal" class="col-sm-3 col-form-label">Kelompok Akun HPP</label>
									<div class="col-sm-9">
										<select name="id_kel_hpp" id="id_kel_hpp_update" class="form-control bootstrap-select" title="-- Pilih Akun --" data-width="100%" data-live-search="true">
											<?php foreach($dt_kel_akun as $row_) : ?>
												<option value="<?= $row_['id_kelompok_akun']; ?>" <?= ($row_['id_kelompok_akun'] == $dt_setting['id_kel_hpp']) ? "selected" : null; ?>><?= $row_['nama_kel_akun']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

							</div>
						</div>
					</div>
		    		<div class="modal-footer">
						<button type="submit" class="btn btn-primary">
							<span class="icon text-white-50">
								<i class="fas fa-fw fa-save"></i>
							</span>
							<span class="text">Simpan</span>
						</button>
	    			</div>
	    		</form>

	  		</div>
		</div>
	</div>

	<!-- row untuk setting penomoran otomatis -->
	<div class="row card_tugas">
  		<div class="col-sm-12">
    		<div class="card border-bottom-primary shadow mb-4">
      			<div class="card-header">
        			<ul class="nav nav-tabs" id="myTab" role="tablist">

						<li class="nav-item" role="presentation">
							<a class="nav-link active"
							id="jurnal-umum-tab"
							data-toggle="tab"
							href="#jurnal-umum"
							role="tab"
							aria-controls="jurnal-umum"
							aria-selected="true">
								Jurnal Umum
							</a>
						</li>

						<li class="nav-item" role="presentation">
							<a class="nav-link"
							id="kas-tab"
							data-toggle="tab"
							href="#kas"
							role="tab"
							aria-controls="kas"
							aria-selected="false">
								Transaksi Kas
							</a>
						</li>

						<li class="nav-item" role="presentation">
							<a class="nav-link"
							id="piutang-tab"
							data-toggle="tab"
							href="#piutang"
							role="tab"
							aria-controls="piutang"
							aria-selected="false">
								Piutang
							</a>
						</li>

						<li class="nav-item" role="presentation">
							<a class="nav-link"
							id="hutang-tab"
							data-toggle="tab"
							href="#hutang"
							role="tab"
							aria-controls="hutang"
							aria-selected="false">
								Hutang
							</a>
						</li>

						<li class="nav-item" role="presentation">
							<a class="nav-link"
							id="jurnal-depresiasi-tab"
							data-toggle="tab"
							href="#jurnal-depresiasi"
							role="tab"
							aria-controls="jurnal-depresiasi"
							aria-selected="false">
								Jurnal Depresiasi
							</a>
						</li>

        			</ul>
      			</div>

      			<div class="card-body">
        			<div class="tab-content" id="myTabContent">
          				<div class="tab-pane fade show active" id="jurnal-umum" role="tabpanel" aria-labelledby="jurnal-umum-tab">
            				<h4>Penomoran Jurnal Umum</h4>
	              			<form method="post" action="<?= base_url('setting/update_nomor_otomatis'); ?>">
	              				<input type="hidden" class="form-control" id="id_menu" name="id_menu" value="1">
	              				<div class="row">
		              				<div class="col-sm-6">
										<div class="form-group">
											<label for="prefix">Prefix</label>
											<input type="text" class="form-control" id="prefix" name="prefix" value="<?= $idSetNoJU['prefix']; ?>" placeholder="Contoh JU">
										</div>
										<div class="form-group">
											<label for="prefix">Reset Nomor Urut</label>
											<div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reset_nomor1" name="reset_nomor" value="1" <?= ($idSetNoJU['reset_nomor'] == 1) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reset_nomor1">Bulanan</label>
												</div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reser_nomor2" name="reset_nomor" value="2" <?= ($idSetNoJU['reset_nomor'] == 2) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reser_nomor2">Tahunan</label>
												</div>
											</div>

										</div>
	              					</div>
		              				<div class="col-sm-6">
										<div class="form-group">
										    <label for="panjang_nomor">Panjang Nomor Urut</label>
										    <select class="form-control" id="panjang_nomor" name="panjang_nomor">
										    	<option value="">-- Pilih Panjang Nomor Urut --</option>
										    	<option value="3" <?= ($idSetNoJU['panjang_nomor'] == 3) ? "selected" : null; ?> >3 Digit (001)</option>
										    	<option value="4" <?= ($idSetNoJU['panjang_nomor'] == 4) ? "selected" : null; ?> >4 Digit (0001)</option>
										    </select>
										</div>
										<div class="form-group">
										    <label for="nomor_berikut">Nomor Berikutnya</label>
										    <input type="text" class="form-control" placeholder="Nomor berikutnya" value="<?= $no_urut_ju; ?>">
										</div>

	              					</div>
	              				</div>
								<div class="form-group">
								    <input type="text" class="form-control" id="contoh" placeholder="Menampilkan Contoh" value="<?= $no_trans_ju; ?>">
								</div>
								<div class="row">
									<div class="col-sm text-right">
										<button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan Jurnal Umum</button>
									</div>
								</div>
							</form>
          				</div>

          				<div class="tab-pane fade" id="kas" role="tabpanel" aria-labelledby="kas-tab">            
            				<h4>Penomoran Transaksi Kas</h4>
	              			<form method="post" action="<?= base_url('setting/update_nomor_otomatis'); ?>">
	              				<input type="hidden" class="form-control" id="id_menu" name="id_menu" value="2">
	              				<div class="row">
		              				<div class="col-sm-6">
										<div class="form-group">
										    <label for="prefix">Prefix</label>
										    <input type="text" class="form-control" id="prefix" name="prefix" value="<?= $idSetNoKas['prefix']; ?>"placeholder="Contoh PIU">
										</div>

										<div class="form-group">
										    <label for="prefix">Reset Nomor Urut</label>
										    <div>
											    <div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reset_nomor3" name="reset_nomor" value="1" <?= ($idSetNoKas['reset_nomor'] == 1) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reset_nomor3">Bulanan</label>
												</div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reser_nomor4" name="reset_nomor" value="2" <?= ($idSetNoKas['reset_nomor'] == 2) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reser_nomor4">Tahunan</label>
												</div>
											</div>
										</div>
	              					</div>
		              				
									<div class="col-sm-6">
										<div class="form-group">
											<label for="panjang_nomor">Panjang Nomor Urut</label>
											<select class="form-control" id="panjang_nomor" name="panjang_nomor">
												<option value="">-- Pilih Panjang Nomor Urut --</option>
												<option value="3" <?= ($idSetNoKas['panjang_nomor'] == 3) ? "selected" : null; ?>>3 Digit (001)</option>
												<option value="4" <?= ($idSetNoKas['panjang_nomor'] == 4) ? "selected" : null; ?>>4 Digit (0001)</option>
											</select>
										</div>

										<div class="form-group">
											<label for="nomor_berikut">Nomor Berikutnya</label>
										    <input type="text" class="form-control" id="nomor_berikut" name="nomor_berikut" placeholder="Nomor berikutnya" value="<?= $no_urut_nokas; ?>">
										</div>
	              					</div>
	              				</div>

								<div class="form-group">
								    <input type="text" class="form-control" id="contoh" name="contoh" placeholder="Menampilkan Contoh" value="<?= $no_trans_nokas; ?>">
								</div>
								<div class="row">
									<div class="col-sm text-right">
										<button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan Transaksi Kas</button>
									</div>
								</div>
							</form>
          				</div>

          				<div class="tab-pane fade" id="piutang" role="tabpanel" aria-labelledby="piutang-tab">            
            				<h4>Penomoran Piutang</h4>
	              			<form method="post" action="<?= base_url('setting/update_nomor_otomatis'); ?>">
	              				<input type="hidden" class="form-control" id="id_menu" name="id_menu" value="3">
	              				<div class="row">
		              				<div class="col-sm-6">
										<div class="form-group">
											<label for="prefix">Prefix</label>
											<input type="text" class="form-control" id="prefix" name="prefix" value="<?= $idSetNoPiut['prefix']; ?>"placeholder="Contoh PIU">
										</div>
										<div class="form-group">
											<label for="prefix">Reset Nomor Urut</label>
											<div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reset_nomor3" name="reset_nomor" value="1" <?= ($idSetNoPiut['reset_nomor'] == 1) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reset_nomor3">Bulanan</label>
												</div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reser_nomor4" name="reset_nomor" value="2" <?= ($idSetNoPiut['reset_nomor'] == 2) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reser_nomor4">Tahunan</label>
												</div>
											</div>
										</div>
	              					</div>
		              			
									<div class="col-sm-6">
										<div class="form-group">
											<label for="panjang_nomor">Panjang Nomor Urut</label>
											<select class="form-control" id="panjang_nomor" name="panjang_nomor">
												<option value="">-- Pilih Panjang Nomor Urut --</option>
												<option value="3" <?= ($idSetNoPiut['panjang_nomor'] == 3) ? "selected" : null; ?>>3 Digit (001)</option>
												<option value="4" <?= ($idSetNoPiut['panjang_nomor'] == 4) ? "selected" : null; ?>>4 Digit (0001)</option>
											</select>
										</div>

										<div class="form-group">
											<label for="nomor_berikut">Nomor Berikutnya</label>
											<input type="text" class="form-control" id="nomor_berikut" name="nomor_berikut" placeholder="Nomor berikutnya" value="<?= $no_urut_piut; ?>">
										</div>
									</div>
	              				</div>

								<div class="form-group">
									<input type="text" class="form-control" id="contoh" name="contoh" placeholder="Menampilkan Contoh" value="<?= $no_trans_piut; ?>">
								</div>
								<div class="row">
									<div class="col-sm text-right">
										<button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan Piutang</button>
									</div>
								</div>
							</form>
          				</div>

          				<div class="tab-pane fade" id="hutang" role="tabpanel" aria-labelledby="hutang-tab">            
            				<h4>Penomoran Hutang</h4>
	              			<form method="post" action="<?= base_url('setting/update_nomor_otomatis'); ?>">
	              				<input type="hidden" class="form-control" id="id_menu" name="id_menu" value="4">
	              				<div class="row">
		              				<div class="col-sm-6">
										<div class="form-group">
										    <label for="prefix">Prefix</label>
										    <input type="text" class="form-control" id="prefix" name="prefix" value="<?= $idSetNoHut['prefix']; ?>"placeholder="Contoh HUT">
										</div>

										<div class="form-group">
										    <label for="prefix">Reset Nomor Urut</label>
										    <div>
											    <div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reset_nomo5" name="reset_nomor" value="1" <?= ($idSetNoHut['reset_nomor'] == 1) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reset_nomo5">Bulanan</label>
												</div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reser_nomor6" name="reset_nomor" value="2" <?= ($idSetNoHut['reset_nomor'] == 2) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reser_nomor6">Tahunan</label>
												</div>
											</div>
										</div>
	              					</div>
		              	
									<div class="col-sm-6">
										<div class="form-group">
											<label for="panjang_nomor">Panjang Nomor Urut</label>
											<select class="form-control" id="panjang_nomor" name="panjang_nomor">
												<option value="">-- Pilih Panjang Nomor Urut --</option>
												<option value="3" <?= ($idSetNoHut['panjang_nomor'] == 3) ? "selected" : null; ?>>3 Digit (001)</option>
												<option value="4" <?= ($idSetNoHut['panjang_nomor'] == 4) ? "selected" : null; ?>>4 Digit (0001)</option>
											</select>
										</div>

										<div class="form-group">
											<label for="nomor_berikut">Nomor Berikutnya</label>
											<input type="text" class="form-control" id="nomor_berikut" name="nomor_berikut" placeholder="Nomor berikutnya" value="<?= $no_urut_hut; ?>">
										</div>
	              					</div>
	              				</div>

								<div class="form-group">
								    <input type="text" class="form-control" id="contoh" name="contoh" placeholder="Menampilkan Contoh" value="<?= $no_trans_hut; ?>">
								</div>
								<div class="row">
									<div class="col-sm text-right">
										<button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan Hutang</button>
									</div>
								</div>
							</form>
          				</div>

          				<div class="tab-pane fade" id="jurnal-depresiasi" role="tabpanel" aria-labelledby="jurnal-depresiasi-tab">            
            				<h4>Penomoran Jurnal Depresiasi</h4>
	              			<form method="post" action="<?= base_url('setting/update_nomor_otomatis'); ?>">
	              				<input type="hidden" class="form-control" id="id_menu" name="id_menu" value="5">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="prefix">Prefix</label>
											<input type="text" class="form-control" id="prefix" name="prefix" value="<?= $idSetNoDep['prefix']; ?>"placeholder="Contoh DEP">
										</div>

										<div class="form-group">
											<label for="prefix">Reset Nomor Urut</label>
											<div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reset_nomor7" name="reset_nomor" value="1" <?= ($idSetNoDep['reset_nomor'] == 1) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reset_nomor7">Bulanan</label>
												</div>
												<div class="custom-control custom-radio custom-control-inline">
													<input type="radio" id="reser_nomor8" name="reset_nomor" value="2" <?= ($idSetNoDep['reset_nomor'] == 2) ? "checked" : null; ?> class="custom-control-input">
													<label class="custom-control-label" for="reser_nomor8">Tahunan</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="panjang_nomor">Panjang Nomor Urut</label>
											<select class="form-control" id="panjang_nomor" name="panjang_nomor">
												<option value="">-- Pilih Panjang Nomor Urut --</option>
												<option value="3" <?= ($idSetNoDep['panjang_nomor'] == 3) ? "selected" : null; ?>>3 Digit (001)</option>
												<option value="4" <?= ($idSetNoDep['panjang_nomor'] == 4) ? "selected" : null; ?>>4 Digit (0001)</option>
											</select>
										</div>

										<div class="form-group">
											<label for="nomor_berikut">Nomor Berikutnya</label>
											<input type="text" class="form-control" id="nomor_berikut" name="nomor_berikut" placeholder="Nomor berikutnya" value="<?= $no_urut_dep; ?>">
										</div>
									</div>
	              				</div>

								<div class="form-group">
									<input type="text" class="form-control" id="contoh" name="contoh" placeholder="Menampilkan Contoh" value="<?= $no_trans_dep; ?>">
								</div>
								<div class="row">
									<div class="col-sm text-right">
										<button type="submit" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Simpan Jurnal Depresiasi</button>
									</div>
								</div>
							</form>
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
<div class="modal fade" id="ubahSettingAkun" tabindex="-1" role="dialog" aria-labelledby="ubahSettingAkunLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="ubahSettingAkunLabel">Ubah Data Kas</h5>
      	</div>
      	
    </div>
  </div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		$('.update-akun').on('click',function(){
			const id_sa = $(this).data('id_sa');
			const nm = $(this).data('nm');
			const id_akun = $(this).data('id_akun');

			$("#id_sa_update").val(id_sa);
			$("#nm_akun_update").val(nm);
			$("#id_akun_update option[value='" + id_akun + "']").prop("selected", true).trigger('change');
		});

	});
</script>
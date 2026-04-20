<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		  <!--   <a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addutang">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah utang Baru</span>
		    </a> -->
	    <?php endif; ?>
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

	            		<!-- Status -->
	            		<div class="col-md-3">
	              			<label for="status">Pilih Status</label>
	              			<select id="status" name="stts"
								class="form-control bootstrap-select"
								data-live-search="true" required>
								<option value="99">Semua</option>
								<option value="0" <?= ($stts == 0) ? 'selected' : null; ?>>Belum Lunas</option>
								<option value="1" <?= ($stts == 1) ? 'selected' : null; ?>>Sudah Lunas</option>
							</select>
	            		</div>

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
								<th scope="col">No Transaksi</th>
								<th scope="col">Keterangan</th>
								<th scope="col">Nominal</th>
								<th scope="col">Dibayar</th>
								<th scope="col">Status</th>
								<?php if (is_user() || is_keuangan()) : ?>
									<th scope="col">Action</th>
								<?php endif; ?>
						  	</tr>
						</thead>
						<tbody>
						  	<?php $i = 1; ?>
				  			<?php foreach ($dtUtang as $row) : ?>
						    	<tr>
									<th scope="row"><?= $i++; ?></th>
									<td><?= convertDbdateToDate($row['tgl']); ?></td>
									<td><?= $row['no_trans']; ?></td>
									<td><?= $row['keterangan']; ?></td>
									<td class="text-right"><?= number_format($row['nilai'], 0, ',', '.'); ?></td>
									<td class="text-right"><?= number_format($row['dibayar'], 0, ',', '.'); ?></td>
									<td>
								      	<?php if($row['dibayar'] < $row['nilai']) {
								      		echo '<b>Belum Lunas</b>';
								      	} else {
								      		echo 'Sudah Lunas';
								      	} ?>
					      			</td>
							      	<?php if (is_user() || is_keuangan()) : ?>
								      	<td class="text-center" width="15%">
								      		<?php if($row['dibayar'] < $row['nilai']) { ?>
												<a href="" class="badge badge-success pembayaran" data-toggle="modal" data-target="#pembayaranUtang"
													data-id_utang="<?= $row['id_utang'] ?>"
													data-nilai="<?= $row['nilai'] ?>"
													data-dibayar="<?= $row['dibayar'] ?>">
													<span class="text">Pembayaran</span>
												</a> 
												<a href="javascript:void(0)" 
											   		class="badge badge-primary btn-detail"
											   		data-id_utang="<?= $row['id_utang']; ?>">
											    	<span class="text">Detail</span>
												</a>
											<?php } else { ?>
									      		<a href="" class="badge badge-secondary pembayaran">
													<span class="text">Pembayaran</span>
												</a> 
											<?php } ?>
								    	</td>
								  	<?php endif; ?>
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

<div class="modal fade" id="modalDetailPelunasan" tabindex="-1">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">Detail Pelunasan Utang</h5>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>

      		<div class="modal-body">
        		<table class="table table-bordered">
          			<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nilai</th>
							<th>Kas</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
          			</thead>
          			<tbody id="pelunasanBody">
						<tr>
							<td colspan="4" class="text-center">Loading...</td>
						</tr>
          			</tbody>
        		</table>
      		</div>

    	</div>
  	</div>
</div>

<script>
$(document).on('click', '.btn-detail', function () {
    let id_utang = $(this).data('id_utang');

    $('#modalDetailPelunasan').modal('show');
    $('#pelunasanBody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: "<?= base_url('utang/detail_pelunasan'); ?>",
        type: "POST",
        data: { id_utang: id_utang },
        dataType: "json",
        success: function (res) {
			    let html = '';

			    if (res.length > 0) {
			        res.forEach(function (row, index) {

			            let no = index + 1;

			            // format rupiah
			            let rupiah = Number(row.nilai).toLocaleString('id-ID');

			            html += `
			                <tr>
			                    <td class="text-center">${no}</td>
			                    <td>${convertDateToText(row.tgl)}</td>
			                    <td>Rp ${rupiah}</td>
			                    <td>${row.nm}</td>
			                    <td>${row.ket}</td>
			                    <td class="text-center">
			                        <button 
			                            class="btn btn-sm btn-danger btn-hapus-pelunasan"
			                            data-id="${row.id_utang_bayar}"
			                            data-tgl="${tgl}"
			                            data-utang="${id_utang}">
			                            <i class="fas fa-trash"></i>
			                        </button>
			                    </td>
			                </tr>
			            `;
			        });
			    } else {
			        html = `
			            <tr>
			                <td colspan="6" class="text-center">Belum ada pelunasan</td>
			            </tr>
			        `;
			    }

			    $('#pelunasanBody').html(html);
			}

    });
});

$(document).on('click', '.btn-hapus-pelunasan', function () {
    let id_pelunasan = $(this).data('id');
    let tgl = $(this).data('tgl');
    let utang   = $(this).data('utang');
    
	if (!tgl) {
        Swal.fire('Error', 'Tanggal transaksi tidak ditemukan', 'error');
        return;
    }

     // 1️⃣ CEK PERIODE DULU
    $.ajax({
        url: APP.base_url + 'periode/cek_periode',
        type: 'POST',
        dataType: 'json',
        data: { tgl: tgl },
        success: function (res) {

            if (res.closed) {
                Swal.fire({
                    icon: 'error',
                    title: 'Periode Ditutup',
                    text: 'Transaksi ini berada di periode yang sudah ditutup dan tidak bisa dihapus'
                });
                return;
            }

		    Swal.fire({
		        title: 'Hapus Pelunasan?',
		        text: 'Data yang dihapus tidak bisa dikembalikan',
		        icon: 'warning',
		        showCancelButton: true,
		        confirmButtonText: 'Ya, Hapus',
		        cancelButtonText: 'Batal'
		    }).then((result) => {
		        if (result.isConfirmed) {

		            $.ajax({
		                url: "<?= base_url('utang/hapus_pelunasan'); ?>",
		                type: "POST",
		                dataType: "json",
		                data: { id_pelunasan: id_pelunasan },
		                success: function (res) {

		                    if (res.status) {
		                        Swal.fire({
						            icon: 'success',
						            title: 'Berhasil',
						            text: 'Pelunasan berhasil dihapus'
						        }).then(() => {
						            // 🔁 redirect ke daftar piutang
						            window.location.href = APP.base_url + 'utang';
						        });
		                    } else {
		                        Swal.fire('Gagal', res.message, 'error');
		                    }
		                }
		            });

		        }
		    });

        }
    });
});

</script>


<!-- Modal -->
<div class="modal fade" id="pembayaranUtang" tabindex="-1" role="dialog" aria-labelledby="pembayaranUtangLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
      			<h5 class="modal-title" id="pembayaranUtangLabel">Pembayaran Utang</h5>
    		</div>
    		<form action="<?= base_url('utang/pembayaran'); ?>" method="post">
	    		<div class="modal-body">
	        		<div class="row">
						<div class="col-sm">
							<input type="hidden" class="form-control" id="id_utang_update" name="id_utang" required="">
			      			<div class="form-group row">
			      				<label for="id_kas" class="col-sm-3 col-form-label">Akun Kas/Bank</label>
				    			<div class="col-sm-9">
							    	<select name="id_kas" id="id_kas" class="form-control bootstrap-select" title="-- Pilih Akun Kas/Bank--" data-width="100%" data-live-search="true" required="">
							    		<?php foreach($dtAkunKas as $row_) : ?>
											<option value="<?= $row_['id_sak']; ?>">(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
										<?php endforeach; ?>
							    	</select>
							    </div>
							</div>
							<div class="form-group row">
								<label for="nominal" class="col-sm-3 col-form-label">Nominal Utang</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nilai_update" readonly>
									<input type="hidden" id="nilai_asli" name="nominal">
								</div>
							</div>

							<div class="form-group row">
								<label for="dibayar" class="col-sm-3 col-form-label">Dibayar</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="dibayar_update" readonly>
									<input type="hidden" id="dibayar_asli" name="dibayar_total">
								</div>
							</div>

							<div class="form-group row">
								<label for="dibayar" class="col-sm-3 col-form-label">Pembayaran</label>
			    				<div class="col-sm-9">
									<input type="text" class="form-control nominal" id="dibayar" name="dibayar" placeholder="Jumlah Pembayaran" required="">
								</div>
							</div>

							<div class="form-group row">
								<label for="tgl" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
			    				<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tgl" name="tgl" data-input required="" value="<?= $now; ?>">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
									</div>	
								</div>
							</div>

							<div class="form-group row">
								<label for="desk" class="col-sm-3 col-form-label">Deskripsi</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="deskripsi" name="desk" rows="3" cols="1" placeholder="Deskripsi Kas" required=""></textarea>
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
	        		<button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
		        		<span class="icon text-white-50">
					  		<i class="fas fa-fw fa-window-close"></i>
						</span>
						<span class="text">Close</span>
					</button>
		    	</div>
	    	</form>
    	</div>
  	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.bootstrap-select').selectpicker();

		function formatRupiah(angka) {
		    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
		}

		$('.pembayaran').on('click', function () {
	    const id_utang   = $(this).data('id_utang');
	    const nilai   = $(this).data('nilai');
	    const dibayar = $(this).data('dibayar');

	    $("#id_utang_update").val(id_utang);

	    // tampil rupiah
	    $("#nilai_update").val(formatRupiah(nilai));
	    $("#dibayar_update").val(formatRupiah(dibayar));

	    // simpan nilai asli (angka)
	    $("#nilai_asli").val(nilai);
	    $("#dibayar_asli").val(dibayar);
		});


	});

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    function cleanNumber(val) {
        return parseInt(val.replace(/[^0-9]/g, '')) || 0;
    }

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '');
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return rupiah ? 'Rp. ' + rupiah : '';
    }


    $('.nominal').on('input', function () {
        let value = $(this).val();
        $(this).val(formatRupiah(value));
    });

		$('.pembayaran').on('click', function () {
	    const id_utang   = $(this).data('id_utang');
	    const nilai   = $(this).data('nilai');
	    const dibayar = $(this).data('dibayar');

	    $("#id_utang_update").val(id_utang);

	    // tampil rupiah
	    $("#nilai_update").val(formatRupiah(nilai.toString()));
	    $("#dibayar_update").val(formatRupiah(dibayar.toString()));

	    // simpan nilai asli (angka)
	    $("#nilai_asli").val(nilai);
	    $("#dibayar_asli").val(dibayar);
		});

    // Validasi realtime saat input pembayaran
    $('#dibayar').on('input', function () {
        let inputBayar   = cleanNumber($(this).val());
        let nilaiAsli    = parseInt($('#nilai_asli').val()) || 0;
        let sudahDibayar = parseInt($('#dibayar_asli').val()) || 0;

        let sisaTagihan = nilaiAsli - sudahDibayar;

        if (inputBayar > sisaTagihan) {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Melebihi Hutang',
                text: 'Jumlah pembayaran tidak boleh melebihi sisa hutang!',
            });

            $(this).val('');
            return false;
        }

        // tetap format rupiah
        $(this).val(formatRupiah(inputBayar.toString()));
    });

   
   
    // Validasi ulang saat submit (WAJIB)
    
	$('#pembayaranUtang form').on('submit', function (e) {
	    e.preventDefault();

	    let form = this;

	    // =====================
	    // 1️⃣ CEK TANGGAL
	    // =====================
	    let tgl_text = document.getElementById('tgl').value;
		let tgl = convertTextToDate(tgl_text);
		
	    if (!tgl) {
	        Swal.fire('Error', 'Tanggal transaksi wajib diisi', 'error');
	        return;
	    }

	    $.ajax({
	        url: APP.base_url + 'periode/cek_periode',
	        type: 'POST',
	        dataType: 'json',
	        data: { tgl: tgl },
	        success: function (res) {

	            if (res.closed) {
	                Swal.fire({
	                    icon: 'error',
	                    title: 'Periode Ditutup',
	                    text: 'Tanggal transaksi berada di periode yang sudah ditutup'
	                });
	                return;
	            }

	            // =====================
	            // 2️⃣ CEK PEMBAYARAN
	            // =====================
	            let inputBayar   = cleanNumber($('#dibayar').val());
	            let nilaiAsli    = parseInt($('#nilai_asli').val()) || 0;
	            let sudahDibayar = parseInt($('#dibayar_asli').val()) || 0;

	            if ((inputBayar + sudahDibayar) > nilaiAsli) {
	                Swal.fire({
	                    icon: 'error',
	                    title: 'Gagal Disimpan',
	                    text: 'Total pembayaran melebihi nilai piutang!'
	                });
	                return;
	            }

	            // kirim angka murni ke server
	            $('#dibayar').val(inputBayar);

	            // =====================
	            // 3️⃣ SUBMIT FINAL
	            // =====================
	            form.submit();
	        },
	        error: function () {
	            Swal.fire('Error', 'Gagal cek periode', 'error');
	        }
	    });
	});


});
</script>

<!-- Modal -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="printLabel"></h5>
      		</div>
      		<form action="<?= base_url('utang/print'); ?>" method="post" target="_BLANK">
      			<div class="modal-body">
      				<div class="form-group row">
            			<label for="stts" class="col-sm-5 col-form-label">Pilih Status</label>
            			<div class="col-sm-7">
              				<select id="status" name="stts"
                      			class="form-control bootstrap-select"
                      			data-live-search="true" required>
                				<option value="99">Semua</option>
                				<option value="0" <?= ($stts == 0) ? 'selected' : null; ?>>Belum Lunas</option>
                				<option value="1" <?= ($stts == 1) ? 'selected' : null; ?>>Sudah Lunas</option>
              				</select>
            			</div>
          			</div>
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


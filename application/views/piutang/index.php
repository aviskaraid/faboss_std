<!-- Begin Page Content -->

<style>
	.no-wrap {
    	white-space: nowrap;
	}
</style>
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
		  	<a href="" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#tambahPiutang">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Piutang</span>
		    </a>
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
	                      	data-live-search="true">
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
							<th scope="col">Invoice</th>
							<th scope="col">Customer</th>
							<th scope="col">Keterangan</th>
							<th scope="col">Nominal</th>
							<th scope="col">Jt. Tempo</th>
							<th scope="col">Dibayar</th>
							<th scope="col">Status</th>
						    <?php if (is_user() || is_keuangan()) : ?>
								<th scope="col">Pembayaran</th>
							<?php endif; ?>
							<?php if (is_user() || is_keuangan()) : ?>
								<th scope="col">Action</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; ?>
				  		<?php foreach ($dtPiutang as $row) : ?>
						    <tr>
						      	<th scope="row"><?= $i++; ?></th>
						      	<td class="text-nowrap"><?= convertDbdateToDate($row['tgl_invoice']); ?></td>
						      	<td><?= $row['no_ref']; ?></td>
						      	<td><?= $row['nama_customer']; ?></td>
						      	<td><?= $row['deskripsi']; ?></td>
						      	<td class="text-right"><?= number_format($row['nilai'], 0, ',', '.'); ?></td>
								<td class="text-nowrap"><?= convertDbdateToDate($row['jt_tempo']); ?></td>
						      	<td class="text-right"><?= number_format($row['dibayar'], 0, ',', '.'); ?></td>
						      	<td>
									<?php if($row['dibayar'] < $row['nilai']) {
										echo '<b>Belum Lunas</b>';
									} else {
										echo 'Sudah Lunas';
									} ?>
					      		</td>
							    <?php if (is_user() || is_keuangan()) : ?>
									<!-- BUTTON PEMBAYARAN -->
								    <td class="text-center text-nowrap">
								      	<?php if($row['dibayar'] < $row['nilai']) { ?>
									      	<a href="" class="badge badge-success pembayaran" data-toggle="modal" data-target="#pembayaranPiutang"
								      			data-id_piutang="<?= $row['id_piutang'] ?>"
								      			data-nilai="<?= $row['nilai'] ?>"
								      			data-dibayar="<?= $row['dibayar'] ?>">
												<span class="text">Pembayaran</span>
											</a> 
											<a href="javascript:void(0)" 
											   class="badge badge-primary btn-detail"
											   data-piutang-id="<?= $row['id_piutang']; ?>">
											    <span class="text">Detail</span>
											</a>

										<?php } else { ?>
								      		<div class="badge badge-secondary">
												<span class="text">Pembayaran</span>
											</div> 
										<?php } ?>
								    </td>
									<!-- BUTTON ACTION -->
									<td class="text-center text-nowrap">
										<?php
											$file_name   = $row['bukti'];
											$file_path   = FCPATH . 'assets/file/bukti/' . $file_name;
											$file_url    = base_url('assets/file/bukti/' . $file_name);
											$file_exists = (!empty($file_name) && file_exists($file_path));
										?>

										<!-- BUTTON VIEW BUKTI -->
										<?php if ($file_exists) : ?>
											<a href="#" 
											class="badge badge-info btn-bukti"
											data-file="<?= $file_url; ?>"
											data-toggle="tooltip"
											title="Lihat Bukti">
												<i class="fas fa-file-alt"></i> Bukti
											</a>
										<?php else : ?>
											<span class="badge badge-secondary"
												style="opacity:0.6; cursor:not-allowed;"
												title="File bukti tidak tersedia">
												<i class="fas fa-file-alt"></i> Bukti
											</span>
										<?php endif; ?>

										<!-- EDIT -->									      	
										<a href="javascript:void(0)" 
											class="badge badge-warning editPiutang"											
											data-toggle="modal" 
											data-target="#editPiutang"
											data-id_piutang="<?= $row['id_piutang'] ?>"
											data-no_ref="<?= $row['no_ref'] ?>"
											data-tgl_invoice="<?= convertDbdateToDate($row['tgl_invoice']) ?>"
											data-jt_tempo="<?= convertDbdateToDate($row['jt_tempo']) ?>"
											data-deskripsi="<?= $row['deskripsi'] ?>"
											data-nilai="<?= $row['nilai'] ?>"
											data-id_customer="<?= $row['id_customer'] ?>"
											data-id_pendapatan="<?= $row['id_pendapatan'] ?>"
											data-bukti="<?= $row['bukti'] ?>"
											data-bukti_url="<?= base_url('assets/file/bukti/'.$row['bukti']) ?>">
											>
											<i class="fas fa-fw fa-edit"></i> Edit
										</a>

										<!-- DELETE -->
										<a href="#" 
											class="badge badge-danger btn-delete" data-id="<?= $row['id_piutang']; ?>" data-tgl_invoice="<?= $row['tgl_invoice']; ?>">
											<span class="icon text-white-50">
											<i class="fas fa-fw fa-trash"></i>
											</span> 
											<span class="text">Delete</span>
										</a>
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

<!-- Modal View -->
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">

		<div class="modal-header">
			<h5 class="modal-title">Preview Bukti Transaksi</h5>

			<div>
			<button type="button" class="btn btn-sm btn-secondary" id="toggleSize">
				<i class="fas fa-search-plus"></i> Actual Size
			</button>
			<button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
			</div>
		</div>

		<div class="modal-body p-0 text-center" style="height:80vh; overflow:auto;">

			<!-- IMAGE PREVIEW -->
			<img id="imgPreview" src="" style="display:none; max-width:100%; height:auto;" />

			<!-- PDF PREVIEW -->
			<iframe id="pdfPreview"
					src=""
					style="display:none; width:100%; height:100%; border:none;">
			</iframe>

		</div>

    </div>
  </div>
</div>

<!-- Modal Pembayaran Piutang  -->
<div class="modal fade" id="pembayaranPiutang" tabindex="-1" role="dialog" aria-labelledby="pembayaranPiutangLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
      			<h5 class="modal-title" id="pembayaranPiutangLabel">Pembayaran Piutang</h5>
    		</div>
    		<form action="<?= base_url('piutang/pembayaran'); ?>" method="post">
	    		<div class="modal-body">
	        		<div class="row">
						<div class="col-sm">
							<input type="hidden" class="form-control" id="id_piutang_update" name="id_piutang" placeholder="Nominal Piutang" required="">
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
								<label for="nominal" class="col-sm-3 col-form-label">Nominal Piutang</label>
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
									<textarea class="form-control" id="deskripsi" name="desk" rows="3" cols="1" placeholder="Deskripsi" required=""></textarea>
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

<script>
// Fungsi-fungsi Pembayaran Piutang
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
	    const id_piutang   = $(this).data('id_piutang');
	    const nilai   = $(this).data('nilai');
	    const dibayar = $(this).data('dibayar');

	    $("#id_piutang_update").val(id_piutang);

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
                title: 'Pembayaran Melebihi Tagihan',
                text: 'Jumlah pembayaran tidak boleh melebihi sisa tagihan!',
            });

            $(this).val('');
            return false;
        }

        // tetap format rupiah
        $(this).val(formatRupiah(inputBayar.toString()));
    });



	$('#pembayaranPiutang form').on('submit', function (e) {
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

<!-- Modal Detail Pembayaran Piutang -->
<div class="modal fade" id="modalDetailPelunasan" tabindex="-1">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">Detail Pembayaran Piutang</h5>
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
    let id_piutang = $(this).data('piutang-id');

    $('#modalDetailPelunasan').modal('show');
    $('#pelunasanBody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: "<?= base_url('piutang/detail_pelunasan'); ?>",
        type: "POST",
        data: { id_piutang: id_piutang },
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
									data-id="${row.id_piutang_bayar}"
									data-tgl="${row.tgl}"
									data-piutang="${id_piutang}">
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

// Hapus Pelunasan
$(document).on('click', '.btn-hapus-pelunasan', function () {
    let id_pelunasan = $(this).data('id');
    let tgl = $(this).data('tgl');
    let id_piutang   = $(this).data('piutang');

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
		                url: "<?= base_url('piutang/hapus_pelunasan'); ?>",
		                type: "POST",
		                dataType: "json",
		                data: { id_pelunasan: id_pelunasan },
		                success: function (res) {

			                	// console.log(res);

		                    if (res.status) {
		                        Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: 'Pelunasan berhasil dihapus'
								}).then(() => {
									// 🔁 redirect ke daftar piutang
									window.location.href = APP.base_url + 'piutang';
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

<!-- Modal Tambah Piutang  -->
<div class="modal fade" id="tambahPiutang" tabindex="-1" role="dialog" aria-labelledby="tambahPiutangLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
      			<h5 class="modal-title" id="tambahPiutangLabel">Tambah Transaksi Piutang</h5>
    		</div>
			<form action="<?= base_url('piutang/insert'); ?>" method="post" enctype="multipart/form-data">
	    		<div class="modal-body">
	        		<div class="row">
						<div class="col-sm">
							<div class="form-group row">
								<label for="no_ref" class="col-sm-3 col-form-label">No. Invoice</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="no_ref" name="no_ref" placeholder="No. Invoice" required="">
								</div>
							</div>

							<div class="form-group row">
			      				<label for="id_customer" class="col-sm-3 col-form-label">Customer</label>
								<div class="col-sm-9">
									<select name="id_customer" id="id_customer" class="form-control bootstrap-select" title="-- Pilih Customer --" data-width="100%" data-live-search="true" required="">
										<?php foreach($dt_customer as $row_) : ?>
											<option value="<?= $row_['id_customer']; ?>">(<?= $row_['kode']; ?>) <?= $row_['nama_customer']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label for="id_pendapatan" class="col-sm-3 col-form-label">Akun Pendapatan</label>
								<div class="col-sm-9">
									<select name="id_pendapatan" id="id_pendapatan" class="form-control bootstrap-select" title="-- Pilih Akun Pendapatan --" data-width="100%" data-live-search="true" required="">
										<?php foreach($dtAkunPendapatan as $row_) : ?>
											<option value="<?= $row_['id_pendapatan']; ?>">(<?= $row_['kode']; ?>) <?= $row_['nm']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label for="tgl_invoice" class="col-sm-3 col-form-label">Tanggal Invoice</label>
								<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tgl_invoice" name="tgl_invoice" data-input required="" value="<?= $now; ?>">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
									</div>
								</div>
							</div>	

							<div class="form-group row">
								<label for="jt_tempo" class="col-sm-3 col-form-label">Jatuh Tempo</label>
								<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="jt_tempo" name="jt_tempo" data-input required="" value="<?= $now; ?>">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
									</div>
								</div>
							</div>	

							<div class="form-group row">
								<label for="nilai" class="col-sm-3 col-form-label">Nominal Piutang</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nilai" name="nilai" placeholder="Nominal Piutang" required="">									
								</div>
							</div>
							
							<div class="form-group row">
								<label for="bukti" class="col-sm-3 col-form-label">
									Upload Bukti (Opsional)
								</label>
								<div class="col-sm-9">
									<div class="custom-file">
										<input type="file" class="custom-file-input col-sm-12" id="bukti" name="bukti">
										<label class="custom-file-label" for="bukti">Choose file</label>
									</div>
								</div>
								<br>
								<div class="col-sm-3">
								</div>	
								<label for="info" class="col-sm-9 col-form-label">
									<small class="text-primary">*format : pdf/jpg/jpeg/png. maksimal: 5mb</small>
								</label>
							</div>

			      			<div class="form-group row">
			      				<label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" cols="1" placeholder="Deskripsi" required=""></textarea>
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

<!-- Modal Edit Piutang  -->
 <div class="modal fade" id="editPiutang" tabindex="-1" role="dialog" aria-labelledby="editPiutangLabel" aria-hidden="true" >
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
      			<h5 class="modal-title" id="editTransaksiPendapatanLabel">Edit Transaksi Pendapatan</h5>
    		</div>

			<form action="<?= base_url('piutang/update'); ?>" method="post" enctype="multipart/form-data">
	    		<div class="modal-body">
	        		<div class="row">
						<div class="col-sm">
							<input type="hidden" class="form-control" id="id_piutang_edit" name="id_piutang" required>
							<div class="form-group row">
								<label for="no_ref_edit" class="col-sm-3 col-form-label">No. Invoice</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="no_ref_edit" name="no_ref" placeholder="No. Invoice" required="">
								</div>
							</div>

							<div class="form-group row">
			      				<label for="id_customer_edit" class="col-sm-3 col-form-label">Customer</label>
								<div class="col-sm-9">
									<select name="id_customer" id="id_customer_edit" class="form-control bootstrap-select" title="-- Pilih Customer --" data-width="100%" data-live-search="true" required="">
										<?php foreach($dt_customer as $row_) : ?>
											<option value="<?= $row_['id_customer']; ?>">(<?= $row_['kode']; ?>) <?= $row_['nama_customer']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label for="id_pendapatan_edit" class="col-sm-3 col-form-label">Akun Pendapatan</label>
								<div class="col-sm-9">
									<select name="id_pendapatan" id="id_pendapatan_edit" class="form-control bootstrap-select" title="-- Pilih Akun Pendapatan --" data-width="100%" data-live-search="true" required="">
										<?php foreach($dtAkunPendapatan as $row_) : ?>
											<option value="<?= $row_['id_pendapatan']; ?>">(<?= $row_['kode']; ?>) <?= $row_['nm']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label for="tgl_invoice_edit" class="col-sm-3 col-form-label">Tanggal Invoice</label>
								<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tgl_invoice_edit" name="tgl_invoice" data-input required="" value="<?= $now; ?>">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
									</div>
								</div>
							</div>	

							<div class="form-group row">
								<label for="jt_tempo_edit" class="col-sm-3 col-form-label">Jatuh Tempo</label>
								<div class="col-sm-9">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="jt_tempo_edit" name="jt_tempo" data-input required="" value="<?= $now; ?>">
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
									</div>
								</div>
							</div>	

							<div class="form-group row">
								<label for="nilai_edit" class="col-sm-3 col-form-label">Nominal Piutang</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="nilai_edit" name="nilai" placeholder="Nominal Piutang" required="">									
								</div>
							</div>
										      			
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">File Bukti</label>
								<div class="col-sm-9">

									<!-- AREA JIKA FILE SUDAH ADA -->
									<div id="file-exists-area" style="display:none;">
										<div class="mb-2">
											<button type="button" class="btn btn-info btn-sm" id="btn-preview-file">
												<i class="fas fa-eye"></i> Preview
											</button>

											<button type="button" class="btn btn-danger btn-sm" id="btn-delete-file">
												<i class="fas fa-trash"></i> Hapus File
											</button>
										</div>
										<small class="text-success">File bukti sudah diupload</small>
									</div>

									<!-- AREA UPLOAD FILE BARU -->
									<div id="file-upload-area">
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="bukti" name="bukti">
											<label class="custom-file-label" for="bukti">Choose file</label>
										</div>
										<small class="text-primary">Format: pdf/jpg/jpeg/png. Maks: 5MB</small>
									</div>

									<input type="hidden" name="old_bukti" id="old_bukti">
								</div>
							</div>

			      			<div class="form-group row">
			      				<label for="deskripsi_edit" class="col-sm-3 col-form-label">Deskripsi</label>
			    				<div class="col-sm-9">
						    		<textarea class="form-control" id="deskripsi_edit" name="deskripsi" rows="3" cols="1" placeholder="Deskripsi" required=""></textarea>
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



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){
	$('.bootstrap-select').selectpicker();

	
	$('.nilai').on('input', function () {
		let value = $(this).val();
		$(this).val(formatRupiah(value));
	});

	$('.hapusPiutang').on('click',function(){
		const id_piutang = $(this).data('id_piutang');

		$("#id_piutang_delete").val(id_piutang);
	});

});

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


// FUNGSI EDIT PIUTANG - TAMPILKAN DATA KE MODAL
$(document).on('click', '.editPiutang', function (e) {
    e.preventDefault(); // STOP PAGE RELOAD

    const id_piutang = $(this).data('id_piutang');	
	const no_ref = $(this).data('no_ref');
	const tgl_invoice = $(this).data('tgl_invoice');
	const jt_tempo = $(this).data('jt_tempo');
	const nilai = $(this).data('nilai');
	const deskripsi = $(this).data('deskripsi');
	const id_customer = $(this).data('id_customer');
	const id_pendapatan = $(this).data('id_pendapatan');
	const bukti = $(this).data('bukti');
	const bukti_url = $(this).data('bukti_url');

	$("#id_piutang_edit").val(id_piutang);
    $("#no_ref_edit").val(no_ref);
	$("#id_customer_edit").val(id_customer).trigger('change');
    $("#id_pendapatan_edit").val(id_pendapatan).trigger('change');
	$("#tgl_invoice_edit").val(tgl_invoice);
	$("#jt_tempo_edit").val(jt_tempo);
	$("#nilai_edit").val(formatRupiah(nilai.toString()));
    $("#deskripsi_edit").val(deskripsi);
    $("#old_bukti").val(bukti);
    $("#bukti_url").val(bukti_url);

	$("#old_bukti").val(bukti);

    if (bukti) {
        $("#file-exists-area").show();
        $("#file-upload-area").hide();
        $("#btn-preview-file").data("url", bukti_url);
        $("#btn-delete-file").data("file", bukti);
    } else {
        $("#file-exists-area").hide();
        $("#file-upload-area").show();
    }
	
	
});

$('#editPiutang').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $('#id_customer_update').selectpicker('refresh');
    $('#id_pendapatan_update').selectpicker('refresh');
});


// PREVIEW FILE from Edit
$(document).on('click', '#btn-preview-file', function () {
    let fileUrl = $(this).data('url');
    if (!fileUrl) return;

    let ext = fileUrl.split('.').pop().toLowerCase();
    let content = '';

    if (ext === 'pdf') {
        content = `<iframe src="${fileUrl}" width="100%" height="500px" style="border:none;"></iframe>`;
    } else {
        content = `<img src="${fileUrl}" class="img-fluid">`;
    }

    Swal.fire({
        title: 'Preview Bukti',
        html: content,
        width: 900,
        showCloseButton: true,
        showConfirmButton: false
    });
});

// DELETE FILE from Edit Modal
$(document).on('click', '#btn-delete-file', function () {

    let fileName = $(this).data('file');

    Swal.fire({
        title: 'Hapus file bukti?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus'
    }).then((result) => {
        if (result.isConfirmed) {

            $.post("<?= base_url('piutang/delete_bukti') ?>", { file: fileName }, function () {

                $("#file-exists-area").hide();
                $("#file-upload-area").show();
                $("#old_bukti").val('');

                Swal.fire('Berhasil', 'File dihapus', 'success');
            });

        }
    });
});


// Validasi Submit Add Piutang
$('#tambahPiutang form').on('submit', function (e) {
	e.preventDefault();

	let form = this;
	let tgl_invoice_text = document.getElementById('tgl_invoice').value;
	let tgl = convertTextToDate(tgl_invoice_text);

	if (!tgl) {
		Swal.fire('Error', 'Tanggal invoice wajib diisi', 'error');
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

			// jika lolos → submit
			form.submit();
		},
		error: function () {
			Swal.fire('Error', 'Gagal cek periode', 'error');
		}
	});
});

// Validasi Submit Edit Piutang
$('#editPiutang form').on('submit', function (e) {
	e.preventDefault();

	let form = this;
	let tgl_invoice_text = document.getElementById('tgl_invoice_edit').value;
	let tgl = convertTextToDate(tgl_invoice_text);
	
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

			// jika lolos → submit
			form.submit();
		},
		error: function () {
			Swal.fire('Error', 'Gagal cek periode', 'error');
		}
	});
});



// Validasi Hapus Piutang
$(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();

    let id  = $(this).data('id');
    let tgl = $(this).data('tgl_invoice');

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

            // 2️⃣ KONFIRMASI HAPUS
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data Transaksi Pendapatan yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // 3️⃣ REDIRECT DELETE
                    window.location.href = APP.base_url + 'transaksipendapatan/delete/' + id;
                }
            });

        },
        error: function () {
            Swal.fire('Error', 'Gagal cek periode', 'error');
        }
    });
});

</script>

<!-- Preview File from Index -->
<script>
let isActualSize = false;
let currentType = ""; // 'image' or 'pdf'

$(document).on('click', '.btn-bukti', function(e){
    e.preventDefault();

    let fileUrl = $(this).data('file');
    let ext = fileUrl.split('.').pop().toLowerCase();

    resetPreview();

    if (['jpg','jpeg','png'].includes(ext)) {
        currentType = 'image';
        $('#imgPreview').attr('src', fileUrl).show();
    } 
    else if (ext === 'pdf') {
        currentType = 'pdf';
        $('#pdfPreview').attr('src', fileUrl).show();
    }

    $('#modalBukti').modal('show');
});

$('#toggleSize').on('click', function(){
    if (currentType === 'image') {
        if (!isActualSize) {
            $('#imgPreview').css({
                'max-width': 'none',
                'width': 'auto'
            });
            $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
        } else {
            $('#imgPreview').css({
                'max-width': '100%',
                'width': '100%'
            });
            $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
        }
    }

    if (currentType === 'pdf') {
        if (!isActualSize) {
            $('#pdfPreview').css({
                'width': '1200px',
                'height': '1600px'
            });
            $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
        } else {
            $('#pdfPreview').css({
                'width': '100%',
                'height': '100%'
            });
            $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
        }
    }

    isActualSize = !isActualSize;
});

function resetPreview() {
    isActualSize = false;
    currentType = "";

    $('#imgPreview').hide().attr('src','').css({
        'max-width':'100%',
        'width':'100%'
    });

    $('#pdfPreview').hide().attr('src','').css({
        'width':'100%',
        'height':'100%'
    });

    $('#toggleSize').html('<i class="fas fa-search-plus"></i> Actual Size');
}

$('#modalBukti').on('hidden.bs.modal', function () {
    resetPreview();
});
</script>


<!-- Modal Print -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="printLabel"></h5>
			</div>
			<form action="<?= base_url('piutang/print'); ?>" method="post" target="_BLANK">
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


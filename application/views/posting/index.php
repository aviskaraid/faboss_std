<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
  		<h1 class="h3 text-gray-800"><?= $title;  ?></h1>
  	</div>
	
	<div class="row">
	  	<div class="col-sm-12">
			<?= $this->session->flashdata('message'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm">
	  		<div class="card border-bottom-primary shadow mb-4">
	             <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-9">
				  			<h6 class="m-0 font-weight-bold text-primary">Tabel Periode Akuntansi (Tahun : <?= $tahun; ?>)</h6>
				  		</div>
				  	</div>
	             </div>
	             <div class="card-body">
			  		<div class="table-responsive">
				  		<table class="table table-menu table-hover table-sm">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Bulan</th>
						      <th scope="col">Status</th>
						      <th scope="col">Tanggal Tutup Bulan</th>
							  <th scope="col">Posting</th>
						      <th scope="col">Aksi</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
						  	<?php foreach ($dtPeriodeDetail as $row) : ?>
						    <tr>
						      <th scope="row"><?= $i++; ?></th>
						      <td><?= getBulan($row['bln']); ?></td>
						      <td><?= ($row['stts'] == 0) ? '<span class="badge badge-success">Buka</span>' : '<span class="badge badge-danger">Tutup</span>'; ?></td>
						      <td><?= (!is_null($row['tgl_tutup'])) ? $row['tgl_tutup'] : 'Periode Bulan Belum Ditutup'; ?></td>
							  <td><?= ($row['posted'] == 0) ? '<span class="badge badge-secondary">Belum</span>' : '<span class="badge badge-info">Sudah</span>'; ?></td>
						      <td>
						      	<?php if($row['posted'] == 0) { ?>
							      	<a href="" class="badge badge-primary posting" data-toggle="modal" data-target="#postingBulanan"
							      		data-id_periode_tutup="<?= $row['id_periode_tutup']; ?>"
										data-bln ="<?= $row['bln']; ?>"
										data-tahun="<?= $tahun; ?>"
										data-stts="<?= $row['stts']; ?>">										
							      		<span class="icon text-white-50">
											<i class="fa-solid fa-check-to-slot"></i>
										</span>
										<span class="text">Posting</span>
							      	</a>

						      	<?php } else { ?>
						      		<a href="" class="badge badge-danger unposting" data-toggle="modal" data-target="#unpostingBulanan"
							      		data-id_periode_tutup="<?= $row['id_periode_tutup']; ?>"
										data-bln ="<?= $row['bln']; ?>"
										data-tahun="<?= $tahun; ?>"
										data-stts="<?= $row['stts']; ?>">
							      		<span class="icon text-white-50">
											<i class="fa-solid fa-inbox"></i>
										</span>
										<span class="text">Unposting</span>
							      	</a>
					      		<?php } ?>
						      </td>
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

<!-- Modal Posting -->
<div class="modal fade" id="postingBulanan" tabindex="-1" role="dialog" aria-labelledby="postingBulananLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="postingBulananLabel">Posting Bulanan</h5>
      	</div>
      	<form action="<?= base_url('posting/posting_bulanan'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_periode_tutup_posting" name="id_periode_tutup">
					<input type="hidden" class="form-control" id="bulan_posting" name="bulan_posting">
					<input type="hidden" class="form-control" id="tahun_posting" name="tahun_posting">
					<div class="row">
						<table class="m-3" width="100%">
							<tbody>
								<tr>
									<td>Periode Akuntansi</td>
									<td class="text-right">
										<span id="periode_bulan_posting"></span> - 
										<span id="periode_tahun_posting"><?= $tahun; ?></span>									
									</td>
								</tr>
								<tr>
									<td>Tanggal Posting</td>
									<td class="text-right"><?= tgl_indo(date('Y-m-d')); ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="row p-2 bg-light">
						<ul>
							<li>Seluruh Transaksi periode berjalan akan di-posting</li>
							<li>Transaksi yang sudah di-posting tidak bisa diubah atau dihapus</li>
							<li>Transaksi baru bulan berjalan masih bisa di-input</li>
							<li>Laporan keuangan untuk periode ini akan bisa diakses</li>
						</ul>
					</div>

					<div class="row mt-3">
						<div class="col-sm">
							<div class="card bg-warning text-dark">
							  <div class="card-body">
							    <b>Peringatan</b>
								<p id="warning_posting">
							    	Proses Posting hanya mengunci transaksi bulan berjalan saja. Untuk mengubah atau menghapus lakukan unposting
								</p>
							  </div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
	        <span class="icon text-white-50">
					  	<i class="fas fa-fw fa-window-close"></i>
					</span>
					<span class="text">Batal</span>
				</button>
	    	<button type="submit" class="btn btn-danger">
	    		<span class="icon text-white-50">
				  	<i class="fas fa-fw fa-save"></i>
					</span>
		    	<span class="text">Lakukan Posting</span>
		    </button>
	    </div>
	    </form>
    </div>
  </div>
</div>

<!-- Modal Unposting -->
<div class="modal fade" id="unpostingBulanan" tabindex="-1" role="dialog" aria-labelledby="unpostingBulananLabel" aria-hidden="true" >
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      	<div class="modal-header">
        	<h5 class="modal-title" id="unpostingBulananLabel">Unposting Bulanan</h5>
      	</div>
      	<form action="<?= base_url('posting/unposting_bulanan'); ?>" method="post">
      	<div class="modal-body">
	        <div class="row">
				<div class="col-sm">
					<input type="hidden" class="form-control" id="id_periode_tutup_unposting" name="id_periode_tutup">
					<input type="hidden" class="form-control" id="bulan_unposting" name="bulan_posting">
					<input type="hidden" class="form-control" id="tahun_unposting" name="tahun_posting">
					<div class="row">
						<table class="m-3" width="100%">
							<tbody>
								<tr>
									<td>Periode Akuntansi</td>
									<td class="text-right">
										<span id="periode_bulan_unposting"></span> - 
										<span id="periode_tahun_unposting"><?= $tahun; ?></span>									
									</td>
								</tr>
								<tr>
									<td>Tanggal Unposting</td>
									<td class="text-right"><?= tgl_indo(date('Y-m-d')); ?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="row p-2 bg-light">
						<ul>
							<li>Seluruh Transaksi periode berjalan akan di-unposting</li>
							<li>Transaksi yang sudah di-unposting dapat diubah atau dihapus</li>
							<li>Transaksi baru bulan berjalan tetap bisa di-input</li>
							<li>Laporan keuangan untuk periode ini tidak bisa diakses</li>
						</ul>
					</div>

					<div class="row mt-3">
						<div class="col-sm">
							<div class="card bg-warning text-dark">
							  <div class="card-body">
							    <b>Peringatan</b>
							    <p id="warning_unposting">
									Proses Unposting akan membuka kembali transaksi bulan berjalan.
								</p>
							  </div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	    <div class="modal-footer">
        <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">
	        <span class="icon text-white-50">
					  	<i class="fas fa-fw fa-window-close"></i>
					</span>
					<span class="text">Batal</span>
				</button>
	    	<button type="submit" class="btn btn-danger">
	    		<span class="icon text-white-50">
				  	<i class="fas fa-fw fa-save"></i>
					</span>
		    	<span class="text">Lakukan Unposting</span>
		    </button>
	    </div>
	    </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
function getBulan(bln) {
    const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // pastikan angka valid
    bln = parseInt(bln);
    if (isNaN(bln) || bln < 1 || bln > 12) {
        return '';
    }

    return bulan[bln - 1];
}

$(document).ready(function(){

	// Event handler untuk tombol posting
    $('.posting').on('click', function(e){
        e.preventDefault();

        const id_periode_tutup_posting = $(this).data('id_periode_tutup');
        const bln_posting   = $(this).data('bln');
        const tahun_posting = $(this).data('tahun');
        const target_posting = $(this).data('target');

        const bulan_posting = getBulan(bln_posting);

        // set modal awal
        $("#id_periode_tutup_posting").val(id_periode_tutup_posting);
        $('#bulan_posting').val(bln_posting);
        $('#tahun_posting').val(tahun_posting);
        $("#periode_bulan_posting").text(bulan_posting);

        // disable submit dulu
        $('#postingBulanan button[type="submit"]').prop('disabled', true);

        // set default message dulu (biar tidak kosong)
        $('#warning_posting').html('Memeriksa validasi periode...');

        // =========== AJAX VALIDASI POSTING =========== //
        $.ajax({
            url: "<?= base_url('posting/validasi_posting'); ?>",
            method: "POST",
            data: {
                bln: bln_posting,
                tahun: tahun_posting
            },
            dataType: "json",
            success: function(res){
				console.log('SUCCESS:', res);

                let html = '';

                // info jumlah jurnal
                html += 'Jumlah jurnal belum posting: <b>' + res.jumlah_jurnal + '</b><br>';

                if (!res.status) {
                    html += '<ul>';
                    res.messages.forEach(function(msg){
                        html += '<li>' + msg + '</li>';
                    });
                    html += '</ul>';
					html += '<br>Anda tidak dapat melanjutkan proses posting.';

                    // disable submit
                    $('#postingBulanan button[type="submit"]').prop('disabled', true);

                } else {
                    html += '<br>Semua validasi terpenuhi. Anda dapat melanjutkan proses posting.';

                    // enable submit
                    $('#postingBulanan button[type="submit"]').prop('disabled', false);
                }

                // inject ke <p>
                $('#warning_posting').html(html);

                // tampilkan modal
                $(target_posting).modal('show');
            },
            error: function(xhr, status, err){
			console.log('STATUS:', status);
			console.log('ERROR:', err);
			console.log('RESPONSE:', xhr.responseText);

			$('#warning_posting').html('Terjadi kesalahan saat validasi.');
			$('#postingBulanan button[type="submit"]').prop('disabled', true);
			$(target_posting).modal('show');

            }
        });
    });


	// Event handler untuk tombol unposting
	$('.unposting').on('click', function(e){
        e.preventDefault();

        const id_periode_tutup_unposting = $(this).data('id_periode_tutup');
        const bln_unposting   = $(this).data('bln');
        const tahun_unposting = $(this).data('tahun');
        const target_unposting = $(this).data('target');

        const bulan_unposting = getBulan(bln_unposting);

        // set modal awal
        $("#id_periode_tutup_unposting").val(id_periode_tutup_unposting);
        $('#bulan_unposting').val(bln_unposting);
        $('#tahun_unposting').val(tahun_unposting);
        $("#periode_bulan_unposting").text(bulan_unposting);

        // disable submit dulu
        $('#unpostingBulanan button[type="submit"]').prop('disabled', true);

        // set default message dulu (biar tidak kosong)
        $('#warning_unposting').html('Memeriksa validasi periode...');

        // AJAX validasi
        $.ajax({
            url: "<?= base_url('posting/validasi_unposting'); ?>",
            method: "POST",
            data: {
                bln: bln_unposting,
                tahun: tahun_unposting
            },
            dataType: "json",
            success: function(res){
				console.log('SUCCESS:', res);
                let html = '';

                // info jumlah jurnal
                html += 'Jumlah jurnal sudah posting: <b>' + res.jumlah_jurnal + '</b><br>';

                if (!res.status) {
                    html += '<ul>';
                    res2.messages.forEach(function(msg){
                        html += '<li>' + msg + '</li>';
                    });
                    html += '</ul>';
					html += '<br>Anda tidak dapat melanjutkan proses unposting.';

                    // disable submit
                    $('#postingBulanan button[type="submit"]').prop('disabled', true);

                } else {
                    html += '<br>Semua validasi terpenuhi. Anda dapat melanjutkan proses unposting.';

                    // enable submit
                    $('#unpostingBulanan button[type="submit"]').prop('disabled', false);
                }

                // inject ke <p>
                $('#warning_unposting').html(html);

                // tampilkan modal
                $(target_unposting).modal('show');
            },
            error: function(xhr, status, err){
			console.log('STATUS:', status);
			console.log('ERROR:', err);
			console.log('RESPONSE:', xhr.responseText);

			$('#warning_unposting').html('Terjadi kesalahan saat validasi.');
			$('#unpostingBulanan button[type="submit"]').prop('disabled', true);
			$(target_unposting).modal('show');

            }
        });
    });

});
</script>
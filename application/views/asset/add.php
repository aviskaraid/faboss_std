<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

	<div class="row justify-content-center">
		<div class="col-sm-6" id="add-data-account">
			<div class="card border-bottom-primary shadow mb-4">
        <div class="card-header py-3">
        	<div class="row">
        		<div class="col-sm">
	            	<h6 class="m-0 font-weight-bold text-primary mt-2">Form <?= $title;  ?></h6>
        		</div>
        	</div>
        </div>
		<div class="card-body">
			<form action="" method="post" enctype="multipart/form-data">
				<div class="form-group row">
					<label for="nama" class="col-sm-3 col-form-label">Nama Aset</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Aset" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="kode" class="col-sm-3 col-form-label">Kode Aset</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Aset" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="kode" class="col-sm-3 col-form-label">Lokasi Aset</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi Aset">
					</div>
				</div>

				<div class="form-group row">
					<label for="tgl" class="col-sm-3 col-form-label">Tanggal Perolehan</label>
					<div class="col-sm-9">
						<div class="datepicker-wrapper">
							<input type="text" class="form-control" id="tgl" name="tgl" data-input required="" value="<?= convertDbdateToDate($now); ?>">
							<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
						</div>	
					</div>
				</div>

				<div class="form-group row">
					<label for="umur" class="col-sm-3 col-form-label">Umur Manfaat (Tahun)</label>
					<div class="col-sm-9">
						<input type="number" class="form-control" id="umur" name="umur" placeholder="Umur Manfaat (Tahun)" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="nilai" class="col-sm-3 col-form-label">Harga Perolehan</label>
					<div class="col-sm-9">
						<input type="text" class="form-control nominal" id="nilai" name="nilai" placeholder="Harga Perolehan" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="id_biaya_peny" class="col-sm-3 col-form-label">Akun Biaya Penyusutan</label>
					<div class="col-sm-9">
						<select name="id_biaya_peny" id="id_biaya_peny" class="form-control bootstrap-select" title="-- Pilih Akun Biaya Penyusutan--" data-width="100%" data-live-search="true" required="">
							<?php foreach($dtAkun as $row_) : ?>
								<option value="<?= $row_['id_akun']; ?>">(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

			    <div class="form-group row">
			      	<label for="id_akum_peny" class="col-sm-3 col-form-label">Akun Akumulasi Penyusutan</label>
					<div class="col-sm-9">
						<select name="id_akum_peny" id="id_akum_peny" class="form-control bootstrap-select" title="-- Pilih Akun Akumulasi Penyusutan--" data-width="100%" data-live-search="true" required="">
							<?php foreach($dtAkun as $row_) : ?>
								<option value="<?= $row_['id_akun']; ?>">(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label class="col-sm-3 col-form-label">Gambar Aset</label>
					<div class="col-sm-9">

						<div id="file-upload-area">
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="gambar" name="gambar">
								<label class="custom-file-label" for="gambar">Choose file</label>
							</div>
							<small class="text-primary">Format: pdf/jpg/jpeg/png. Maks: 5MB</small>
						</div>
					</div>
				</div>								

				<hr class="mb-3 mt-3">

				<div class="form-group row">
					<div class="col-12 d-flex justify-content-end gap-2">

						<button type="submit" class="btn btn-primary mr-2">
							<i class="fas fa-fw fa-save mr-1"></i> Simpan
						</button>

						<a href="<?= base_url('asset'); ?>" class="btn btn-secondary">
							<i class="fas fa-fw fa-window-close mr-1"></i> Close
						</a>

					</div>
				</div>

			</form>

		</div>
	</div>
</div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
$(document).ready(function () {

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
});
</script>


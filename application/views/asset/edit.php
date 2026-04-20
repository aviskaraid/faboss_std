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
				<input type="hidden" name="nonaktif" id="nonaktifInput" value="0">

				<div class="form-group row">
					<label for="nama" class="col-sm-3 col-form-label">Nama Aset</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Aset" value="<?= $dtById['nama']; ?>" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="kode" class="col-sm-3 col-form-label">Kode Aset</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Aset" value="<?= $dtById['kode']; ?>" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="lokasi" class="col-sm-3 col-form-label">Lokasi Aset</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi Aset" value="<?= $dtById['lokasi']; ?>">
					</div>
				</div>

				<div class="form-group row">
					<label for="tgl" class="col-sm-3 col-form-label">Tanggal Perolehan</label>
					<div class="col-sm-9">
						<div class="datepicker-wrapper">
							<input type="text" class="form-control" id="tgl" name="tgl" data-input required="" value="<?= convertDbdateToDate($dtById['tgl']); ?>">
							<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
						</div>		
					</div>
				</div>

				<div class="form-group row">
					<label for="umur" class="col-sm-3 col-form-label">Umur Manfaat (Tahun)</label>
					<div class="col-sm-9">
						<input type="number" class="form-control" id="umur" name="umur" placeholder="Umur Manfaat (Tahun)" value="<?= $dtById['umur']; ?>" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="nilai" class="col-sm-3 col-form-label">Harga Perolehan</label>
					<div class="col-sm-9">
						<input type="text" class="form-control nominal" id="nilai" name="nilai" placeholder="Harga Perolehan" value="Rp. <?= number_format($dtById['nilai'],0,',','.'); ?>" required="">
					</div>
				</div>

				<div class="form-group row">
					<label for="id_biaya_peny" class="col-sm-3 col-form-label">Akun Biaya Penyusutan</label>
					<div class="col-sm-9">
						<select name="id_biaya_peny" id="id_biaya_peny" class="form-control bootstrap-select" title="-- Pilih Akun Biaya Penyusutan--" data-width="100%" data-live-search="true" required="">
							<?php foreach($dtAkun as $row_) : ?>
								<option value="<?= $row_['id_akun']; ?>" <?= ($row_['id_akun'] == $dtById['id_biaya_peny']) ? "selected" : null; ?> >(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

			    <div class="form-group row">
			      	<label for="id_akum_peny" class="col-sm-3 col-form-label">Akun Akumulasi Penyusutan</label>
					<div class="col-sm-9">
						<select name="id_akum_peny" id="id_akum_peny" class="form-control bootstrap-select" title="-- Pilih Akun Akumulasi Penyusutan--" data-width="100%" data-live-search="true" required="">
							<?php foreach($dtAkun as $row_) : ?>
								<option value="<?= $row_['id_akun']; ?>" <?= ($row_['id_akun'] == $dtById['id_akum_peny']) ? "selected" : null; ?> >(<?= $row_['noakun']; ?>) <?= $row_['nama']; ?></option>
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

						<?php if (!empty($dtById['gambar'])): ?>
							<small class="text-success d-flex align-items-center mt-2">

								<!-- PREVIEW BUTTON -->
								<button type="button"
										class="btn btn-info btn-sm mr-2 btn-preview-gambar"
										data-file="<?= base_url('assets/file/aset/'. $dtById['gambar']); ?>">
									<i class="fas fa-eye"></i> View
								</button>

								<!-- DELETE BUTTON -->
								
								<button type="button"
									class="btn btn-danger btn-sm mr-2 btn-delete-gambar"
									data-file="<?= $dtById['gambar']; ?>">
									<i class="fas fa-trash"></i> Hapus
								</button>

								<!-- FILE NAME -->
								<?= htmlspecialchars($dtById['gambar']); ?>

							</small>
						<?php else: ?>
							<small class="text-muted d-block mt-2">Belum ada file bukti</small>
						<?php endif; ?>
					</div>
				</div>	

				<hr class="mb-3 mt-3">

				<div class="form-group row">
					<div class="col-12 d-flex justify-content-end gap-2">
						<button type="button" id="btnNonaktif" class="btn btn-danger mr-2">
							<i class="fa-regular fa-share-from-square"></i> Non Aktif
						</button>

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

<!-- Modal Preview -->
<div class="modal fade" id="modalPreviewGambar" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Preview Gambar Aset</h5>
        <div>
          <button type="button" class="btn btn-sm btn-secondary" id="toggleSize">
            <i class="fas fa-search-plus"></i> Actual Size
          </button>
          <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
        </div>
      </div>

      <div class="modal-body p-0 text-center" style="height:80vh; overflow:auto;">
        <img id="imgPreview" style="display:none; max-width:100%; height:auto;" />
        <iframe id="pdfPreview" style="display:none; width:100%; height:100%; border:none;"></iframe>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ===============================
   PREVIEW AND DELETE FILE
================================ */

// Show selected filename in custom file input
$('.custom-file-input').on('change', function () {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});

// PREVIEW
$(document).on("click", ".btn-preview-gambar", function () {
    let fileUrl = $(this).data("file");
    let ext = fileUrl.split('.').pop().toLowerCase();

    $("#imgPreview, #pdfPreview").hide();

    if (["jpg","jpeg","png","gif","webp"].includes(ext)) {
        $("#imgPreview").attr("src", fileUrl).show().css("max-width","100%");
    } else if (ext === "pdf") {
        $("#pdfPreview").attr("src", fileUrl).show();
    }

    $("#modalPreviewGambar").modal("show");
});

// TOGGLE SIZE
let actualSize = false;
$("#toggleSize").click(function () {
    actualSize = !actualSize;

    if (actualSize) {
        $("#imgPreview").css("max-width", "none");
        $(this).html('<i class="fas fa-search-minus"></i> Fit to Screen');
    } else {
        $("#imgPreview").css("max-width", "100%");
        $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
    }
});

// DELETE FILE
$(document).on('click', '.btn-delete-gambar', function () {

    let file = $(this).data('file');
    let btn  = $(this);

    console.log("File to delete:", file);

    Swal.fire({
        title: 'Hapus file gambar aset?',
        text: "File yang sudah dihapus tidak bisa dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "<?= base_url('asset/delete_gambar'); ?>",
                type: "POST",
                data: { file: file },
                headers: { "X-Requested-With": "XMLHttpRequest" }, // IMPORTANT
                success: function () {

                    Swal.fire('Terhapus!', 'File berhasil dihapus.', 'success');

                    // Remove file UI
                    btn.closest('small').remove();
                    btn.closest('.col-sm-9').append(
                        '<small class="text-muted d-block mt-2">Belum ada file bukti</small>'
                    );
                },
                error: function (xhr) {
                    console.log("AJAX ERROR:", xhr.status, xhr.responseText);
                    Swal.fire('Error', 'Gagal menghapus file', 'error');
                }
            });

        }
    });
});

</script>

<script>
document.getElementById('btnNonaktif').addEventListener('click', function () {
    Swal.fire({
        title: 'Nonaktifkan aset?',
        text: "Data aset akan dipindahkan ke daftar aset nonaktif.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Nonaktifkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Set hidden input so controller knows this is nonaktif action
            document.getElementById('nonaktifInput').value = "1";

            // Submit the form
            this.closest('form').submit();
        }
    });
});
</script>


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


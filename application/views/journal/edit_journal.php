<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

	<?= $this->session->flashdata('message'); ?>
	
	<div class="row" id="tambah-data-transaksi">
	    <div class="col-lg">
	      	<div class="card border-bottom-primary shadow mb-4">
	         	<div class="card-header py-3">
	          		<div class="row">
	            		<div class="col-sm-9">
	              			<h6 class="m-0 font-weight-bold text-primary mt-2">Form Edit Transaksi</h6>
	            		</div>
		  				<div class="col-sm-3 text-right">
							<a href="<?= base_url('journal'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-fw fa-times"></i> Batal</a>
		  				</div>
	          		</div>
	        	</div>
	        	<div class="card-body">
	          		<div class="col-lg">
						<?= form_open_multipart(''); ?>
	            		<input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
		            	<div class="row">
							<div class="col-sm">
								<div class="form-group row">
		                  			<input type="hidden" class="form-control" id="id" name="id" value="<?= $dt_byID['id_jurnal']; ?>">
		                		</div>
								<div class="form-group row">
									<label for="tglTransaksi" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
						    		<div class="col-sm-9">
										<div class="datepicker-wrapper">
											<input type="text" class="form-control" id="tglTransaksi" name="tglTransaksi" value="<?= convertDbdateToDate($dt_byID['tgl']); ?>" data-input required>
											<i class="fa fa-calendar datepicker-icon" data-toggle></i>	
										</div>	
									</div>
								</div>
						      	<div class="form-group row">
						      		<label for="noTransaksi" class="col-sm-3 col-form-label">Nomor Transaksi</label>
									<div class="col-sm-9">
										<input type="hidden" class="form-control" id="no_urut" name="no_urut" placeholder="No. Urut" value="<?= $no_urut; ?>" required>
										<input type="text" class="form-control" placeholder="No. Transaksi" value="<?= $no_trans; ?>" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $dt_byID['keterangan']; ?>" autocomplete="off">
									</div>
								</div>
								

								<div class="form-group row">
									<label for="keterangan" class="col-sm-3 col-form-label">
										Upload Bukti (Opsional) <br>
										<small class="text-primary">*format : pdf/jpg/jpeg/png. maksimal: 5mb</small>
									</label>

									<div class="col-sm-9">

										<div class="custom-file">
											<input type="file" class="custom-file-input col-sm-12" id="bukti" name="bukti">
											<label class="custom-file-label" for="bukti">Choose file</label>
										</div>

										<?php if (!empty($dt_byID['bukti'])): ?>
											<small class="text-success d-flex align-items-center mt-2">

												<!-- PREVIEW BUTTON -->
												<button type="button"
														class="btn btn-info btn-sm mr-2 btn-preview-bukti"
														data-file="<?= base_url('assets/file/bukti/'. $dt_byID['bukti']); ?>">
													<i class="fas fa-eye"></i> Lihat File
												</button>

												<!-- DELETE BUTTON -->
												
												<button type="button"
        											class="btn btn-danger btn-sm mr-2 btn-delete-bukti"
        											data-file="<?= $dt_byID['bukti']; ?>">
													<i class="fas fa-trash"></i> Hapus File
												</button>

												<!-- FILE NAME -->
												<?= htmlspecialchars($dt_byID['bukti']); ?>

											</small>
										<?php else: ?>
											<small class="text-muted d-block mt-2">Belum ada file bukti</small>
										<?php endif; ?>

									</div>
								</div>


							</div>
						</div>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-hover table-sm table-bordered">
									<thead>
										<tr>
											<th scope="col">Nomor Akun</th>
											<th scope="col">Nama Akun</th>
											<th scope="col">Nominal</th>
											<th scope="col">Perkiraan</th>
											<th class="text-center"><a href="#" class="text-success addsection" id="tambah-tab-input"><i class="fa fa-fw fa-plus"></i></a></th>
										</tr>
									</thead>
									<tbody id="insert-form">
										<?php 
										$total_debit = 0;
										$total_kredit = 0;
										foreach ($dt_detail as $row) : ?>	
										  	<tr id="clone">
										  		<td>
													<select type="text" class="form-control bootstrap-select idAkun" id="idAkun" name="idAkun[]" title="Nomor Akun" data-container="body" data-live-search="true" required>
										  				<?php foreach ($akun as $ak) : ?>
															<option value="<?= $ak['id_akun']; ?>" <?= ($row['id_akun'] == $ak['id_akun']) ? "selected" : null; ?>><?= $ak['noakun']; ?></option>
														<?php endforeach; ?>
										  			</select>
										  		</td>
										  		<td>
										  			<select type="text" class="form-control bootstrap-select namaAkunJurnal" id="namaAkunJurnal" name="namaAkun[]" data-container="body" title="Pilih Nama Akun" data-live-search="true" required>
										  				<?php foreach ($akun as $ak) : ?>
															<option value="<?= $ak['nama']; ?>" <?= ($row['id_akun'] == $ak['id_akun']) ? "selected" : null; ?>><?= $ak['nama']; ?></option>
														<?php endforeach; ?>
										  			</select>
										  		</td>
										  		<td>
										  			<input type="text" class="form-control nominalJurnal" id="nilai_debit" name="nilai_debit[]" value="<?= ($row['nilai_debit'] != "") ? 'Rp. '.number_format($row['nilai_debit'],0,',','.') : null; ?>" placeholder="Nominal" autocomplete="off">
										  		</td>
										  		<td>
										  			<input type="text" class="form-control nominalJurnal" id="nilai_kredit" name="nilai_kredit[]" value="<?= ($row['nilai_kredit'] != "") ? 'Rp. '.number_format($row['nilai_kredit'],0,',','.') : null; ?>" placeholder="Nominal" autocomplete="off">
										  		</td>
										  		<td class="text-center">
										  			<a href="#" id="hapus-tab-input" class="text-danger mt-5">
										  				<i class="fa fa-fw fa-times"></i>
										  			</a>
										  		</td>
										  	</tr>
										<?php 

										  	$total_debit += ($row['nilai_debit'] != "") ? $row['nilai_debit'] : 0;
										  	$total_kredit += ($row['nilai_kredit'] != "") ? $row['nilai_kredit'] : 0;

										endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" class="text-right">Total</td>
											<td><span id="total_debit"><?= 'Rp. '.number_format($total_debit,0,',','.'); ?></span></td>
											<td><span id="total_kredit"><?= 'Rp. '.number_format($total_kredit,0,',','.'); ?></span></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
			          	<hr class="mb-3 mt-3">
			          	<div class="row">
			            	<div class="col-sm text-right">
			                	<button type="submit" class="btn btn-primary btn-sm">Simpan</button>
			            	</div>
			          	</div>
			        </form>
		        </div>
		    </div>

	    </div>
	 </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal Preview -->
<div class="modal fade" id="modalPreviewBukti" tabindex="-1">
  <div class="modal-dialog modal-xl">
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
$(document).on("click", ".btn-preview-bukti", function () {
    let fileUrl = $(this).data("file");
    let ext = fileUrl.split('.').pop().toLowerCase();

    $("#imgPreview, #pdfPreview").hide();

    if (["jpg","jpeg","png","gif","webp"].includes(ext)) {
        $("#imgPreview").attr("src", fileUrl).show().css("max-width","100%");
    } else if (ext === "pdf") {
        $("#pdfPreview").attr("src", fileUrl).show();
    }

    $("#modalPreviewBukti").modal("show");
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
$(document).on('click', '.btn-delete-bukti', function () {

    let file = $(this).data('file');
    let btn  = $(this);

    console.log("File to delete:", file);

    Swal.fire({
        title: 'Hapus file bukti?',
        text: "File yang sudah dihapus tidak bisa dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "<?= base_url('journal/delete_bukti'); ?>",
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
/* ===============================
   HELPER FORMAT & PARSE RUPIAH
================================ */
	
function parseRupiah(value) {
    if (!value) return 0;
    return parseInt(value.replace(/[^0-9]/g, '')) || 0;
}

function formatRupiahJS(angka) {
    return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatRupiah(angka, prefix){
    let number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   	 = number_string.split(','),
        sisa     	 = split[0].length % 3,
        rupiah     	 = split[0].substr(0, sisa),
        ribuan     	 = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
}

/* ===============================
   HITUNG TOTAL DEBIT & KREDIT
================================ */

/* ===============================
   HITUNG TOTAL DEBIT & KREDIT
================================ */
function hitungTotal() {
    let totalDebit  = 0;
    let totalKredit = 0;

    $("input[name='nilai_debit[]']").each(function () {
        totalDebit += parseRupiah($(this).val());
    });

    $("input[name='nilai_kredit[]']").each(function () {
        totalKredit += parseRupiah($(this).val());
    });

    $("#total_debit").text(formatRupiahJS(totalDebit));
    $("#total_kredit").text(formatRupiahJS(totalKredit));
    
    return {
        debit: totalDebit,
        kredit: totalKredit
    };
}

$('form').on('submit', function (e) {
    e.preventDefault();

    let form = this;
	let tgl_text = document.getElementById('tglTransaksi').value;
	let tgl = convertTextToDate(tgl_text);
    let total = hitungTotal();

    // 1️⃣ Cek periode dulu
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

            // 2️⃣ Cek balance jurnal
            if (total.debit !== total.kredit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Jurnal Tidak Balance',
                    text: 'Total Debit dan Kredit harus sama!'
                });
                return;
            }

            // 3️⃣ Konfirmasi simpan
            Swal.fire({
                icon: 'question',
                title: 'Simpan Jurnal?',
                text: 'Pastikan data sudah benar',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit final
                }
            });
        }
    });
});


/* ===============================
   FORMAT INPUT & HITUNG TOTAL
================================ */
$(document).on("keyup", "input[name='nilai_debit[]'], input[name='nilai_kredit[]']", function () {
    let rupiah = $(this).val();
    $(this).val(formatRupiah(rupiah, 'Rp. '));
    hitungTotal();
});

/* ===============================
   TAMBAH BARIS TRANSAKSI
================================ */
let parent = $("#tambah-data-transaksi");
let form   = parent.find("form");
let table  = form.find("table");
let index  = 1;

let akun       = <?= json_encode($akun); ?>;
let perkiraan  = <?= json_encode($perkiraan); ?>;

$("#tambah-tab-input").on("click", function(e){
    e.preventDefault();

    let optionAkun = '';
    let optionNamaAkun = '';

    $.each(akun, function(i, row){
        optionAkun += `<option value="${row.id_akun}">${row.noakun}</option>`;
        optionNamaAkun += `<option value="${row.nama}">${row.nama}</option>`;
    });

    let el = `
    <tr id="data${index}">
        <td>
            <select class="form-control bootstrap-select idAkun" id="idAkun" name="idAkun[]" data-container="body" title="Pilih Nomor Akun" data-live-search="true"  required>
                ${optionAkun}
            </select>
        </td>
        <td>
            <select class="form-control bootstrap-select namaAkunJurnal" id="namaAkun" name="namaAkun[]" data-container="body" title="Pilih Nama Akun" data-live-search="true"  required>
                ${optionNamaAkun}
            </select>
        </td>
        <td>
            <input type="text" class="form-control" id="nilai_debit" name="nilai_debit[]" placeholder="Nominal" autocomplete="off">
        </td>
        <td>
            <input type="text" class="form-control" id="nilai_kredit" name="nilai_kredit[]" placeholder="Nominal" autocomplete="off">
        </td>
        <td class="text-center">
            <a href="#" class="text-danger hapus-tab-input" data-id="${index}">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </td>
    </tr>`;

    $("#insert-form").append(el);
    $(".bootstrap-select").selectpicker('refresh');

    index++;

	
});

/* ===============================
   HAPUS BARIS
================================ */
$(document).on("click", ".hapus-tab-input", function(e){
    e.preventDefault();
    let id = $(this).data("id");
    $("#data" + id).remove();
    hitungTotal();
});

/* ===============================
   INIT AWAL
================================ */
$(document).ready(function(){
    $(".bootstrap-select").selectpicker();
    hitungTotal();
});


/* ===============================
NOMOR AKUN → NAMA AKUN
================================ */
$(document).on('change', '.idAkun', function () {
	let value = $(this).val();
	let row   = $(this).closest('tr');

	if (!value) return;

	$.ajax({
		url: "<?= base_url('journal/auto_name'); ?>",
		method: "POST",
		data: { id: value },
		dataType: "json",
		success: function (data) {
			if (!data) {
				row.find('.idAkun').val('').selectpicker('refresh');
				row.find('.namaAkunJurnal').val('').selectpicker('refresh');
			} else {
				row.find(".namaAkunJurnal")
					.val(data.nama)
					.selectpicker('refresh');
			}
		}
	});
});

/* ===============================
NAMA AKUN → NOMOR AKUN
================================ */
$(document).on('change', '.namaAkunJurnal', function () {
	let value = $(this).val();
	let row   = $(this).closest('tr');

	if (!value) return;

	$.ajax({
		url: "<?= base_url('journal/auto_account_id'); ?>",
		method: "POST",
		data: { name: value },
		dataType: "json",
		success: function (data) {
			if (!data) {
				row.find('.idAkun').val('').selectpicker('refresh');
				row.find('.namaAkunJurnal').val('').selectpicker('refresh');
			} else {
				row.find(".idAkun")
					.val(data)
					.selectpicker('refresh');
			}
		}
	});
});
</script>

<!-- Begin Page Content -->
<div class="container-fluid">

  	<!-- Page Heading -->
  	<h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>
  	
	<?= $this->session->flashdata('message'); ?>

	<div class="row" id="tambah-data-transaksi">
		<div class="col-sm">
			<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	            	<div class="row">
	            		<div class="col-sm-6">
				            <h6 class="m-0 font-weight-bold text-primary mt-2">Form Tambah Data Transaksi</h6>
	            		</div>
				  		<div class="col-sm-6 text-right">
							<a href="<?= base_url('journal'); ?>" class="btn btn-sm btn-danger"><i class="fas fa-fw fa-times"></i> Batal</a>
				  		</div>
	            	</div>
	            </div>
				<div class="card-body">
				<?= form_open_multipart('journal/add'); ?>
					<input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
					<div class="row">
						<div class="col-sm">
							<div class="form-group row">
								<label for="tglTransaksi" class="col-sm-2 col-form-label">Tanggal Transaksi *</label>
				    			<div class="col-sm-10">
									<div class="datepicker-wrapper">
										<input type="text" class="form-control" id="tglTransaksi" name="tglTransaksi" value="<?= convertDbdateToDate($now); ?>" data-input required>
										<i class="fa fa-calendar datepicker-icon" data-toggle></i>		
									</div>	
								</div>
							</div>
					      	<div class="form-group row">
					      		<label for="noTransaksi" class="col-sm-2 col-form-label">Nomor Transaksi *</label>
				    			<div class="col-sm-10">
							    	<input type="hidden" class="form-control" id="no_urut" name="no_urut" placeholder="No. Urut" value="<?= $no_urut; ?>" required>
							    	<input type="text" class="form-control" placeholder="No. Transaksi" value="<?= $no_trans; ?>" readonly>
							    </div>
							</div>
							<div class="form-group row">
								<label for="keterangan" class="col-sm-2 col-form-label">Keterangan *</label>
				    			<div class="col-sm-10">
							    	<input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" autocomplete="off" required>
							    </div>
							</div>
							<div class="form-group row">
					    		<label for="keterangan" class="col-sm-2 col-form-label">
					    			Upload Bukti (Opsional) <br>
									<small class="text-primary">*format : pdf/jpg/jpeg/png. maksimal: 5mb</small>
					    		</label>
					    		<div class="col-sm-10">
					    			<div class="custom-file">
									  <input type="file" class="custom-file-input col-sm-12" id="bukti" name="bukti">
									  <label class="custom-file-label" for="bukti">Choose file</label>
									</div>
					    		</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="table-responsive">
							<table class="table table-sm table-hover table-bordered">
							  <thead>
							    <tr>
							      <th scope="col">Nomor Akun</th>
							      <th scope="col">Nama Akun</th>
							      <th scope="col">Debit</th>
							      <th scope="col">Kredit</th>
							      <th class="text-center"><a href="#" class="text-success addsection" id="tambah-tab-input"><i class="fa fa-fw fa-plus"></i></a></th>
							    </tr>
							  </thead>
							  <tbody id="insert-form">
							  	<tr>
							  		<td>
										<select type="text" class="form-control bootstrap-select idAkun" id="idAkun" name="idAkun[]" title="Nomor Akun" data-container="body" data-live-search="true" required>
							  				<?php foreach ($akun as $ak) : ?>
												<option value="<?= $ak['id_akun']; ?>"><?= $ak['noakun']; ?></option>
											<?php endforeach; ?>
							  			</select>
							  		</td>
							  		<td>
							  			<select type="text" class="form-control bootstrap-select namaAkunJurnal" id="namaAkunJurnal" name="namaAkun[]" data-container="body" title="Pilih Nama Akun" data-live-search="true" required>
							  				<?php foreach ($akun as $ak) : ?>
												<option value="<?= $ak['nama']; ?>"><?= $ak['nama']; ?></option>
											<?php endforeach; ?>
							  			</select>
							  		</td>
							  		<td>
							  			<input type="text" class="form-control nominalJurnal" id="nilai_debit" name="nilai_debit[]" placeholder="Nominal" autocomplete="off">
							  		</td>
							  		<td>
							  			<input type="text" class="form-control nominalJurnal" id="nilai_kredit" name="nilai_kredit[]" placeholder="Nominal" autocomplete="off">
							  		</td>
							  		<td class="text-center"></td>
							  	</tr>
							  	<tr>
							  		<td>
								        <select type="text" class="form-control bootstrap-select idAkun" id="idAkun" name="idAkun[]" title="Nomor Akun"  data-container="body" data-live-search="true" required>
							  				<?php foreach ($akun as $ak) : ?>
												<option value="<?= $ak['id_akun']; ?>"><?= $ak['noakun']; ?></option>
											<?php endforeach; ?>
							  			</select>
							  		</td>
							  		<td>
							  			<select type="text" class="form-control bootstrap-select namaAkunJurnal" id="namaAkunJurnal" name="namaAkun[]" data-container="body" title="Pilih Nama Akun" data-live-search="true" required>
							  				<?php foreach ($akun as $ak) : ?>
												<option value="<?= $ak['nama']; ?>"><?= $ak['nama']; ?></option>
											<?php endforeach; ?>
							  			</select>
							  		</td>
							  		<td>
							  			<input type="text" class="form-control nominalJurnal" id="nilai_debit" name="nilai_debit[]" placeholder="Nominal" autocomplete="off">
							  		</td>
							  		<td>
							  			<input type="text" class="form-control nominalJurnal" id="nilai_kredit" name="nilai_kredit[]" placeholder="Nominal" autocomplete="off">
							  		</td>
							  		<td class="text-center"></td>
							  	</tr>
							  </tbody>
							  <tfoot>
								<tr>
									<td colspan="2" class="text-right">Total</td>
									<td><span id="total_debit">Rp. 0</span></td>
									<td><span id="total_kredit">Rp. 0</span></td>
								</tr>
							  </tfoot>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-sm text-right">
							<button type="submit" class="btn btn-primary btn-mds simpan">
								<span class="icon text-white-50">
						          <i class="fas fa-fw fa-save"></i>
						        </span>
						        <span class="text">Simpan</span>
						    </button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

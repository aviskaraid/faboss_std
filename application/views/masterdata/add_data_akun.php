<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

	<div class="row justify-content-center">
		<div class="col-sm-6" id="add-data-account">
			<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	            	<div class="row">
	            		<div class="col-sm-9">
				            <h6 class="m-0 font-weight-bold text-primary mt-2">Form Tambah Data Akun</h6>
	            		</div>
	            	</div>
	            </div>
				<div class="card-body">
						<div class="col-sm">
							<form action="<?= base_url('masterdata/add'); ?>" method="post">
							    <input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
								<div class="form-group ">
								    <select name="tipeAkun" id="tipeAkun" class="form-control bootstrap-select" title="Pilih Kelompok akun" data-container="body">
										<?php foreach ($accountgroup as $ag) : ?>
											<option kelakun="<?= $ag['kel_akun']; ?>" value="<?= $ag['id_kelompok_akun']; ?>"><?= $ag['nama_kel_akun']; ?></option>
										<?php endforeach; ?>
									</select>
									<?= form_error('tipeAkun', '<small class="text-danger pl-3">', '</small>'); ?>
								</div>
								<div class="form-group ">
									<div class="input-group">
										<div class="input-group-prepend">
								          <div class="input-group-text" id="kel_akun">No Akun</div>
								        </div>
										<input type="text" class="form-control" id="noAkun" name="noAkun" placeholder="Nomor Akun">
										<?= form_error('noAkun', '<small class="text-danger pl-3">', '</small>'); ?>
									</div>
								</div>
								<div class="form-group ">
								    <input type="text" class="form-control" id="namaAkun" name="namaAkun" placeholder="Nama Akun">
								    <?= form_error('namaAkun', '<small class="text-danger pl-3">', '</small>'); ?>
								</div>
								<div class="form-group ">
									<select id="perkiraan" name="perkiraan" class="form-control bootstrap-select" title="Perkiraan akun" data-container="body">
										<?php foreach ($perkiraan as $p) : ?>
											<option value="<?= $p['id_perkiraan']; ?>"><?= $p['nama']; ?></option>
										<?php endforeach; ?>
									</select>
									<?= form_error('perkiraan', '<small class="text-danger pl-3">', '</small>'); ?>
								</div>
								<div class="form-group ">
								    <input type="text" class="form-control" id="saldoAwal" name="saldoAwal" placeholder="Saldo Awal">
								    <?= form_error('saldoAwal', '<small class="text-danger pl-3">', '</small>'); ?>
								<hr class="mb-3 mt-3">
								<div class="row">
									<div class="col-sm text-right">
								    	
                      <button type="submit" class="btn btn-primary">
                        <span class="icon text-white-50">
                          <i class="fas fa-fw fa-save"></i>
                        </span>
                        <span class="text">Simpan</span>
                      </button>
                      <a href="<?= base_url('masterdata'); ?>" class="btn btn-secondary">
                        <span class="icon text-white-50">
                          <i class="fas fa-fw fa-window-close"></i>
                        </span>
                        <span class="text">Close</span>
                      </a>

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

<script>
	$(document).on("keyup", "#saldoAwal", function() {
		//Menangani Input otomatis Format Ribuan
		let rupiah = $(this).val();

		$(this).val(formatRupiah(rupiah, 'Rp. '));

		/* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
			let number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}
    });

	$("#tipeAkun").change(function(){
        var kelAkun = $("#tipeAkun option:selected").attr('kelakun');
        var idkelAkun = $("#tipeAkun option:selected").val();
        $("#kel_akun").html(kelAkun);
        $("#kelAkun").val(kelAkun);

        $.ajax({
		    url: "<?= base_url('master_data/account_group'); ?>",
		    method : "POST",
		    data : {idkelAkun: idkelAkun},
		    async : true,
		    dataType : 'json',
		    success: function(data){
		    	let noakun;
		    	const no_akun = data[0].no_akun;
		    	if( (data[0].kel_akun == 110) || (data[0].kel_akun == 120) || (data[0].kel_akun == 210) || (data[0].kel_akun == 220) || (data[0].kel_akun == 310) || (data[0].kel_akun == 410) || (data[0].kel_akun == 420) ){
			    	noakun = parseInt(no_akun) + 10;
			    } else if( (data[0].kel_akun == 510) || (data[0].kel_akun == 520) ){
			    	noakun = parseInt(no_akun) + 1;
			    }
			    // if ()
			    if(noakun == undefined) {
			    	alert("Tidak ada nomor akun. Silahkan input nomor akun dengan benar!.")
			    }

		    	// console.log(noakun);
		        $("#noAkun").val(noakun);
		    }
		});
    });
</script>

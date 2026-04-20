<!--Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card border-bottom-primary shadow mb-4">
         <div class="card-header py-3">
          <div class="row">
            <div class="col-sm-9">
              <h6 class="m-0 font-weight-bold text-primary mt-2">Form Edit Data Akun</h6>
            </div>
          </div>
        </div>
        <div class="card-body">

          <div class="col-lg">
            <form action="" method="post">
                <input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
                <div class="form-group row">
                  <input type="hidden" class="form-control" id="id" name="id" value="<?= $masterData['id_akun']; ?>" readonly>
                </div>
                <div class="form-group">
                  <select name="tipeAkun" id="tipeAkun" class="form-control bootstrap-select">
                    <?php foreach ($kelompok_akun as $ka) : ?>
                      <?php $selected = in_array($masterData['id_kelompok_akun'], $ka) ? " selected " : null;?>
                      <option value="<?= $ka['id_kelompok_akun']; ?>" <?= $selected?> ><?= $ka['nama_kel_akun']; ?>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text" id="kel_akun"><?= $masterData['kel_akun']; ?></div>
                      </div>
                    <input type="text" class="form-control" id="noAkun" name="noAkun" value="<?= $masterData['noakun']; ?>">
                    <?= form_error('noAkun', '<small class="text-danger pl-3 mt-1">', '</small>'); ?>
                  </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="namaAkun" name="namaAkun" value="<?= $masterData['nama']; ?>">
                </div>
                <div class="form-group">
                  <select id="perkiraan" name="perkiraan" class="form-control bootstrap-select" >
                      <?php foreach ($perkiraan as $p) : ?>
                        <?php $selected = in_array($masterData['id_perkiraan'], $p) ? " selected " : null;?>
                          <option value="<?= $p['id_perkiraan']; ?>" <?=$selected?> ><?= $p['nama']; ?></option>
                        <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="saldoAwal" name="saldoAwal" value="<?= 'Rp. '.number_format($masterData['saldo_awal'],0,',','.') ?>">
                    <?= form_error('saldoAwal', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
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
  
 <!-- /.container-fluid -->

<script>
  $(document).on("keyup", "#saldoAwal", function() {
    //Menangani Input otomatis Format Ribuan
    let rupiah = $(this).val();

    $(this).val(formatRupiah(rupiah, 'Rp. '));

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
      let number_string = angka.replace(/[^,\d]/g, '').toString(),
      split       = number_string.split(','),
      sisa        = split[0].length % 3,
      rupiah        = split[0].substr(0, sisa),
      ribuan        = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
    });
</script>
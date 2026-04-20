<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-bottom-primary shadow mb-4">
         <div class="card-header py-3">
          <div class="row">
            <div class="col-sm">
              <h6 class="m-0 font-weight-bold text-primary mt-2">Form Edit Profile Perusahaan</h6>
            </div>
          </div>
        </div>
        <div class="card-body">

          <div class="col-lg">
            <?= form_open_multipart(''); ?>
                <div class="form-group">
                  <input type="text" class="form-control" id="idUser" name="idUser" value="<?= $user['id_user']; ?>" hidden>
                  <input type="hidden" class="form-control" id="id" name="id" value="<?= $profile['id_profile']; ?>" readonly>
                </div>
                <div class="form-group">
                	<label for="description">Nama Perusahaan</label>
        				    <input type="text" class="form-control" id="name" name="name" value="<?= $profile['name']; ?>">
        				</div>
                <div class="form-group">
			      				<label for="alamat">Alamat</label>                    
                      <textarea class="form-control" id="alamat" name="alamat" rows="3" cols="1" placeholder="Alamat Lengkap"><?= $profile['alamat']; ?></textarea>
                </div>
                <div class="form-group">
                	<label for="description">Nomor Telepon</label>
        				    <input type="text" class="form-control" id="telp" name="telp" value="<?= $profile['telp']; ?>">
        				</div>
                <div class="form-group">
                	<label for="description">NPWP Perusahaan</label>
        				    <input type="text" class="form-control" id="npwp" name="npwp" value="<?= $profile['npwp']; ?>">
        				</div>
        				<div class="form-group">
        					<label for="description">Deskripsi</label>
            				<textarea class="form-control" name="content" id="ckeditor" required><?= $profile['deskripsi']; ?></textarea>
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
  
</div>
<!-- /.container-fluid -->
<script type="text/javascript">
    $(function () {
        CKEDITOR.replace('ckeditor',{
            filebrowserBrowseUrl: '<?php echo base_url('assets/vendor/ckfinder/ckfinder.html');?>',
            filebrowserImageBrowseUrl: '<?php echo base_url('assets/vendor/ckfinder/ckfinder.html?type=Images');?>',
            filebrowserFlashBrowseUrl: '<?php echo base_url('assets/vendor/ckfinder/ckfinder.html?type=Flash');?>',
            filebrowserUploadUrl: '<?php echo base_url('assets/vendor/ckfinder/connector?command=QuickUpload&type=Files');?>',
            filebrowserImageUploadUrl: '<?php echo base_url('assets/vendor/ckfinder/connector?command=QuickUpload&type=Images');?>',
            filebrowserFlashUploadUrl: '<?php echo base_url('assets/vendor/ckfinder/connector?command=QuickUpload&type=Flash');?>'
        });
    });
</script>




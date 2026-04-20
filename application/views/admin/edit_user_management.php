<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card border-bottom-primary shadow mb-4">
         <div class="card-header py-3">
          <div class="row">
            <div class="col-sm">
              <h6 class="m-0 font-weight-bold text-primary mt-2">Form Edit User</h6>
            </div>
          </div>
        </div>
        <div class="card-body">

          <div class="col-lg">
            <?= form_open_multipart(''); ?>
                <div class="form-group">
                  <input type="hidden" class="form-control" id="id" name="id" value="<?= $user['id_user']; ?>" readonly>
                </div>
                <div class="form-group">
				    <input type="text" class="form-control" id="name" name="name" value="<?= $user['name']; ?>">
				</div>
				<div class="form-group">
					<select name="role_id" id="role_id" class="form-control">
						<?php foreach ($user_role as $ur) : ?>
		  					<?php $selected = in_array($user['id_role'], $ur) ? " selected " : null;?>
							<option value="<?= $ur['id_role']; ?>" <?=$selected?> ><?= $ur['role']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
				    <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>">
				</div>
				<div class="form-group">
				    <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal" value="<?= $date; ?>" readonly>
				</div>
				<div class="form-group">
			    	<div class="row">
			    		<div class="col-sm-3">
			    			<img src="<?= base_url('assets/img/profile/') . $user['image'];  ?>" class="img-thumbnail">
			    		</div>
			    		<div class="col-sm-9">
			    			<div class="custom-file">
							  <input type="file" class="custom-file-input" id="image" name="image">
							  <label class="custom-file-label" for="image">Choose file</label>
							</div>
			    		</div>
			    	</div>
				 </div>
				<div class="form-group">
				    <div class="form-check">
					  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
					  <label class="form-check-label" for="is_active">
					    Active?
					  </label>
					</div>
				</div>
                <hr class="mb-3 mt-3">
                <div class="row">
                  <div class="col-sm text-right">
                        <button type="submit" class="btn btn-primary">
                            <span class="icon text-white-50">
                              <i class="fas fa-fw fa-save"></i>
                            </span>
                          Simpan
                        </button>
                      <a href="<?= base_url('admin/user_management'); ?>" class="btn btn-secondary">
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
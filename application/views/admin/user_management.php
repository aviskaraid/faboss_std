<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>

      <div>						        
		    <a href="<?= base_url('admin/access_user') ?>" class="btn btn-sm btn-secondary shadow-sm mb-2">
		        <i class="fas fa-fw fa-cog fa-sm text-white-50"></i>
		        <span class="text">Setting Role Akses User</span>
		    </a>
		    <a href="" class="btn btn-sm btn-primary shadow-sm mb-2" data-toggle="modal" data-target="#addDataUser" >
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Data User</span>
		    </a>
      </div>
	</div>
  	
  	<div class="row clearfix">
	  	<div class="col-lg-12">

			<?= $this->session->flashdata('message'); ?>
			
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
	  		<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	              <h6 class="m-0 font-weight-bold text-primary">Tabel Data User</h6>
	            </div>
				<div class="card-body">
					<div class="table-responsive">
				  		<table class="table table-user-management table-striped table-bordered table-sm" id="tabel-data">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Nama</th>
						      <th scope="col">Posisi</th>
						      <th scope="col">Email</th>
						      <th scope="col">Gambar</th>
						      <th scope="col">Tanggal</th>
						      <th scope="col">Status</th>
						      <th scope="col">Action</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
				  			<?php foreach ($user_all as $data) : ?>
						    <tr>
						      <th scope="row"><?= $i++; ?></th>
						      <td><?= $data['name']; ?></td>
						      <td width="150px"><?php 
						      	if ($data['id_role'] == 1) {
						      		echo "Administrator";
						      	} else if ($data['id_role'] == 2){
						      		echo "Bagian Akuntansi";
						      	} else if ($data['id_role'] == 3){
						      		echo "Bagian Keuangan";
						      	} else {
						      		echo "Posisi Tidak ada";
						      	}
						      		?></td>
						      <td><?= $data['email']; ?></td>
						      <td><?= $data['image']; ?></td>
						      <td width="150px"><?= date('d F Y', $data['date_created']); ?></td>
						      <td><?php 
						      	if ($data['is_active'] == 1) {
						      		echo "Aktif";
						      	} else {
						      		echo "Non-Aktif";
						      	}
						      		?></td>
						      <td class="text-center" width="140px">
						      		<a href="<?= base_url(); ?>admin/edit_user_management/<?= $data['id_user']; ?>" class="badge badge-primary">
						      			<span class="icon text-white-50">
										  	<i class="fas fa-fw fa-edit"></i>
										</span>
										<span class="text">Edit</span>
									</a>
						      		<a href="<?= base_url(); ?>admin/delete_user_management/<?= $data['id_user']; ?>" class="badge badge-danger" onclick="return confirm('yakin?');">
						      			<span class="icon text-white-50">
										  	<i class="fas fa-fw fa-trash"></i>
										</span>
										<span class="text">Delete</span>
						      		</a>
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

<!-- Modal -->
<div class="modal fade" id="addDataUser" tabindex="-1" role="dialog" aria-labelledby="addDataUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDataUserLabel">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('admin/user_management'); ?>" method="post">
	      <div class="modal-body">
	        <div class="form-group">
			    <input type="text" class="form-control" id="name" name="name" placeholder="Nama">
			</div>
			<div class="form-group">
				<select name="role_id" id="role_id" class="form-control">
					<option value="">Select Posisi</option>
					<?php foreach($user_role as $role) : ?>
						<option value="<?= $role['id_role']; ?>"><?= $role['role']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
			    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
			</div>
			<div class="form-group">
			    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
			</div>
			<div class="form-group">
			    <input type="date" class="form-control" id="date" name="date" placeholder="Tanggal" value="<?= $date; ?>" readonly>
			</div>
			<div class="form-group">
			    <div class="form-check">
				  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
				  <label class="form-check-label" for="is_active">
				    Active?
				  </label>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Add</button>
	      </div>
      </form>
    </div>
  </div>
</div>
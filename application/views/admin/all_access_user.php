<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	</div>
  	
  	<div class="row clearfix">
	  	<div class="col-lg-12">

			<?= $this->session->flashdata('message'); ?>
			
		</div>
	</div>

	<div class="row">
		<div class="col-sm">
	  		<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	            	<div class="row">
		              <div class="col-sm-9">
			              <h6 class="m-0 font-weight-bold text-primary">Tabel Access User</h6>
		              </div>
		              <div class="col-sm-3 text-right">
		                <a href="<?= base_url('admin/user_management'); ?>" class="btn btn-sm btn-secondary mb-2"><i class="fas fa-fw fa-arrow-left"></i> Kembali</a>
		              </div>
	              </div>
	            </div>

	            <?= form_open_multipart(''); ?>
				<div class="card-body">

	                <div class="row">
	                  <div class="col-sm">
							<div class="table-responsive">
						  		<table class="table table-user-management table-striped table-bordered table-sm">
								  <thead>
								    <tr>
								      <th scope="col">#</th>
								      <th scope="col">Nama</th>
								      <th scope="col">Url</th>
								      <?php foreach($user_role as $row) { ?>
									      <th scope="col"><?= $row['role']; ?></th>
									  <?php } ?>
								    </tr>
								  </thead>
								  <tbody>
								  	<?php $i = 1; ?>
						  			<?php foreach ($dt_all_menu as $data) : ?>

						  			<tr>
								      <th scope="row"><?= $i++; ?></th>
								      <td colspan="5"><?= $data['nama']; ?></td>
									</tr>
						  			<?php foreach ($data['dt_sub_menu'] as $row) : 
						  				?>
								    <tr>
								      <th scope="row"></th>
								      <td><?= $row['nama']; ?></td>
								      <td><?= $row['url']; ?></td>
								      <?php foreach($row['dt_access'] as $row_stts) :?>
								      <td>
									    <div class="form-check">
									      <input type="checkbox" class="form-check-input" <?= check_access($row_stts['id_role'], $row['id_sub_menu']); ?> data-id_role="<?= $row_stts['id_role']; ?>" data-id_sub_menu="<?= $row['id_sub_menu']; ?>">
										</div>
								      </td>
								    <?php endforeach; ?>


								    </tr>

								    <?php endforeach; ?>
								    <?php endforeach; ?>
								  </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

	            </form>
			</div>
		</div>
	</div>
	

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<script>
    $('.form-check-input').on('click', function() {
        const id_role = $(this).data('id_role');
        const id_sub_menu = $(this).data('id_sub_menu');

        console.log(id_role);

        $.ajax({
            url: "<?= base_url('admin/changeAccess'); ?>",
            type: 'post',
            data: {
                roleId: id_role,
                subMenuId: id_sub_menu
            },
            success: function() {
                  console.log('Akses berhasil disimpan!');
                }
            });

    });
</script>

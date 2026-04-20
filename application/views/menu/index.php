<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
  		<h1 class="h3 text-gray-800"><?= $title;  ?></h1>
  	</div>
	
	<div class="row">
	  	<div class="col-sm-12">
			<?= $this->session->flashdata('message'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<a href="" class="btn btn-primary btn-sm mb-3 shadow-sm" data-toggle="modal" data-target="#newMenuModal">
	  			<i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
			    <span class="text">Add New Menu</span>
		  	</a>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-7">
	  		<div class="card border-bottom-primary shadow mb-4">
	             <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-9">
				  			<h6 class="m-0 font-weight-bold text-primary">Tabel Menu</h6>
				  		</div>
				  	</div>
	             </div>
	             <div class="card-body">
			  		<div class="table-responsive">
				  		<table class="table table-menu table-hover table-sm">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Menu</th>
						      <th scope="col">Action</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
						  	<?php foreach ($menu as $m) : ?>
						    <tr>
						      <th scope="row" width="40px"><?= $i++; ?></th>
						      <td><?= $m['menu']; ?></td>
						      <td width="150px">
						      		<a href="<?= base_url(); ?>menu/submenu/<?= $m['id']; ?>" class="badge badge-success">detail</a>
						      		
						      		<a href="" data-toggle="modal" data-target="#editMenuModal" data-id="<?= $m['id']; ?>" data-menu="<?= $m['menu']; ?>" class="btn btn-success badge badge-primary editMenu text-whiteu">Edit</a>
						      		<a href="<?= base_url(); ?>menu/deletemenu/<?= $m['id']; ?>" class="badge badge-danger" onclick="return confirm('yakin?');">Delete</a>
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
<div class="modal fade" id="newMenuModal" tabindex="-1" role="dialog" aria-labelledby="newMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newMenuModalLabel">Add New Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu'); ?>" method="post">
	      <div class="modal-body">
	        <div class="form-group">
			    <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name">
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

<!-- Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMenuModalLabel">Add New Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('menu/editmenu'); ?>" method="post">
	      <div class="modal-body">
	      	<div class="form-group">
					    <input type="hidden" class="form-control" id="id" name="id">
					</div>
	        <div class="form-group">
					    <input type="text" class="form-control" id="name-menu" name="menu">
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Edit</button>
	      </div>
      </form>
    </div>
  </div>
</div>

<script>
$('.editMenu').on('click',function(){
    var id = $(this).data('id');
    var menu = $(this).data('menu');

    $("#id").val(id);
    $("#name-menu").val(menu);
});
</script>



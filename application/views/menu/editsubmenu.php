<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>
  
	<div class="row">
		<div class="col-lg-8">
			<form action="" method="post">
		      	<div class="form-group">
				    <input type="hidden" class="form-control" id="id" name="id" value="<?= $subMenuById['id']; ?>">
				    <input type="hidden" class="form-control" id="menu_id" name="menu_id" value="<?= $subMenuById['menu_id']; ?>">
				</div>
	        	<div class="form-group row">
				    <label for="title" class="col-sm-2 col-form-label">Title</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="title" name="title" value="<?= $subMenuById['title']; ?>">
				    </div>
				 </div>
				 <div class="form-group row">
				    <label for="url" class="col-sm-2 col-form-label">Url</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="url" name="url" value="<?= $subMenuById['url']; ?>">
				    </div>
				 </div>
				<div class="form-group row">
					<div class="col-sm-10">
					    <div class="form-check">
						  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
						  <label class="form-check-label" for="is_active">
						    Active?
						  </label>
						</div>
					</div>
				</div>
			    <div class="form-group row justify-content-end">
					<div class="col-sm-10">
			        	<button type="submit" class="btn btn-primary">Simpan</button>
			      	</div>
				</div>
	      </form>
		</div>
	</div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

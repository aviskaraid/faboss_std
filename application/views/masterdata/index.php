<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <?php if (is_user()) : ?>
	    	<div class="">
			    <a href="<?= base_url(); ?>masterdata/export_data_akun" class="btn btn-sm btn-secondary shadow-sm">
			        <i class="fas fa-fw fa-download fa-sm text-white-50"></i>
			        <span class="text">Ekspor EXCEL</span>
			    </a>
			    <a href="<?= base_url(); ?>masterdata/add" class="btn btn-sm btn-primary shadow-sm">
			        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
			        <span class="text">Tambah Data Akun</span>
			    </a>
		    </div>
	    <?php endif; ?>
	</div>

  	<div class="row clearfix">
	  	<div class="col-lg-12">
			
			<?= form_error('dataakun', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

			<?= $this->session->flashdata('message'); ?>
			
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
	  		<div class="card border-bottom-primary shadow mb-4">
	            <div class="card-header py-3">
	              <h6 class="m-0 font-weight-bold text-primary">Tabel Data Akun</h6>
	            </div>
				<div class="card-body">
					<div class="table-responsive">
				  		<table class="table table-dataAkun table-striped table-bordered table-sm" id="tabel-data">
						  <thead class="text-center">
						  	<tr>
						      <th scope="col" rowspan="2">#</th>
						      <th scope="col" rowspan="2">Kelompok Akun</th>
						      <th scope="col" rowspan="2" width="20%">No Akun</th>
						      <th scope="col" rowspan="2">Nama Akun</th>
						      <th scope="col" colspan="2">Saldo Awal</th>
						      <?php if (is_user()) : ?>
							      <th scope="col" rowspan="2">Action</th>
							  <?php endif; ?>
						  	</tr>
						    <tr>
						      <th scope="col">Debit</th>
						      <th scope="col">Kredit</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php $i = 1; ?>
						  	<?php $total_debit = 0; ?>
						  	<?php $total_kredit = 0; ?>
				  			<?php foreach ($masterdata as $md) : ?>
						    <tr>
						      <th width="10px" scope="row"><?= $i++; ?></th>
						      <td width="20%"><?= $md['nama_kel_akun']; ?></td>
						      <td width="10%" class="text-center"><?= $md['noakun']; ?></td>
						      <td ><?= $md['nama']; ?></td>
								<?php 
						      	if ($md['id_perkiraan'] == 1) {
						      		?>
						      		<td width="15%" align="right"><?= 'Rp. '.number_format($md['saldo_awal'],0,',','.') ?></td>
						      		<td></td>
								    <?php $total_debit += $md['saldo_awal']; ?>
						      		<?php  
						      	} else {
						      		?>
						      		<td></td>
						      		<td width="15%" align="right"><?= 'Rp. '.number_format($md['saldo_awal'],0,',','.') ?></td>
								    <?php $total_kredit += $md['saldo_awal']; ?>
						      		<?php  
						      	}
						      		?>
						      <?php if (is_user()) : ?>
							      <td class="text-center" width="15%">
							      		<a href="<?= base_url(); ?>masterdata/edit/<?= $md['id_akun']; ?>" class="badge badge-primary">
							      			<span class="icon text-white-50">
											  	<i class="fas fa-fw fa-edit"></i>
											</span>
											<span class="text">Edit</span>
										</a>
							      		<a href="javascript:void(0);" 
										class="badge badge-danger btn-delete" 
										data-url="<?= base_url('masterdata/delete/'.$md['id_akun']); ?>">
											<span class="icon text-white-50">
												<i class="fas fa-fw fa-trash"></i>
											</span>
											<span class="text">Delete</span>
										</a>

							      </td>
							  <?php endif; ?>
						    </tr>
						    <?php endforeach; ?>
						  </tbody>
						  <tfoot>
						  	<tr>
							  	<th colspan="4" class="text-right">Total</th>
							  	<th><?= 'Rp. '.number_format($total_debit,0,',','.') ?></th>
							  	<th><?= 'Rp. '.number_format($total_kredit,0,',','.') ?></th>
							  	<?php if (is_user()) : ?>
							  	<th></th>
							  	<?php endif; ?>
						  	</tr>
						  </tfoot>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const url = this.getAttribute('data-url');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

});
</script>


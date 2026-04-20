<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
		<div class="col-md-3 d-flex align-items-end">
			
		</div>	
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
	        		<h6 class="m-0 font-weight-bold text-primary">Tabel <?= $title;  ?></h6>
	      		</div>
				<div class="card-body">
					<div class="table-responsive">
				  		<table class="table table-dataAkun table-striped table-bordered table-sm" id="tabel-data">
						  	<thead class="text-center">
								<tr>
									<th scope="col">#</th>
									<th scope="col">Nama Aset</th>
									<th scope="col">Kode</th>
									<th scope="col">Lokasi</th>
									<th scope="col">Tanggal Perolehan</th>
									<th scope="col">Masa Manfaat Berakhir</th>
									<th scope="col">Umur Manfaat (Tahun)</th>
									<th scope="col">Harga Perolehan</th>
									<th scope="col">Tanggal Nonaktif</th>
									<?php if (is_user()) : ?>
										<th scope="col">Action</th>
									<?php endif; ?>
								</tr>
						  	</thead>
						  	<tbody>
								<?php $i = 1; 
								$perolehan = 0;
								foreach ($dtAll as $row) : ?>
								<tr>
									<th scope="row"><?= $i++; ?></th>
									<td><?= $row['nama']; ?></td>
									<td><?= $row['kode']; ?></td>
									<td><?= $row['lokasi']; ?></td>
									<td><?= convertDbdateToDate($row['tgl']); ?></td>
									<td><?= convertDbdateToDate(date('Y-m-d', strtotime('-1 days', strtotime('+'.$row['umur'].' months', strtotime($row['tgl']))))); ?></td>
									<td class="text-center"><?= $row['umur']." (". ($row['umur']*12) ." Bulan)"; ?></td>
									<td class="text-right"><?= number_format($row['nilai'], 0, ',', '.'); ?></td>
									<td><?= convertDbdateToDate($row['tgl_nonaktif']); ?></td>
									<?php if (is_user()) : ?>
										<td style="white-space: nowrap;" class="text-center">
											<?php
												$file_name   = $row['gambar'];
												$file_path   = FCPATH . 'assets/file/aset/' . $file_name;
												$file_url    = base_url('assets/file/aset/' . $file_name);
												$file_exists = (!empty($file_name) && file_exists($file_path));
											?>

											<!-- BUTTON VIEW BUKTI -->
											<?php if ($file_exists) : ?>
												<a href="#" 
												class="badge badge-info btn-gambar"
												data-file="<?= $file_url; ?>"
												data-toggle="tooltip"
												title="Lihat Gambar Aset">
													<i class="fas fa-file-alt"></i> Gambar
												</a>
											<?php else : ?>
												<span class="badge badge-secondary"
													style="opacity:0.6; cursor:not-allowed;"
													title="File gambar aset tidak tersedia">
													<i class="fas fa-file-alt"></i> Gambar
												</span>
											<?php endif; ?>											
										</td>
									<?php endif; ?>
								</tr>
								<?php 
									$perolehan += $row['nilai'];
									endforeach; 
								?>
							</tbody>
						  	<tfoot>
								<tr>
									<td colspan="7">Jumlah</td>
									<td class="text-right"><?= number_format($perolehan, 0, ',', '.'); ?></td>
									<td></td>
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

<!-- Modal View -->
<div class="modal fade" id="modalGambar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Preview Gambar Aset</h5>

        <div>
          <button type="button" class="btn btn-sm btn-secondary" id="toggleSize">
            <i class="fas fa-search-plus"></i> Actual Size
          </button>
          <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
        </div>
      </div>

      <div class="modal-body p-0 text-center" style="height:80vh; overflow:auto;">

        <!-- IMAGE PREVIEW -->
        <img id="imgPreview" src="" style="display:none; max-width:100%; height:auto;" />

        <!-- PDF PREVIEW -->
        <iframe id="pdfPreview"
                src=""
                style="display:none; width:100%; height:100%; border:none;">
        </iframe>

      </div>

    </div>
  </div>
</div>

<!-- Preview File from Index -->
<script>
let isActualSize = false;
let currentType = ""; // 'image' or 'pdf'

$(document).on('click', '.btn-gambar', function(e){
    e.preventDefault();

    let fileUrl = $(this).data('file');
    let ext = fileUrl.split('.').pop().toLowerCase();

    resetPreview();

    if (['jpg','jpeg','png'].includes(ext)) {
        currentType = 'image';
        $('#imgPreview').attr('src', fileUrl).show();
    } 
    else if (ext === 'pdf') {
        currentType = 'pdf';
        $('#pdfPreview').attr('src', fileUrl).show();
    }

    $('#modalGambar').modal('show');
});

$('#toggleSize').on('click', function(){
    if (currentType === 'image') {
        if (!isActualSize) {
            $('#imgPreview').css({
                'max-width': 'none',
                'width': 'auto'
            });
            $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
        } else {
            $('#imgPreview').css({
                'max-width': '100%',
                'width': '100%'
            });
            $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
        }
    }

    if (currentType === 'pdf') {
        if (!isActualSize) {
            $('#pdfPreview').css({
                'width': '1200px',
                'height': '1600px'
            });
            $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
        } else {
            $('#pdfPreview').css({
                'width': '100%',
                'height': '100%'
            });
            $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
        }
    }

    isActualSize = !isActualSize;
});

function resetPreview() {
    isActualSize = false;
    currentType = "";

    $('#imgPreview').hide().attr('src','').css({
        'max-width':'100%',
        'width':'100%'
    });

    $('#pdfPreview').hide().attr('src','').css({
        'width':'100%',
        'height':'100%'
    });

    $('#toggleSize').html('<i class="fas fa-search-plus"></i> Actual Size');
}

$('#modalBukti').on('hidden.bs.modal', function () {
    resetPreview();
});
</script>



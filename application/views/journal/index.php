<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	    <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title;  ?></h1>
	    <div class="justify-content-end">
		    <a href="" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#cetakJournal">
				<i class="fas fa-fw fa-print fa-sm text-white-50"></i>
				<span class="text">Cetak</span>
			</a>
	    <?php if (is_user()) : ?>
		    <a href="<?= base_url(); ?>journal/add/" class="btn btn-sm btn-primary shadow-sm">
		        <i class="fas fa-fw fa-plus fa-sm text-white-50"></i>
		        <span class="text">Tambah Transaksi</span>
		    </a>
		<?php endif; ?>
		</div>
	 </div>
  <!-- Page Heading -->
  

  	<div class="row">
	  	<div class="col-sm-12">
			<?= form_error('dataakun', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

			<?= $this->session->flashdata('message'); ?>
		</div>
  	</div>
	
	<div class="row">
		<div class="col-sm-12">
	  		<div class="card border-bottom-primary shadow mb-4">
	             <div class="card-header py-3">
			  		<div class="row">
				  		<div class="col-sm-9">
				  			<h6 class="m-0 font-weight-bold text-primary mb-3">Tabel Jurnal</h6>
				  		</div>
				  	</div>
	             </div>
				 <div class="card-body">

				 	<form action="" method="post">
	          			<div class="form-row align-items-end">

							<!-- Tanggal Awal -->
							<div class="col-md-3">
								<label for="tglAwal">Tanggal Awal</label>
								<div class="datepicker-wrapper">
									<input type="text" class="form-control" id="tglAwal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input required>
									<i class="fa fa-calendar datepicker-icon" data-toggle></i>
								</div>	
							</div>

	            			<!-- Tanggal Akhir -->
	            			<div class="col-md-3">
	              				<label for="tglAkhir">Tanggal Akhir</label>
								<div class="datepicker-wrapper">
	              					<input type="text" class="form-control" id="tglAkhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input required>
									<i class="fa fa-calendar datepicker-icon" data-toggle></i>
								</div>	
	            			</div>

							<!-- Button -->
							<div class="col-md-3 d-flex align-items-end">
								<button type="button" id="btnFilter" class="btn btn-success mr-2">
									<i class="fas fa-fw fa-filter"></i> Filter
								</button>
							</div>

	          			</div>
	        		</form>

					<hr> 
				 	<div class="table-responsive">
				  		<table class="table table-journal table-hover table-sm table-bordered" id="table-serverside">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col" class="text-center">Tanggal</th>
						      <th scope="col" class="text-center">No. Transaksi</th>
						      <th scope="col">Keterangan</th>
						      <th scope="col" class="text-center">Nominal</th>
						      <th scope="col" width="20%">Action</th>
						    </tr>
						  </thead>
						  <tbody>
							
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
<div class="modal fade" id="cetakJournal" tabindex="-1" role="dialog" aria-labelledby="cetakJournalLabel" aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cetakJournalLabel"></h5>
      </div>
      <form action="<?= base_url('journal/printgeneraljournal'); ?>" method="post" target="_BLANK">
      <div class="modal-body">
			<div class="form-group row">
				<label for="tglTransaksi" class="col-sm-4 col-form-label">Tanggal Awal</label>
				<div class="col-sm-8">
					<div class="datepicker-wrapper">
						<input type="text" class="form-control" id="tgl_awal" name="tglAwal" value="<?= convertDbdateToDate($tglAwal); ?>" data-input>
						<i class="fa fa-calendar datepicker-icon" data-toggle></i>
					</div>	
				</div>
			</div>
	      	<div class="form-group row">
	      		<label for="noTransaksi" class="col-sm-4 col-form-label">Tanggal Akhir</label>
				<div class="col-sm-8">
					<div class="datepicker-wrapper">
			    		<input type="text" class="form-control" id="tgl_akhir" name="tglAkhir" value="<?= convertDbdateToDate($tglAkhir); ?>" data-input>
						<i class="fa fa-calendar datepicker-icon" data-toggle></i>
					</div>	
			    </div>
			</div>
		</div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-success">Cetak</button>
	     </div>
    </div>
	</form>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

var table;
$(document).ready(function () {

    table = $('#table-serverside').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('journal/ajax_list_journal'); ?>",
            type: "POST",
            data: function (d) {
                d.tgl_awal  = $('#tglAwal').val();
                d.tgl_akhir = $('#tglAkhir').val();
            }
        },
        columnDefs: [
            {
                targets: [0],
                orderable: false
            }
        ]
    });
});

$('#btnFilter').on('click', function () {
    table.ajax.reload(null, false);
});

</script>

<script>
$(document).on('click', '.btn-edit', function (e) {
    e.preventDefault();
	let id = $(this).data('id');
	let tgl = $(this).data('tgl');
	let posted = $(this).data('posted');

	if (posted == 1) {
		Swal.fire('Error', 'Transaksi sudah diposting dan tidak bisa diedit', 'error');
		return;
	} else {
		if (!tgl) {
			Swal.fire('Error', 'Tanggal transaksi tidak ditemukan', 'error');
			return;
		}

		// 1️⃣ CEK PERIODE DULU
		$.ajax({
			url: APP.base_url + 'periode/cek_periode',
			type: 'POST',
			dataType: 'json',
			data: { tgl: tgl },
			success: function (res) {
				if (res.closed) {
					Swal.fire({
						icon: 'error',
						title: 'Periode Ditutup',
						text: 'Transaksi ini berada di periode yang sudah ditutup dan tidak bisa diedit'
					});
					return;
				}
				window.location.href = "<?= site_url('journal/edit/'); ?>" + id;					
			}
		});
	}
});
	
</script>


<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on('click', '.btn-delete', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    let tgl = $(this).data('tgl');
	let posted = $(this).data('posted');

	if (posted == 1) {
		Swal.fire('Error', 'Transaksi sudah diposting dan tidak bisa dihapus', 'error');
		return;
	} else {

		if (!tgl) {
			Swal.fire('Error', 'Tanggal transaksi tidak ditemukan', 'error');
			return;
		}

    // 1️⃣ CEK PERIODE DULU
		$.ajax({
			url: APP.base_url + 'periode/cek_periode',
			type: 'POST',
			dataType: 'json',
			data: { tgl: tgl },
			success: function (res) {

				if (res.closed) {
					Swal.fire({
						icon: 'error',
						title: 'Periode Ditutup',
						text: 'Transaksi ini berada di periode yang sudah ditutup dan tidak bisa dihapus'
					});
					return;
				}
				
					Swal.fire({
						title: 'Yakin ingin menghapus?',
						text: 'Data jurnal yang dihapus tidak bisa dikembalikan!',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#d33',
						cancelButtonColor: '#3085d6',
						confirmButtonText: 'Ya, hapus!',
						cancelButtonText: 'Batal'
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = "<?= site_url('journal/delete/'); ?>" + id;
					}
				});
			}
		});
	}	
});
</script>


<script type="text/javascript">
$(document).ready(function() {

	$('.detailJournal').on('click',function(){
	    var id = $(this).data('id_jurnal');

	    alert(id);
	    
	    $('#detailJournal').modal({backdrop: 'static', keyboard: false}) ;

	    //AJAX REQUEST TO GET SELECTED PRODUCT
	    $.ajax({
	        url: "<?= base_url('journal/auto_journal'); ?>",
	        method: "POST",
	        data :{id:id},
	        async : true,
	        dataType : 'json',
	        success : function(data){

	            $("#detailJournalabel").text("Detail Transaksi : " + data[0].no_trans),
	            $("#tglTransaksi").val(data[0].tgl),
	            $("#noTransaksi").val(data[0].no_trans),
	            $("#keterangan").val(data[0].keterangan);
	            var href = '<?= base_url('assets/file/bukti/') ?>';
	            var bukti = data[0].bukti;

	            var url_href = href+bukti;
				$("#buktiTransaksi").prop('href', url_href);

	        	console.log(url_href);
	            // $("#buktiTransaksi").prop('href', '');

	            function change(data){
			       var reverse = data.toString().split('').reverse().join(''),
			       thousand = reverse.match(/\d{1,3}/g);
			       thousand = thousand.join(',').split('').reverse().join('');
			       return thousand;
			     }

	            let el = "";
	            $.each(data,function(index,da){
	                el += "<tr>";
	                el += "<td width='100px'><input type='text' class='form-control' value='"+da.kel_akun + "-"+da.noakun+"' readonly></td>";
	                el += "<td><input type='text' class='form-control' value='"+da.nama+"' readonly></td>";
	                el += "<td><input type='text' class='form-control' value='Rp. "+change(da.nilai)+"' readonly></td>";
	                el += "<td width='100px'><input type='text' class='form-control' value='"+da.perkiraan+"' readonly></td>";
	                el += "</tr>";

	                 });

	            $("#insert-form").append(el);
	        }
	    });
	});
});

</script>
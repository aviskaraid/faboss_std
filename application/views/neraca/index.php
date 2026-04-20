<style>
    .neraca-report { font-size:14px; }
    .neraca-report .section-title {
        font-weight:bold;
        font-size:16px;
        margin-top:25px;
    }
    .neraca-report .group-title {
        font-weight:bold;
        padding-top:15px;
    }
    .neraca-report .akun-row td:first-child {
        padding-left:30px;
    }
    .neraca-report .total-group {
        font-weight:bold;
        border-top:1px solid #ccc;
    }
    .neraca-report .grand-total {
        font-weight:bold;
        border-top:2px solid #000;
        font-size:15px;
    }
    .spacer-row td {
        height:25px;
        border:none !important;
    }
</style>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 mb-3"><?= $title; ?></h1>
    </div>

    <!-- FILTER -->
    <div class="card border-bottom-primary shadow mb-4">
        <div class="card-body">
            <form method="post" class="form-inline">
                <div class="form-group col-sm-4">
                    <label class="col-sm-5 col-form-label">Pilih Bulan</label>
                    <div class="col-sm-7">
                        <select name="bulan" class="bootstrap-select" data-width="100%">
                            <?php foreach($bulan_list as $row): ?>
                                <option value="<?= $row['id']; ?>" <?= ($bulan == $row['id'])?'selected':''; ?>>
                                    <?= $row['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="col-sm-5 col-form-label">Pilih Tahun</label>
                    <div class="col-sm-7">
                        <select name="tahun" class="bootstrap-select" data-width="100%">
                            <?php foreach($tahun_list as $th): ?>
                                <option value="<?= $th; ?>" <?= ($tahun == $th)?'selected':''; ?>>
                                    <?= $th; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4 justify-content-center">
                    <button type="submit" class="btn btn-success mr-3">
                        <i class="fas fa-fw fa-filter"></i> Filter
                    </button>
                    <a href="" class="btn btn-secondary" data-toggle="modal" data-target="#print">
                        <i class="fas fa-fw fa-print"></i> Cetak
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- LAPORAN -->
    <div class="card shadow mb-4">
        <div class="card-header text-center font-weight-bold">
            <div><?= $title; ?></div>
            <div><?= $profile['name']; ?></div>
            <div>Periode <?= $periode; ?></div>
        </div>

        <div class="card-body table-responsive">

            <?php
            function rupiah($nilai){
                return $nilai < 0
                    ? '(Rp. '.number_format(abs($nilai),0,',','.').')'
                    : 'Rp. '.number_format($nilai,0,',','.');
            }

            $total_aset = 0;
            $total_liabilitas = 0;
            $total_ekuitas = 0;
            ?>

            <table class="table table-striped table-bordered table-sm">
                <tbody>

                <!-- ASET -->
                <tr class="bg-light font-weight-bold">
                    <td colspan="2">ASET</td>
                </tr>

                <?php foreach($neraca as $n): ?>
                    <?php if($n['tipe']=='A'): ?>
                        <tr class="font-weight-bold">
                            <td colspan="2"><?= $n['nama_kel_akun']; ?></td>
                        </tr>

                        <?php foreach($n['akun'] as $a): ?>
                        <tr>
                            <td style="padding-left:30px;">
                                <?= $a['noakun'].' '.$a['nama']; ?>
                            </td>
                            <td align="right"><?= rupiah($a['saldo']); ?></td>
                        </tr>
                        <?php endforeach; ?>

                        <tr class="font-weight-bold">
                            <td>Total <?= $n['nama_kel_akun']; ?></td>
                            <td align="right"><?= rupiah($n['subtotal']); ?></td>
                        </tr>

                        <tr><td colspan="2"></td></tr>
                        <?php $total_aset += $n['subtotal']; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <tr class="font-weight-bold bg-light">
                    <td>Total Aset</td>
                    <td align="right"><?= rupiah($total_aset); ?></td>
                </tr>

                <tr class="spacer-row"><td colspan="2"></td></tr>

                <!-- LIABILITAS & EKUITAS -->
                <tr class="bg-light font-weight-bold">
                    <td colspan="2">LIABILITAS DAN EKUITAS</td>
                </tr>

                <?php foreach($neraca as $n): ?>
                    <?php if($n['tipe']=='P'): ?>
                        <tr class="font-weight-bold">
                            <td colspan="2"><?= $n['nama_kel_akun']; ?></td>
                        </tr>

                        <?php foreach($n['akun'] as $a): ?>
                        <tr>
                            <td style="padding-left:30px;">
                                <?= $a['noakun'].' '.$a['nama']; ?>
                            </td>
                            <td align="right"><?= rupiah($a['saldo']); ?></td>
                        </tr>
                        <?php endforeach; ?>

                        <tr class="font-weight-bold">
                            <td>Total <?= $n['nama_kel_akun']; ?></td>
                            <td align="right"><?= rupiah($n['subtotal']); ?></td>
                        </tr>

                        <tr><td colspan="2"></td></tr>

                        <?php
                        if (stripos($n['nama_kel_akun'],'modal') !== false || stripos($n['nama_kel_akun'],'ekuitas') !== false) {
                            $total_ekuitas += $n['subtotal'];
                        } else {
                            $total_liabilitas += $n['subtotal'];
                        }
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <tr class="font-weight-bold bg-light">
                    <td>Total Liabilitas dan Ekuitas</td>
                    <td align="right"><?= rupiah($total_liabilitas + $total_ekuitas); ?></td>
                </tr>

                </tbody>
            </table>

            <?php 
            $selisih = $total_aset - ($total_liabilitas + $total_ekuitas);
            if ($selisih != 0): ?>
                <div class="alert alert-danger mt-3">
                    <strong>Neraca tidak balance!</strong><br>
                    Selisih: <?= rupiah($selisih); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-success mt-3">
                    Neraca balance.
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Modal Print -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true" >
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="printLabel">Cetak Neraca</h5>
			</div>
			<form action="<?= base_url('neraca/print'); ?>" method="post" target="_BLANK">
				<div class="modal-body">
					<div class="form-group row">
                        <label class="col-sm-5 col-form-label">Pilih Bulan</label>
                        <div class="col-sm-7">
                            <select name="bulan" class="bootstrap-select" data-width="100%">
                                <?php foreach($bulan_list as $row): ?>
                                    <option value="<?= $row['id']; ?>" <?= ($bulan == $row['id'])?'selected':''; ?>>
                                        <?= $row['nama']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Pilih Tahun</label>
                        <div class="col-sm-7">
                            <select name="tahun" class="bootstrap-select" data-width="100%">
                                <?php foreach($tahun_list as $th): ?>
                                    <option value="<?= $th; ?>" <?= ($tahun == $th)?'selected':''; ?>>
                                        <?= $th; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" name="pdf"><i class="fas fa-fw fa-print"></i> PDF</button>
					<button type="submit" class="btn btn-success" name="excel"><i class="fas fa-fw fa-download"></i> EXCEL</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
    		
  			</form>
  		</div>
	</div>	
</div>
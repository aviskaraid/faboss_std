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

                $total_saldo_awal_debit = 0;
                $total_saldo_awal_credit = 0;
                $total_mutasi_debit = 0;
                $total_mutasi_credit = 0;
                $total_saldo_akhir_debit = 0;  
                $total_saldo_akhir_credit = 0;
            ?>

            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr class="bg-primary text-white text-center">
                    <th rowspan="2">No. Akun</th>
                    <th rowspan="2">Nama Akun</th>
                    <th colspan="2">Saldo Awal</th>
                    <th colspan="2">Mutasi</th>
                    <th colspan="2">Saldo Akhir</th>
                </tr>
                <tr class="bg-primary text-white text-center">
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
                </thead>
                <tbody>                    
                    <?php foreach($neracasaldo as $n): 

                        // Ambil nilai (hindari null)
                        $sa_debit  = $n['saldo_awal_debit'] ?? 0;
                        $sa_kredit = $n['saldo_awal_credit'] ?? 0;
                        $mu_debit  = $n['mutasi_debit'] ?? 0;
                        $mu_kredit = $n['mutasi_credit'] ?? 0;
                        $ak_debit  = $n['saldo_akhir_debit'] ?? 0;
                        $ak_kredit = $n['saldo_akhir_credit'] ?? 0;

                        // Akumulasi total
                        $total_saldo_awal_debit  += $sa_debit;
                        $total_saldo_awal_credit += $sa_kredit;
                        $total_mutasi_debit      += $mu_debit;
                        $total_mutasi_credit     += $mu_kredit;
                        $total_saldo_akhir_debit += $ak_debit;
                        $total_saldo_akhir_credit+= $ak_kredit;
                    ?>

                    <tr>
                        <td><?= $n['noakun']; ?></td>
                        <td><?= $n['nama']; ?></td>

                        <td class="text-right"><?= $sa_debit != 0 ? number_format($sa_debit,0,',','.') : '-'; ?></td>
                        <td class="text-right"><?= $sa_kredit != 0 ? number_format($sa_kredit,0,',','.') : '-'; ?></td>

                        <td class="text-right"><?= $mu_debit != 0 ? number_format($mu_debit,0,',','.') : '-'; ?></td>
                        <td class="text-right"><?= $mu_kredit != 0 ? number_format($mu_kredit,0,',','.') : '-'; ?></td>

                        <td class="text-right"><?= $ak_debit != 0 ? number_format($ak_debit,0,',','.') : '-'; ?></td>
                        <td class="text-right"><?= $ak_kredit != 0 ? number_format($ak_kredit,0,',','.') : '-'; ?></td>
                    </tr>

                    <?php endforeach; ?>

                    <!-- TOTAL -->
                    <tr class="font-weight-bold bg-light text-right">
                        <td colspan="2" class="text-center">TOTAL</td>

                        <td><?= number_format($total_saldo_awal_debit,0,',','.'); ?></td>
                        <td><?= number_format($total_saldo_awal_credit,0,',','.'); ?></td>

                        <td><?= number_format($total_mutasi_debit,0,',','.'); ?></td>
                        <td><?= number_format($total_mutasi_credit,0,',','.'); ?></td>

                        <td><?= number_format($total_saldo_akhir_debit,0,',','.'); ?></td>
                        <td><?= number_format($total_saldo_akhir_credit,0,',','.'); ?></td>
                    </tr>
                </tbody>
            </table>
            

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
			<form action="<?= base_url('neracasaldo/print'); ?>" method="post" target="_BLANK">
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
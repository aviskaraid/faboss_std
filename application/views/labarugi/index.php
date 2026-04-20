<style>
.laba-rugi-report { font-size:14px; }
.laba-rugi-report td:last-child { width:25%; white-space:nowrap; }

.section-title { font-weight:bold; font-size:16px; }
.group-title { font-weight:bold; padding-top:15px; }
.akun-row td:first-child { padding-left:30px; }

.total-group {
    font-weight:bold;
    border-top:1px solid #ccc;
    background:#f9f9f9;
}

.highlight-total {
    font-weight:bold;
    background:#eef3ff;
    border-top:2px solid #000;
}

.grand-total {
    font-weight:bold;
    font-size:16px;
    background:#dfe9ff;
    border-top:3px double #000;
}

.spacer-row td { height:10px; border:none !important; }
</style>

<div class="container-fluid">

<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

<!-- ================= FILTER ================= -->
    <div class="card border-bottom-primary shadow mb-4">
        <div class="card-body">
            <form method="post">
                <div class="form-row align-items-end">

                    <!-- BULAN -->
                    <div class="col-md-3 mb-2">
                        <label for="bulan" class="col-sm-8 col-form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control bootstrap-select">
                            <?php foreach($bulan_list as $row): ?>
                                <option value="<?= $row['id']; ?>" <?= ($bulan == $row['id'])?'selected':''; ?>>
                                    <?= $row['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- TAHUN -->
                    <div class="col-md-3 mb-2">
                        <label for="tahun" class="col-sm-8 col-form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control bootstrap-select">
                            <?php foreach($tahun_list as $th): ?>
                                <option value="<?= $th; ?>" <?= ($tahun == $th)?'selected':''; ?>>
                                    <?= $th; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- JENIS PERIODE -->
                    <div class="col-md-3 mb-2">
                        <label for="mode_periode" class="col-sm-8 col-form-label">Jenis Periode</label>
                        <select name="mode_periode" id="mode_periode" class="form-control bootstrap-select">
                            <option value="bulan" <?= ($mode_periode ?? 'bulan')=='bulan'?'selected':''; ?>>
                                Hanya bulan ini
                            </option>
                            <option value="sd_bulan" <?= ($mode_periode ?? '')=='sd_bulan'?'selected':''; ?>>
                                Dari awal tahun s/d bulan ini
                            </option>
                        </select>
                    </div>

                    <!-- TOMBOL -->
                    <div class="col-md-3 mb-2 text-md-right text-left">
                        <button type="submit" class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-fw fa-filter"></i> Filter
                        </button>

                        <a href="" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#print">
                            <i class="fas fa-fw fa-print"></i> Cetak
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

<!-- ================= LAPORAN ================= -->
<div class="card shadow mb-4">
    <div class="card-header text-center font-weight-bold">
        <div><?= $profile['name']; ?></div>
        <div>LAPORAN LABA RUGI</div>

        <?php
        $nama_bulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        ?>
        <div>Periode <?= $periode; ?></div>
    </div>

    <div class="card-body laba-rugi-report">
        <?php
        function rupiah($n){
            return $n < 0 ? '(Rp '.number_format(abs($n),0,',','.').')'
                          : 'Rp '.number_format($n,0,',','.');
        }
        ?>

        <table class="table table-bordered">
            <tbody>

            <!-- ================= PENDAPATAN ================= -->
            <tr class="section-title bg-light"><td colspan="2">PENDAPATAN</td></tr>

            <?php foreach($pendapatan as $group): ?>
                <tr class="group-title"><td colspan="2"><?= $group['nama_kel_akun']; ?></td></tr>
                <?php foreach($group['akun'] as $a): ?>
                    <tr class="akun-row">
                        <td><?= $a['noakun'].' '.$a['nama']; ?></td>
                        <td align="right"><?= rupiah($a['saldo']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-group">
                    <td>Total <?= $group['nama_kel_akun']; ?></td>
                    <td align="right"><?= rupiah($group['subtotal']); ?></td>
                </tr>
                <tr class="spacer-row"><td colspan="2"></td></tr>
            <?php endforeach; ?>

            <tr class="highlight-total">
                <td>TOTAL PENDAPATAN</td>
                <td align="right"><?= rupiah($total_pendapatan); ?></td>
            </tr>

            <tr class="spacer-row"><td colspan="2"></td></tr>

            <!-- ================= HPP ================= -->
            <tr class="section-title bg-light"><td colspan="2">HARGA POKOK PENJUALAN</td></tr>

            <?php foreach($hpp as $group): ?>
                <?php foreach($group['akun'] as $a): ?>
                    <tr class="akun-row">
                        <td><?= $a['noakun'].' '.$a['nama']; ?></td>
                        <td align="right"><?= rupiah($a['saldo']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <tr class="total-group">
                <td>Total HPP</td>
                <td align="right"><?= rupiah($total_hpp); ?></td>
            </tr>

            <tr class="highlight-total">
                <td>LABA / RUGI KOTOR</td>
                <td align="right"><?= rupiah($laba_kotor); ?></td>
            </tr>

            <tr class="spacer-row"><td colspan="2"></td></tr>

            <!-- ================= BEBAN ================= -->
            <tr class="section-title bg-light"><td colspan="2">BEBAN USAHA</td></tr>

            <?php foreach($beban as $group): ?>
                <tr class="group-title"><td colspan="2"><?= $group['nama_kel_akun']; ?></td></tr>
                <?php foreach($group['akun'] as $a): ?>
                    <tr class="akun-row">
                        <td><?= $a['noakun'].' '.$a['nama']; ?></td>
                        <td align="right"><?= rupiah($a['saldo']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-group">
                    <td>Total <?= $group['nama_kel_akun']; ?></td>
                    <td align="right"><?= rupiah($group['subtotal']); ?></td>
                </tr>
                <tr class="spacer-row"><td colspan="2"></td></tr>
            <?php endforeach; ?>

            <tr class="highlight-total">
                <td>TOTAL BEBAN</td>
                <td align="right"><?= rupiah($total_beban); ?></td>
            </tr>

            <tr class="grand-total">
                <td>LABA / RUGI BERSIH</td>
                <td align="right"><?= rupiah($laba_bersih); ?></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
</div>

<!-- ================= MODAL PRINT LABA RUGI ================= -->
<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="printLabel">Cetak Laporan Laba Rugi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= base_url('labarugi/print'); ?>" method="post" target="_BLANK">
                <div class="modal-body">

                    <!-- BULAN -->
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

                    <!-- TAHUN -->
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

                    <!-- JENIS PERIODE -->
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Jenis Periode</label>
                        <div class="col-sm-7">
                            <select name="mode_periode" class="bootstrap-select" data-width="100%">
                                <option value="bulan" <?= ($mode_periode ?? 'bulan')=='bulan'?'selected':''; ?>>
                                    Hanya bulan ini
                                </option>
                                <option value="sd_bulan" <?= ($mode_periode ?? '')=='sd_bulan'?'selected':''; ?>>
                                    Dari awal tahun s/d bulan ini
                                </option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="pdf">
                        <i class="fas fa-fw fa-print"></i> PDF
                    </button>
                    <button type="submit" class="btn btn-success" name="excel">
                        <i class="fas fa-fw fa-download"></i> EXCEL
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </form>
        </div>
    </div>
</div>
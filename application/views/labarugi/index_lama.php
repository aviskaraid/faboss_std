<style>
    .laba-rugi-report { font-size:14px; }

    .section-title {
        font-weight:bold;
        font-size:16px;
        margin-top:10px;
    }

    .group-title {
        font-weight:bold;
        padding-top:15px;
    }

    .akun-row td:first-child {
        padding-left:30px;
    }

    .total-group {
        font-weight:bold;
        border-top:1px solid #ccc;
        background:#f9f9f9;
    }

    .grand-total {
        font-weight:bold;
        border-top:2px solid #000;
        font-size:15px;
        background:#eef3ff;
    }

    .spacer-row td {
        height:10px;
        border:none !important;
    }
</style>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- FILTER -->
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

    <!-- LAPORAN -->
    <div class="card shadow mb-4">
        <div class="card-header text-center font-weight-bold">
            <div><?= $title; ?></div>
            <div><?= $profile['name']; ?></div>
            <div>Periode <?= bulan_indo($bulan) . ' ' . $tahun; ?></div>
        </div>

        <div class="card-body table-responsive laba-rugi-report">

            <?php
            function rupiah($nilai){
                return $nilai < 0
                    ? '(Rp. '.number_format(abs($nilai),0,',','.').')'
                    : 'Rp. '.number_format($nilai,0,',','.');
            }

            $total_pendapatan = 0;
            $total_beban = 0;
            ?>

            <table class="table table-striped table-bordered table-sm">
                <tbody>

                <!-- ================= PENDAPATAN ================= -->
                <tr class="bg-light font-weight-bold section-title">
                    <td colspan="2">PENDAPATAN</td>
                </tr>

                <?php foreach($labarugi as $group): ?>
                    <?php if($group['tipe'] == 'L'): ?>
                        <tr class="group-title">
                            <td colspan="2"><?= $group['nama_kel_akun']; ?></td>
                        </tr>

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

                        <?php $total_pendapatan += $group['subtotal']; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <tr class="total-group">
                    <td>Total Pendapatan</td>
                    <td align="right"><?= rupiah($total_pendapatan); ?></td>
                </tr>

                <tr class="spacer-row"><td colspan="2"></td></tr>


                <!-- ================= BEBAN ================= -->
                <tr class="bg-light font-weight-bold section-title">
                    <td colspan="2">BEBAN</td>
                </tr>

                <?php foreach($labarugi as $group): ?>
                    <?php if($group['tipe'] == 'B'): ?>
                        <tr class="group-title">
                            <td colspan="2"><?= $group['nama_kel_akun']; ?></td>
                        </tr>

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

                        <?php $total_beban += abs($group['subtotal']); ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <tr class="total-group">
                    <td>Total Beban</td>
                    <td align="right"><?= rupiah(-$total_beban); ?></td>
                </tr>

                <tr class="spacer-row"><td colspan="2"></td></tr>


                <!-- ================= LABA BERSIH ================= -->
                <?php $laba_bersih = $total_pendapatan - $total_beban; ?>

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
<!-- Modal Print -->
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


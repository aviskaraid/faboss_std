<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-4 text-gray-800"><?= $title;  ?></h1>

        <!-- TIDAK BOLEH MEMBUKA PERIODE AKTIF
        <form action="<?= base_url('dashboard/ubah_periode_aktif'); ?>" method="post" class="form-inline">
          <div class="form-group mb-2 mr-2">
            <select class="form-control" name="id_bln" id="id_bln" required="">
                <option value="">Buka Periode Akuntansi</option>
                <?php foreach($dt_bln as $cab) : ?>
                  <option value="<?= $cab['id_bln']; ?>" <?= ($user['id_bln'] == $cab['id_bln']) ? " selected " : null; ?>><?= $cab['nm_bln']; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-save"></i></button>
        </form>
        -->

    </div>
  

    <div class="row">
        <div class="col-sm-12">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>
  
  <div class="row">
  	<!-- Pendapatan Total Data Akun -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Data Aset</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_aset, 0, ',','.'); ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-fw fa-folder fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pendapatan Total Data Akun -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Piutang Belum Lunas</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_piutang, 0, ',','.'); ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-fw fa-history fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pendapatan Per Bulan -->
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Utang Belum Lunas</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_utang, 0, ',','.'); ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-fw fa-user-plus fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      

  </div>

  <div class="row">

    <!-- Area Chart -->
    <!--
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            
            <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Informasi Transaksi Kas / Bank Tahun <?= $tahun; ?></h6>
            </div>
            
            <div class="card-body">
                <div class="chart-area">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="myAreaChart" width="669" height="320" class="chartjs-render-monitor" style="display: block; width: 669px; height: 320px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    -->

    <!-- Area Chart -->
  <div class="col-xl-8 col-lg-7">
      <div class="card shadow mb-4">
          <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-white">
                  Informasi Transaksi Kas / Bank Tahun <?= $tahun; ?>
              </h6>
          </div>

          <div class="card-body">
              <div class="chart-area" style="height: 320px;">
                  <canvas id="myAreaChart"></canvas>
              </div>
          </div>
      </div>
  </div>

    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary py-3">
                <h6 class="m-0 font-weight-bold text-white text-center">10 Transaksi Terakhir</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-sm table-striped text-center">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No Transaksi</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jurnal as $row) : ?>
                            <tr>
                                <td><strong><?= date("d/n/Y", strtotime($row['tgl'])); ?></strong></td>
                                <td><?= $row['no_trans']; ?></td>
                                <td><span class="badge badge-danger"><?= 'Rp. '.number_format($row['nominal'],0,',','.') ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
  </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById("myAreaChart").getContext("2d");

new Chart(ctx, {
    type: "line",
    data: {
        labels: [
            "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
            "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
        ],
        datasets: [
        {
            label: "Terima",
            data: <?= json_encode($chart_terima); ?>,
            fill: true,
            tension: 0.3,
            borderWidth: 2
        },
        {
            label: "Bayar",
            data: <?= json_encode($chart_bayar); ?>,
            fill: true,
            tension: 0.3,
            borderWidth: 2
        }
    ]

    },
    options: {
        maintainAspectRatio: false,
        interaction: {
            mode: "index",
            intersect: false
        },
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                ticks: {
                    callback: function(value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    }
                }
            }
        }
    }
});
</script>

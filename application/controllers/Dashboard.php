<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		$this->load->model('Admin_model', 'admin');
	}

	public function index()
	{
		$data['title'] = 'Dashboard';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Journal_model', 'journal');
		$this->load->model('Piutang_model','piutang');
		$this->load->model('Utang_model','utang');
		$this->load->model('Asset_model','asset');


		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");

		$data['dt_bln'] = getAllBulan();

		// =======================
		// ASSET
		// =======================
		$dt_aset_all = $this->asset->getAllData();
		$data['dtAll'] = $this->asset->dataAllAsetTetap($dt_aset_all, $data['now']);
		$data['total_aset'] = $this->pemrosesan_aset($data['dtAll']);

		// =======================
    	// PIUTANG
    	// =======================
		$dt_piutang = $this->piutang->getAllData();
		$data['total_piutang'] = $this->pemrosesan_piutang($dt_piutang);

		// =======================
    	// UTANG
    	// =======================
		$dt_utang = $this->utang->getAllData();
		$data['total_utang'] = $this->pemrosesan_utang($dt_utang);

		// =======================
    	// 10 TRANSAKSI TERAKHIR
    	// =======================
		$this->db->limit(10);
		$data['jurnal'] = $this->pemrosesan_report();

		// =======================
		// LINE CHART KAS MASUK & KELUAR
		// =======================
		$this->load->model('Dashboard_model'); // model we created earlier

		$dt_tahun = $data['user']['id_periode']; // tahun aktif user
		$data['tahun'] = $dt_tahun;

		$result_chart = $this->Dashboard_model->get_kas_bank_bulanan($dt_tahun);

		// siapkan 12 bulan default = 0
		$terima = array_fill(1, 12, 0);
		$bayar  = array_fill(1, 12, 0);

		foreach ($result_chart as $row) {
			$bulan = (int)$row->bulan;
			$terima[$bulan] = (float)$row->terima;
			$bayar[$bulan]  = (float)$row->bayar;
		}

		$data['chart_terima'] = array_values($terima);
		$data['chart_bayar']  = array_values($bayar);

		// =======================
		// LOAD VIEW
		// =======================
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('dashboard', $data);
		$this->load->view('templates/footer');
	}

	function pemrosesan_aset($data)
	{
		$total_aset = 0;
		foreach($data as $row) {
			$total_aset += $row['nilai'];
		}

		return $total_aset;
	}

	function pemrosesan_piutang($data)
	{
		$this->load->model('Setting_model','setting');
		$this->load->model('Piutang_model','piutang');

		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$total_belum_lunas = 0;
		foreach($data as $row) {

			// nomor trans 
			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			// mendapatkan dibayar
			$jumlah_dibayar = 0;
			$dt_terbayar = $this->piutang->get_terbayar($row['id_piutang']);
			foreach($dt_terbayar as $row_bayar)
			{
				$jumlah_dibayar += $row_bayar['nilai'];
			}

			$sisa_piutang = $row['nilai'] - $jumlah_dibayar;
			if($sisa_piutang > 0) {
				$total_belum_lunas += $sisa_piutang;
			}
		}

		return $total_belum_lunas;
	}

	function pemrosesan_utang($data)
	{
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$result = array();
		$total_belum_lunas = 0;
		foreach($data as $row) {
			// nomor otomatis
			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			// mendapatkan dibayar
			$jumlah_dibayar = 0;
			$dt_terbayar = $this->utang->get_terbayar($row['id_utang']);
			foreach($dt_terbayar as $row_bayar)
			{
				$jumlah_dibayar += $row_bayar['nilai'];
			}

			$sisa_utang = $row['nilai'] - $jumlah_dibayar;
			if($sisa_utang > 0) {
				$total_belum_lunas += $sisa_utang;
			}
		}

		return $total_belum_lunas;
	}



	function pemrosesan_report()
	{
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$dt_transaksi = $this->journal->getJournal();
		$result = array();
		foreach($dt_transaksi as $row) {
			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			$result[] = $row;
		}

		return $result;
	}

    public function ubah_periode_aktif()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['id_bln'] = $this->input->post('id_bln');

        // update id_cabang
        $this->db->set('id_bln', $data['id_bln']);
        $this->db->where('id_user', $data['user']['id_user']);
        $this->db->update('user');

        $this->session->set_flashdata('message', '
        <div class="alert alert-success" role="alert">
            Periode '.getBulan($data['id_bln']).' sedang dibuka!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>');
        redirect('dashboard');
    }
}
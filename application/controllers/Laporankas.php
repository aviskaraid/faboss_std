<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporankas extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Laporankas_model','laporankas');
		$this->load->model('Admin_model','admin');
		$this->load->model('Setting_model','setting');
	}

	public function index()
	{
		$data['title'] = 'Laporan Kas & Bank';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');

		$now = date("Y-m-d");
		$data['t_awal'] = date('Y-m-01', strtotime($now));
		$data['t_akhir'] = date('Y-m-t', strtotime($now));
		// semua data kas
		$dt_kas = $this->laporankas->getAllData();
		$data['dt_kas'] = $this->laporankas->data_kas_by_tgl($dt_kas, $tglAwal, $tglAkhir);
		$data['now'] = date("d-m-Y");
		//$data['tglAwal'] = $tglAwal;
		//$data['tglAkhir'] = $tglAkhir;

		// data_akun ditampilkan aset lancar
		$this->db->where_in('kelompok_akun.id_kelompok_akun', 1);
		$data['dt_akun'] = $this->masterdata->getAccountData();		

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('laporankas/index', $data);
		$this->load->view('templates/footer');
	}

	function pemrosesan_kas($data)
	{
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$result = array();
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

			$di_bayar = array('dibayar' => $jumlah_dibayar);

			if($stts == 0) {
			// 	// belum lunas
				if($jumlah_dibayar < $row['nilai']) {
					$result[] = array_merge($row, $di_bayar);					
				}
			} else if($stts == 1) { 
			// 	// lunas
				if($jumlah_dibayar == $row['nilai']) {
					$result[] = array_merge($row, $di_bayar);					
				}
			} else if($stts == 99) { 
				// semua piutang
				$result[] = array_merge($row, $di_bayar);
			}
		}

		return $result;
	}

	public function print()
	{		
		$id_sak = $this->input->post('id_sak', true);
		
		$t_awal = $this->input->post('tglAwal', true);
		$t_akhir = $this->input->post('tglAkhir', true);
		$tglAwal = convertDateToDbdate($t_awal);
		$tglAkhir = convertDateToDbdate($t_akhir);

		$data['tglAwal'] = $tglAwal;
		$data['tglAkhir'] = $tglAkhir;
		$data['profile'] = $this->admin->companyProfile();
		$data['nama'] = $this->laporankas->getNmAkunKas($id_sak);

		$dt_report = $this->laporankas->getReportData($id_sak, $tglAwal, $tglAkhir);
		$data['dbAll'] = $dt_report;
		
		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('laporankas/laporan_kas_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('laporankas/laporan_kas_excel', $data);
		}
	}
}
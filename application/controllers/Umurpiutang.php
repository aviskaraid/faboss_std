<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umurpiutang extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Umurpiutang_model','umurpiutang');
		$this->load->model('Kas_model','kas');
		$this->load->model('Pendapatan_model','pendapatan');
		$this->load->model('Setting_model','setting');
		$this->load->model('Journal_model','journal');
		$this->load->model('Admin_model','admin');
	}

	public function index()
	{
		$data['title'] = 'Laporan Umur Piutang';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("d-m-Y");
		$data['tglAwal'] = date('Y-m-01', strtotime($data['now']));
		$data['tglAkhir'] = date('Y-m-t', strtotime($data['now']));

		$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
		$this->db->where($where);
		$dt_piutang = $this->umurpiutang->getAllData();

		$data['dtPiutang'] = $this->pemrosesan_umurpiutang($dt_piutang);
		
		// data akun kas
		$data['dtAkunKas'] = $this->kas->getAllData();

		// data customer
		$dt_customer = $this->umurpiutang->getCustomerData();
		$data['dt_customer'] = $dt_customer;

		// data akun pendapatan		
		$data['dtAkunPendapatan'] = $this->pendapatan->getAllData(); 

		$this->form_validation->set_rules('tglAwal','Tgl Awal', 'required');
		$this->form_validation->set_rules('tglAkhir','Tgl Akhir', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('umurpiutang/index', $data);
			$this->load->view('templates/footer');
		} else {
			// mendapatkan data
			$t_awal = $this->input->post('tglAwal', true);
			$t_akhir = $this->input->post('tglAkhir', true);
			$data['tglAwal'] = convertDateToDbdate($t_awal);
			$data['tglAkhir'] = convertDateToDbdate($t_akhir);

			$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
			$this->db->where($where);
			$dt_piutang = $this->umurpiutang->getAllData();
			$data['dtPiutang'] = $this->pemrosesan_umurpiutang($dt_piutang);

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('umurpiutang/index', $data);
			$this->load->view('templates/footer');
		}
	}

	function pemrosesan_umurpiutang($data)
	{
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
			$dt_terbayar = $this->umurpiutang->get_terbayar($row['id_piutang']);
			foreach($dt_terbayar as $row_bayar)
			{
				$jumlah_dibayar += $row_bayar['nilai'];
			}

			$di_bayar = array('dibayar' => $jumlah_dibayar);

            if($jumlah_dibayar < $row['nilai']) {
                $result[] = array_merge($row, $di_bayar);					
			}
		}

		return $result;
	}

	public function print()
	{
		$t_awal = $this->input->post('tglAwal', true);
		$t_akhir = $this->input->post('tglAkhir', true);
		$tglAwal = convertDateToDbdate($t_awal);
		$tglAkhir = convertDateToDbdate($t_akhir);

		$data['tglAwal'] = $tglAwal;
		$data['tglAkhir'] = $tglAkhir;
		$data['profile'] = $this->admin->companyProfile();

		$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
		$this->db->where($where);
		$dt_piutang = $this->umurpiutang->getAllData();
		$data['result'] = $this->pemrosesan_umurpiutang($dt_piutang);

		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('umurpiutang/laporan_umurpiutang_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('umurpiutang/laporan_umurpiutang_excel', $data);
		}
	}
}    
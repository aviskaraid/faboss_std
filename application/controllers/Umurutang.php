<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umurutang extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Umurutang_model','umurutang');
		$this->load->model('Kas_model','kas');
		$this->load->model('Biaya_model','biaya');
		$this->load->model('Setting_model','setting');
		$this->load->model('Journal_model','journal');
		$this->load->model('Admin_model','admin');
	}

	public function index()
	{
		$data['title'] = 'Laporan Umur Hutang';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("d-m-Y");
		$data['tglAwal'] = date('Y-m-01', strtotime($data['now']));
		$data['tglAkhir'] = date('Y-m-t', strtotime($data['now']));

		$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
		$this->db->where($where);
		$dt_utang = $this->umurutang->getAllData();

		$data['dtUtang'] = $this->pemrosesan_umurutang($dt_utang);
		
		// data akun kas
		$data['dtAkunKas'] = $this->kas->getAllData();

		// data supplier
		$dt_supplier = $this->umurutang->getSupplierData();
		$data['dt_supplier'] = $dt_supplier;

		// data akun biaya		
		$data['dtAkunBiaya'] = $this->biaya->getAllData(); 

		$this->form_validation->set_rules('tglAwal','Tgl Awal', 'required');
		$this->form_validation->set_rules('tglAkhir','Tgl Akhir', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('umurutang/index', $data);
			$this->load->view('templates/footer');
		} else {
			// mendapatkan data
			$t_awal = $this->input->post('tglAwal', true);
			$t_akhir = $this->input->post('tglAkhir', true);
			$data['tglAwal'] = convertDateToDbdate($t_awal);
			$data['tglAkhir'] = convertDateToDbdate($t_akhir);

			$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
			$this->db->where($where);
			$dt_utang = $this->umurutang->getAllData();
			$data['dtUtang'] = $this->pemrosesan_umurutang($dt_utang);

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('umurutang/index', $data);
			$this->load->view('templates/footer');
		}
	}

	function pemrosesan_umurutang($data)
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
			$dt_terbayar = $this->umurutang->get_terbayar($row['id_utang']);
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
		$dt_utang = $this->umurutang->getAllData();
		$data['result'] = $this->pemrosesan_umurutang($dt_utang);

		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('umurutang/laporan_umurutang_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('umurutang/laporan_umurutang_excel', $data);
		}
	}
}    
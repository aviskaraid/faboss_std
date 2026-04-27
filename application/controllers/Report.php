<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('Report_model','report');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Admin_model', 'admin');
		$this->load->model('Setting_model','setting');
	}

	public function index()
	{
		$data['title'] = 'Buku Besar';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();		

		//date
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$data['tglAwal'] = date('Y-m-01', strtotime($now));
		$data['tglAkhir'] = date('Y-m-t', strtotime($now));

		//data akun
		$this->db->order_by('kelompok_akun.kel_akun', 'ASC');
		$data['akun'] =  $this->masterdata->getAccountData();

		$this->form_validation->set_rules('namaAkun','Nama Akun', 'required');
		$this->form_validation->set_rules('tglAwal','Tgl Awal', 'required');
		$this->form_validation->set_rules('tglAkhir','Tgl Akhir', 'required');

		if($this->form_validation->run() == false) {
			$data['status'] = 0;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/index', $data);
			$this->load->view('templates/footer');
		} else {
			$idAkun = $this->input->post('namaAkun', true);
			// convert date
			$tglAwal = convertDateToDbdate($this->input->post('tglAwal', true));
			$tglAkhir = convertDateToDbdate($this->input->post('tglAkhir', true));
			$data['tglAwal'] = $tglAwal;
			$data['tglAkhir'] = $tglAkhir;
			
			// mencari saldo awal
			$tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
			$tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tglAwal)));
			$account_data = $this->masterdata->getAccountDataById($idAkun);

			$array = array('jurnal_detail.id_akun' => $idAkun, 'jurnal.tgl >=' => $tgl_awal, 'jurnal.tgl <=' => $tgl_akhir);
			$this->db->where($array);
			$journal_data =  $this->report->getData();

			// Memperoleh nominal saldo awal
			$totalDebit = 0;
			$totalKredit = 0;
			$sum = 0;
			$debit_jurnal = 0;
			if ($account_data) :

				$sum = intval($account_data['saldo_awal']);
				if ($account_data['id_perkiraan'] == 1) {
					$totalDebit += $sum;
				} else {
					$totalKredit -= $sum;
				}

			endif;

			if ($journal_data) :

				foreach ($journal_data as $data) :
					if ($data['id_perkiraan'] == 1) {
						$sum += intval($data['nilai']);
						$totalDebit += intval($data['nilai']);
					} else {
						$sum -= intval($data['nilai']);
						$totalKredit -= intval($data['nilai']);
					}
				endforeach;
			endif;

			if ($account_data['id_perkiraan'] == 1) {
				$data['account_data'] = array(
					'id_perkiraan' => 1,
					'nama' => $account_data['nama'], 'kel_akun' => $account_data['kel_akun'], 'noakun' => $account_data['noakun'],
					'saldo_awal' => $sum
				);
			} else {
				$data['account_data'] = array('id_perkiraan' => 2, 
				'nama' => $account_data['nama'], 'kel_akun' => $account_data['kel_akun'], 'noakun' => $account_data['noakun'],
				'saldo_awal' => $totalKredit);
			}
			// Tutup memperoleh saldo awal

			

			$data['tglAwal'] = $tglAwal;
			$data['tglAkhir'] = $tglAkhir;

			$array = array('jurnal_detail.id_akun' => $idAkun, 'jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
			$this->db->where($array);

			$journal_data_bb = $this->report->getData($idAkun, $tglAwal, $tglAkhir);
			// Inject format nomor transaksi
			foreach ($journal_data_bb as $key => $item) {
				// Ambil bulan & tahun dari tanggal
				$bulan = date('m', strtotime($item['tgl']));
				$tahun = date('Y', strtotime($item['tgl']));

				// Ambil setting penomoran
				$setJu = $this->setting->getPenomoranByIdMenu($item['type']);

				// Format nomor transaksi
				$no_trans_format = sprintf(
					'%0'.$setJu['panjang_nomor'].'d',
					(int)$item['no_trans']
				).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;

				// Tambahkan ke array
				$journal_data_bb[$key]['no_trans_format'] = $no_trans_format;
			}

			$data['journal_data'] = $journal_data_bb;

			$data['status'] = 1;
			$data['title'] = 'Buku Besar';
			$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
			$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

			$this->load->model('Masterdata_model', 'masterdata');
			$this->db->order_by('kelompok_akun.kel_akun', 'ASC');
			$data['akun'] =  $this->masterdata->getAccountData();
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/index', $data);
			$this->load->view('templates/footer');
		}
	}

	public function printledger()
	{
		$idAkun = $this->input->post('namaAkun', true);
		$tglAwal = convertDateToDbdate($this->input->post('tglAwal', true));
		$tglAkhir = convertDateToDbdate($this->input->post('tglAkhir', true));
		
		// mencari saldo awal
		$tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
		$tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tglAwal)));
		$account_data = $this->masterdata->getAccountDataById($idAkun);

		$array = array('jurnal_detail.id_akun' => $idAkun, 'jurnal.tgl >=' => $tgl_awal, 'jurnal.tgl <=' => $tgl_akhir);
		$this->db->where($array);
		$journal_data =  $this->report->getData();

		// Memperoleh nominal saldo awal
		$totalDebit = 0;
		$totalKredit = 0;
		$sum = 0;
		$debit_jurnal = 0;
		if ($account_data) :

			$sum = intval($account_data['saldo_awal']);
			if ($account_data['id_perkiraan'] == 1) {
				$totalDebit += $sum;
			} else {
				$totalKredit -= $sum;
			}

		endif;

		if ($journal_data) :

			foreach ($journal_data as $data) :
				if ($data['id_perkiraan'] == 1) {
					$sum += intval($data['nilai']);
					$totalDebit += intval($data['nilai']);
				} else {
					$sum -= intval($data['nilai']);
					$totalKredit -= intval($data['nilai']);
				}
			endforeach;
		endif;

		if ($account_data['id_perkiraan'] == 1) {
			$data['account_data'] = array(
				'id_perkiraan' => 1,
				'nama' => $account_data['nama'], 'kel_akun' => $account_data['kel_akun'], 'noakun' => $account_data['noakun'],
				'saldo_awal' => $sum
			);
		} else {
			$data['account_data'] = array('id_perkiraan' => 2, 
				'nama' => $account_data['nama'], 'kel_akun' => $account_data['kel_akun'], 'noakun' => $account_data['noakun'],
				'saldo_awal' => $sum);
		}

		$array = array('jurnal_detail.id_akun' => $idAkun, 'jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);

		$journal_data_bb = $this->report->getData($idAkun, $tglAwal, $tglAkhir);
		// Inject format nomor transaksi
		foreach ($journal_data_bb as $key => $item) {
			// Ambil bulan & tahun dari tanggal
			$bulan = date('m', strtotime($item['tgl']));
			$tahun = date('Y', strtotime($item['tgl']));

			// Ambil setting penomoran
			$setJu = $this->setting->getPenomoranByIdMenu($item['type']);

			// Format nomor transaksi
			$no_trans_format = sprintf(
				'%0'.$setJu['panjang_nomor'].'d',
				(int)$item['no_trans']
			).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;

			// Tambahkan ke array
			$journal_data_bb[$key]['no_trans_format'] = $no_trans_format;
		}
		$data['journal_data'] = $journal_data_bb;

		$data['tglAwal'] = $tglAwal;
		$data['tglAkhir'] = $tglAkhir;
		$data['profile'] = $this->admin->companyProfile();

		$data['result'] = array(
			'account_data' => $data['account_data'],
			'journal_data' => $data['journal_data'],
		);

		// echo json_encode($data['result']);
		// die;


		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('report/pdf/ledger', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('report/excel/ledger', $data);
		}
	}

	public function trialbalance()
	{
		$data['title'] = 'Neraca Saldo';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		// menampilkan daftar nama bulan
		$data['bulan_sekarang'] = date('m');
		$data['bulan'] = $this->bulan();
		// menampilkan tahun
		$thn = date('Y');
		$data['tahun_sekarang'] = $thn;
		$data['tahun'] = array();
		$j = 0;
		for($i=0; $i < 10; $i++) {
			$data['tahun'][] = $thn-$i;
			$j++;
		}

		$this->form_validation->set_rules('bln','', 'required');
		$this->form_validation->set_rules('tahun','', 'required');

		if($this->form_validation->run() == false) {
			$data['status'] = 0;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/trialbalance', $data);
			$this->load->view('templates/footer');
		} else {
			$bln 	= $this->input->post('bln', true);
			$tahun 	= $this->input->post('tahun', true);
			$tgl_set = date($tahun.'-'.$bln.'-d');

			$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
			$tglAkhir = date('Y-m-t', strtotime($tgl_set));
			$data['bulan_sekarang'] = $bln;
			$data['tahun_sekarang'] = $tahun;

			$kelAkun = $this->db->get('kelompok_akun')->result_array();
			$inArray = array(110,120,210,220,310,410,510,610,710,810);
			$data['neraca_saldo_data'] = $this->getData($tglAwal, $tglAkhir, $inArray);

			$data['status'] = 1;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/trialbalance', $data);
			$this->load->view('templates/footer');
		}
	}

	public function printtrialbalance()
	{
		$bln 	= $this->input->post('bln', true);
		$tahun 	= $this->input->post('tahun', true);
		$tgl_set = date($tahun.'-'.$bln.'-d');

		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-t', strtotime($tgl_set));

		$this->load->model('Admin_model','admin');

		$kelAkun = $this->db->get('kelompok_akun')->result_array();
		$inArray = array(110,120,210,220,310,410,510,610,710,810);
		$data['bln'] = $bln;
		$data['tahun'] = $tahun;
		$data['profile'] = $this->admin->companyProfile();
		$data['neraca_saldo_data'] = $this->getData($tglAwal, $tglAkhir, $inArray);

		$data['kel_akun'] = array(110,120,210,220,310,410,510,610,710,810);
		
		if(isset($_POST['pdf'])) {
			$this->load->library('fpdf');
			$this->load->view('report/pdf/trialbalance', $data);
		} else {
			$this->load->view('report/excel/trialbalance', $data);
		}
	}

	public function incomestatement()
	{
		$data['title'] = 'Laporan Laba Rugi';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		// menampilkan daftar nama bulan
		$data['bulan_sekarang'] = date('m');
		$data['bulan'] = $this->bulan();
		// menampilkan tahun
		$thn = date('Y');
		$data['tahun_sekarang'] = $thn;
		$data['tahun'] = array();
		$j = 0;
		for($i=0; $i < 10; $i++) {
			$data['tahun'][] = $thn-$i;
			$j++;
		}

		// $this->form_validation->set_rules('bln','', 'required');
		$this->form_validation->set_rules('tahun','', 'required');

		if($this->form_validation->run() == false) {
			$data['status'] = 0;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/incomestatement', $data);
			$this->load->view('templates/footer');
		} else {
			$bln 	= $this->input->post('bln', true);
			$tahun 	= $this->input->post('tahun', true);
			if(empty($bln)) {				
				$tgl_set = date($tahun.'-m-d');

				$tglAwal = date('Y-01-01', strtotime($tgl_set));
				$tglAkhir = date('Y-m-t', strtotime($tgl_set));
			} else {				
				$tgl_set = date($tahun.'-'.$bln.'-d');

				$tglAwal = date('Y-m-1', strtotime($tgl_set));
				$tglAkhir = date('Y-m-t', strtotime($tgl_set));
			}

			$data['bulan_sekarang'] = $bln;
			$data['tahun_sekarang'] = $tahun;
			
			$data['laba_rugi_data'] = $this->getData($tglAwal, $tglAkhir, array(410,510,610,710,810));


			$data['status'] = 1;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/incomestatement', $data);
			$this->load->view('templates/footer');
		}
	}

	public function printincomestatement()
	{
		$bln 	= $this->input->post('bln', true);
		$tahun 	= $this->input->post('tahun', true);
		if(empty($bln)) {				
			$tgl_set = date($tahun.'-m-d');

			$tglAwal = date('Y-01-01', strtotime($tgl_set));
			$tglAkhir = date('Y-m-t', strtotime($tgl_set));
		} else {				
			$tgl_set = date($tahun.'-'.$bln.'-d');

			$tglAwal = date('Y-m-1', strtotime($tgl_set));
			$tglAkhir = date('Y-m-t', strtotime($tgl_set));
		}
		
		$this->load->model('Admin_model','admin');

		$data['bln'] = $bln;
		$data['tahun'] = $tahun;
		$data['profile'] = $this->admin->companyProfile();
		$data['laba_rugi_data'] = $this->getData($tglAwal, $tglAkhir, array(410,510,610,710,810));

		if(isset($_POST['pdf'])) {
			$this->load->library('fpdf');
			$this->load->view('report/pdf/incomestatement',$data);
		} else {
			$this->load->view('report/excel/incomestatement', $data);
		}
	}

	public function capitalstatement()
	{
		$data['title'] = 'Laporan Perubahan Ekuitas';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		// menampilkan daftar nama bulan
		$data['bulan_sekarang'] = date('m');
		$data['bulan'] = $this->bulan();
		// menampilkan tahun
		$thn = date('Y');
		$data['tahun_sekarang'] = $thn;
		$data['tahun'] = array();
		$j = 0;
		for($i=0; $i < 10; $i++) {
			$data['tahun'][] = $thn-$i;
			$j++;
		}

		// $this->form_validation->set_rules('bln','', 'required');
		$this->form_validation->set_rules('tahun','', 'required');

		if($this->form_validation->run() == false) {
			$data['status'] = 0;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/capitalstatement', $data);
			$this->load->view('templates/footer');
		} else {
			$tglAwalSet = date('Y-m-d', strtotime('1/1/1971'));

			$bln 	= $this->input->post('bln', true);
			$tahun 	= $this->input->post('tahun', true);
			if(empty($bln)) {				
				$tgl_set = date($tahun.'-m-15');

				$tglAwal = date('Y-01-01', strtotime($tgl_set));
				$tglAkhirSebelumnya = date('Y-m-d', strtotime("-1 Days", strtotime($tglAwal)));
				$tglAkhir = date('Y-12-t', strtotime($tgl_set));
			} else {				
				$tgl_set = date($tahun.'-'.$bln.'-15');

				$tglAwal = date('Y-m-01', strtotime($tgl_set));
				$tglAwalTahun = date('Y-01-01', strtotime($tgl_set));
				$tglAkhirSebelumnya = date('Y-m-d', strtotime("-1 Days", strtotime($tglAwal)));
				// $tglAkhirBulanSebelumnya = date('Y-m-d', strtotime("-1 Days", strtotime($tglAwal)));
				$tglAkhir = date('Y-m-t', strtotime($tgl_set));
			}

			$this->load->model('Setting_model','setting');

			// memperoleh saldo awal akun modal

			$data['tglAkhir'] = $tglAkhir;
			$data['bulan_sekarang'] = $bln;
			$data['tahun_sekarang'] = $tahun;
			$data['data_akun'] = $this->report->getAccountData();
			$data['labarugi'] = $this->getTotalLabaRugi($tglAwal, $tglAkhir, array(410,510,610,710,810));
			$data['perubahan_ekuitas_data'] = $this->getData_capital_statement($tglAwal, $tglAkhir, array(310));
				
            // 	mencari modal awal dari semua akun ekuitas
			$dt_set_account = $this->setting->getDataById(1);

			// pencarian laba ditahan dan laba tahun sebelumnya
			$laba_ditahan = $this->getTotalLabaRugi($tglAwalTahun, $tglAkhirSebelumnya, array(410,510,610,710,810));
			$laba_tahun_sebelumnya = $this->getTotalLabaRugi($tglAwalSet, $tglAkhirSebelumnya, array(410,510,610,710,810));
			$data['saldo_awal'] = $this->cek_saldo_modal_by_tgl($tglAwalSet, $tglAkhirSebelumnya, $dt_set_account, $laba_ditahan, $laba_tahun_sebelumnya);

			$data['status'] = 1;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/capitalstatement', $data);
			$this->load->view('templates/footer');
		}
	}

	public function printcapitalstatement()
	{
		$tglAwalSet = date('Y-m-d', strtotime('1/1/1971'));

		$bln 	= $this->input->post('bln', true);
		$tahun 	= $this->input->post('tahun', true);
		if(empty($bln)) {				
			$tgl_set = date($tahun.'-m-d');

			$tglAwal = date('Y-01-01', strtotime($tgl_set));
			$tglAkhirSebelumnya = date('Y-m-d', strtotime("-1 Days", strtotime($tglAwal)));
			$tglAkhir = date('Y-12-t', strtotime($tgl_set));
		} else {				
			$tgl_set = date($tahun.'-'.$bln.'-d');

			$tglAwal = date('Y-m-01', strtotime($tgl_set));
			$tglAwalTahun = date('Y-01-01', strtotime($tgl_set));
			$tglAkhirSebelumnya = date('Y-m-d', strtotime("-1 Days", strtotime($tglAwal)));
			// $tglAkhirBulanSebelumnya = date('Y-m-d', strtotime("-1 Days", strtotime($tglAwal)));
			$tglAkhir = date('Y-m-t', strtotime($tgl_set));
		}

		$this->load->model('Setting_model','setting');
		$this->load->model('Admin_model','admin');

		// memperoleh saldo awal akun modal

		$data['bln'] = $bln;
		$data['tahun'] = $tahun;
		$data['profile'] = $this->admin->companyProfile();
		$data['tglAkhir'] = $tglAkhir;
		$data['data_akun'] = $this->report->getAccountData();
		$data['labarugi'] = $this->getTotalLabaRugi($tglAwal, $tglAkhir, array(410,510,610,710,810));
		$data['perubahan_ekuitas_data'] = $this->getData_capital_statement($tglAwal, $tglAkhir, array(310));
			
        // 	mencari modal awal dari semua akun ekuitas
		$dt_set_account = $this->setting->getDataById(1);
		// pencarian laba ditahan dan laba tahun sebelumnya
		$laba_ditahan = $this->getTotalLabaRugi($tglAwalTahun, $tglAkhirSebelumnya, array(410,510,610,710,810));
		$laba_tahun_sebelumnya = $this->getTotalLabaRugi($tglAwalSet, $tglAkhirSebelumnya, array(410,510,610,710,810));
		$data['saldo_awal'] = $this->cek_saldo_modal_by_tgl($tglAwalSet, $tglAkhirSebelumnya, $dt_set_account, $laba_ditahan, $laba_tahun_sebelumnya);

		if(isset($_POST['pdf'])) {
			$this->load->library('fpdf');
			$this->load->view('report/pdf/capitalstatement',$data);
		} else if(isset($_POST['excel'])) {

			$this->load->view('report/excel/capitalstatement', $data);
		}
	}

	public function cek_saldo_modal_by_tgl($tgl_awal, $tgl_akhir, $set_account, $laba_ditahan, $laba_tahun_sebelumnya) 
	{
		$dt_ekuitas = $this->getData_capital_statement($tgl_awal, $tgl_akhir, array(310));
		$saldo_ekuitas = $this->report->_saldo_awal($set_account['id_modal'], $tgl_awal, $tgl_akhir);

		// akunlaba ditahan dan laba sebelumnya
		$this->load->model('Admin_model','admin');
		$akun_lb_ditahan = $this->admin->GetDataById('akun', 'id_akun', $set_account['id_lb_ditahan']);
		$akun_lb_sebelum = $this->admin->GetDataById('akun', 'id_akun', $set_account['id_lb_sebelum']);

		$sum_ekuitas_debit = 0;
        $sum_ekuitas_kredit = 0;
        $sum_ekuitas = 0;
        if(isset($dt_ekuitas[0][310]))
        {
            foreach ($dt_ekuitas[0][310] as $key => $row)
            {	
            	$nilai = $row['saldo'];
            	if ($row['noakun'] == $akun_lb_ditahan['noakun']) {
            		// laba ditahan
            		$nilai += (-1)*$laba_ditahan;
            	} else if ($row['noakun'] == $akun_lb_sebelum['noakun']) {
            		// laba ditahan
            		$nilai += (-1)*$laba_tahun_sebelumnya;
            	} 

            	if($nilai <= 0) {
            		$sum_ekuitas_kredit += $nilai; 
            	} else {
            		$sum_ekuitas_debit += $nilai;
            	}
            }

            $sum_ekuitas = $sum_ekuitas_kredit + $sum_ekuitas_debit;
        }

        return $saldo_ekuitas['saldo_awal']-$sum_ekuitas;
	}

	public function balancesheet()
	{
		$data['title'] = 'Laporan Neraca';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		// menampilkan daftar nama bulan
		$data['bulan_sekarang'] = date('m');
		$data['bulan'] = $this->bulan();
		// menampilkan tahun
		$thn = date('Y');
		$data['tahun_sekarang'] = $thn;
		$data['tahun'] = array();
		$j = 0;
		for($i=0; $i < 10; $i++) {
			$data['tahun'][] = $thn-$i;
			$j++;
		}

		$this->form_validation->set_rules('bln','', 'required');
		$this->form_validation->set_rules('tahun','', 'required');

		if($this->form_validation->run() == false) {
			$data['status'] = 0;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/balancesheet', $data);
			$this->load->view('templates/footer');
		} else {
			$bln 	= $this->input->post('bln', true);
			$tahun 	= $this->input->post('tahun', true);
			$inArray = array(110,120,210,220,310,410,510,610,710,810);

			$this->load->model('Setting_model','setting');
			$this->load->model('Masterdata_model','masterdata');

			// memperoleh saldo awal akun modal
			$set_account = $this->setting->getDataById(1);

			$tgl_set = date($tahun.'-'.$bln.'-d');
			$tgl_set_sebelumnya = date(($tahun-1).'-'.$bln.'-d');
			$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
			$tglAkhirTahunSebelumnya = date('Y-12-31', strtotime($tgl_set_sebelumnya));
			$tglAwalTahun = date('Y-01-1', strtotime($tgl_set));
			$tglAkhir = date('Y-m-t', strtotime($tgl_set));


			$data['bulan_sekarang'] = $bln;
			$data['tahun_sekarang'] = $tahun;
			$data['data_akun'] = $this->report->getAccountData();
			$data['laba_ditahan'] = $this->getTotalLabaRugi($tglAwalTahun, $tglAkhir, array(410,510,610,710,810));
			$data['laba_tahun_sebelumnya'] = $this->getTotalLabaRugi($tglAwal, $tglAkhirTahunSebelumnya, array(410,510,610,710,810));

			$data['neraca_saldo_data'] = $this->getData($tglAwal, $tglAkhir, $inArray);

			// mendapatkan id akun laba ditahan dan akun laba tahun sebelumnya
			$data['akun_laba_ditahan'] = $this->masterdata->getAccountDataById($set_account['id_lb_ditahan']);
			$data['akun_laba_sebelumnya'] = $this->masterdata->getAccountDataById($set_account['id_lb_sebelum']);
			
			// echo json_encode($set_account);
			// echo json_encode($data['akun_laba_ditahan']);


			$data['status'] = 1;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/balancesheet', $data);
			$this->load->view('templates/footer');
		}
	}

	public function printbalancesheet()
	{
		$bln 	= $this->input->post('bln', true);
		$tahun 	= $this->input->post('tahun', true);
		$inArray = array(110,120,210,220,310,410,510,610,710,810);

		$this->load->model('Setting_model','setting');
		$this->load->model('Admin_model','admin');

		// memperoleh saldo awal akun modal
		$set_account = $this->setting->getDataById(1);

		$tgl_set = date($tahun.'-'.$bln.'-d');
		$tgl_set_sebelumnya = date(($tahun-1).'-'.$bln.'-d');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhirTahunSebelumnya = date('Y-12-31', strtotime($tgl_set_sebelumnya));
		$tglAwalTahun = date('Y-01-1', strtotime($tgl_set));
		$tglAkhir = date('Y-m-t', strtotime($tgl_set));


		$data['bln'] = $bln;
		$data['tahun'] = $tahun;
		$data['profile'] = $this->admin->companyProfile();
		$data['data_akun'] = $this->report->getAccountData();
		$data['laba_ditahan'] = $this->getTotalLabaRugi($tglAwalTahun, $tglAkhir, array(410,510,610,710,810));
		$data['laba_tahun_sebelumnya'] = $this->getTotalLabaRugi($tglAwal, $tglAkhirTahunSebelumnya, array(410,510,610,710,810));

		$data['neraca_saldo_data'] = $this->getData($tglAwal, $tglAkhir, $inArray);

		// mendapatkan id akun laba ditahan dan akun laba tahun sebelumnya
		$akun_laba_ditahan= $this->masterdata->getAccountDataById($set_account['id_lb_ditahan']);
		$akun_laba_sebelumnya = $this->masterdata->getAccountDataById($set_account['id_lb_sebelum']);

		$data['set_account'] = array(
			'noakun_lb_ditahan' => $akun_laba_ditahan['noakun'],
			'noakun_lb_sebelumnya' => $akun_laba_sebelumnya['noakun'],
		);


		if(isset($_POST['pdf'])) {
			$this->load->library('fpdf');
			$this->load->view('report/pdf/balancesheet', $data);
		} else {
			$this->load->view('report/excel/balancesheet', $data);
		}
	}

	public function cashflowstatement()
	{
		$data['title'] = 'Laporan Arus Kas';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		// menampilkan daftar nama bulan
		$data['bulan_sekarang'] = date('m');
		$data['bulan'] = $this->bulan();
		// menampilkan tahun
		$thn = date('Y');
		$data['tahun_sekarang'] = $thn;
		$data['tahun'] = array();
		$j = 0;
		for($i=0; $i < 10; $i++) {
			$data['tahun'][] = $thn-$i;
			$j++;
		}

		$this->form_validation->set_rules('bln','', 'required');
		$this->form_validation->set_rules('tahun','', 'required');

		if($this->form_validation->run() == false) {
			$data['status'] = 0;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/cashflowstatement', $data);
			$this->load->view('templates/footer');
		} else {
			$bln 	= $this->input->post('bln', true);
			$tahun 	= $this->input->post('tahun', true);

			$tgl_set = date($tahun.'-'.$bln.'-d');
			$tgl_set_sebelumnya = date(($tahun-1).'-'.$bln.'-d');
			$tglAwalSet = date('Y-m-d', strtotime('1/1/1971'));
			$tglAkhirTahunSebelumnya = date('Y-12-31', strtotime($tgl_set_sebelumnya));
			$tglAwalTahun = date('Y-01-1', strtotime($tgl_set));
			$tglAkhir = date('Y-m-t', strtotime($tgl_set));

			$data['bulan_sekarang'] = $bln;
			$data['tahun_sekarang'] = $tahun;
			$data['data_akun'] = $this->report->getAccountData();

			$this->load->model('Kas_model','kas');
			$dt_kas = $this->kas->getAllData();
			$data['dt_kas'] = $this->kas->data_kas_by_tgl($dt_kas, $tglAwalSet, $tglAkhirTahunSebelumnya);

			$data['saldo_awal_tahun'] = 0;
			foreach($data['dt_kas'] as $row) {
				$data['saldo_awal_tahun'] += $row['saldo_akhir'];
			}

			// Menampilkan aktivitas operasi
			$this->report->set_kategori_by_tipe_aktivitas(1);
			$data['activitas_operasi'] = $this->getArusKas($tglAwalTahun,$tglAkhir);

			// Menampilkan aktivitas operasi
			$this->report->set_kategori_by_tipe_aktivitas(2);
			$data['activitas_investasi'] = $this->getArusKas($tglAwalTahun,$tglAkhir);

			// Menampilkan aktivitas operasi
			$this->report->set_kategori_by_tipe_aktivitas(3);
			$data['activitas_pendanaan'] = $this->getArusKas($tglAwalTahun,$tglAkhir);


			$data['dt_kas'] = $this->kas->data_kas_by_tgl($dt_kas, $tglAwalSet, $tglAkhir);

			$data['saldo_akhir_tahun'] = 0;
			foreach($data['dt_kas'] as $row) {
				$data['saldo_akhir_tahun'] += $row['saldo_akhir'];
			}


			$data['status'] = 1;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/cashflowstatement', $data);
			$this->load->view('templates/footer');
		}
	}

	public function printcashflowstatement()
	{
		$bln 	= $this->input->post('bln', true);
		$tahun 	= $this->input->post('tahun', true);

		$tgl_set = date($tahun.'-'.$bln.'-d');
		$tgl_set_sebelumnya = date(($tahun-1).'-'.$bln.'-d');
		$tglAwalSet = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhirTahunSebelumnya = date('Y-12-31', strtotime($tgl_set_sebelumnya));
		$tglAwalTahun = date('Y-01-1', strtotime($tgl_set));
		$tglAkhir = date('Y-m-t', strtotime($tgl_set));

		$this->load->model('Admin_model','admin');

		$data['bln'] = $bln;
		$data['tahun'] = $tahun;
		$data['profile'] = $this->admin->companyProfile();
		$data['data_akun'] = $this->report->getAccountData();

		$this->load->model('Kas_model','kas');
		$dt_kas = $this->kas->getAllData();
		$data['dt_kas'] = $this->kas->data_kas_by_tgl($dt_kas, $tglAwalSet, $tglAkhirTahunSebelumnya);

		$data['saldo_awal_tahun'] = 0;
		foreach($data['dt_kas'] as $row) {
			$data['saldo_awal_tahun'] += $row['saldo_akhir'];
		}

		// Menampilkan aktivitas operasi
		$this->report->set_kategori_by_tipe_aktivitas(1);
		$data['activitas_operasi'] = $this->getArusKas($tglAwalTahun,$tglAkhir);

		// Menampilkan aktivitas operasi
		$this->report->set_kategori_by_tipe_aktivitas(2);
		$data['activitas_investasi'] = $this->getArusKas($tglAwalTahun,$tglAkhir);

		// Menampilkan aktivitas operasi
		$this->report->set_kategori_by_tipe_aktivitas(3);
		$data['activitas_pendanaan'] = $this->getArusKas($tglAwalTahun,$tglAkhir);

		if(isset($_POST['pdf'])) {
			$this->load->library('fpdf');
			$this->load->view('report/pdf/cashflowstatement', $data);
		} else {
			$this->load->view('report/excel/cashflowstatement', $data);
		}
	}

	public function setting()
	{
		$data['title'] = 'Setting Laporan Arus Kas';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		// Menampilkan aktivitas
		$data['activitas'] = $this->report->kel_aktivitas();

		// Menampilkan aktivitas operasi
		$this->report->set_kategori_by_tipe_aktivitas(1);
		$data['activitas_operasi'] = $this->report->kategori_arus_kas();

		// Menampilkan aktivitas operasi
		$this->report->set_kategori_by_tipe_aktivitas(2);
		$data['activitas_investasi'] = $this->report->kategori_arus_kas();

		// Menampilkan aktivitas operasi
		$this->report->set_kategori_by_tipe_aktivitas(3);
		$data['activitas_pendanaan'] = $this->report->kategori_arus_kas();
		
		// Menampilkan data akun
		$data['masterdata'] = $this->masterdata->getAccountData();

		$this->form_validation->set_rules('nama-kategori','Nama Kategori', 'required');
		$this->form_validation->set_rules('tipe-aktivitas','Tipe Aktivitas', 'required');
		$this->form_validation->set_rules('akun-sumber[]','Akun Sumber', 'required');

		if($this->form_validation->run() == false ){
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('report/setting', $data);
			$this->load->view('templates/footer');
		} else {
			$namaKategori = $this->input->post('nama-kategori', true);
			$tipeAktivitas = $this->input->post('tipe-aktivitas', true);
			$akunSumber = $this->input->post('akun-sumber[]', true);
			
			// echo json_encode($akunSumber);
			$this->report->insert_kategori();
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">New kategori Added! 
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    	<span aria-hidden="true">&times;</span>
					  	</button></div>');
			redirect('report/setting');
		}
	}

	public function delete($id)
	{
		$this->report->delete_kategori($id);
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Kategori has been deleted!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
				redirect('report/setting');
	}

	public function data_aktivitas()
	{
		$id = $this->input->post('id');
		$this->db->where('kategori.id_kategori', $id);
		$aktivitas = $this->report->aktivitas_arus_kas();
		foreach ($aktivitas as $result) {
    		$value[] = array( 
    			'id_akun' => (float) $result['id_akun'], 
    			'id_kelompok_aktivitas' => (float) $result['id_kelompok_aktivitas'],
    			'keterangan' => (float) $result['keterangan']
    		);
    	}
    	echo json_encode($value);
	}

	public function update_katagori()
	{
		$this->report->updateKategori();
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Kategori has been Updated! 
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    	<span aria-hidden="true">&times;</span>
				  	</button></div>');
		redirect('report/setting');
	}

	function getTotalSaldo()
	{
		$dt_kas = $this->report->getAllDataKas();

		$tot_saldo = 0;
		foreach($dt_kas as $row) {
			$tot_saldo += $row['saldo_awal'];
		}
		return $tot_saldo;
	}


	function getArusKas($tglAwal, $tglAkhir)
	{

		$aktivitas = $this->report->kategori_arus_kas();
		$aktivitas_arus_kas = $this->report->aktivitas_arus_kas();

		if($aktivitas)
		{
			$result = array();
			foreach ($aktivitas as $row)
			{

				$id = $row['id_kategori'];

				if($row['keterangan'] == 1)
				{
				    // kredit
					$array = array(
						'jurnal.tgl >=' => $tglAwal, 
						'jurnal.tgl <=' => $tglAkhir, 
						'jurnal_detail.tipe_kas' => 1,
						'jurnal_detail.id_perkiraan' => 2,
						'kategori.id_kategori' => $id, 
						);
					$this->db->where($array);
					$journal_data =  $this->report->getDataKas();
				
					$result1 = array();
					foreach ($journal_data as $row)
					{
						$result1[] = array(
					        'id'				=>$row['id_akun'],
					        'nama'				=>$row['nama'],
					        'tgl'				=>$row['tgl'],
					        'id_perkiraan'		=>$row['id_perkiraan'], 
					        'nilai'				=>$row['nilai'], 
					        'id_akun'			=>$row['id_akun'], 
				      	);
					}

					$result[] = array(
				        'id'					=>$row['id_kategori'],
				        'nama_kategori'			=>$row['nama_kategori'], 
				        'id_kelompok_aktivitas'	=>$row['id_kelompok_aktivitas'], 
				        'akun_sumber'			=>$result1,
			      	);
				} else {
				    // debit
					$array = array(
						'jurnal.tgl >=' => $tglAwal, 
						'jurnal.tgl <=' => $tglAkhir, 
						'jurnal_detail.tipe_kas' => 1,
						'jurnal_detail.id_perkiraan' => 1,
						'kategori.id_kategori' => $id, 
						);
					$this->db->where($array);
					$journal_data =  $this->report->getDataKas();
				
					$result1 = array();
					foreach ($journal_data as $row)
					{
						$result1[] = array(
					        'id'				=>$row['id_akun'],
					        'nama'				=>$row['nama'],
					        'tgl'				=>$row['tgl'],
					        'id_perkiraan'		=>$row['id_perkiraan'], 
					        'nilai'				=>-$row['nilai'], 
					        'id_akun'			=>$row['id_akun'], 
				      	);
					}

					$result[] = array(
				        'id'					=>$row['id_kategori'],
				        'nama_kategori'			=>$row['nama_kategori'], 
				        'id_kelompok_aktivitas'	=>$row['id_kelompok_aktivitas'], 
				        'akun_sumber'			=>$result1,
			      	);
				}
				

				}

			return $result;
		}			
	}




	function getModalAkhir($modal_awal, $tglAwal, $tglAkhir, $inArray, $totalLabarugi, $data_akun){
		$perubahan_ekuitas_data = $this->getData_capital_statement($tglAwal, $tglAkhir, $inArray);

        // Menangani Penambahan
        $sum_ekuitas_debit = 0;
        // cek apakah ada data di perubahan ekuitas
	    if(isset($perubahan_ekuitas_data[0][310]))
	    {
	        // jika total laba rugi >= 0, maka jalankan perintah dibawah
	        if($totalLabarugi >= 0) {  
            
             // Mendapatkan noakun 11 
            foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
            {
                if($row['saldo'] < 0){ 
                    	 $sum_ekuitas_debit += abs($row['saldo']); 
                    } 
                }   
                // jumlah penambahan ekuitas
                $sum_ekuitas_debit = $sum_ekuitas_debit + abs($totalLabarugi); 

   			 } else { 
		        // jika kondisi false maka, jalankan perintah dibawah 
		        // Mendapatkan noakun 11 
                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] < 0){ 
                      	$sum_ekuitas_debit += abs($row['saldo']); 
                    } 
                }   
                // jumlah penambahan ekuitas
                $sum_ekuitas_debit = $sum_ekuitas_debit; 
            }
        }
      
      	//Menangani Pengurangan
        $sum_ekuitas_kredit = 0;
        // Jika terdapat data di $perubahan_ekuitas_data
        if(isset($perubahan_ekuitas_data[0][310]))
        {
            //jika totdal laba rugi kurang dari 0
            if($totalLabarugi < 0) 
            { 
            	// Menampilkan akun selain modal
                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] > 0){  
                        $sum_ekuitas_kredit += $row['saldo']; 
                    }
                }
                // jumlah pengurangan ekuitas
                $sum_ekuitas_kredit = $sum_ekuitas_kredit + abs($totalLabarugi);
            }
            // Tutup ada total laba rugi
             else 
            //Jika tidak ada total laba rugi
            { 

                foreach ($perubahan_ekuitas_data[0][310] as $key => $row)
                {
                    if($row['saldo'] > 0){  
                       	$sum_ekuitas_kredit += $row['saldo'];
                    }
                }
                // jumlah pengurangan ekuitas
                $sum_ekuitas_kredit = $sum_ekuitas_kredit; 
            }
        }
        
        
                
            // Modal Akhir 
        $modal_akhir = $modal_awal + $sum_ekuitas_debit - $sum_ekuitas_kredit; 

        return $modal_akhir;
	}


	public function getData_capital_statement($tglAwal, $tglAkhir, $inArray){
		$this->load->model('Masterdata_model','masterdata');

		$array = array('jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		$this->report->set_account_group_id($inArray);
		$journal_data =  $this->report->getData();

		$this->masterdata->set_account_group_id($inArray);
		$account_data = $this->masterdata->getAccountData();

		// return $result= $account_data;
		if($account_data)
		{
			foreach ($account_data as $row)
			{	
				$result[0][$row['kel_akun']][$row['id_akun']] = array('nama' => $row['nama'], 'saldo' => 0, 'kel_akun' => $row['kel_akun'], 'noakun' => $row['noakun']);
			}

			if($journal_data)
			{
				foreach ($journal_data as $row)
				{
					if(isset($result[0][$row['kel_akun']][$row['id_akun']]))
					{
						if($row['id_perkiraan'] == 1)
						{
							$result[0][$row['kel_akun']][$row['id_akun']]['saldo'] += $row['nilai'];
						}
						else
						{
							$result[0][$row['kel_akun']][$row['id_akun']]['saldo'] -=  $row['nilai'];
						}
					}
				}
			}			
			return $result;
		}
	}


	public function getTotalLabaRugi($tglAwal, $tglAkhir, $inArray){
		$labaRugi = $this->getData($tglAwal, $tglAkhir, $inArray);

		// Pendapatan Operasional
        $sum_penjualan_debit = 0;
        $sum_penjualan_kredit = 0;
        if(isset($labaRugi[0][410]))
        {
            foreach ($labaRugi[0][410] as $key => $row)
            {
            	if($row['saldo'] >= 0) { 
                    $sum_penjualan_debit += $row['saldo']; 
                } else { 
                   	$sum_penjualan_kredit += abs($row['saldo']);  
               	} 
            }
        }
         //tutup pendapatan operasional 
         // tutup pendapatan non-operasional 
     	$total_pendapatan = $sum_penjualan_kredit - $sum_penjualan_debit;

         //pendapatan non-operasional -->
      	$sum_hpp_debit = 0;
      	$sum_hpp_kredit = 0;
        if(isset($labaRugi[0][510]))
        {
            foreach ($labaRugi[0][510] as $key => $row)
            {
                  if($row['saldo'] >= 0) { 
                       $sum_hpp_debit += $row['saldo']; 
                  } else { 
                      $sum_hpp_kredit += $row['saldo'];
                  }
            }
        }

        // total hpp
        $total_hpp = $sum_hpp_debit - $sum_hpp_kredit; 

        // laba rugi kotor
        $laba_kotor = $total_pendapatan - $total_hpp;

		//Looping Data Beban 
	    //beban operasional                                         
        $sum_beban_operasional_debit = 0;
        $sum_beban_operasional_kredit = 0;
        if(isset($labaRugi[0][610]))
        {
            foreach ($labaRugi[0][610] as $key => $row)
            {
                  if($row['saldo'] >= 0) { 
                      $sum_beban_operasional_debit += $row['saldo']; 
                  } else { 
                      $sum_beban_operasional_kredit += abs($row['saldo']); 
                  } 
            }
        }

		$total_beban = $sum_beban_operasional_debit - $sum_beban_operasional_kredit;

		// laba bersih operasional
		$laba_rugi_operasional = $laba_kotor - $total_beban;

		$sum_pend_beban_lain_debit = 0;
        $sum_pend_beban_lain_kredit = 0;
        if(isset($laba_rugi_data[0][710]))
        {
            foreach ($laba_rugi_data[0][710] as $key => $row)
            {
            	if($row['saldo'] >= 0) { 
            		$sum_pend_beban_lain_debit += $row['saldo'];
            	} else {
            		$sum_pend_beban_lain_kredit += abs($row['saldo']);
            	}
            }
        }

        $total_pend_beban_lain = $sum_pend_beban_lain_kredit - $sum_pend_beban_lain_debit;

        $laba_rugi_sebelum_pajak = $laba_rugi_operasional + $total_pend_beban_lain;

        $sum_beban_pajak_debit = 0;
        $sum_beban_pajak_kredit = 0;
        if(isset($laba_rugi_data[0][810]))
        {
            foreach ($laba_rugi_data[0][810] as $key => $row)
            {
            	if($row['saldo'] >= 0) {
            		$sum_beban_pajak_debit += $row['saldo'];
            	} else {
            		$sum_beban_pajak_kredit += abs($row['saldo']);
            	}
            }
        }
        $total_pajak = $sum_beban_pajak_debit-$sum_beban_pajak_kredit;

        $laba_rugi_setelah_pajak = $laba_rugi_sebelum_pajak - $total_pajak;

		return $laba_rugi_setelah_pajak;
	}


	function getData($tglAwal, $tglAkhir, $inArray)
	{
		$this->load->model('Masterdata_model','masterdata');

		$array = array('jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		$this->report->set_account_group_id($inArray);
		$journal_data =  $this->report->getData();

		$this->masterdata->set_account_group_id($inArray);
		$account_data = $this->masterdata->getAccountData();

		// return $result= $account_data;
		$saldo_awal_debit = 0;
		$saldo_awal_kredit = 0;
		if($account_data)
		{
			foreach ($account_data as $row)
			{	
				if($row['id_perkiraan'] == 1){
					// sisa saldo akun jika SNA debit
					$saldo_awal_debit = intval($row['saldo_awal']);
					$result[0][$row['kel_akun']][$row['id_akun']] = array(
						'nama' => $row['nama'], 
						'saldo' => $saldo_awal_debit, 
						'kel_akun' => $row['kel_akun'], 
						'noakun' => $row['noakun']
					);
				} else {
					// sisa saldo akun jika ini SNA nya kredit
					$saldo_awal_kredit = (-1)*intval($row['saldo_awal']);
					$result[0][$row['kel_akun']][$row['id_akun']] = array(
						'nama' => $row['nama'], 
						'saldo' => $saldo_awal_kredit, 
						'kel_akun' => $row['kel_akun'], 
						'noakun' => $row['noakun']
					);
				}
			}

			if($journal_data)
			{
				foreach ($journal_data as $row)
				{
					if(isset($result[0][$row['kel_akun']][$row['id_akun']]))
					{
						if($row['id_perkiraan'] == 1)
						{
							$result[0][$row['kel_akun']][$row['id_akun']]['saldo'] += $row['nilai'];
						}
						else
						{
							$result[0][$row['kel_akun']][$row['id_akun']]['saldo'] -=  $row['nilai'];
						}
					}
				}
			}			
			return $result;
		}
	}

	function bulan()
	{
		$list_bulan = array(
				array(
					'id' => 1,
					'nm' => 'Januari'
				),
				array(
					'id' => 2,
					'nm' => 'Februari'
				),
				array(
					'id' => 3,
					'nm' => 'Maret'
				),
				array(
					'id' => 4,
					'nm' => 'April'
				),
				array(
					'id' => 5,
					'nm' => 'Mei'
				),
				array(
					'id' => 6,
					'nm' => 'Juni'
				),
				array(
					'id' => 7,
					'nm' => 'Juli'
				),
				array(
					'id' => 8,
					'nm' => 'Agustus'
				),
				array(
					'id' => 9,
					'nm' => 'September'
				),
				array(
					'id' => 10,
					'nm' => 'Oktober'
				),
				array(
					'id' => 11,
					'nm' => 'November'
				),
				array(
					'id' => 12,
					'nm' => 'Desember'
				),
			);

		return $list_bulan;
	}
	
}




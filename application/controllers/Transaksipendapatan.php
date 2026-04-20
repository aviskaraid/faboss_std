<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksipendapatan extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Admin_model','admin');
		$this->load->model('Transaksipendapatan_model','transaksipendapatan');
		$this->load->model('Kas_model','kas');
		$this->load->model('Pendapatan_model','pendapatan');
		$this->load->model('Journal_model','journal');
		$this->load->model('Setting_model','setting');		
	}


	public function index()
	{
		$data['title'] = 'Transaksi Pendapatan';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$data['tglAwal'] = date('Y-m-01', strtotime($now));
		$data['tglAkhir'] = date('Y-m-t', strtotime($now));

		$data['dtAll'] = $this->pemrosesan($data['tglAwal'], $data['tglAkhir']);

		// data akun kas		
		$data['dtAkunKas'] = $this->kas->getAllData();

		// data akun pendapatan
		$data['dtAkunPendapatan'] = $this->pendapatan->getAllData(); 

		$data['stts'] = 0;

		$this->form_validation->set_rules('tglAwal','Tgl Awal', 'required');
		$this->form_validation->set_rules('tglAkhir','Tgl Akhir', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('transaksipendapatan/index', $data);
			$this->load->view('templates/footer');
		} else {
			// mendapatkan data
			$t_awal = $this->input->post('tglAwal', true);
			$t_akhir = $this->input->post('tglAkhir', true);
			$data['tglAwal'] = convertDateToDbdate($t_awal);
			$data['tglAkhir'] = convertDateToDbdate($t_akhir);

			// pemrosesan
			$data['dtAll'] = $this->pemrosesan($data['tglAwal'], $data['tglAkhir']);

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('transaksipendapatan/index', $data);
			$this->load->view('templates/footer');
		}
	}

	function pemrosesan($tgl_awal, $tgl_akhir)
	{
		date_default_timezone_set('Asia/Jakarta');

		$where_id = array('d.tgl >=' => $tgl_awal, 'd.tgl <=' => $tgl_akhir);
		$this->db->where($where_id);
		$dt_transaksi = $this->transaksipendapatan->getAllData();
		$result = array();
		foreach($dt_transaksi as $row) {
			$now = date("Y-m-d", strtotime($row['tgl']));
			$tahun = date('Y', strtotime($now));
	        $bulan = date('m', strtotime($now));

			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			$result[] = $row;
		}

		return $result;
	}


	public function insert()
	{
		// terima data
		$dt_user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		
		$id_kas = $this->input->post('id_kas', true);
		$dt_by_id_kas = $this->admin->GetDataById('set_account_kas', 'id_sak', $id_kas);

		$id_pendapatan = $this->input->post('id_pendapatan', true);
		$dt_by_id_pendapatan = $this->admin->GetDataById('pendapatan', 'id_pendapatan', $id_pendapatan);

		// cek id akun pendapatan
		$tgl_input = $this->input->post('tgl', true); // convert to Y-m-d
		$tgl = convertDateToDbdate($tgl_input);
		$deskripsi = $this->input->post('desk', true);
		$nominal_ = $this->input->post('nominal', true);
		$hide 		= array("Rp", ".", " ");
		$nominal 	= str_replace($hide, "", $nominal_);
		
		
		$id_akun_kas = $dt_by_id_kas['id_akun'];
		$id_akun_pendapatan = $dt_by_id_pendapatan['akun_id'];

		// mencari transaksi selanjutnya		
		$this->load->model('Setting_model','setting');

		date_default_timezone_set('Asia/Jakarta');
		$tgl_hari_ini = date("Y-m-d");
		$tahun = date('Y', strtotime($tgl_hari_ini));
        $bulan = date('m', strtotime($tgl_hari_ini));

		$type_nokas = 2;
		$set_number_auto = $this->setting->getPenomoranByIdMenu($type_nokas); 
		$no_urut = $this->journal->generate_no_trans($set_number_auto['reset_nomor'], $type_nokas, $tgl_hari_ini);
		$no_trans = sprintf('%0'.$set_number_auto['panjang_nomor'].'d', (int)$no_urut).'/'.$set_number_auto['prefix'].'/'.$bulan.'/'.$tahun;
		
		// menjurnal transaksi dahulu
		$id_jurnal = $this->journal->insertJournalTransaction($no_urut, $tgl, $deskripsi, $dt_user['id_user'], $type_nokas);

		// menjurnal detail transaksi	
		$idAkun = array($id_akun_kas, $id_akun_pendapatan);	
		$result = array(
			array(
		        'id_akun'		=> $id_akun_kas,
		        'id_perkiraan'	=> 1, 
		        'nilai'			=> $nominal, 
	      	),
			array(
		        'id_akun'		=> $id_akun_pendapatan,
		        'id_perkiraan'	=> 2, 
		        'nilai'			=> $nominal, 
	      	),
		);
		$this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);
		// simpan data di transaksi kas terima

		// upload bukti transaksi
		$bukti_transaksi = "";

		if (!empty($_FILES['bukti']['name'])) {

			$new_name = time() . rand();

			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']      = 5048; // KB
			$config['upload_path']   = FCPATH . 'assets/file/bukti/';
			$config['file_name']     = $new_name;
			$config['encrypt_name']  = FALSE;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('bukti')) {
				$bukti_transaksi = $this->upload->data('file_name');
			} else {
				// VERY IMPORTANT for debugging
				$error = $this->upload->display_errors();
				log_message('error', 'Upload bukti gagal: ' . $error);
				$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$error.'</div>');
				redirect('transaksipendapatan');
				return;
			}
		}

		// mendapatkan id jurnal, lalu disimpan di table transaksi kas
		$dt_insert_trans_pendapatan = array(
			'nilai' => $nominal,
			'pendapatan_id' => $id_pendapatan,
			'kas_id' => $id_kas,
			'jurnal_id' => $id_jurnal,
			'bukti' => $bukti_transaksi,
		);
		$this->admin->addDatabyTable('transaksi_pendapatan', $dt_insert_trans_pendapatan);

		// dikasih alert
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Transaksi Pendapatan berhasil disimpan!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('transaksipendapatan');
        

	}

	public function update()
	{
		// terima data
		$dt_user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		
		$id_trans_pendapatan = $this->input->post('id_trans_pendapatan', true);
		$dt_by_id_trans_pendapatan = $this->admin->GetDataById('transaksi_pendapatan', 'id_trans_pendapatan', $id_trans_pendapatan);
		
		$id_jurnal = $dt_by_id_trans_pendapatan['jurnal_id'];
		$dt_by_id_jurnal = $this->admin->GetDataById('jurnal', 'id_jurnal', $id_jurnal);

		$id_kas = $this->input->post('id_kas', true);
		$dt_by_id_kas = $this->admin->GetDataById('set_account_kas', 'id_sak', $id_kas);

		$id_pendapatan = $this->input->post('id_pendapatan', true);
		$dt_by_id_pendapatan = $this->admin->GetDataById('pendapatan', 'id_pendapatan', $id_pendapatan);

		// cek id akun pendapatan
		$no_trans = $dt_by_id_jurnal['no_trans'];
		$tgl_input = $this->input->post('tgl', true);
		$tgl = convertDateToDbdate($tgl_input);
		$deskripsi = $this->input->post('desk', true);
		$nominal_ = $this->input->post('nominal', true);
		$hide 		= array("Rp", ".", " ");
		$nominal 	= str_replace($hide, "", $nominal_);
		
	
		$id_akun_kas = $dt_by_id_kas['id_akun'];
		$id_akun_pendapatan = $dt_by_id_pendapatan['akun_id'];


		// menjurnal transaksi dahulu
		$this->journal->updateJournalTransaction($id_jurnal, $no_trans, $tgl, $deskripsi, $dt_user['id_user']);

		// menghapus detail jurnal dan menjurnal detail transaksi	
		$this->admin->deleteDataById('jurnal_detail', 'id_jurnal', $id_jurnal);
		$idAkun = array($id_akun_kas, $id_akun_pendapatan);	
		$result = array(
			array(
		        'id_akun'		=> $id_akun_kas,
		        'id_perkiraan'	=> 1, 
		        'nilai'			=> $nominal, 
	      	),
			array(
		        'id_akun'		=> $id_akun_pendapatan,
		        'id_perkiraan'	=> 2, 
		        'nilai'			=> $nominal, 
	      	),
		);
		$this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);		

		// mendapatkan id jurnal, lalu disimpan di table transaksi kas
		$dt_update_trans_pendapatan = array(
			'nilai' => $nominal,
			'pendapatan_id' => $id_pendapatan,
			'kas_id' => $id_kas,
		);

		$old_bukti = $this->input->post('old_bukti');

		if (!empty($_FILES['bukti']['name'])) {

			$config['upload_path']   = './assets/file/bukti/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']      = 5120;
			$config['encrypt_name']  = TRUE;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('bukti')) {
				$upload = $this->upload->data();
				$new_file = $upload['file_name'];

				// hapus file lama jika ada
				if ($old_bukti && file_exists('./assets/file/bukti/'.$old_bukti)) {
					unlink('./assets/file/bukti/'.$old_bukti);
				}

				$dt_update_trans_pendapatan['bukti'] = $new_file;
			}
		} else {
			$dt_update_trans_pendapatan['bukti'] = $old_bukti;
		}

		// simpan data di transaksi kas
		$this->admin->updateDatabyTable('transaksi_pendapatan', 'id_trans_pendapatan', $id_trans_pendapatan, $dt_update_trans_pendapatan);

		// dikasih alert
		$this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Transaksi Pendapatan berhasil diubah!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('transaksipendapatan');
        
	}

	public function delete_bukti()
	{
		$file = $this->input->post('file');

		if ($file && file_exists('./assets/file/bukti/'.$file)) {
			unlink('./assets/file/bukti/'.$file);
		}
	}

	public function delete($id)
	{
		$dt_by_id_trans_pendapatan = $this->admin->GetDataById('transaksi_pendapatan', 'id_trans_pendapatan', $id);
		$id_jurnal = $dt_by_id_trans_pendapatan['jurnal_id'];
		// menghapus jurnal detail
		$this->admin->deleteDataById('jurnal_detail', 'id_jurnal', $id_jurnal);
		$this->admin->deleteDataById('jurnal', 'id_jurnal', $id_jurnal);

		// menghapus data transaksi kas
		$this->admin->deleteDataById('transaksi_pendapatan', 'id_trans_pendapatan', $id);

		$bukti = $dt_by_id_trans_pendapatan['bukti'];
		if ($bukti && file_exists(FCPATH . 'assets/file/bukti/' . $bukti)) {
			unlink(FCPATH . 'assets/file/bukti/' . $bukti);
		}

		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Transaksi Pendapatan berhasil dihapus!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('transaksipendapatan');
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

		$dt_report = $this->transaksipendapatan->getReportData($tglAwal, $tglAkhir);
		$data['dbAll'] = $dt_report;
		
		$test = json_encode($data);
		/*
		file_put_contents(APPPATH.'logs/data_transpendapatan.txt',
			"Array:\n $test\n",
			FILE_APPEND
		);
		*/
		
		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('transaksipendapatan/transaksi_pendapatan_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('transaksipendapatan/transaksi_pendapatan_excel', $data);
		}
		
	}
}
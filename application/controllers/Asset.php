<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		if ($this->router->fetch_method() !== 'delete_gambar') {
			is_logged_in();
		}
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Asset_model','asset');
	}


	public function index()
	{
		$data['title'] = 'Daftar Aset';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");

		// menampilkan data aset
		$dt_aset_all = $this->asset->getAllData();
		//$data['dtAll'] = $this->asset->dataAllAsetTetap($dt_aset_all, $data['now']);	
		$data['dtAll'] = $dt_aset_all;	

		// echo json_encode($data['dtAll']);
		// die;

		$this->form_validation->set_rules('tgl','Tanggal', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('asset/index', $data);
			$this->load->view('templates/footer');
		} else {

			$tglAkhir = $this->input->post('tgl', true);

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('asset/index', $data);
			$this->load->view('templates/footer');
		}	
	}

	public function nonaktif()
	{
		$data['title'] = 'Daftar Aset Non Aktif';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");

		// Get all inactive assets
		$data['dtAll'] = $this->asset->getAsetNonaktif();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('asset/nonaktif', $data); // new view file
		$this->load->view('templates/footer');
	}


	public function insert()
	{
		$data['title'] = 'Tambah Aset';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Asset_model','asset');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Admin_model','admin');

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");

		// penomoran otomatis

		// menampilkan data akun
		$data['dtAkun'] = $this->masterdata->getAccountData();

		$this->form_validation->set_rules('nama','Nama Aset', 'required');
		$this->form_validation->set_rules('kode','Kode Aset', 'required');
		$this->form_validation->set_rules('lokasi','Lokasi Aset');
		$this->form_validation->set_rules('tgl','Tanggal', 'required');
		$this->form_validation->set_rules('nilai','Harga Perolehan', 'required');
		$this->form_validation->set_rules('umur','Umur (bulan)', 'required');
		$this->form_validation->set_rules('id_biaya_peny','Akun Biaya Penyusutan', 'required');
		$this->form_validation->set_rules('id_akum_peny','Akun Akumulasi Penyusutan', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('asset/add', $data);
			$this->load->view('templates/footer');
		} else {
			$tgl = convertDateToDbdate($this->input->post('tgl', true));
			$nominal_ = $this->input->post('nilai', true);
			$hide 		= array("Rp", ".", " ");
			$nominal 	= str_replace($hide, "", $nominal_);	

			// ================= FILE UPLOAD CONFIG =================
			$config['upload_path']   = './assets/file/aset/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']      = 5120; // 5 MB (in KB)
			$config['encrypt_name']  = true; // prevent overwrite & make filename unique

			$this->load->library('upload', $config);

			$gambar = null;

			if (!empty($_FILES['gambar']['name'])) {

				if ($this->upload->do_upload('gambar')) {

					$uploadData = $this->upload->data();
					$gambar = $uploadData['file_name'];

				} else {

					// If upload fails, reload form with error
					$this->session->set_flashdata('message',
						'<div class="alert alert-danger" role="alert">'
						. $this->upload->display_errors() .
						'</div>');

					$this->load->view('templates/header', $data);
					$this->load->view('templates/sidebar', $data);
					$this->load->view('templates/topbar', $data);
					$this->load->view('asset/add', $data);
					$this->load->view('templates/footer');
					return;
				}
			}
			// ======================================================

			$dt_insert = array(
				'nama' => $this->input->post('nama', true),
				'kode' => $this->input->post('kode', true),
				'lokasi' => $this->input->post('lokasi', true),
				'tgl' => $tgl,
				'nilai' => $nominal,
				'umur' => $this->input->post('umur', true),
				'id_biaya_peny' => $this->input->post('id_biaya_peny', true),
				'id_akum_peny' => $this->input->post('id_akum_peny', true),
				'gambar' => $gambar,
			);
			$this->admin->addDatabyTable('aset', $dt_insert);

			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Aset berhasil ditambahkan! 
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			  	</button></div>');
			redirect('asset');
		}
	}

	public function update($id_aset)
	{
		$data['title'] = 'Ubah Aset';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Asset_model','asset');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Admin_model','admin');

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");

		// menampilkan aset by id
		$data['dtById'] = $this->admin->GetDataById('aset', 'id_aset', $id_aset);	

		// menampilkan data akun
		$data['dtAkun'] = $this->masterdata->getAccountData();

		$this->form_validation->set_rules('nama','Nama Aset', 'required');
		$this->form_validation->set_rules('kode','Kode Aset', 'required');
		$this->form_validation->set_rules('lokasi','Lokasi Aset');
		$this->form_validation->set_rules('tgl','Tanggal', 'required');
		$this->form_validation->set_rules('nilai','Harga Perolehan', 'required');
		$this->form_validation->set_rules('umur','Umur (bulan)', 'required');
		$this->form_validation->set_rules('id_biaya_peny','Akun Biaya Penyusutan', 'required');
		$this->form_validation->set_rules('id_akum_peny','Akun Akumulasi Penyusutan', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('asset/edit', $data);
			$this->load->view('templates/footer');
		} else {
			// ================= NON AKTIF ACTION =================
			if ($this->input->post('nonaktif')) {

				// Get current asset data
				$aset = $this->admin->GetDataById('aset', 'id_aset', $id_aset);

				if (!$aset) {
					show_404();
				}

				// Optional: add timestamp of deactivation
				$aset['tgl_nonaktif'] = date('Y-m-d H:i:s');

				// Use model to move data
				$this->asset->moveToNonaktif($aset);

				$this->session->set_flashdata('message',
					'<div class="alert alert-warning" role="alert">
						Aset berhasil dinonaktifkan!
					</div>'
				);

				redirect('asset');
			}

			$tgl = convertDateToDbdate($this->input->post('tgl', true));
			$nominal_ = $this->input->post('nilai', true);
			$hide 		= array("Rp", ".", " ");
			$nominal 	= str_replace($hide, "", $nominal_);	
			
			// ================= FILE UPLOAD CONFIG =================
			$config['upload_path']   = './assets/file/aset/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']      = 5120; // 5MB
			$config['encrypt_name']  = true;

			$this->load->library('upload', $config);

			$gambar = $data['dtById']['gambar']; // default = old file

			if (!empty($_FILES['gambar']['name'])) {

				if ($this->upload->do_upload('gambar')) {

					$uploadData = $this->upload->data();
					$gambar_baru = $uploadData['file_name'];

					// Delete old file if exists
					if (!empty($data['dtById']['gambar'])) {
						$old_path = FCPATH . 'assets/file/aset/' . $data['dtById']['gambar'];
						if (file_exists($old_path)) {
							unlink($old_path);
						}
					}

					$gambar = $gambar_baru;

				} else {

					$this->session->set_flashdata('message',
						'<div class="alert alert-danger" role="alert">'
						. $this->upload->display_errors() .
						'</div>');

					$this->load->view('templates/header', $data);
					$this->load->view('templates/sidebar', $data);
					$this->load->view('templates/topbar', $data);
					$this->load->view('asset/edit', $data);
					$this->load->view('templates/footer');
					return;
				}
			}
			// ======================================================

			$dt_update = array(
				'nama' => $this->input->post('nama', true),
				'kode' => $this->input->post('kode', true),
				'lokasi' => $this->input->post('lokasi', true),
				'tgl' => $tgl,
				'nilai' => $nominal,
				'umur' => $this->input->post('umur', true),
				'id_biaya_peny' => $this->input->post('id_biaya_peny', true),
				'id_akum_peny' => $this->input->post('id_akum_peny', true),
				'gambar' => $gambar,
			);

			$this->admin->updateDatabyTable('aset', 'id_aset', $id_aset, $dt_update);

			$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Aset berhasil diubah! 
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			  	</button></div>');
			redirect('asset');
		}
	}

	public function delete($id_aset)
	{
		// menghapus data aset
		$this->load->model('Admin_model','admin');
		$this->admin->deleteDataById('aset', 'id_aset', $id_aset);

		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Aset berhasil dihapus! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
		redirect('asset');
	}

	public function delete_gambar()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('No direct access allowed', 403);
		}

		$file = $this->input->post('file', true);

		// Debug log
		file_put_contents(APPPATH.'logs/data_aset.txt',
			"Data:\n $file\n",
			FILE_APPEND
		);

		if (empty($file)) {
			http_response_code(400);
			echo "File tidak valid";
			return;
		}

		// Path to file
		$path = FCPATH . 'assets/file/aset/' . $file;

		// ================= DELETE FILE FROM SERVER =================
		if (file_exists($path) && is_file($path)) {
			unlink($path);
		}

		// ================= REMOVE FILE NAME FROM DATABASE =================
		$this->db->set('gambar', NULL);
		$this->db->where('gambar', $file);
		$this->db->update('aset');

		// Optional: check affected rows
		if ($this->db->affected_rows() > 0) {
			http_response_code(200);
			echo "success";
		} else {
			// File may exist but DB already null, still OK
			http_response_code(200);
			echo "file_deleted_db_not_found";
		}
	}

	public function print()
	{
		$this->load->model('Admin_model','admin');
		
		$data['profile'] = $this->admin->companyProfile();
		date_default_timezone_set('Asia/Jakarta');
		$data['hari_ini'] = date("Y-m-d");

		// menampilkan data aset
		$dt_aset_all = $this->asset->getAllData();
		//$data['result'] = $this->asset->dataAllAsetTetap($dt_aset_all, $data['hari_ini']);
		$data['result'] = $dt_aset_all;

		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('asset/laporan_asset_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('asset/laporan_asset_excel', $data);
		}
	}
}

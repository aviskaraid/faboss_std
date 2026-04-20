<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelompokakun extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Admin_model','admin');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Kelompokakun_model','kelompokakun');
	}

	public function index()
	{
		$data['title'] = 'Kelompok Akun';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');

		// semua data kelompok akun
		$data['dtKelompokakun'] = $this->kelompokakun->getAllData(); 

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('kelompokakun/index', $data);
		$this->load->view('templates/footer');
	}

	// menambah data kelompok akun
	public function insert()
	{	
		$result = array(
			'kel_akun' => $this->input->post('kel_akun', true),
			'nama_kel_akun' => $this->input->post('nama_kel_akun', true),
			'tipe' => $this->input->post('tipe', true),
		);
		$this->admin->addDatabyTable('kelompok_akun', $result);
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Kelompok Akun berhasil ditambahkan! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('kelompokakun');
	}

	// mengupdate data kelompok akun
	public function update()
	{
		$id_kelompok_akun = $this->input->post('id_kelompok_akun', true);
		$result = array(
			'kel_akun' => $this->input->post('kel_akun', true),
			'nama_kel_akun' => $this->input->post('nama_kel_akun', true),
			'tipe' => $this->input->post('tipe', true),
		);
		
		$this->admin->updateDatabyTable('kelompok_akun', 'id_kelompok_akun', $id_kelompok_akun, $result);
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Kelompok Akun berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('kelompokakun');
	}

	// menghapus data kelompk akun
	public function delete()
	{
		$id_kelompok_akun = $this->input->post('id_kelompok_akun', true);

		// Cek apakah masih dipakai di tabel akun
		$cek = $this->db->get_where('akun', ['id_kelompok_akun' => $id_kelompok_akun])->num_rows();

		if ($cek > 0) {
			$this->session->set_flashdata('message',
				'<div class="alert alert-warning">Kelompok Akun tidak bisa dihapus karena sudah digunakan pada data akun.</div>');
			redirect('kelompokakun');
			return;
		}

		$this->admin->deleteDataById('kelompok_akun', 'id_kelompok_akun', $id_kelompok_akun);

		$this->session->set_flashdata('message',
			'<div class="alert alert-danger">Kelompok Akun berhasil dihapus!</div>');
		redirect('kelompokakun');
	}

}
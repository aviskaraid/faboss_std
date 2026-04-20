<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biaya extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Admin_model','admin');
	}

	public function index()
	{
		$data['title'] = 'Daftar Biaya';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Biaya_model','biaya');

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');

		// semua data biaya
		$data['dtBiaya'] = $this->biaya->getAllData(); 

		// data_akun ditampilkan aset lancar
		$data['dt_akun'] = $this->masterdata->getAccountData();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('biaya/index', $data);
		$this->load->view('templates/footer');
	}

	// menambah data akun biaya
	public function insert()
	{	
		$result = array(
			'kode' => $this->input->post('kode', true),
			'nm' => $this->input->post('nama', true),
			'akun_id' => $this->input->post('akun_id', true),
		);
		$this->admin->addDatabyTable('biaya', $result);
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Biaya berhasil ditambahkan! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('biaya');
	}

	// mengupdate data akun biaya
	public function update()
	{
		$id_biaya = $this->input->post('id_biaya', true);
		$result = array(
			'kode' => $this->input->post('kode', true),
			'nm' => $this->input->post('nama', true),
			'akun_id' => $this->input->post('akun_id', true),
		);
		$this->admin->updateDatabyTable('biaya', 'id_biaya', $id_biaya, $result);
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Biaya berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('biaya');
	}

	// menghapus data akun biaya
	public function delete()
	{
		$id_biaya = $this->input->post('id_biaya', true);
		$this->admin->deleteDataById('biaya', 'id_biaya', $id_biaya);
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Biaya berhasil dihapus! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('biaya');
	}
}
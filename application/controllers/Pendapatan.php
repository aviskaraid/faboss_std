<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendapatan extends CI_Controller 
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
		$data['title'] = 'Daftar Pendapatan';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Pendapatan_model','pendapatan');

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');

		// semua data pendapatan
		$data['dtPendapatan'] = $this->pendapatan->getAllData(); 

		// data_akun ditampilkan aset lancar
		$data['dt_akun'] = $this->masterdata->getAccountData();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('pendapatan/index', $data);
		$this->load->view('templates/footer');
	}

	// menambah data akun pendapatan
	public function insert()
	{	
		$result = array(
			'kode' => $this->input->post('kode', true),
			'nm' => $this->input->post('nama', true),
			'akun_id' => $this->input->post('akun_id', true),
		);
		$this->admin->addDatabyTable('pendapatan', $result);
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Pendapatan berhasil ditambahkan! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('pendapatan');
	}

	// mengupdate data akun pendapatan
	public function update()
	{
		$id_pendapatan = $this->input->post('id_pendapatan', true);
		$result = array(
			'kode' => $this->input->post('kode', true),
			'nm' => $this->input->post('nama', true),
			'akun_id' => $this->input->post('akun_id', true),
		);
		$this->admin->updateDatabyTable('pendapatan', 'id_pendapatan', $id_pendapatan, $result);
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Pendapatan berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('pendapatan');
	}

	// menghapus data akun pendapatan
	public function delete()
	{
		$id_pendapatan = $this->input->post('id_pendapatan', true);
		$this->admin->deleteDataById('pendapatan', 'id_pendapatan', $id_pendapatan);
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Pendapatan berhasil dihapus! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('pendapatan');
	}
}
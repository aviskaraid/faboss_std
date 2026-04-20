<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Kas_model','kas');
	}

	public function index()
	{
		$data['title'] = 'Kas Tunai & Bank';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');
		// semua data kas
		$dt_kas = $this->kas->getAllData();
		$data['dt_kas'] = $this->kas->data_kas_by_tgl($dt_kas, $tglAwal, $tglAkhir);


		// data_akun ditampilkan aset lancar
		//$this->db->where_in('kelompok_akun.id_kelompok_akun', 1);
		$data['dt_akun'] = $this->masterdata->getAccountData();
		

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('kas/index', $data);
		$this->load->view('templates/footer');
	}

	// menambah data akun kas
	public function insert()
	{
		$this->kas->insert();
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Akun Kas berhasil ditambahkan! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('kas');
	}

	// mengupdate data akun kas
	public function update()
	{
		$id = $this->input->post('id_sak', true);
		$this->kas->update($id);
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Akun Kas berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('kas');
	}

	// menghapus data akun kas
	public function delete()
	{
		$id = $this->input->post('id_sak', true);
		$this->kas->delete($id);
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Akun Kas berhasil dihapus! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('kas');
	}
}
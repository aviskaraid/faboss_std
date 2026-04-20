<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Supplier_model','supplier');
	}

	public function index()
	{
		$data['title'] = 'Supplier';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');
		// semua data supplier
		$data['dt_supplier'] = $this->supplier->getAllData();
		
		// data_akun ditampilkan
		//$this->db->where_in('kelompok_akun.id_kelompok_akun', 1);
		$data['dt_akun'] = $this->masterdata->getAccountData();
		

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('supplier/index', $data);
		$this->load->view('templates/footer');
	}

	// menambah data akun supplier
	public function insert()
	{
		$this->supplier->insert();
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Supplier berhasil ditambahkan! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('supplier');
	}

	// mengupdate data akun supplier
	public function update()
	{
		$id = $this->input->post('id_supplier', true);
		$this->supplier->update($id);
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Supplier berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('supplier');
	}

	// menghapus data akun supplier
	public function delete()
	{
		$id = $this->input->post('id_supplier', true);
		$this->supplier->delete($id);
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Supplier berhasil dihapus! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('supplier');
	}
}
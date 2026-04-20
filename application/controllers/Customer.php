<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Customer_model','customer');
	}

	public function index()
	{
		$data['title'] = 'Customer';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		date_default_timezone_set('Asia/Jakarta');
		$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		$tglAkhir = date('Y-m-d');
		// semua data customer
		$data['dt_customer'] = $this->customer->getAllData();
		
		// data_akun ditampilkan aset lancar
		$data['dt_akun'] = $this->masterdata->getAccountData();
		

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('customer/index', $data);
		$this->load->view('templates/footer');
	}

	// menambah data akun customer
	public function insert()
	{
		$this->customer->insert();
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Customer berhasil ditambahkan! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('customer');
	}

	// mengupdate data akun customer
	public function update()
	{
		$id = $this->input->post('id_customer', true);
		$this->customer->update($id);
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Customer berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('customer');
	}

	// menghapus data akun customer
	public function delete()
	{
		$id = $this->input->post('id_customer', true);
		$this->customer->delete($id);
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Customer berhasil dihapus! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('customer');
	}
}
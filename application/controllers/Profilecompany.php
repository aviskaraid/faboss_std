<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profilecompany extends CI_Controller 
{ 
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
	}

	public function index()
    {
    	$data['title'] = 'Profile Perusahaan';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Admin_model','admin');

		date_default_timezone_set('Asia/Jakarta');
		$data['date'] = date("Y-m-d");
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('profilecompany/index', $data);
		$this->load->view('templates/footer');
    }

    public function edit_profile_perusahaan($id)
    {
    	$data['title'] = 'Edit Profile Perusahaan';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => $id])->row_array();
		$this->load->model('Admin_model','admin');

		date_default_timezone_set('Asia/Jakarta');
		$data['date'] = date("Y-m-d");

		$this->form_validation->set_rules('name','Role', 'required');
		$this->form_validation->set_rules('content','content', 'required');
		if( $this->form_validation->run() == false ){
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('profilecompany/edit_profile_perusahaan', $data);
			$this->load->view('templates/footer');
		} else {
			$name = $this->input->post('name');
			$description = $this->input->post('content');
			$alamat = $this->input->post('alamat');
			$telp = $this->input->post('telp');
			$npwp = $this->input->post('npwp');
			$id_user = $this->input->post('idUser');
			
			$this->db->set('name', $name);
        	$this->db->set('deskripsi', $description);
			$this->db->set('alamat', $alamat);
			$this->db->set('telp', $telp);
			$this->db->set('npwp', $npwp);
        	$this->db->set('id_user', $id_user);

        	$this->db->where('id_profile', $this->input->post('id'));
			$this->db->update('profile');

			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profile Company has been updated!
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			  	</button></div>');
    		redirect('profilecompany/');
		}
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{ 
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{
		if( $this->session->userdata('email')) {
			redirect('user');
		}

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if( $this->form_validation->run() == false ){
			$data['title'] = 'Login Aplikasi';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else { 

			//validasinya success
			$this->_login();
			
		}
	}

	private function _login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email ])->row_array();

		//jika usernya ada
		if($user) {
			//jika userny aktif
			if($user['is_active'] == 1) {
				if(password_verify($password, $user['password'])){
					$data = [
						'email' 	=> $user['email'],
						'id_role' 	=> $user['id_role'],
					];

					$this->session->set_userdata($data);

					if($user['id_role'] == 3){
						redirect('transaksikas');
					} else {
						redirect('dashboard');
					}
					
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This Email has not ben activated!</div>');
				redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered!</div>');
			redirect('auth');
		}
	}

	public function registration()
	{
		if( $this->session->userdata('email')) {
			redirect('user');
		}
		
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
			'is_unique' => 'This email has already registered!'
			]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches' => 'password dont match!',
			'min_length' => 'Password too short'
			]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');


		if( $this->form_validation->run() == false ){
			$data['title'] = 'WPU User Registration';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration');
			$this->load->view('templates/auth_footer');
		} else {
			$data = [
				'name' => htmlspecialchars( $this->input->post('name', true)),
				'email' => htmlspecialchars( $this->input->post('email', true)),
				'image' => 'default.jpg', 
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'id_role' => 2,
				'is_active' => 1,
				'date_created' => time()
			];

			$this->db->insert('user', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Congratulation! Your account has been created. Please Login</div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('id_role');
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have ben logged out!!</div>');
		redirect('auth');
	}

	public function blocked()
	{
		$this->load->view('auth/blocked');
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Setting_model','setting');
	}

	public function index()
	{
		$data['title'] = 'Setting Account';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Journal_model','journal');

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");
		$tahun = date('Y', strtotime($data['now']));
        $bulan = date('m', strtotime($data['now']));

		// semua data setting
		$data['dt_setting'] = $this->setting->getDataById(1);

		// data_akun ditampilkan aset lancar
		// $this->db->where_in('kelompok_akun.id_kelompok_akun', 5);
		// $data['dt_akun'] = $this->masterdata->getAccountData();

		// data akun
		$data['dt_akun'] = $this->setting->getDataAkun();

		// data kelompok akun utk hpp
		$data['dt_kel_akun'] = $this->setting->getDataKelAkun();

		// penomoran otomatis
		// menu jurnal umum
		$type_ju = 1;
		$data['idSetNoJU'] = $this->setting->getPenomoranByIdMenu($type_ju); 
		$data['no_urut_ju'] = $this->journal->generate_no_trans($data['idSetNoJU']['reset_nomor'], $type_ju, $data['now']);
		$data['no_trans_ju'] = sprintf('%0'.$data['idSetNoJU']['panjang_nomor'].'d', (int)$data['no_urut_ju']).'/'.$data['idSetNoJU']['prefix'].'/'.$bulan.'/'.$tahun;

		// mencari nomor berikutnya ju
		// menu transaksi kas
		$type_nokas = 2;
		$data['idSetNoKas'] = $this->setting->getPenomoranByIdMenu($type_nokas); 
		$data['no_urut_nokas'] = $this->journal->generate_no_trans($data['idSetNoKas']['reset_nomor'], $type_nokas, $data['now']);
		$data['no_trans_nokas'] = sprintf('%0'.$data['idSetNoKas']['panjang_nomor'].'d', (int)$data['no_urut_nokas']).'/'.$data['idSetNoKas']['prefix'].'/'.$bulan.'/'.$tahun;
		// mencari nomor berikutnya transaksi kas

		// menu piutang
		$type_piut = 3;
		$data['idSetNoPiut'] = $this->setting->getPenomoranByIdMenu($type_piut); 
		$data['no_urut_piut'] = $this->journal->generate_no_trans($data['idSetNoPiut']['reset_nomor'], $type_piut, $data['now']);
		$data['no_trans_piut'] = sprintf('%0'.$data['idSetNoPiut']['panjang_nomor'].'d', (int)$data['no_urut_piut']).'/'.$data['idSetNoPiut']['prefix'].'/'.$bulan.'/'.$tahun;
		// mencari nomor berikutnya piutang
		// menu hutang
		$type_hut = 4;
		$data['idSetNoHut'] = $this->setting->getPenomoranByIdMenu($type_hut); 
		$data['no_urut_hut'] = $this->journal->generate_no_trans($data['idSetNoHut']['reset_nomor'], $type_hut, $data['now']);
		$data['no_trans_hut'] = sprintf('%0'.$data['idSetNoHut']['panjang_nomor'].'d', (int)$data['no_urut_hut']).'/'.$data['idSetNoHut']['prefix'].'/'.$bulan.'/'.$tahun;
		// mencari nomor berikutnya hutang
		// menu depresiasi
		$type_dep = 5;
		$data['idSetNoDep'] = $this->setting->getPenomoranByIdMenu($type_dep); 
		$data['no_urut_dep'] = $this->journal->generate_no_trans($data['idSetNoDep']['reset_nomor'], $type_dep, $data['now']);
		$data['no_trans_dep'] = sprintf('%0'.$data['idSetNoDep']['panjang_nomor'].'d', (int)$data['no_urut_dep']).'/'.$data['idSetNoDep']['prefix'].'/'.$bulan.'/'.$tahun;
		// mencari nomor berikutnya depresiasi


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('setting/index', $data);
		$this->load->view('templates/footer');
	}

	// mengupdate data akun modal
	public function update()
	{
		$this->setting->update();
		$this->session->set_flashdata('message','<div class="alert alert-primary" role="alert">Akun berhasil diupdate! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('setting');
	}

	// simpan update data penomoran otomatis
	public function update_nomor_otomatis()
	{
		$this->load->model('Admin_model','admin');
		$id_menu = $this->input->post('id_menu', true);

		$dtArray = array(
			'prefix' => $this->input->post('prefix', true),
			'reset_nomor' => $this->input->post('reset_nomor', true),
			'panjang_nomor' => $this->input->post('panjang_nomor', true),

		);

		$this->admin->updateDatabyTable('set_nomor_otomatis', 'id_menu', $id_menu, $dtArray);

		if($id_menu == 1) {
			$nm_menu = 'Jurnal Umum';
		} else if($id_menu == 2) {
			$nm_menu = 'Piutang';
		} else if($id_menu == 3) {
			$nm_menu = 'Hutang';
		} else if($id_menu == 4) {
			$nm_menu = 'Jurnal Depresiasi';
		} else {
			$nm_menu = 'Tidak ditemukan';
		}
		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Penomoran '.$nm_menu.' berhasil diubah! 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('setting');
	}
}
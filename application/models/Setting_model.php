<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model
{
	// menampilkan semua
	public function getAllData()
	{
		$this->db->select('*');
		$this->db->from('set_account');
		$this->db->join('akun', 'set_account.id_akun = akun.id_akun', 'left');
		$this->db->order_by("id_sa", "asc");
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getDataById($id)
	{
		$this->db->select('*');
		$this->db->from('set_account');
		$this->db->where('id_sa', $id);
		return $this->db->get()->row_array();
	}

	public function getDataAkun()
	{
		$this->db->select('*');
		$this->db->from('akun');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getDataKelAkun()
	{
		$this->db->select('*');
		$this->db->from('kelompok_akun');
		$query = $this->db->get()->result_array();
		return $query;
	}

	// mengubah data
	public function update()
	{
		$data = [
			'id_modal' => $this->input->post('id_modal', true),
			'id_lb_ditahan' => $this->input->post('id_lb_ditahan', true),
			'id_lb_sebelum' => $this->input->post('id_lb_sebelum', true),
			'id_kel_hpp' => $this->input->post('id_kel_hpp', true),
		];

		$this->db->where('id_sa', 1);
		$this->db->update('set_account', $data);

		// update akun laba ditahan
		$dt_lb_ditahan = array(
			"id_akun" 		=> $this->input->post('id_lb_ditahan', true),			
		);

		$this->db->where('id_setting', 1);
		$this->db->update('set_account_system', $dt_lb_ditahan);

		$dt_lb_berjalan = array(
			"id_akun" 		=> $this->input->post('id_lb_sebelum', true),			
		);

		// update akun laba berjalan
		$this->db->where('id_setting', 2);
		$this->db->update('set_account_system', $dt_lb_berjalan);

		
	}

	// penomoran otomatis
	public function getPenomoranByIdMenu($id)
	{
		$this->db->select('*');
		$this->db->from('set_nomor_otomatis');
		$this->db->where_in('id_menu', $id);
		return $this->db->get()->row_array();
	}

}	
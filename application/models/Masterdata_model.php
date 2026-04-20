<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterdata_model extends CI_Model
{
	public function getAccountData()
	{
		$this->db->select('akun.*, kelompok_akun.nama_kel_akun, kelompok_akun.kel_akun');
		$this->db->from('akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->order_by('kelompok_akun.kel_akun', 'ASC');
		$this->db->order_by('akun.id_perkiraan', 'ASC');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function set_account_group_id($id){
		$this->db->where_in('kelompok_akun.kel_akun', $id);
	}
	
	public function insertData($saldoAwal)
	{
		$data = [
	        "noakun" => $this->input->post('noAkun', true),
	        "id_kelompok_akun" => $this->input->post('tipeAkun', true),
	        "nama" => $this->input->post('namaAkun', true),
	        "id_perkiraan" => $this->input->post('perkiraan', true),
	        "saldo_awal" => $saldoAwal,
        	"id_user" => $this->input->post('idUser', true)
		];

		$this->db->insert('akun', $data);
	}

	public function getAccountDataById($id)
	{	
		$this->db->select('akun.*, kelompok_akun.nama_kel_akun, kelompok_akun.kel_akun');
		$this->db->from('akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->where('akun.id_akun', $id);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function getAccountGroupName($id)
	{
		return $this->db->get_where('kelompok_akun', ['id_kelompok_akun' => $id])->row_array();
	}

	public function deleteAccountData($id)
	{
		return $this->db->delete('akun', ['id_akun' => $id]);
	}

	public function getPerkiraan()
	{
		return $this->db->get('perkiraan')->result_array();
	}

	public function getIdPerkiraan($id)
	{
		return $this->db->get_where('perkiraan', ['id_perkiraan' => $id])->row_array();
	}

	public function editAccountData($saldoAwal)
	{
		$data = [
        "id_kelompok_akun" => $this->input->post('tipeAkun', true),
        "noAkun" => $this->input->post('noAkun', true),
        "nama" => $this->input->post('namaAkun', true),
        "id_perkiraan" => $this->input->post('perkiraan', true),
        "saldo_awal" => $saldoAwal,
        "id_user" => $this->input->post('idUser', true)
		];

		$this->db->where('id_akun', $this->input->post('id'));
		$this->db->update('akun', $data);
	}

}
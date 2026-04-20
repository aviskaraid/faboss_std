<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas_model extends CI_Model
{
	// menampilkan semua
	public function getAllData()
	{
		$this->db->select('*');
		$this->db->from('set_account_kas');
		$this->db->join('akun', 'set_account_kas.id_akun = akun.id_akun');
		$this->db->order_by("id_sak", "asc");
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getTotalSaldo()
	{
		$dt_kas = $this->getAllData();
		$tot_saldo = 0;
		foreach($dt_kas as $row){
			$tot_saldo += $row['saldo_awal'];
		}

		return $tot_saldo;
	}

	function getIdAkunKas()
	{
		$this->db->select('set_account_kas.id_akun');
		$this->db->from('set_account_kas');
		$this->db->order_by("id_sak", "asc");
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getArrayIdKas()
	{
		$akun = $this->getIdAkunKas();

		$array_result = array();
		foreach($akun as $row){
			$array_result[] = intval($row['id_akun']);
		}

		return $array_result;
	}

	// menampilkan data berdasarkan id
	public function getDataById($id)
	{
		return $this->db->get_where('set_account_kas', ['id_sak' => $id])->row_array();
	}

	// menambah data kas
	public function insert()
	{
		$data = [
			'nm' => $this->input->post('nm_kas', true),
			'desk' => $this->input->post('desk', true),
			'id_akun' => $this->input->post('id_akun', true),
			'date_created' => time(),
		];

		$this->db->insert('set_account_kas', $data);
	}

	// mengubah data kas
	public function update($id)
	{
		$data = [
			'nm' => $this->input->post('nm', true),
			'desk' => $this->input->post('desk', true),
			'id_akun' => $this->input->post('id_akun', true),
		];

		$this->db->where('id_sak', $id);
		$this->db->update('set_account_kas', $data);
	}
	// hapus data kas
	public function delete($id)
	{
		return $this->db->delete('set_account_kas', ['id_sak' => $id]);
	}


	public function data_kas_by_tgl($data, $tgl_awal, $tgl_akhir)
	{		
		$this->load->model('Report_model','report');
		$result = array();
		foreach($data as $row) {
			// mencari transaksi by id_akun
			$dt_kas = $this->report->_saldo_awal($row['id_akun'], $tgl_awal, $tgl_akhir);

			$result[] = array(
				"id_sak" => $row['id_sak'],
				"noakun" => $row['noakun'],
				"nm" => $row['nm'],
				"desk" => $row['desk'],
				"saldo_awal" => $row['saldo_awal'],
				"id_akun" => $row['id_akun'],
				"saldo_akhir" => $dt_kas['saldo_awal'],
			);

		}

		return $result;
	}
}
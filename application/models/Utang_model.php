<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utang_model extends CI_Model
{
	public function getAllData()
	{
		$this->db->select('a.*, b.*');
		$this->db->from('utang a');
		$this->db->join('jurnal b', 'a.jurnal_id = b.id_jurnal', 'left');
		$this->db->order_by('b.tgl', 'DESC');
		return $this->db->get()->result_array();
	}

	public function get_terbayar($id_utang)
	{
		$this->db->select('a.*, b.nm');
		$this->db->from('utang_bayar a');
		$this->db->join('set_account_kas b', 'a.kas_id = b.id_sak', 'left');
		$this->db->where_in('a.utang_id', $id_utang);
		return $this->db->get()->result_array();
	}

}
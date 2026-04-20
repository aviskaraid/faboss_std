<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendapatan_model extends CI_Model
{
	public function getAllData()
	{
		$this->db->select('a.*, b.noakun, b.nama');
		$this->db->from('pendapatan a');
		$this->db->join('akun b', 'a.akun_id = b.id_akun');
		$this->db->order_by('a.id_pendapatan', 'DESC');
		return $this->db->get()->result_array();		
	}		
}
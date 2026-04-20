<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelompokakun_model extends CI_Model
{
	public function getAllData()
	{
		$this->db->select('*,
		(CASE
			WHEN tipe="A" THEN "Aktiva"
			WHEN tipe="P" THEN "Pasiva"
			WHEN tipe="L" THEN "Pendapatan"
			WHEN tipe="B" THEN "Biaya"
			ELSE "-"
		END) AS nama_tipe
		');
		$this->db->from('kelompok_akun');
		$this->db->order_by('id_kelompok_akun');
		return $this->db->get()->result_array();		
	}		
}
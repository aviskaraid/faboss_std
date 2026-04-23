<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umurpiutang_model extends CI_Model
{
	public function getAllData()
	{
		$this->db->select('a.id_piutang,
			a.id_customer, 
			a.id_pendapatan,
			a.nilai, 
			a.jurnal_id, 
			a.no_ref, 
			a.tgl_invoice, 
			a.jt_tempo, 
			a.deskripsi,
			a.bukti,
			b.no_trans,
			b.type,
			b.tgl,
			c.nama_customer'

		);
		$this->db->from('piutang a');
		$this->db->join('jurnal b', 'a.jurnal_id = b.id_jurnal', 'left');
		$this->db->join('customer c', 'a.id_customer = c.id_customer', 'left');
		$this->db->order_by('b.tgl', 'DESC');
		return $this->db->get()->result_array();
	}

	public function getCustomerData()
	{
		$this->db->select('a.kode, a.id_customer, a.nama_customer, b.noakun');
		$this->db->from('customer a');
		$this->db->join('akun b', 'a.id_akun = b.id_akun', 'left');
		$this->db->order_by('a.nama_customer', 'ASC');
		return $this->db->get()->result_array();
	}

	public function getAkunCustomerById($id)
	{
		$this->db->select('p.id_piutang, p.id_customer, c.id_akun');
		$this->db->from('piutang p');
		$this->db->join('customer c', 'p.id_customer = c.id_customer', 'left');
		$this->db->where('p.id_piutang', $id);
		return $this->db->get()->row_array();
	}


	public function get_terbayar($id_piutang)
	{
		$this->db->select('a.*, b.nm');
		$this->db->from('piutang_bayar a');
		$this->db->join('set_account_kas b', 'a.kas_id = b.id_sak', 'left');
		$this->db->where_in('a.piutang_id', $id_piutang);
		return $this->db->get()->result_array();
	}

}
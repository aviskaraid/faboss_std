<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utang_model extends CI_Model
{
	public function getAllData()
	{
		$this->db->select('a.id_utang,
			a.id_supplier, 
			a.id_biaya,
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
			b.posted,
			c.nama_supplier'

		);
		$this->db->from('utang a');
		$this->db->join('jurnal b', 'a.jurnal_id = b.id_jurnal', 'left');
		$this->db->join('supplier c', 'a.id_supplier = c.id_supplier', 'left');
		$this->db->order_by('b.tgl', 'DESC');
		return $this->db->get()->result_array();
	}

	public function getSupplierData()
	{
		$this->db->select('a.kode, a.id_supplier, a.nama_supplier, b.noakun');
		$this->db->from('supplier a');
		$this->db->join('akun b', 'a.id_akun = b.id_akun', 'left');
		$this->db->order_by('a.nama_supplier', 'ASC');
		return $this->db->get()->result_array();
	}


	public function getAkunSupplierById($id)
	{
		$this->db->select('u.id_utang, u.id_supplier, s.id_akun');
		$this->db->from('utang u');
		$this->db->join('supplier s', 'u.id_supplier = s.id_supplier', 'left');
		$this->db->where('u.id_utang', $id);
		return $this->db->get()->row_array();
	}

	public function get_bayar_posted($id_utang_bayar)
	{
		$this->db->select('a.id_utang_bayar, a.jurnal_id, b.posted');
		$this->db->from('utang_bayar a');
		$this->db->join('jurnal b', 'a.jurnal_id = b.id_jurnal', 'left');
		$this->db->where_in('a.id_utang_bayar', $id_utang_bayar);
		return $this->db->get()->result_array();
	}

	public function get_terbayar($id_utang)
	{
		$this->db->select('a.*, b.nm, c.posted');
		$this->db->from('utang_bayar a');
		$this->db->join('set_account_kas b', 'a.kas_id = b.id_sak', 'left');
		$this->db->join('jurnal c', 'a.jurnal_id = c.id_jurnal', 'left');
		$this->db->where_in('a.utang_id', $id_utang);
		return $this->db->get()->result_array();
	}

}
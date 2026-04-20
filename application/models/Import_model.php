<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class import_model extends CI_Model
{
	public function getAllData()
	{	
		$this->db->select('*');
		$this->db->from('import');
		$this->db->order_by("id_import", "asc");
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getDataById($id)
	{
		return $this->db->get_where('import', ['id_import' => $id])->row_array();
	}

	public function getDataDetailById($id)
	{
		return $this->db->get_where('import_detail', ['id_import' => $id])->result_array();
	}

	public function deleteData($id)
	{
		return $this->db->delete('import', ['id_import' => $id]);
	}

	// insert kedatabase
	public function insert_import()
	{
		$data = [
			'ket' => $this->input->post('desk', true),
			'date_created' => time(),
		];

		$this->db->insert('import', $data);
		$id_import 	= $this->db->insert_id();

		return $id_import;
	}

	public function insert_import_detail($id_import, $id_jurnal)
	{
		$data = [
			'id_import' => $id_import,
			'id_jurnal' => $id_jurnal,
		];

		$this->db->insert('import_detail', $data);
	}

	public function insert_transaksi($no_trans, $tgl, $desk, $id_user, $detail){
	    //insert tabel jurnal
		date_default_timezone_set("Asia/Bangkok");
		$data = [
			"id_jurnal" 	=> "",
			"no_trans" 		=> $no_trans,
			"tgl"	 		=> $tgl,
			"keterangan" 	=> $desk,
    		"id_user" 		=> $id_user
		];

		$this->db->insert('jurnal', $data);
			//GET ID PACKAGE
		$jurnal_id 	= $this->db->insert_id();

	    //insert tabel detail jurnal
	    $result = array();
	   	foreach($detail AS $row){
		     $result[] = array(
			     	'id_jurnal_detail' 	=> '',
			     	'id_jurnal' 		=> $jurnal_id,
			        'id_akun'			=> $row['id_akun'],
			        'tipe_kas'			=> $row['tipe_kas'],
			        'id_perkiraan'		=> $row['id_perkiraan'],
			        'nilai'				=> $row['nilai'],
		      );
	    }    

		//MULTIPLE INSERT TO DETAIL TABLE
		$this->db->insert_batch('jurnal_detail', $result);
		return $jurnal_id;
	}

	public function delete_journal($id)
	{
		$this->db->delete('jurnal_detail', array('id_jurnal' => $id));
		$this->db->delete('jurnal', array('id_jurnal' => $id));
	}

	public function delete_import($id)
	{
		$this->db->delete('import_detail', array('id_import' => $id));
		$this->db->delete('import', array('id_import' => $id));
	}
	


}
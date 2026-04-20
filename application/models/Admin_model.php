<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
	public function getRoleById($id)
	{
		return $this->db->get_where('user_role', ['id_role' => $id])->row_array();
	}

	public function getAllDataById($table, $primary, $id)
	{
		return $this->db->get_where($table, [$primary => $id])->result_array();
	}

	public function editRole()
	{
		$data = [
        	"role" => $this->input->post('role', true)
		];

		$this->db->where('id_role', $this->input->post('id'));
		$this->db->update('user_role', $data);
	}

	public function deleteRole($id)
	{
		return $this->db->delete('user_role', ['id_role' => $id]);
	}

	public function getUser()
	{	
		$this->db->select('*');
		$this->db->from('user');
		$this->db->order_by("id_role", "asc");
		return $this->db->get()->result_array();
	}

	public function getUserById($id)
	{
		return $this->db->get_where('user', ['id_user' => $id])->row_array();
	}

	public function deleteUser($id)
	{
		return $this->db->delete('user', ['id_user' => $id]);
	}

	public function count($table)
    {
        return $this->db->count_all($table);
    }

	public function companyProfile()
	{
		return $this->db->get_where('profile', ['id_profile' => 1])->row_array();
	}





	// function model untuk keseluruhan
	public function getAllByTable($table, $primary, $urut)
	{	
		$this->db->select('*');
		$this->db->from($table);
		$this->db->order_by($primary, $urut);
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getAllByTableById($table, $primary, $urut, $id_where, $value)
	{	
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($id_where, $value);
		$this->db->order_by($primary, $urut);
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getAllByTableById2($table, $id_where, $value)
	{	
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($id_where, $value);
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function GetDataById($table, $primary, $value)
	{
		return $this->db->get_where($table, [$primary => $value])->row_array();
	}

	public function addDatabyTable($table,$data_array)
	{	
		$this->db->insert($table, $data_array);
	}

	public function updateDatabyTable($table, $primary, $value, $data_array)
	{	
		$this->db->where($primary, $value);
		$this->db->update($table, $data_array);
	}

	public function deleteDataById($table, $primary, $value)
	{
		return $this->db->delete($table, [$primary => $value]);
	}
}
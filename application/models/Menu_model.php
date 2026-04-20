<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{
	public function getUserAccess($id_role, $id_sub_menu)
	{
		$this->db->select('*');
		$this->db->from('user_access_menu');
		$array = array('role_id' => $id_role, 'sub_menu_id' => $id_sub_menu);
		$this->db->where($array);
		$query = $this->db->get()->row_array();
		return $query;
	}


	public function getSubMenu($id)
	{
		$this->db->select('user_sub_menu.*, user_menu.menu');
		$this->db->from('user_sub_menu');
		$this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');
        $this->db->where_in('user_sub_menu.menu_id', $id);
		$this->db->order_by("user_sub_menu.id", "desc");
		return $this->db->get()->result_array();
	}

	public function getMenu($id)
	{
		return $this->db->get_where('user_menu', ['id' => $id])->row_array();
	}

	public function getMenuById($id)
	{
		return $this->db->get_where('user_menu', ['id' => $id])->row_array();
	}

	public function editMenu()
	{
		$data = [
	        "menu" => $this->input->post('menu', true)
		];

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('user_menu', $data);
	}

	public function deleteMenu($id)
	{
		return $this->db->delete('user_menu', ['id' => $id]);
	}

	public function getSubmenuById($id)
	{
		return $this->db->get_where('user_sub_menu', ['id' => $id])->row_array();
	}

	public function editSubmenu()
	{
		$data = [
			'title' => $this->input->post('title'),
			'menu_id' => $this->input->post('menu_id'),
			'url' => $this->input->post('url'),
			'is_active' => $this->input->post('is_active')
		];

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('user_sub_menu', $data);
	}

	public function deleteSubmenu($id)
	{
		return $this->db->delete('user_sub_menu', ['id' => $id]);
	}

}
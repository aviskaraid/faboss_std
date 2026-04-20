<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	//data akun
	public function getRoleId()
	{
		return $this->db->get('user_role')->result_array();
	}
}
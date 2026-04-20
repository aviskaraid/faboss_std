<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model
{
	// menampilkan semua
	public function getAllData()
	{
		$this->db->select('
			customer.id_customer,
			customer.kode,
			customer.nama_customer,
			customer.alamat_customer,
			customer.id_akun,
			akun.noakun,
			COALESCE(SUM(
				CASE 
					WHEN jurnal_detail.id_perkiraan = 1 THEN jurnal_detail.nilai
					WHEN jurnal_detail.id_perkiraan = 2 THEN -jurnal_detail.nilai
					ELSE 0
				END
			), 0) AS saldo_piutang
		');

		$this->db->from('customer');

		// join ke akun (tetap dipertahankan seperti semula)
		$this->db->join('akun', 'customer.id_akun = akun.id_akun');

		// join ke jurnal_detail
		$this->db->join(
			'jurnal_detail', 
			'customer.id_akun = jurnal_detail.id_akun', 
			'left'
		);

		// group by WAJIB karena ada SUM
		$this->db->group_by([
			'customer.id_customer',
			'customer.nama_customer',
			'customer.alamat_customer'
		]);

		$this->db->order_by("customer.id_customer", "asc");

		return $this->db->get()->result_array();
	}

	function getIdAkunCustomer()
	{
		$this->db->select('id_akun');
		$this->db->from('customer');
		$this->db->order_by("id_customer", "asc");
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
		return $this->db->get_where('customer', ['id_customer' => $id])->row_array();
	}

	// menambah data customer
	public function insert()
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama_customer' => $this->input->post('nama_customer', true),
			'alamat_customer' => $this->input->post('alamat_customer', true),
			'id_akun' => $this->input->post('id_akun', true),
			'date_created' => time(),
		];

		$this->db->insert('customer', $data);
	}

	// mengubah data customer
	public function update($id)
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama_customer' => $this->input->post('nama_customer', true),
			'alamat_customer' => $this->input->post('alamat_customer', true),
			'id_akun' => $this->input->post('id_akun', true),
		];

		$this->db->where('id_customer', $id);
		$this->db->update('customer', $data);
	}
	// hapus data customer
	public function delete($id)
	{
		return $this->db->delete('customer', ['id_customer' => $id]);
	}

}
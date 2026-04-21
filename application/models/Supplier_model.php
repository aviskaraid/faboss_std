<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
	// menampilkan semua
	public function getAllData()
	{
		$this->db->select('
			supplier.id_supplier,
			supplier.kode,
			supplier.nama_supplier,
			supplier.alamat_supplier,
			supplier.id_akun,
			akun.noakun,
			COALESCE(SUM(
				CASE 
					WHEN jurnal_detail.id_perkiraan = 1 THEN -jurnal_detail.nilai
					WHEN jurnal_detail.id_perkiraan = 2 THEN jurnal_detail.nilai
					ELSE 0
				END
			), 0) AS saldo_hutang
		');

		$this->db->from('supplier');

		// join ke akun (tetap dipertahankan seperti semula)
		$this->db->join('akun', 'supplier.id_akun = akun.id_akun');

		// join ke jurnal_detail
		$this->db->join(
			'jurnal_detail', 
			'supplier.id_akun = jurnal_detail.id_akun', 
			'left'
		);

		// group by WAJIB karena ada SUM
		$this->db->group_by([
			'supplier.id_supplier',
			'supplier.kode',
			'supplier.nama_supplier',
			'supplier.alamat_supplier'
		]);

		$this->db->order_by("supplier.id_supplier", "asc");

		return $this->db->get()->result_array();
	}

	function getIdAkunSupplier()
	{
		$this->db->select('id_akun');
		$this->db->from('supplier');
		$this->db->order_by("id_supplier", "asc");
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
		return $this->db->get_where('supplier', ['id_supplier' => $id])->row_array();
	}

	// menambah data supplier
	public function insert()
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama_supplier' => $this->input->post('nama_supplier', true),
			'alamat_supplier' => $this->input->post('alamat_supplier', true),
			'id_akun' => $this->input->post('id_akun', true),
			'date_created' => time(),
		];

		$this->db->insert('supplier', $data);
	}

	// mengubah data supplier
	public function update($id)
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama_supplier' => $this->input->post('nama_supplier', true),
			'alamat_supplier' => $this->input->post('alamat_supplier', true),
			'id_akun' => $this->input->post('id_akun', true),
		];

		$this->db->where('id_supplier', $id);
		$this->db->update('supplier', $data);
	}
	// hapus data supplier
	public function delete($id)
	{
		return $this->db->delete('supplier', ['id_supplier' => $id]);
	}

}
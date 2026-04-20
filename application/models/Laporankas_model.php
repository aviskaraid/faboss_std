<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporankas_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Setting_model', 'setting');
    }

	// menampilkan semua
	public function getAllData()
	{
		$this->db->select('*');
		$this->db->from('set_account_kas');
		$this->db->join('akun', 'set_account_kas.id_akun = akun.id_akun');
		$this->db->order_by("id_sak", "asc");
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getReportData($idAkun, $tglAwal, $tglAkhir)
	{
		$this->db->select("
			a.id_sak,
			a.nm,
			a.id_akun,
			b.id_perkiraan,
			b.nilai,
			c.type,
			c.no_trans,
			c.tgl,
			c.keterangan,
			IF(b.id_perkiraan = 1, b.nilai, 0) AS debit,
			IF(b.id_perkiraan = 2, b.nilai, 0) AS credit
		", false);

		$this->db->from('set_account_kas a');
		$this->db->join('jurnal_detail b', 'a.id_akun = b.id_akun', 'left');
		$this->db->join('jurnal c', 'b.id_jurnal = c.id_jurnal', 'left');

		$this->db->where('a.id_sak', $idAkun);
		$this->db->where('c.tgl >=', $tglAwal);
		$this->db->where('c.tgl <=', $tglAkhir);

		$result = $this->db->get()->result_array();

		$cachePenomoran = [];

		foreach ($result as &$row) {
			$type = $row['type'];

			if (!isset($cachePenomoran[$type])) {
				$cachePenomoran[$type] = $this->setting->getPenomoranByIdMenu($type);
			}

			$setJu = $cachePenomoran[$type];

			$tahun = date('Y', strtotime($row['tgl']));
			$bulan = date('m', strtotime($row['tgl']));

			$row['no_trans_formatted'] = sprintf(
				'%0' . $setJu['panjang_nomor'] . 'd',
				(int)$row['no_trans']
			) . '/' . $setJu['prefix'] . '/' . $bulan . '/' . $tahun;
		}
		unset($row);


		return $result;
	}


	public function getNmAkunKas($idAkun)
	{
		$this->db->select('nm, desk');
		$this->db->from('set_account_kas');
		$this->db->where('id_sak', $idAkun);
		return $this->db->get()->row_array();
	}

	public function getTotalSaldo()
	{
		$dt_kas = $this->getAllData();
		$tot_saldo = 0;
		foreach($dt_kas as $row){
			$tot_saldo += $row['saldo_awal'];
		}

		return $tot_saldo;
	}

	function getIdAkunKas()
	{
		$this->db->select('set_account_kas.id_akun');
		$this->db->from('set_account_kas');
		$this->db->order_by("id_sak", "asc");
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
		return $this->db->get_where('set_account_kas', ['id_sak' => $id])->row_array();
	}

	// menambah data kas
	public function insert()
	{
		$data = [
			'nm' => $this->input->post('nm_kas', true),
			'desk' => $this->input->post('desk', true),
			'id_akun' => $this->input->post('id_akun', true),
			'date_created' => time(),
		];

		$this->db->insert('set_account_kas', $data);
	}

	// mengubah data kas
	public function update($id)
	{
		$data = [
			'nm' => $this->input->post('nm', true),
			'desk' => $this->input->post('desk', true),
			'id_akun' => $this->input->post('id_akun', true),
		];

		$this->db->where('id_sak', $id);
		$this->db->update('set_account_kas', $data);
	}
	// hapus data kas
	public function delete($id)
	{
		return $this->db->delete('set_account_kas', ['id_sak' => $id]);
	}


	public function data_kas_by_tgl($data, $tgl_awal, $tgl_akhir)
	{		
		$this->load->model('Report_model','report');
		$result = array();
		foreach($data as $row) {
			// mencari transaksi by id_akun
			$dt_kas = $this->report->_saldo_awal($row['id_akun'], $tgl_awal, $tgl_akhir);

			$result[] = array(
				"id_sak" => $row['id_sak'],
				"noakun" => $row['noakun'],
				"nm" => $row['nm'],
				"desk" => $row['desk'],
				"saldo_awal" => $row['saldo_awal'],
				"id_akun" => $row['id_akun'],
				"saldo_akhir" => $dt_kas['saldo_awal'],
			);

		}

		return $result;
	}
}
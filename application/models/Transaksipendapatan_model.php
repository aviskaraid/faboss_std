<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksipendapatan_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Setting_model', 'setting');
    }

	// menampilkan semua
	public function getAllData()
	{
		$this->db->select('
			a.id_trans_pendapatan, a.nilai, a.pendapatan_id, a.kas_id, a.bukti,
			d.no_trans, d.tgl, d.keterangan, d.type,
			b.id_pendapatan, b.akun_id as id_akun_pendapatan, b.nm as nm_pendapatan, 
			c.id_sak, c.id_akun as id_akun_kas, c.nm as nm_kas
		');
		$this->db->from('transaksi_pendapatan a');
		$this->db->join('pendapatan b', 'a.pendapatan_id = b.id_pendapatan', 'LEFT');
		$this->db->join('set_account_kas c', 'a.kas_id = c.id_sak', 'LEFT');
		$this->db->join('jurnal d', 'a.jurnal_id = d.id_jurnal', 'LEFT');
		$this->db->order_by("d.tgl", "DESC");
		return $this->db->get()->result_array();
	}

	public function getReportData($tglAwal, $tglAkhir)
	{
		$this->db->select("
			a.id_trans_pendapatan, 
			a.nilai, 
			a.pendapatan_id, 
			b.nm as nm_pendapatan,
			a.kas_id, 
			c.nm as nm_kas,
			a.jurnal_id, 
			d.no_trans,
			d.type, 
			d.tgl,
			d.keterangan,
		", false);

		$this->db->from('transaksi_pendapatan a');
		$this->db->join('pendapatan b', 'a.pendapatan_id = b.id_pendapatan', 'left');
		$this->db->join('set_account_kas c', 'a.kas_id = c.id_sak', 'left');
		$this->db->join('jurnal d', 'a.jurnal_id = d.id_jurnal', 'left');
		$this->db->where('d.tgl >=', $tglAwal);
		$this->db->where('d.tgl <=', $tglAkhir);

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

}
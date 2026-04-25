<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksibiaya_model extends CI_Model
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
			a.id_trans_biaya, a.nilai, a.biaya_id, a.kas_id, a.bukti,
			d.no_trans, d.tgl, d.keterangan, d.type, d.posted,
			b.id_biaya, b.akun_id as id_akun_biaya, b.nm as nm_biaya, 
			c.id_sak, c.id_akun as id_akun_kas, c.nm as nm_kas
		');
		$this->db->from('transaksi_biaya a');
		$this->db->join('biaya b', 'a.biaya_id = b.id_biaya', 'LEFT');
		$this->db->join('set_account_kas c', 'a.kas_id = c.id_sak', 'LEFT');
		$this->db->join('jurnal d', 'a.jurnal_id = d.id_jurnal', 'LEFT');
		$this->db->order_by("d.tgl", "DESC");
		return $this->db->get()->result_array();
	}

	public function getReportData($tglAwal, $tglAkhir)
	{
		$this->db->select("
			a.id_trans_biaya, 
			a.nilai, 
			a.biaya_id, 
			b.nm as nm_biaya,
			a.kas_id, 
			c.nm as nm_kas,
			a.jurnal_id, 
			d.no_trans,
			d.type, 
			d.tgl,
			d.keterangan,
		", false);

		$this->db->from('transaksi_biaya a');
		$this->db->join('biaya b', 'a.biaya_id = b.id_biaya', 'left');
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
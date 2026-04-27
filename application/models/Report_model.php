<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model
{
	//data akun
	public function getAllDataKas()
	{
		$this->db->select('*');
		$this->db->from('set_account_kas');
		$this->db->join('akun', 'set_account_kas.id_akun = akun.id_akun');
		$this->db->order_by("id_sak", "asc");
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getAccountData()
	{
		return $this->db->get('akun')->result_array();
	}

	public function getAccountDataById($id)
	{	
		$this->db->select('*');
		$this->db->from('akun a');
		$this->db->join('kelompok_akun b', 'a.id_kelompok_akun = b.id_kelompok_akun');
		$this->db->where_in('a.id_akun', $id);
		return $this->db->get()->row_array();
	}

	public function set_account_group_id($id){
		$this->db->where_in('kelompok_akun.kel_akun', $id);
	}

	//ditampilkan di buku besar
	public function getData()
	{
		$this->db->select('
			jurnal.id_jurnal, jurnal.no_trans, jurnal.tgl, jurnal.keterangan, jurnal.type, 
			jurnal_detail.id_perkiraan, jurnal_detail.nilai, jurnal_detail.id_akun,
			akun.nama, akun.noakun, perkiraan.nama AS perkiraan,
			kelompok_akun.kel_akun');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->join('akun', 'jurnal_detail.id_akun = akun.id_akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->join('perkiraan', 'jurnal_detail.id_perkiraan = perkiraan.id_perkiraan');
		$this->db->where('jurnal.posted', 1);
		$this->db->order_by('jurnal.tgl', 'ASC');
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function getAkunId(){
		$this->db->select('
			jurnal_detail.id_akun, 
			akun.nama,
			kelompok_akun.kel_akun');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->join('akun', 'jurnal_detail.id_akun = akun.id_akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->group_by('jurnal_detail.id_akun');
		$this->db->order_by('jurnal_detail.id_akun', 'ASC');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function getTrialBalance($id_akun, $tglAwal,$tglAkhir){
		$this->db->select('
			jurnal_detail.*, 
			jurnal.*, 
			akun.nama, akun.noakun, 
			kelompok_akun.kel_akun, 
			perkiraan.nama AS perkiraan');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->join('akun', 'jurnal_detail.id_akun = akun.id_akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->join('perkiraan', 'jurnal_detail.id_perkiraan = perkiraan.id_perkiraan');
		$array = array('jurnal_detail.id_akun' => $id_akun, 'jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		$this->db->order_by('jurnal_detail.id_akun', 'ASC');
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function getSaldoTrialBalance($id_akun, $tglAwal,$tglAkhir){
		$this->db->select('
			jurnal_detail.id_perkiraan, jurnal_detail.nilai');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$array = array('jurnal_detail.id_akun' => $id_akun, 'jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		$this->db->order_by('jurnal_detail.id_akun', 'ASC');
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function get_all_data()
	{
		$this->db->select('
			akun.id_akun, akun.noakun, akun.id_kelompok_akun, akun.nama, akun.saldoawal, 
			kelompok_akun.nama, kelompok_akun.kel_akun');
		$this->db->from('akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function insert_kategori()
	{
		date_default_timezone_set("Asia/Bangkok");
		$data = [
			"id_kategori" 						=> "",
			"nama_kategori" 			=> $this->input->post('nama-kategori', true),
			"id_kelompok_aktivitas" 	=> $this->input->post('tipe-aktivitas', true),
			"keterangan" 				=> $this->input->post('keterangan', true),
			"date" 						=> date('Y-m-d H:i:s') ,
        	"id_user" 					=> $this->input->post('idUser', true)
		];

		$this->db->insert('kategori', $data);
		$kategoriId 	= $this->db->insert_id();
		
		$akunSumber 	= $this->input->post('akun-sumber[]', true);

		$result = array();
			   foreach($akunSumber AS $key => $val){
				     $result[] = array(
					     	'id_akun_sumber' 	=> '',
					     	'id_kategori' 		=> $kategoriId,
					        'id_akun'			=>$_POST['akun-sumber'][$key],
				      );
			    }

		$this->db->insert_batch('akun_sumber', $result);   
	}

	public function updateKategori()
	{
		$id = $this->input->post('id', true);

		date_default_timezone_set("Asia/Bangkok");
		$data = [
			"nama_kategori" 			=> $this->input->post('nama-kategori', true),
			"id_kelompok_aktivitas" 	=> $this->input->post('tipe-aktivitas', true),
			"keterangan" 				=> $this->input->post('keterangan', true),
			"date" 						=> date('Y-m-d H:i:s'),
        	"id_user" 					=> $this->input->post('idUser', true) 
		];
		$this->db->where('id_kategori', $id);
		$this->db->update('kategori', $data);

		$this->db->delete('akun_sumber', array('id_kategori' => $id));

		$akunSumber 	= $this->input->post('akun-sumber[]', true);

		$result = array();
		   foreach($akunSumber AS $key => $val){
			     $result[] = array(
				     	'id_akun_sumber' 	=> '',
				     	'id_kategori' 		=> $id,
				        'id_akun'			=> $_POST['akun-sumber'][$key],
			      );
		    }
		$this->db->insert_batch('akun_sumber', $result);   
	}

	public function delete_kategori($id)
	{
		$this->db->delete('akun_sumber', array('id_kategori' => $id));
		$this->db->delete('kategori', array('id_kategori' => $id));
	}

	public function aktivitas_arus_kas()
	{
		$this->db->select('
			kategori.*,
			akun_sumber.*');
		$this->db->from('akun_sumber');
		$this->db->join('kategori', 'akun_sumber.id_kategori = kategori.id_kategori');
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function kel_aktivitas()
	{
		return $this->db->get('kelompok_aktivitas')->result_array();
	}

	public function set_kategori_by_tipe_aktivitas($id){
		$this->db->where_in('id_kelompok_aktivitas', $id);
	}

	public function kategori_arus_kas()
	{
		return $this->db->get('kategori')->result_array();
	}

	public function getDataKas()
	{
		$this->db->select('
			jurnal.no_trans, jurnal.tgl,
			jurnal_detail.id_perkiraan, jurnal_detail.nilai, jurnal_detail.tipe_kas,
			akun.nama, akun.noakun, 
			perkiraan.nama AS perkiraan,
			kelompok_akun.kel_akun, 
			akun_sumber.id_akun, 
			kategori.id_kategori, kategori.nama_kategori, kategori.id_kelompok_aktivitas');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->join('akun', 'jurnal_detail.id_akun = akun.id_akun');
		$this->db->join('akun_sumber', 'jurnal_detail.id_akun = akun_sumber.id_akun');
		$this->db->join('kategori', 'akun_sumber.id_kategori = kategori.id_kategori');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->join('perkiraan', 'jurnal_detail.id_perkiraan = perkiraan.id_perkiraan');
		$this->db->where('jurnal.posted',1);
		$this->db->order_by('jurnal.tgl', 'ASC');
		$query = $this->db->get()->result_array();

		return $query;
	}
	
	public function _saldo_awal($idAkun, $tglAwal, $tglAkhir)
	{
	    	
		// mencari saldo awal
		if(is_null($tglAwal)) {
			$tglAwal = date('Y-m-d', strtotime('1/1/1971'));
		}
		$account_data = $this->getAccountDataById($idAkun);
		
		$array = array('jurnal_detail.id_akun' => $idAkun, 'jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		$journal_data =  $this->getData();

		// Memperoleh nominal saldo awal
		$totalDebit = 0;
		$totalKredit = 0;
		$sum = 0;
		$debit_jurnal = 0;
		if ($account_data) :

			if ($account_data['id_perkiraan'] == 1) {
			    $sum += $account_data['saldo_awal'];
				$totalDebit += $sum;
			} else {
			    $sum -= $account_data['saldo_awal'];
				$totalKredit -= $sum;
			}

		endif;

		if ($journal_data) :

			foreach ($journal_data as $data) :
				if ($data['id_perkiraan'] == 1) {
					$sum += $data['nilai'];
					$totalDebit += $data['nilai'];
				} else {
					$sum -= $data['nilai'];
					$totalKredit -= $data['nilai'];
				}
			endforeach;
		endif;

		if ($account_data['id_perkiraan'] == 1) {
			$data['account_data'] = array(
				'id_perkiraan' => 1,
				'nama' => $account_data['nama'], 'kel_akun' => $account_data['kel_akun'], 'noakun' => $account_data['noakun'],
				'saldo_awal' => $sum
			);
		} else {
			$data['account_data'] = array(
			    'id_perkiraan' => 2, 
				'nama' => $account_data['nama'], 'kel_akun' => $account_data['kel_akun'], 'noakun' => $account_data['noakun'],
			    'saldo_awal' => $sum);
		}
		// Tutup memperoleh saldo awal

		return $data['account_data'];
	}

}
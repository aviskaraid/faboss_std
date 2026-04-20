<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_model extends CI_Model
{
	public function getAllData()
	{
		$this->db->select('*');
		$this->db->from('aset');
		$this->db->order_by('tgl', 'DESC');
		return $this->db->get()->result_array();
	}

	public function getDataById($id)
	{	
		$this->db->select('*');
		$this->db->from('aset');
		$this->db->where('id_aset', $id);
		return $this->db->get()->row_array();
	}

	public function getAllDataById($id, $tgl)
	{
		$this->db->select('a.*');
		$this->db->from('aset a');
		$this->db->where('a.id_aset', $id);
		$this->db->where('a.tgl <', $tgl);
		$this->db->order_by("a.tgl", "asc");
		return $this->db->get()->result_array();
	}


	// pemrosesan data aset
	public function dataAllAsetTetap($dt_aset, $tgl_set)
	{
		//tanggal berdasarkan tgl set, saat ini hari ini
		$tgl_hari_ini = new DateTime($tgl_set);
		$tgl_hari_ini = $tgl_hari_ini->format('d/m/Y');

		$dt_aset_detail = array();

		foreach($dt_aset as $row) {			
			$dt_aset_by_id_ak = $this->asset->getAllDataById($row['id_aset'], $tgl_set);
			if($dt_aset_by_id_ak){
				if (strtotime($row['tgl']) < strtotime($tgl_set)) {
					$penyusutan = $this->mencari_penyusutan($tgl_hari_ini, $row['id_aset']);
					if($penyusutan != false) {
						$dt_aset_detail[] = array_merge($row, $penyusutan);
					}
				}
			}
		}

		return $dt_aset_detail;		
	}

	function mencari_penyusutan($tgl, $id)
	{
		// Ubah format tanggal pencarian menjadi bulan dan tahun
		$date_to_find_obj = DateTime::createFromFormat('d/m/Y', $tgl);
		$search_month = $date_to_find_obj->format('F Y');

		$asetById = $this->asset->getDataById($id);

		$penyusutan = $this->perhitungan_aset_tetap($asetById['tgl'], $asetById['nilai'], 0, $asetById['umur']);

		$found_element = null;

		foreach ($penyusutan as $item) {
		    if ($item['bln'] === $search_month) {                                       

		        $found_element = $item;
		        break; // Keluar dari perulangan setelah menemukan elemen yang dicari
		    }
		}

		if ($found_element !== null) {
		    // Elemen ditemukan
		    return $found_element;
		} else {
		    // Elemen tidak ditemukan
		    return false;
		}

	}

	// perhitungan aset tetap garis lurus
	public function perhitungan_aset_tetap($tgl_awal, $harga_perolehan, $nilai_residu, $masa_manfaat)
	{		
		// Membuat objek DateTime dari tanggal awal
		$tanggal_awal_obj = new DateTime($tgl_awal);
		
        // Mengatur tanggal menjadi awal bulan
        $tanggal_awal_obj->modify('first day of');

		// Mengubah format tanggal menjadi 'd-m-Y'
		$tanggal_awal_obj = $tanggal_awal_obj->format('d/m/Y');

		// $tgl_awal = '2023-1-1';
		$harga_perolehan = intval($harga_perolehan);
		// $nilai_residu = 0;
		$masa_manfaat = intval($masa_manfaat)*12;
		// Mengubah format tanggal menjadi timestamp

		// Tetapkan nilai $selisih_bulan ke 8
		$tgl_akhir = $this->mencari_tgl_akhir_masa($tgl_awal, $masa_manfaat);

		// Menghitung penyusutan bulanan
		$metode_gl = ($harga_perolehan - $nilai_residu) / $masa_manfaat;
		$penyusutan_bulanan = $metode_gl;

		// Menginisialisasi tanggal awal
		$tanggal_awal_obj = DateTime::createFromFormat('d/m/Y', $tanggal_awal_obj);

		$tanggal_akhir_obj = DateTime::createFromFormat('d/m/Y', $tgl_akhir);

		// Menghitung riwayat penyusutan tiap bulan selama 8 bulan
		$result = array();
	    $akum_peny = 0;
	    $nilai_buku = $harga_perolehan;
		while ($tanggal_awal_obj < $tanggal_akhir_obj) {
		    $current_month = $tanggal_awal_obj->format('n');
		    $current_year = $tanggal_awal_obj->format('Y');
		    
		    // Menghitung penyusutan untuk bulan ini
		    $penyusutan_bulan_ini = $penyusutan_bulanan;

		    $peny = $penyusutan_bulan_ini;
			$akum_peny += $penyusutan_bulan_ini;
			$nilai_buku -= $penyusutan_bulan_ini;
		    
		    // Output atau gunakan data sesuai kebutuhan Anda
		    $result[] = array(
		    	'bln' => $tanggal_awal_obj->format('F Y'),
		    	'peny' => round($peny),
		    	'akum_peny' => round($akum_peny),
		    	'nilai_buku' => round($nilai_buku),
		    );
		    
		    // Lanjut ke bulan berikutnya
		    $tanggal_awal_obj->add(new DateInterval('P1M'));
		}

		return $result;
	}

	function mencari_tgl_akhir_masa($tgl_awal, $ms_manfaat)
    {
        // Membuat objek DateTime dari tanggal awal
        $tanggal_awal_obj = new DateTime($tgl_awal);
    
        // Menambahkan 20 bulan ke tanggal awal
        $tanggal_awal_obj->add(new DateInterval('P'.$ms_manfaat.'M'));
    
        // Mengatur hari menjadi 1 untuk memastikan kita berada di awal bulan
        $tanggal_awal_obj->setDate($tanggal_awal_obj->format('Y'), $tanggal_awal_obj->format('m'), 1);
    
        // Mundurkan satu hari untuk mendapatkan akhir bulan sebelumnya
        $tanggal_awal_obj->sub(new DateInterval('P1D'));
    
        // Mengambil tanggal akhir dalam format 'd/m/Y'
        $tanggal_akhir = $tanggal_awal_obj->format('d/m/Y');
    
        return $tanggal_akhir;
    }

	public function moveToNonaktif($data)
	{
		$this->db->trans_start();

		// Add nonaktif timestamp
		$data['tgl_nonaktif'] = date('Y-m-d H:i:s');

		// Insert copy into aset_nonaktif
		$this->db->insert('aset_nonaktif', $data);

		// Delete original from aset
		$this->db->where('id_aset', $data['id_aset']);
		$this->db->delete('aset');

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function getAsetNonaktif()
	{
		$this->db->select('*');
		$this->db->from('aset_nonaktif');
		$this->db->order_by('tgl_nonaktif', 'DESC');

		return $this->db->get()->result_array();
	}


}	
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_model extends CI_Model
{
	public function getAccountData()
	{
		return $this->db->get('akun')->result_array();
	}

	public function getPerkiraan()
	{
		return $this->db->get('perkiraan')->result_array();
	}

	public function insertJournal($bukti_transaksi, $type){
	    //insert tabel jurnal

			$tgl = convertDateToDbdate($this->input->post('tglTransaksi', true));
			date_default_timezone_set("Asia/Bangkok");
			$data = [
				"no_trans" 		=> $this->input->post('no_urut', true),
				"type" 			=> $type,
				"tgl"	 		=> $tgl,
				"keterangan" 	=> $this->input->post('keterangan', true),
				"bukti" 	    => $bukti_transaksi,
				"posted"		=> 0,
        		"id_user" 		=> $this->input->post('idUser', true)
			];

			$this->db->insert('jurnal', $data);
				//GET ID PACKAGE
			return $this->db->insert_id();
	}

	public function detailInsertJournal($jurnal_id, $idAkun, $data)
	{
		// menampilkan data akun kas
		$this->load->model('Kas_model','kas');
		$data_kas = $this->kas->getArrayIdKas();
		$tipe_kas_array = array();
		foreach($data_kas as $row){
			if(in_array(strval($row), $idAkun)){
				$tipe_kas_array[] = 1;
			} else {
				$tipe_kas_array[] = 0;
			}
		}
				
		if(in_array(1, $tipe_kas_array)){
		    $tipe_kas = 1;
		} else {
		    $tipe_kas = 0;
		}

	   $result = array();
	   foreach($data as $row){
		     $result[] = array(
			     	'id_jurnal' 		=> $jurnal_id,
			        'id_akun'			=> $row['id_akun'],
			        'tipe_kas'			=> $tipe_kas,
			        'id_perkiraan'		=> $row['id_perkiraan'], 
			        'nilai'				=> $row['nilai'], 
		      );
	    }    

		$this->db->insert_batch('jurnal_detail', $result);		
	}
	// tutup insert detail jurnal

	// bisa dipakai untuk otomatis beberapa menu
	public function generate_no_trans($reset, $type_trans, $tanggal = null)
    {
        // Jika tanggal tidak dikirim, pakai hari ini
        $tanggal = $tanggal ?? date('Y-m-d');

        $tahun = date('Y', strtotime($tanggal));
        $bulan = date('m', strtotime($tanggal));

        // =============================
        // Tentukan range tanggal
        // =============================
        if ($reset == 1) {
            // BULANAN
            $tgl_awal  = "$tahun-$bulan-01";
            $tgl_akhir = date('Y-m-t', strtotime($tgl_awal));

        } elseif ($reset == 2) {
            // TAHUNAN
            $tgl_awal  = "$tahun-01-01";
            $tgl_akhir = "$tahun-12-31";

        } else {
            return false;
        }

        // =============================
        // Ambil nomor terakhir
        // =============================
        $this->db->select('no_trans');
        $this->db->from('jurnal');
        $this->db->where('type', $type_trans);
        $this->db->where('tgl >=', $tgl_awal);
        $this->db->where('tgl <=', $tgl_akhir);
        $this->db->order_by('id_jurnal', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_no = $query->row()->no_trans;

            // Ambil nomor urut (sebelum slash pertama)
            // $last_urut = (int) explode('/', $last_no)[0];
            $urut = (int) $last_no + 1;
        } else {
            $urut = 1;
        }

        return $urut;
    }


	public function insertJournalTransaction($no_trans, $tgl, $deskripsi, $id_user, $type)
	{
		$dt_insert = array(
			"no_trans" 		=> $no_trans,
			"type" 			=> $type,
			"tgl"	 		=> $tgl,
			"keterangan" 	=> $deskripsi,
			"bukti" 	    => "",
			"posted"		=> 0,
			"id_user" 		=> $id_user,
		);
		$this->db->insert('jurnal', $dt_insert);
			//GET ID PACKAGE
		return $this->db->insert_id();
	}

	public function updateJournalTransaction($id_jurnal, $no_trans, $tgl, $deskripsi, $id_user)
	{
		$dt_update = array(
			"no_trans" 		=> $no_trans,
			"tgl"	 		=> $tgl,
			"keterangan" 	=> $deskripsi,
			"posted"		=> 0,
			"id_user" 		=> $id_user,
		);

		$this->db->where('id_jurnal', $id_jurnal);
		$this->db->update('jurnal', $dt_update);

	}

	public function detailInsertJournalTransaction($jurnal_id, $idAkun, $data)
	{
		// menampilkan data akun kas
		$this->load->model('Kas_model','kas');
		$data_kas = $this->kas->getArrayIdKas();
		$tipe_kas_array = array();
		foreach($data_kas as $row){
			if(in_array(strval($row), $idAkun)){
				$tipe_kas_array[] = 1;
			} else {
				$tipe_kas_array[] = 0;
			}
		}
		
		if(in_array(1, $tipe_kas_array)){
		    $tipe_kas = 1;
		} else {
		    $tipe_kas = 0;
		}

	   $result = array();
	   foreach($data as $row){
		     $result[] = array(
			     	'id_jurnal' 		=> $jurnal_id,
			        'id_akun'			=> $row['id_akun'],
			        'tipe_kas'			=> $tipe_kas,
			        'id_perkiraan'		=> $row['id_perkiraan'], 
			        'nilai'				=> $row['nilai'], 
		      );
	    }    

		$this->db->insert_batch('jurnal_detail', $result);		
	}

	// datatables server side jurnal umum
    var $column_order = array(
    	'jurnal.id_jurnal', 'jurnal.no_trans', 'jurnal.tgl', 'jurnal.keterangan', 'jurnal.bukti', 'jurnal.id_user'
    	); //set column field database for datatable orderable
    //var $column_search = array('jurnal.no_trans', 'jurnal.tgl'); //set column field database for datatable searchable 
    var $order = array('jurnal.tgl' => 'desc'); // default order 

    private function _get_datatables_query()
    {		
        $this->db->select('jurnal.*, jurnal_detail.nilai AS nominal');
		$this->db->from('jurnal');
		$this->db->join('jurnal_detail', 'jurnal.id_jurnal = jurnal_detail.id_jurnal', 'left');
		$this->db->where('jurnal_detail.id_perkiraan', 1);

        $i = 0;

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {		
        $this->_get_datatables_query();
		
        $query = $this->db->get();
        return $query->result_array();
    }

	public function getAllData($tgl_awal = null, $tgl_akhir = null)
	{		
		$this->db->select("
    		jurnal.*,
    		jurnal_detail.nilai AS nominal,
    		CASE jurnal.type
				WHEN 1 THEN 'Jurnal Umum'
				WHEN 2 THEN 'Kas / Bank'
				WHEN 3 THEN 'Piutang'
				WHEN 4 THEN 'Utang'
				WHEN 5 THEN 'Penyusutan'
				ELSE '-'
    		END AS tipe_jurnal
		", false);
		$this->db->from('jurnal');
		$this->db->join('jurnal_detail', 'jurnal.id_jurnal = jurnal_detail.id_jurnal', 'left');
		$this->db->where('jurnal_detail.id_perkiraan', 1);
		if ($tgl_awal !== null) {
        $this->db->where('jurnal.tgl >=', $tgl_awal);
		}

		if ($tgl_akhir !== null) {
			$this->db->where('jurnal.tgl <=', $tgl_akhir);
		}	
		$this->db->order_by('jurnal.tgl', 'DESC');

		return $this->db->get()->result_array();
	}

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('jurnal.*, jurnal_detail.nilai AS nominal');
		$this->db->from('jurnal');
		$this->db->join('jurnal_detail', 'jurnal.id_jurnal = jurnal_detail.id_jurnal', 'left');
		$this->db->where('jurnal_detail.id_perkiraan', 1);
		// $this->db->group_by('jurnal_detail.id_jurnal');
        return $this->db->count_all_results();
    }
    // tutup datatables serverside



	public function getJournal()
	{
		$this->db->select('jurnal.*, SUM(jurnal_detail.nilai) AS nominal');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->where('jurnal_detail.id_perkiraan', 1);
		$this->db->group_by('jurnal_detail.id_jurnal');
		$this->db->order_by('jurnal.tgl', 'DESC');
		$query = $this->db->get()->result_array();
		return $query;
	}

	public function get_jurnal_by_id($id)
	{
		$this->db->select('
			jurnal_detail.id_akun, jurnal_detail.nilai, jurnal_detail.id_perkiraan, 
			jurnal.id_jurnal, jurnal.tgl, jurnal.no_trans, jurnal.keterangan, jurnal.bukti, 
			akun.noakun, akun.nama, 
			kelompok_akun.kel_akun, 
			perkiraan.nama AS perkiraan
			');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->join('akun', 'jurnal_detail.id_akun = akun.id_akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$this->db->join('perkiraan', 'jurnal_detail.id_perkiraan = perkiraan.id_perkiraan');
		$this->db->where('jurnal_detail.id_jurnal', $id); 
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function proses_detail_journal($id)
	{
		$dt_detail = $this->get_jurnal_by_id($id);
		$result = array();
		foreach($dt_detail as $row) {
			if($row['id_perkiraan'] == 1) {
				$result[] = array(
					'id_akun' => $row['id_akun'],
					'nama' => $row['nama'],
					'nilai_debit' => $row['nilai'],
					'nilai_kredit' => "",
				);
			} else {
				$result[] = array(
					'id_akun' => $row['id_akun'],
					'nama' => $row['nama'],
					'nilai_debit' => "",
					'nilai_kredit' => $row['nilai'],
				);
			}
		}

		return $result;
	}	

	public function delete_journal($id)
	{
		$this->db->delete('jurnal_detail', array('id_jurnal' => $id));
		$this->db->delete('jurnal', array('id_jurnal' => $id));
	}


	public function check_number_tramsaction()
	{
		$query = $this->db->query("SELECT MAX(no_trans) as no_trans from jurnal");
        $hasil = $query->row();
        return $hasil->no_trans;
	}

	public function getIdPerkiraan($id)
	{
		return $this->db->get_where('perkiraan', ['id_perkiraan' => $id])->row_array();
	}

	public function changeJournal($id_jurnal, $bukti_transaksi)
	{
		$this->db->trans_start();

		date_default_timezone_set("Asia/Bangkok");
		$tgl = convertDateToDbdate($this->input->post('tglTransaksi', true));

		if($bukti_transaksi == "") {
			$data = [
				"no_trans" 		=> $this->input->post('no_urut', true),
				"tgl"	 		=> $tgl,
				"keterangan" 	=> $this->input->post('keterangan', true),
				"posted"		=> 0,
        		"id_user" 		=> $this->input->post('idUser', true)
			];
		} else {
			$data = [
				"no_trans" 		=> $this->input->post('no_urut', true),
				"tgl"	 		=> $tgl,
				"keterangan" 	=> $this->input->post('keterangan', true),
				"bukti" 		=> $bukti_transaksi,
				"posted"		=> 0,
        		"id_user" 		=> $this->input->post('idUser', true)
			];
		}
		$this->db->where('id_jurnal', $id_jurnal);
		$this->db->update('jurnal', $data);
		
		//DELETE DETAIL PACKAGE
		$this->db->delete('jurnal_detail', array('id_jurnal' => $id_jurnal));

		$this->db->trans_complete();
	}


	public function reportJournal($tglAwal, $tglAkhir)
	{
		$this->db->select('
			jurnal.id_jurnal, jurnal.tgl, jurnal.no_trans, jurnal.type, 
			jurnal_detail.id_perkiraan, jurnal_detail.nilai, 
			akun.noakun, akun.nama, 
			kelompok_akun.kel_akun');
		$this->db->from('jurnal_detail');
		$this->db->join('jurnal', 'jurnal_detail.id_jurnal = jurnal.id_jurnal');
		$this->db->join('akun', 'jurnal_detail.id_akun = akun.id_akun');
		$this->db->join('kelompok_akun', 'akun.id_kelompok_akun = kelompok_akun.id_kelompok_akun');
		$array = array('jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		// $this->db->order_by('jurnal.id', 'ASC');
		$this->db->order_by('jurnal.tgl', 'ASC');
		$this->db->order_by('jurnal_detail.id_jurnal_detail', 'ASC');
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function chartOmset($bulan){
		$like = date('Y').'-'. $bulan;

		$this->db->select('a.nilai as nilai, b.id_jurnal, b.no_trans, b.tgl');
		$this->db->from('jurnal_detail a');
		$this->db->join('jurnal b', 'a.id_jurnal = b.id_jurnal');
		$this->db->join('akun c', 'a.id_akun = c.id_akun');
		$this->db->join('kelompok_akun d', 'c.id_kelompok_akun = d.id_kelompok_akun');
		$this->db->where('a.id_perkiraan', 2);
		$array = array(410);
		$this->db->where_in('d.kel_akun', $array);
		$this->db->like('b.tgl', $like);
		// $this->db->group_by('a.id_jurnal');
		$this->db->order_by('b.no_trans', 'DESC');
		$query = $this->db->get()->result_array();
		return $query;
	}
}

// , 'akun.id' => 8, 'akun.id' => 41, 'akun.id' => 42
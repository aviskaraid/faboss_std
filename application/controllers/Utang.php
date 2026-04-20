<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utang extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Utang_model','utang');
	}

	public function index()
	{
		$data['title'] = 'Daftar Utang';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("d-m-Y");
		$data['tglAwal'] = date('Y-m-01', strtotime($data['now']));
		$data['tglAkhir'] = date('Y-m-t', strtotime($data['now']));
		$data['stts'] = 0;

		$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
		$this->db->where($where);
		$dt_utang = $this->utang->getAllData();

		$data['dtUtang'] = $this->pemrosesan_utang($dt_utang, $data['stts']);
		// echo json_encode($data['dtUtang']);
		
		$this->load->model('Kas_model','kas');
		$data['dtAkunKas'] = $this->kas->getAllData();

		$this->form_validation->set_rules('stts','Nama Akun', 'required');
		$this->form_validation->set_rules('tglAwal','Tgl Awal', 'required');
		$this->form_validation->set_rules('tglAkhir','Tgl Akhir', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('utang/index', $data);
			$this->load->view('templates/footer');
		} else {
			// mendapatkan data
			$data['stts'] = $this->input->post('stts', true);
			$t_awal = $this->input->post('tglAwal', true);
			$t_akhir = $this->input->post('tglAkhir', true);
			$data['tglAwal'] = convertDateToDbdate($t_awal);
			$data['tglAkhir'] = convertDateToDbdate($t_akhir);

			// pemrosesan
			$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
			$this->db->where($where);
			$dt_utang = $this->utang->getAllData();

			$data['dtUtang'] = $this->pemrosesan_utang($dt_utang, $data['stts']);

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('utang/index', $data);
			$this->load->view('templates/footer');
		}
	}

	function pemrosesan_utang($data, $stts = null)
	{
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$result = array();
		foreach($data as $row) {
			// nomor otomatis
			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			// mendapatkan dibayar
			$jumlah_dibayar = 0;
			$dt_terbayar = $this->utang->get_terbayar($row['id_utang']);
			foreach($dt_terbayar as $row_bayar)
			{
				$jumlah_dibayar += $row_bayar['nilai'];
			}

			$di_bayar = array('dibayar' => $jumlah_dibayar);

			if($stts == 0) {
			// 	// belum lunas
				if($jumlah_dibayar < $row['nilai']) {
					$result[] = array_merge($row, $di_bayar);					
				}
			} else if($stts == 1) { 
			// 	// lunas
				if($jumlah_dibayar == $row['nilai']) {
					$result[] = array_merge($row, $di_bayar);					
				}
			} else if($stts == 99) { 
				// semua piutang
				$result[] = array_merge($row, $di_bayar);
			}
		}

		return $result;
	}


	public function print()
	{
		$this->load->model('Admin_model','admin');

		$data['stts'] = $this->input->post('stts', true);
		$t_awal = $this->input->post('tglAwal', true);
		$t_akhir = $this->input->post('tglAkhir', true);
		$tglAwal = convertDateToDbdate($t_awal);
		$tglAkhir = convertDateToDbdate($t_akhir);

		$data['tglAwal'] = $tglAwal;
		$data['tglAkhir'] = $tglAkhir;
		$data['profile'] = $this->admin->companyProfile();
		
		$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
		$this->db->where($where);
		$dt_utang = $this->utang->getAllData();

		$data['result'] = $this->pemrosesan_utang($dt_utang, $data['stts']);

		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('utang/laporan_utang_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('utang/laporan_utang_excel', $data);
		}
	}

	public function tambah()
	{
		// data yang dibutuhkan untuk membuat transaksi 
		$tgl = date("Y-m-d");
		$deskripsi = "Pinjaman A";
		$nominal = 1000000;
		$id_user = 30; //user yang menginputkan

		// setting jurnal : piutang usaha pada penjualan
		$id_akun_debit = 1; //akun kas
		$id_akun_kredit = 9; //akun hutang usaha

		// mencari transaksi selanjutnya
		$this->load->model('Setting_model','setting');
		$this->load->model('Journal_model','journal');

		date_default_timezone_set('Asia/Jakarta');
		$tgl_hari_ini = date("Y-m-d");
		$tahun = date('Y', strtotime($tgl_hari_ini));
        $bulan = date('m', strtotime($tgl_hari_ini));

		$type_trans = 4;
		$set_number_auto = $this->setting->getPenomoranByIdMenu($type_trans); 
		$no_urut = $this->journal->generate_no_trans($set_number_auto['reset_nomor'], $type_trans, $tgl_hari_ini);
		$no_trans = sprintf('%0'.$set_number_auto['panjang_nomor'].'d', (int)$no_urut).'/'.$set_number_auto['prefix'].'/'.$bulan.'/'.$tahun;

		// menjurnal transaksi dahulu
		$id_jurnal = $this->journal->insertJournalTransaction($no_urut, $tgl, $deskripsi, $id_user, $type_trans);

		// menjurnal detail transaksi	
		$idAkun = array($id_akun_debit, $id_akun_kredit);	
		$result = array(
			array(
		        'id_akun'		=> $id_akun_debit,
		        'id_perkiraan'	=> 1, 
		        'nilai'			=> $nominal, 
	      	),
			array(
		        'id_akun'		=> $id_akun_kredit,
		        'id_perkiraan'	=> 2, 
		        'nilai'			=> $nominal, 
	      	),
		);
		$this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);
		// simpan data di transaksi kas

		// mendapatkan id jurnal, lalu disimpan di table transaksi kas
		$dt_insert_trans_kas = array(
			'nilai' => $nominal,
			'jurnal_id' => $id_jurnal,
		);
		$this->load->model('Admin_model','admin');
		$this->admin->addDatabyTable('utang', $dt_insert_trans_kas);
		// transaksi piutang baru berhasil ditambahkan
	}

	public function pembayaran()
	{
		$dt_user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->model('Journal_model','journal');
		$this->load->model('Admin_model','admin');

		// sumber kas //akun debit
		$id_kas = $this->input->post('id_kas', true);
		$dt_by_id_kas = $this->admin->GetDataById('set_account_kas', 'id_sak', $id_kas);

		// mendapatkan nilai
		$id_utang = $this->input->post('id_utang', true);
		$tgl = convertDateToDbdate($this->input->post('tgl', true));
		$deskripsi = $this->input->post('desk', true);
		$nominal_ = $this->input->post('dibayar', true);
		$hide 		= array("Rp", ".", " ");
		$nominal 	= str_replace($hide, "", $nominal_);	
		$id_user = $dt_user['id_user'];
        
        
    	$tgl_check = date('Y-m-d', strtotime($tgl));
    	$this->load->model('Periode_model','periode');
        if ($this->periode->check_tgl_with_periode($tgl_check)) {
            
			$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Periode sudah ditutup, transaksi tidak bisa disimpan! 
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			  	</button></div>');
			redirect('utang');
			
        } else {
            
    		// akun kredit (piutang usaha)
    		$id_akun_debit =  9;//akun utang
    		$id_akun_kredit = $dt_by_id_kas['id_akun']; //akun kas
    
    		// mencari transaksi selanjutnya
    		$this->load->model('Setting_model','setting');
    		$this->load->model('Journal_model','journal');
    
    		date_default_timezone_set('Asia/Jakarta');
    		$tgl_hari_ini = date("Y-m-d");
    		$tahun = date('Y', strtotime($tgl_hari_ini));
            $bulan = date('m', strtotime($tgl_hari_ini));
    
    		$type_trans = 2;
    		$set_number_auto = $this->setting->getPenomoranByIdMenu($type_trans); 
    		$no_urut = $this->journal->generate_no_trans($set_number_auto['reset_nomor'], $type_trans, $tgl_hari_ini);
    		$no_trans = sprintf('%0'.$set_number_auto['panjang_nomor'].'d', (int)$no_urut).'/'.$set_number_auto['prefix'].'/'.$bulan.'/'.$tahun;
    
    		// menjurnal transaksi dahulu
    		$id_jurnal = $this->journal->insertJournalTransaction($no_urut, $tgl, $deskripsi, $id_user, $type_trans);
    
    		// menjurnal detail transaksi	
    		$idAkun = array($id_akun_debit, $id_akun_kredit);	
    		$result = array(
    			array(
    		        'id_akun'		=> $id_akun_debit,
    		        'id_perkiraan'	=> 1, 
    		        'nilai'			=> $nominal, 
    	      	),
    			array(
    		        'id_akun'		=> $id_akun_kredit,
    		        'id_perkiraan'	=> 2, 
    		        'nilai'			=> $nominal, 
    	      	),
    		);
    		$this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);
    		// simpan data di transaksi kas
    
    		// mendapatkan id jurnal, lalu disimpan di table transaksi kas
    		$dt_insert_trans_kas = array(
    			'tgl' => $tgl,
    			'utang_id' => $id_utang,
    			'kas_id' => $id_kas,
    			'nilai' => $nominal,
    			'ket' => $deskripsi,
    			'jurnal_id' => $id_jurnal,
    		);
    		$this->admin->addDatabyTable('utang_bayar', $dt_insert_trans_kas);
    		// transaksi piutang baru berhasil ditambahkan
    
    		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pembayaran utang berhasil disimpan!
    			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    		    	<span aria-hidden="true">&times;</span>
    		  	</button></div>');
    			redirect('utang');
        }
	}

	// daftar pelunasan
	public function detail_pelunasan()
    {
        $id_hutang = $this->input->post('id_hutang');

        $data = $this->utang->get_terbayar($id_hutang);

        echo json_encode($data);
    }

    public function hapus_pelunasan()
    {
    	$id = $this->input->post('id_pelunasan');

    	// mendapatkan data by is
		$this->load->model('Admin_model','admin');
    	$dtByid = $this->admin->GetDataById('utang_bayar', 'id_utang_bayar', $id);

 

		$this->load->model('Journal_model','journal');
		$this->journal->delete_journal($dtByid['jurnal_id']);

	    $this->admin->deleteDataById('utang_bayar', 'id_utang_bayar', $dtByid['id_utang_bayar']);

	    echo json_encode([
	        'status' => true
	    ]);
    }
}
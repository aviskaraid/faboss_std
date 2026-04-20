<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang extends CI_Controller 
{
	public function __construct()
	{
	 	parent::__construct();
		is_logged_in();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('Piutang_model','piutang');
		$this->load->model('Kas_model','kas');
		$this->load->model('Pendapatan_model','pendapatan');
		$this->load->model('Setting_model','setting');
		$this->load->model('Journal_model','journal');
		$this->load->model('Admin_model','admin');
	}

	public function index()
	{
		$data['title'] = 'Daftar Piutang';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("d-m-Y");
		$data['tglAwal'] = date('Y-m-01', strtotime($data['now']));
		$data['tglAkhir'] = date('Y-m-t', strtotime($data['now']));
		$data['stts'] = 0;

		$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
		$this->db->where($where);
		$dt_piutang = $this->piutang->getAllData();

		$data['dtPiutang'] = $this->pemrosesan_piutang($dt_piutang, $data['stts']);
		
		// data akun kas
		$data['dtAkunKas'] = $this->kas->getAllData();

		// data customer
		$dt_customer = $this->piutang->getCustomerData();
		$data['dt_customer'] = $dt_customer;

		// data akun pendapatan		
		$data['dtAkunPendapatan'] = $this->pendapatan->getAllData(); 

		$this->form_validation->set_rules('tglAwal','Tgl Awal', 'required');
		$this->form_validation->set_rules('tglAkhir','Tgl Akhir', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('piutang/index', $data);
			$this->load->view('templates/footer');
		} else {
			// mendapatkan data
			$data['stts'] = $this->input->post('stts', true);
			$t_awal = $this->input->post('tglAwal', true);
			$t_akhir = $this->input->post('tglAkhir', true);
			$data['tglAwal'] = convertDateToDbdate($t_awal);
			$data['tglAkhir'] = convertDateToDbdate($t_akhir);

			$where = array('b.tgl >=' => $data['tglAwal'], 'b.tgl <=' => $data['tglAkhir']);
			$this->db->where($where);
			$dt_piutang = $this->piutang->getAllData();
			$data['dtPiutang'] = $this->pemrosesan_piutang($dt_piutang, $data['stts']);

			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('piutang/index', $data);
			$this->load->view('templates/footer');
		}
	}

	function pemrosesan_piutang($data, $stts = null)
	{
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$result = array();
		foreach($data as $row) {

			// nomor trans 
			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			// mendapatkan dibayar
			$jumlah_dibayar = 0;
			$dt_terbayar = $this->piutang->get_terbayar($row['id_piutang']);
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
		$dt_piutang = $this->piutang->getAllData();
		$data['result'] = $this->pemrosesan_piutang($dt_piutang, $data['stts']);

		if(isset($_POST['pdf'])) {
			$this->load->library('PDF_MC_Table');
			$this->load->view('piutang/laporan_piutang_pdf', $data);
		} else if(isset($_POST['excel'])) {
			$this->load->view('piutang/laporan_piutang_excel', $data);
		}
	}

	public function insert()
	{
		// data yang dibutuhkan untuk membuat transaksi 
		$dt_user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$id_user = $dt_user['id_user'];
		
		$id_customer = $this->input->post('id_customer', true);
		$dt_by_id_customer = $this->admin->GetDataById('customer', 'id_customer', $id_customer);

		$id_pendapatan = $this->input->post('id_pendapatan', true);
		$dt_by_id_pendapatan = $this->admin->GetDataById('pendapatan', 'id_pendapatan', $id_pendapatan);

		$no_ref = $this->input->post('no_ref', true);
		$tgl_invoice = convertDateToDbdate($this->input->post('tgl_invoice', true));
		$jt_tempo = convertDateToDbdate($this->input->post('jt_tempo', true));
		$deskripsi = $this->input->post('deskripsi', true);
		$nilai_ = $this->input->post('nilai', true);
		$hide 	= array("Rp", ".", " ");
		$nilai 	= str_replace($hide, "", $nilai_);


		// setting jurnal : piutang usaha pada penjualan
		$id_akun_debit = $dt_by_id_customer['id_akun'];
		$id_akun_kredit = $dt_by_id_pendapatan['akun_id'];
		
		date_default_timezone_set('Asia/Jakarta');
		$tgl_hari_ini = date("Y-m-d");
		$tahun = date('Y', strtotime($tgl_hari_ini));
        $bulan = date('m', strtotime($tgl_hari_ini));

		$type_trans = 3;
		$set_number_auto = $this->setting->getPenomoranByIdMenu($type_trans); 
		$no_urut = $this->journal->generate_no_trans($set_number_auto['reset_nomor'], $type_trans, $tgl_hari_ini);
		$no_trans = sprintf('%0'.$set_number_auto['panjang_nomor'].'d', (int)$no_urut).'/'.$set_number_auto['prefix'].'/'.$bulan.'/'.$tahun;
		
		// menjurnal transaksi dahulu
		$id_jurnal = $this->journal->insertJournalTransaction($no_urut, $tgl_invoice, $deskripsi, $id_user, $type_trans);

		// menjurnal detail transaksi	
		$idAkun = array($id_akun_debit, $id_akun_kredit);	
		$result = array(
			array(
		        'id_akun'		=> $id_akun_debit,
		        'id_perkiraan'	=> 1, 
		        'nilai'			=> $nilai, 
	      	),
			array(
		        'id_akun'		=> $id_akun_kredit,
		        'id_perkiraan'	=> 2, 
		        'nilai'			=> $nilai, 
	      	),
		);
		$this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);
		// simpan data di transaksi piutang

		// upload bukti transaksi
		$bukti_transaksi = "";

		if (!empty($_FILES['bukti']['name'])) {

			$new_name = time() . rand();

			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']      = 5048; // KB
			$config['upload_path']   = FCPATH . 'assets/file/bukti/';
			$config['file_name']     = $new_name;
			$config['encrypt_name']  = FALSE;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('bukti')) {
				$bukti_transaksi = $this->upload->data('file_name');
			} else {
				// VERY IMPORTANT for debugging
				$error = $this->upload->display_errors();
				log_message('error', 'Upload bukti gagal: ' . $error);
				$this->session->set_flashdata('message', '<div class="alert alert-danger">'.$error.'</div>');
				redirect('piutang');
				return;
			}
		}
		// mendapatkan id jurnal, lalu disimpan di table transaksi kas
		$dt_insert_piutang = array(
			'id_customer' => $id_customer,
			'id_pendapatan' => $id_pendapatan,
			'nilai' => $nilai,
			'jurnal_id' => $id_jurnal,
			'no_ref' => $no_ref,			
			'tgl_invoice' => $tgl_invoice,
			'jt_tempo' => $jt_tempo,
			'deskripsi' => $deskripsi,
			'bukti' => $bukti_transaksi,			
		);
		
		$this->admin->addDatabyTable('piutang', $dt_insert_piutang);
		// transaksi piutang baru berhasil ditambahkan
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Transaksi Piutang berhasil disimpan!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('piutang');
	}

	public function update()
	{
		// terima data
		$dt_user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$id_user = $dt_user['id_user'];

		$id_piutang = $this->input->post('id_piutang', true);
		$dt_by_id_piutang = $this->admin->GetDataById('piutang', 'id_piutang', $id_piutang);
		
		$id_jurnal = $dt_by_id_piutang['jurnal_id'];
		$dt_by_id_jurnal = $this->admin->GetDataById('jurnal', 'id_jurnal', $id_jurnal);

		$id_customer = $this->input->post('id_customer', true);
		$dt_by_id_customer = $this->admin->GetDataById('customer', 'id_customer', $id_customer);

		$id_pendapatan = $this->input->post('id_pendapatan', true);
		$dt_by_id_pendapatan = $this->admin->GetDataById('pendapatan', 'id_pendapatan', $id_pendapatan);

		// data yang dibutuhkan untuk membuat transaksi
		$no_ref = $this->input->post('no_ref', true);
		$tgl_invoice = convertDateToDbdate($this->input->post('tgl_invoice', true));
		$jt_tempo = convertDateToDbdate($this->input->post('jt_tempo', true));
		$deskripsi = $this->input->post('deskripsi', true);
		$nilai_ = $this->input->post('nilai', true);
		$hide 	= array("Rp", ".", " ");
		$nilai 	= str_replace($hide, "", $nilai_);


		// setting jurnal : piutang usaha pada penjualan
		$id_akun_debit = $dt_by_id_customer['id_akun'];
		$id_akun_kredit = $dt_by_id_pendapatan['akun_id'];
		
		// menjurnal transaksi dahulu
		$this->journal->updateJournalTransaction($id_jurnal, $no_trans, $tgl_invoice, $deskripsi, $dt_user['id_user']);

		// menghapus detail jurnal dan menjurnal detail transaksi	
		$this->admin->deleteDataById('jurnal_detail', 'id_jurnal', $id_jurnal);
		$idAkun = array($id_akun_debit, $id_akun_kredit);	
		$result = array(
			array(
		        'id_akun'		=> $id_akun_debit,
		        'id_perkiraan'	=> 1, 
		        'nilai'			=> $nilai, 
	      	),
			array(
		        'id_akun'		=> $id_akun_kredit,
		        'id_perkiraan'	=> 2, 
		        'nilai'			=> $nilai, 
	      	),
		);
		$this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);		

		// mendapatkan id jurnal, lalu disimpan di table transaksi piutang
		 $dt_update_piutang = array(
			'id_customer' => $id_customer,
			'id_pendapatan' => $id_pendapatan,
			'nilai' => $nilai,
			'no_ref' => $this->input->post('no_ref', true),			
			'tgl_invoice' => $tgl_invoice,
			'jt_tempo' => $jt_tempo,
			'deskripsi' => $deskripsi,			
		);

		$old_bukti = $this->input->post('old_bukti');

		if (!empty($_FILES['bukti']['name'])) {

			$config['upload_path']   = './assets/file/bukti/';
			$config['allowed_types'] = 'pdf|jpg|jpeg|png';
			$config['max_size']      = 5120;
			$config['encrypt_name']  = TRUE;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('bukti')) {
				$upload = $this->upload->data();
				$new_file = $upload['file_name'];

				// hapus file lama jika ada
				if ($old_bukti && file_exists('./assets/file/bukti/'.$old_bukti)) {
					unlink('./assets/file/bukti/'.$old_bukti);
				}

				$dt_update_piutang['bukti'] = $new_file;
			}
		} else {
			$dt_update_piutang['bukti'] = $old_bukti;
		}

		// simpan data di transaksi piutang
		$this->admin->updateDatabyTable('piutang', 'id_piutang', $id_piutang, $dt_update_piutang);

		// dikasih alert
		$this->session->set_flashdata('message', '<div class="alert alert-primary" role="alert">Transaksi Piutang berhasil diubah!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('piutang');
        
	}

	public function delete_bukti()
	{
		$file = $this->input->post('file');

		if ($file && file_exists('./assets/file/bukti/'.$file)) {
			unlink('./assets/file/bukti/'.$file);
		}
	}

	public function delete($id)
	{
		$dt_by_id_piutang = $this->admin->GetDataById('piutang', 'id_piutang', $id);
		$id_jurnal = $dt_by_id_piutang['jurnal_id'];
		// menghapus jurnal detail
		$this->admin->deleteDataById('jurnal_detail', 'id_jurnal', $id_jurnal);
		$this->admin->deleteDataById('jurnal', 'id_jurnal', $id_jurnal);

		// menghapus data piutang
		$this->admin->deleteDataById('piutang', 'id_piutang', $id);

		$bukti = $dt_by_id_piutang['bukti'];
		if ($bukti && file_exists(FCPATH . 'assets/file/bukti/' . $bukti)) {
			unlink(FCPATH . 'assets/file/bukti/' . $bukti);
		}

		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Transaksi Piutang berhasil dihapus!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('transaksipendapatan');
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
		$id_piutang = $this->input->post('id_piutang', true);
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
			redirect('piutang');
			
        } else {

    		// akun kredit (piutang usaha)	
    		$id_akun_debit = $dt_by_id_kas['id_akun']; //akun kas
    		$id_akun_kredit = 5; //akun piutang usaha
    
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
    			'piutang_id' => $id_piutang,
    			'kas_id' => $id_kas,
    			'nilai' => $nominal,
    			'ket' => $deskripsi,
    			'jurnal_id' => $id_jurnal,
    		);
    		$this->admin->addDatabyTable('piutang_bayar', $dt_insert_trans_kas);
    		// transaksi piutang baru berhasil ditambahkan
    
    		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pembayaran piutang berhasil disimpan!
    			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    		    	<span aria-hidden="true">&times;</span>
    		  	</button></div>');
    			redirect('piutang');
        }
	}


	// daftar pelunasan
	public function detail_pelunasan()
    {
        $id_piutang = $this->input->post('id_piutang');

        $data = $this->piutang->get_terbayar($id_piutang);

        echo json_encode($data);
    }

    public function hapus_pelunasan()
    {
    	$id = $this->input->post('id_pelunasan');

    	// mendapatkan data by is
		$this->load->model('Admin_model','admin');
    	$dtByid = $this->admin->GetDataById('piutang_bayar', 'id_piutang_bayar', $id);

		$this->load->model('Journal_model','journal');
		$this->journal->delete_journal($dtByid['jurnal_id']);

	    $this->admin->deleteDataById('piutang_bayar', 'id_piutang_bayar', $dtByid['id_piutang_bayar']);

	    echo json_encode([
	        'status' => true
	    ]);
    }
}
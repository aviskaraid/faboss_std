<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Masterdata extends CI_Controller 
{ 
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index()
	{
		$data['title'] = 'Data Akun';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		$data['masterdata'] = $this->masterdata->getAccountData();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('masterdata/index', $data);
		$this->load->view('templates/footer');
	}
	
	public function realtime()
	{
		// membuat halaman perubahan saldo realtime
		$data['title'] = 'Realtime Update Saldo';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		$master = $this->masterdata->getAccountData();
		date_default_timezone_set('Asia/Jakarta');
		$data['date'] = date("Y-m-d");
		
		
		$tglAwal = "1971/1/1";
		$tglAkhir = date("Y-m-d");

		$kelAkun = $this->db->get('kelompok_akun')->result_array();
		$inArray = array(110,120,210,220,310,410,510,610,710,810);
		$data['neraca_saldo_data'] = $this->getData($tglAwal, $tglAkhir, $inArray);

		// echo json_encode($data['neraca_saldo_data']);
		// die;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('masterdata/realtime', $data);
		$this->load->view('templates/footer');
	}

	function getData($tglAwal, $tglAkhir, $inArray)
	{
		$this->load->model('Report_model','report');
		$this->load->model('Masterdata_model','masterdata');

		$array = array('jurnal.tgl >=' => $tglAwal, 'jurnal.tgl <=' => $tglAkhir);
		$this->db->where($array);
		$this->report->set_account_group_id($inArray);
		$journal_data =  $this->report->getData();

		$this->masterdata->set_account_group_id($inArray);
		$account_data = $this->masterdata->getAccountData();

		// return $result= $account_data;
		$saldo_awal_debit = 0;
		$saldo_awal_kredit = 0;
		if($account_data)
		{
			foreach ($account_data as $row)
			{	
				if($row['id_perkiraan'] == 1){
					$saldo_awal_debit = intval($row['saldo_awal']);
					$result[0][$row['kel_akun']][$row['id_akun']] = array('nama' => $row['nama'], 'saldo_awal' => $saldo_awal_debit, 'saldo' => $saldo_awal_debit, 'kel_akun' => $row['kel_akun'], 'noakun' => $row['noakun']);
				} else {
					$saldo_awal_kredit = (-1)*intval($row['saldo_awal']);
					$result[0][$row['kel_akun']][$row['id_akun']] = array('nama' => $row['nama'], 'saldo_awal' => $saldo_awal_kredit, 'saldo' => $saldo_awal_kredit, 'kel_akun' => $row['kel_akun'], 'noakun' => $row['noakun']);
				}
			}

			if($journal_data)
			{
				foreach ($journal_data as $row)
				{
					if(isset($result[0][$row['kel_akun']][$row['id_akun']]))
					{
						if($row['id_perkiraan'] == 1)
						{
							$result[0][$row['kel_akun']][$row['id_akun']]['saldo'] += $row['nilai'];
						}
						else
						{
							$result[0][$row['kel_akun']][$row['id_akun']]['saldo'] -=  $row['nilai'];
						}
					}
				}
			}			
			return $result;
		}
	}

	//tambah data akun
	public function add()
	{
		$data['title'] = 'Tambah Data Akun';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		date_default_timezone_set('Asia/Jakarta');
		$data['date'] = date("Y-m-d");

		$data['masterdata'] = $this->masterdata->getAccountData();
		$data['accountgroup'] = $this->db->get('kelompok_akun')->result_array();
		$data['perkiraan'] = $this->masterdata->getPerkiraan();

		$this->form_validation->set_rules('tipeAkun','Tipe Akun', 'required');
		$this->form_validation->set_rules('noAkun','Nomor Akun', 'required');
		$this->form_validation->set_rules('namaAkun','Nama Akun', 'required');
		$this->form_validation->set_rules('perkiraan','Perkiraan', 'required');
		$this->form_validation->set_rules('saldoAwal','Saldo Awal', 'required');
		
		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('masterdata/add_data_akun', $data);
			$this->load->view('templates/footer');
		} else {
			$saldoAwal1 	= $this->input->post('saldoAwal', true);
			$hide 			= array("Rp", ".", " ");
			$saldoAwal 		= str_replace($hide, "", $saldoAwal1);
			$this->load->model('Masterdata_model','masterdata');
			$this->masterdata->insertData($saldoAwal);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Akun baru berhasil ditambahkan! 
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			  	</button></div>');
				redirect('masterdata');
		}
	}

	//edit data akun
	public function edit($id)
	{
		$data['title'] = 'Edit Data Akun';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Masterdata_model','masterdata');

		$data['masterData'] = $this->masterdata->getAccountDataById($id);
		//kumpulan akun
		$data['kelompok_akun'] = $this->db->get('kelompok_akun')->result_array();

		//debit kredit
		$data['perkiraan'] = $this->db->get('perkiraan')->result_array();

		$this->form_validation->set_rules('noAkun','Nomor Akun', 'required');
		$this->form_validation->set_rules('namaAkun','Nama Akun', 'required');
		$this->form_validation->set_rules('saldoAwal','Saldo Awal', 'required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('masterdata/edit_data_akun', $data);
			$this->load->view('templates/footer');
		} else {
			$saldoAwal1 	= $this->input->post('saldoAwal', true);
			$hide 			= array("Rp", ".", " ");
			$saldoAwal 		= str_replace($hide, "", $saldoAwal1);
			$data['masterData'] = $this->masterdata->getAccountDataById($id);
			$name = $data['masterData']['nama'];
			$this->masterdata->editAccountData($saldoAwal);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Akun '. $name .' berhasil di update!
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			  	</button></div>');
				redirect('masterdata');
		}
	}

	//menghapus data akun
	public function delete($id)
	{
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Admin_model','admin');
		$data['masterData'] = $this->masterdata->getAccountDataById($id);
		$name = $data['masterData']['nama'];
		// cek apakah akun masih digunakan di db lain
		$pesan = '';
		$akun_sumber = $this->admin->getAllDataById('akun_sumber', 'id_akun', $id); // cash flow
		$jurnal_detail = $this->admin->getAllDataById('jurnal_detail', 'id_akun', $id); // transaksi
		$set_account = $this->admin->getAllDataById('set_account', 'id_sa', $id); // set modal usaha
		$set_account_kas = $this->admin->getAllDataById('set_account_kas', 'id_akun', $id); // set account kas
		$set_account_system = $this->admin->getAllDataById('set_account_system', 'id_akun', $id);
		$akun_biaya = $this->admin->getAllDataById('biaya', 'akun_id', $id);
		$akun_pendapatan = $this->admin->getAllDataById('pendapatan', 'akun_id', $id);
		$akun_biaya_peny = $this->admin->getAllDataById('aset', 'id_biaya_peny', $id);
		$akun_akum_peny = $this->admin->getAllDataById('aset', 'id_akum_peny', $id);
		if(!empty($akun_sumber)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada setting laporan arus kas';
		} else if(!empty($jurnal_detail)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada transaksi';
		} else if(!empty($set_account)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada setting akun';
		}  else if(!empty($set_account_kas)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada setting akun kas';
		} else if(!empty($set_account_system)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada setting akun system';	
		} else if(!empty($akun_biaya)) {
			$pesan = 'GAGAL! Akun ini masih digunakan pada akun biaya';	
		} else if(!empty($akun_pendapatan)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada akun pendapatan';
		} else if(!empty($akun_biaya_peny)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada akun biaya penyusutan';
		} else if(!empty($akun_akum_peny)){
			$pesan = 'GAGAL! Akun ini masih digunakan pada akun akumulasi penyusutan';		
		} else {
			$this->masterdata->deleteAccountData($id);
			$pesan = 'BERHASIL! Akun berhasil dihapus';
		}
		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">'. $pesan .'
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
			redirect('masterdata');
	}

	public function account_group()
	{
		$idkelAkun = $this->input->post('idkelAkun',TRUE);
		
		$this->load->model('Masterdata_model','masterdata');
		$this->db->select('MAX(akun.noakun) AS no_akun');
		$this->db->where_in('akun.id_kelompok_akun', $idkelAkun);
		$query = $this->masterdata->getAccountData();
		echo json_encode($query);
	}


	// eksport data akun
	public function export_data_akun()
	{
		$spreadsheet = new Spreadsheet(); // instantiate Spreadsheet
		$sheet = $spreadsheet->getActiveSheet();

	    
		$style_col = [
		  'font' => ['bold' => true], // Set font nya jadi bold
		  'alignment' => [
		    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
		    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		];
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = [
		  'alignment' => [
		    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
		  ],
		  'borders' => [
		    'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
		    'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
		    'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
		    'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
		  ]
		];


	    $sheet->setCellValue('A1', "Data Akun Keuangan Perusahaan"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
	    $sheet->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
	    $sheet->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
	    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
		$sheet->getStyle('A3:G3')->applyFromArray($style_col);

	    // Buat header tabel nya pada baris ke 3
	    $sheet->setCellValue('A3', "#"); 
	    $sheet->setCellValue('B3', "ID Akun"); 
	    $sheet->setCellValue('C3', "Nomor Akun"); 
	    $sheet->setCellValue('D3', "Nama Akun");
	    $sheet->setCellValue('E3', "Saldo Normal Akun"); 
	    $sheet->setCellValue('F3', "Debit"); 
	    $sheet->setCellValue('G3', "Kredit"); 

	    // Apply style header yang telah kita buat tadi ke masing-masing kolom header
	    $sheet->getStyle('A3')->applyFromArray($style_row);
	    $sheet->getStyle('B3')->applyFromArray($style_row);
	    $sheet->getStyle('C3')->applyFromArray($style_row);
	    $sheet->getStyle('D3')->applyFromArray($style_row);
	    $sheet->getStyle('E3')->applyFromArray($style_row);
	    $sheet->getStyle('F3')->applyFromArray($style_row);
	    $sheet->getStyle('G3')->applyFromArray($style_row);

	    // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya

		$this->load->model('Masterdata_model','masterdata');
	    $masterdata = $this->masterdata->getAccountData();
	    $no = 1; // Untuk penomoran tabel, di awal set dengan 1
	    $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
	    $debit = 0;
	    $kredit = 0;
	    $tot_debit = 0;
	    $tot_kredit = 0;
	    foreach($masterdata as $data){ // Lakukan looping pada variabel siswa

	      	$sheet->setCellValue('A'.$numrow, $no);
	      	$sheet->setCellValue('B'.$numrow, $data['id_akun']);
	      	$sheet->setCellValue('C'.$numrow, $data['noakun']);
	      	$sheet->setCellValue('D'.$numrow, $data['nama']);

	    	// saldo normal akun
	    	if($data['id_perkiraan'] == 1) {
	    		$sna = 'DEBIT';
	    		$debit = $data['saldo_awal'];
	    		$tot_debit += $debit;
	    	
	      		$sheet->setCellValue('E'.$numrow, $sna);
		      	$sheet->setCellValue('F'.$numrow, $debit);
		      	$sheet->setCellValue('G'.$numrow, '');
	    	} else {
	    		$sna = 'KREDIT';
	    		$kredit = $data['saldo_awal'];
	    		$tot_kredit += $kredit;
	    	
	      		$sheet->setCellValue('E'.$numrow, $sna);
		      	$sheet->setCellValue('F'.$numrow, '');
		      	$sheet->setCellValue('G'.$numrow, $kredit);
	    	}

	      
	      	// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
	      	$sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
	      	$sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
	      
	      	$no++; // Tambah 1 setiap kali looping
	      	$numrow++; // Tambah 1 setiap kali looping
	    }

	    $sheet->setCellValue('A'.$numrow, "Jumlah"); // Set kolom A1 dengan tulisan "DATA SISWA"
	    $sheet->mergeCells('A'.$numrow.':E'.$numrow); // Set Merge Cell pada kolom A1 sampai E1

      	$sheet->setCellValue('F'.$numrow, $tot_debit);
      	$sheet->setCellValue('G'.$numrow, $tot_kredit);
      
      	// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
      	$sheet->getStyle('A'.$numrow.':E'.$numrow)->applyFromArray($style_row);
      	$sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
      	$sheet->getStyle('G'.$numrow)->applyFromArray($style_row);


	    // Set width kolom
	    $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
	    $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
	    $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
	    $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
	    $sheet->getColumnDimension('E')->setWidth(20); // Set width kolom E
	    $sheet->getColumnDimension('F')->setWidth(25); // Set width kolom E
	    $sheet->getColumnDimension('G')->setWidth(25); // Set width kolom E
	    
	    // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
	    $sheet->getDefaultRowDimension()->setRowHeight(-1);


	    // Set judul file excel nya
	    $sheet->setTitle("Data Akun Keuangan");
	    $writer = new Xls($spreadsheet); // instantiate Xlsx
 
		$filename = time().'_data_akun_keuangan'; // set filename for excel file to be exported

		// header('Content-Type: application/vnd.ms-excel'); // generate excel file

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'. $filename .'.xls"'); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');  // download file 
	}



	// Piutang karyawan
	// public function
}
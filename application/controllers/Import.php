<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Import extends CI_Controller 
{ 
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('Import_model','import');
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		$data['title'] = 'Import Transaksi';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

		$data['dt_import'] = $this->import->getAllData();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('import/index', $data);
		$this->load->view('templates/footer');
	}

	public function import_excel()
	{
	    $user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");

		// mengurai excel 
		$notifikasi = array();


		$tgl_sekarang = date('YmdHis'); // Ini akan mengambil waktu sekarang dengan format yyyymmddHHiiss
        $nama_file_baru = 'data' . $tgl_sekarang . '.xlsx';

        // Cek apakah terdapat file data.xlsx pada folder tmp
        if (is_file('./assets/file/' . $nama_file_baru)) // Jika file tersebut ada
            unlink('./assets/file/' . $nama_file_baru); // Hapus file tersebut

        $ext = pathinfo($_FILES['import']['name'], PATHINFO_EXTENSION); // Ambil ekstensi filenya apa
        $tmp_file = $_FILES['import']['tmp_name'];


        // Cek apakah file yang diupload adalah file Excel 2007 (.xlsx)
        if ($ext == "xls") {
            
            // Upload file yang dipilih ke folder tmp
            // dan rename file tersebut menjadi data{tglsekarang}.xlsx
            // {tglsekarang} diganti jadi tanggal sekarang dengan format yyyymmddHHiiss
            // Contoh nama file setelah di rename : data20210814192500.xlsx
            move_uploaded_file($tmp_file, './assets/file/' . $nama_file_baru);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $spreadsheet = $reader->load('./assets/file/' . $nama_file_baru); // Load file yang tadi diupload ke folder tmp
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // mendapatkan data sheet
            $dt_jurnal = array();
			$numrow = 0;
			foreach($sheet as $row) {

				if ($numrow > 0) {
					$dt_jurnal[] = explode("|",$row['A']);
				}
				$numrow++;
			}
    
			// menyusun data
			$result = array();

			foreach ($dt_jurnal as $data) {
			    $detail = array(
			        'id_akun' => $data[4],
			        'tipe_kas' => $data[5],
			        'id_perkiraan' => $data[6],
			        'nilai' => $data[7],
			    );

			    // Check if jurnal ID already exists in $result
			    $jurnal_id_exists = false;
			    foreach ($result as &$item) {
			        if ($item['id'] == $data[0]) {
			            $item['detail'][] = $detail;
			            $jurnal_id_exists = true;
			            break;
			        }
			    }

			    if (!$jurnal_id_exists) {
			        $new_array = array(
			            'id' => $data[0],
			            'tgl' => $data[1],
			            'desk' => $data[2],
			            'id_user' => $data[3],
			            'detail' => array($detail),
			        );
			        $result[] = $new_array;
			    }
			}

			// echo json_encode($result);
			// die;

			
			// masukkan import
			$id_impor = $this->import->insert_import();

			// memasukkan ke dalam database secara berurutan
			foreach($result as $row){
				// memasukkan ke dalam database 
				$tgl = date('Y-m-d', strtotime($row['tgl']));
				$no_trans = date('ymd', strtotime($row['tgl'])).sprintf("%04s", $row['id']);
				$id_jurnal = $this->import->insert_transaksi($no_trans, $tgl, $row['desk'], $row['id_user'], $row['detail']);

				// masukkan ke database impor detail
				$this->import->insert_import_detail($id_impor, $id_jurnal);
			}

			$pesan = "Data impor Berhasil disimpan!";
        } else {
            $pesan = "File yang diupload tidak berextensi xls!";
        }

		$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">'.$pesan.' 
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
		redirect('import');
	}

	public function delete_import()
	{
		// hapus yang telah di import
		$this->load->model('Journal_model','journal');

		$id = $this->input->post('id_import', true);
		$dtDetail = $this->import->getDataDetailById($id);

		foreach($dtDetail as $row) {
			// menghapus detail jurnal dan jurnal
			$this->journal->delete_journal(intval($row['id_jurnal']));
		}

		$this->import->delete_import($id);
		$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">Data import berhasil dihapus!
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button></div>');
		redirect('import');
	}

}
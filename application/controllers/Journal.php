<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// Allow AJAX delete_bukti without redirect loop
		if ($this->router->fetch_method() !== 'delete_bukti') {
			is_logged_in();
		}
		$this->load->model('Journal_model','journal');
	}
                        
	public function index()
	{
		$data['title'] = 'Jurnal Umum';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
				
		date_default_timezone_set('Asia/Jakarta');
		
		$now = date("Y-m-d");
		$data['tglAwal']  = date('Y-01-01', strtotime($now));
		$data['tglAkhir'] = date('Y-12-31', strtotime($now));
		
		$data['journal'] =  $this->journal->getJournal();
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('journal/index', $data);
		$this->load->view('templates/footer', $data);
		
	}

    public function ajax_list_journal()
    {
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$t_awal = $this->input->post('tgl_awal');
		$t_akhir = $this->input->post('tgl_akhir');
		
		$tgl_awal = convertDateToDbdate($t_awal);
		$tgl_akhir = convertDateToDbdate($t_akhir);

		$this->session->set_userdata([
			'journal_tgl_awal'  => $tgl_awal,
			'journal_tgl_akhir' => $tgl_akhir
		]);

		$list = $this->journal->getAllData($tgl_awal, $tgl_akhir);

        //$list = $this->journal->get_datatables();

		// Inisialisasi array baru
		$array_baru = array();

		// Loop untuk menghitung total nominal berdasarkan nilai "no_trans"
		foreach ($list as $row) {
		    $id_jurnal = $row["id_jurnal"]; // Ambil nilai "id_jurnal"
		    $key = array_search($id_jurnal, array_column($array_baru, "id_jurnal")); // Cari apakah grup "id_jurnal" sudah ada di array baru

		    if ($key !== false) {
		        // Jika grup "no_trans" sudah ada di array baru, tambahkan nominal ke grup yang ada
		        $array_baru[$key]["nominal"] += $row["nominal"];
		    } else {
		        // Jika grup "no_trans" belum ada di array baru, tambahkan sebagai elemen baru
		        $array_baru[] = $row;
		    }
		}

        $data = array();
        //$no = $_POST['start'];
		$no = 0;
        foreach ($array_baru as $item) {

        	// membuat no transaksi
			$setJu = $this->setting->getPenomoranByIdMenu($item['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$item['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date("d/n/Y", strtotime($item['tgl']));
            $row[] = $no_trans;
            $row[] = $item['keterangan'];
            $row[] = number_format($item['nominal'],0,",",".");

            $row[] = ' 		
        			<a href="'.base_url('journal/detail/').$item['id_jurnal'].'" class="badge badge-success">
                        <span class="icon text-white-50">
                            <i class="fas fa-fw fa-eye"></i>
                        </span>
                        <span class="text">Detail</span>
                    </a>
        			<a href="'.base_url('journal/edit/').$item['id_jurnal'].'" class="badge badge-primary">
                        <span class="icon text-white-50">
                            <i class="fas fa-fw fa-edit"></i>
                        </span>
                        <span class="text">Edit</span>
                    </a>                            
					<a href="#" 
					   class="badge badge-danger btn-delete" data-id="'.$item['id_jurnal'].'" data-tgl="'.$item['tgl'].'" >
					   <span class="icon text-white-50">
					    <i class="fas fa-fw fa-trash"></i>
					    </span> 
					    <span class="text">Delete</span>
					</a>';
            $data[] = $row;
        }
 
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->journal->count_all(),
                    "recordsFiltered" => $this->journal->count_filtered(),
                    "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

	function pemrosesan($tgl_awal, $tgl_akhir)
	{
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');

		$where_id = array('d.tgl >=' => $tgl_awal, 'd.tgl <=' => $tgl_akhir);
		$this->db->where($where_id);
		$dt_jurnal = $this->jurnal->getAllData();

		$result = array();
		foreach($dt_transaksi as $row) {
			$now = date("Y-m-d", strtotime($row['tgl']));
			$tahun = date('Y', strtotime($now));
	        $bulan = date('m', strtotime($now));

			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			$result[] = $row;
		}

		return $result;
	}

    public function detail($id)
    {
    	$data['title'] = 'Detail Transaksi';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Journal_model','journal');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Kas_model','kas');
		$this->load->model('Admin_model','admin');
		//menampilkan semua data

		$data['dt_byID'] = $this->admin->GetDataById('jurnal', 'id_jurnal', $id);
    	$data['dt_detail'] = $this->journal->proses_detail_journal($id);



    	//menampilkan data akun
    	$this->db->order_by('kelompok_akun.kel_akun', 'ASC');
		$data['akun'] = $this->masterdata->getAccountData();
    	//perkiraan debit kredit
		$data['perkiraan'] = $this->journal->getPerkiraan();
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('journal/detail_journal', $data);
		$this->load->view('templates/footer');
    }
    
	public function add()
	{
		$data['title'] = 'Tambah Data Transaksi';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Journal_model','journal');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Setting_model','setting');

		//date
		date_default_timezone_set('Asia/Jakarta');
		$data['now'] = date("Y-m-d");
		$tahun = date('Y', strtotime($data['now']));
        $bulan = date('m', strtotime($data['now']));

		$type_trans = 1;
		$setJu = $this->setting->getPenomoranByIdMenu($type_trans); 
		$data['no_urut'] = $this->journal->generate_no_trans($setJu['reset_nomor'], $type_trans, $data['now']);
		$data['no_trans'] = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$data['no_urut']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;


		//debit kredit
		$data['perkiraan'] = $this->journal->getPerkiraan();

		$this->db->order_by('kelompok_akun.kel_akun', 'ASC');
		$data['akun'] =  $this->masterdata->getAccountData();

		$this->form_validation->set_rules('tglTransaksi','Tanggal Transaksi', 'required');
		$this->form_validation->set_rules('keterangan','Keterangan', 'trim|required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('journal/add_journal', $data);
			$this->load->view('templates/footer');
		} else {
    		  
			$idAkun 	= $this->input->post('idAkun[]', true);
			$nilai1 	= $this->input->post('nilai_debit[]', true);
			$nilai2 	= $this->input->post('nilai_kredit[]', true);

			$hide 		= array("Rp", ".", " ");
			$nilai_debit 	= str_replace($hide, "", $nilai1);
			$nilai_kredit 	= str_replace($hide, "", $nilai2);


			$result = array();
			$debit_sum = 0;
			$kredit_sum = 0;
		   	foreach($idAkun as $key => $val){
			   	if($nilai_debit[$key] != "") {
			   		// debit
			     	$result[] = array(
				        'id_akun'		=> $_POST['idAkun'][$key],
				        'id_perkiraan'	=> 1, 
				        'nilai'			=> $nilai_debit[$key], 
			      	);
					$debit_sum += $nilai_debit[$key];
			   	} else {
			   		// kredit
			     	$result[] = array(
				        'id_akun'		=> $_POST['idAkun'][$key],
				        'id_perkiraan'	=> 2, 
				        'nilai'			=> $nilai_kredit[$key], 
			      	);

					$kredit_sum += $nilai_kredit[$key];
			   	}
		    };
			// mencari total nilai debit dan kredit

			// upload bukti transaksi
			$upload_bukti = $_FILES['bukti']['name'];
			$bukti_transaksi = "";
			if($upload_bukti){
                $new_name = time().rand();

				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size']      = '5048';
				$config['upload_path']   = './assets/file/bukti/';
                $config['file_name']     = $new_name;

				$this->load->library('upload', $config);

				if($this->upload->do_upload('bukti')) {
					$bukti_transaksi = $this->upload->data('file_name');
				} else {
					echo $this->upload->display_errors();
				}
			}

		    $error = array();
		    // jika terdapat error

		    // mencari akun duplikat
			function findDuplicates($count) {
		        return $count > 1;
		    }


			if (count($idAkun) > 1){
				//ngecek debit dan kredit
				$na_duplicat = array_unique(array_filter(array_count_values($idAkun), "findDuplicates"));

				if (!$na_duplicat){
					//jika melewati semuanya
					if($debit_sum == $kredit_sum && ($debit_sum != 0 || $kredit_sum != 0)) {

						$id_journal = $this->journal->insertJournal($bukti_transaksi, 1);
						$this->journal->detailInsertJournal($id_journal, $idAkun, $result);

						$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Data transaksi baru berhasil ditambahkan! 
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    	<span aria-hidden="true">&times;</span>
						  	</button></div>');
						redirect('journal');
					};

					//jika nominal debit 0 dan kredit 0
					if($debit_sum == 0 || $kredit_sum == 0){
						$error[] = "Jumlah data debit maupun kredit tidak boleh 0!";
					};

					//jika jumlah debit tidak balance dengan kredit
					if($debit_sum != $kredit_sum){
						$error[] = "Jumlah debit harus sama dengan jumlah kredit!";
					};

				} else {
					$error[] = "Data akun yang digunakan tidak boleh sama!";
				};

			} else {
				$error[] = "Akun yang dimasukkan minimal 2 akun!";
			};

			if(!empty($error)) {
			    foreach ($error as $value) {
			    	$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">'.$value.'
			    		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    	<span aria-hidden="true">&times;</span>
					  	</button></div>');
			    	redirect('journal/add');
			    }
			}
            
		}
	}

	public function name_account()
	{
		$this->load->model('Masterdata_model','masterdata');
		
    	$data = $this->masterdata->getAccountData();

    	echo json_encode($data);
	}

	public function auto_account_id()
	{
		$name = $this->input->post('name',TRUE);
		
		$this->load->model('Masterdata_model','masterdata');
		$query = $this->masterdata->getAccountData();

		$result = array();
		foreach ($query as $row){
			$result[] = array(
		        'id'		=>$row['id_akun'],
		        'nama'		=>$row['nama'],
	      	);
		}

		$id = 0;
		foreach ($result as $res){
			if ($res['nama'] == $name){
				$id = $res['id'];
			}
		}

		echo json_encode($id);
	}

	public function auto_name()
	{
		$id = $this->input->post('id',TRUE);
		
		$this->load->model('Masterdata_model','masterdata');
		$query = $this->masterdata->getAccountDataById($id);
		echo json_encode($query);
	}

	public function auto_journal()
	{
		$this->load->model('Journal_model','journal');
		$id = $this->input->post('id');

    	$data = $this->journal->get_jurnal_by_id($id);

    	echo json_encode($data);
	}

	public function edit($id)
	{
		$data['title'] = 'Edit Data Transaksi';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$this->load->model('Journal_model','journal');
		$this->load->model('Masterdata_model','masterdata');
		$this->load->model('Kas_model','kas');
		$this->load->model('Admin_model','admin');
		$this->load->model('Setting_model','setting');


		//menampilkan semua data
		$data['dt_byID'] = $this->admin->GetDataById('jurnal', 'id_jurnal', $id);
    	$data['dt_detail'] = $this->journal->proses_detail_journal($id);

		date_default_timezone_set('Asia/Jakarta');
		$tahun = date('Y', strtotime($data['dt_byID']['tgl']));
        $bulan = date('m', strtotime($data['dt_byID']['tgl']));

		$type_trans = 1;
		$setJu = $this->setting->getPenomoranByIdMenu($type_trans); 
		$data['no_urut'] = $data['dt_byID']['no_trans'];
		$data['no_trans'] = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$data['dt_byID']['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;


    	//menampilkan data akun
    	$this->db->order_by('kelompok_akun.kel_akun', 'ASC');
		$data['akun'] = $this->masterdata->getAccountData();
    	//perkiraan debit kredit
		$data['perkiraan'] = $this->journal->getPerkiraan();
		
		$this->form_validation->set_rules('keterangan','Keterangan', 'trim|required');

		if($this->form_validation->run() == false) {
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('journal/edit_journal', $data);
			$this->load->view('templates/footer');
		} else {
    		  
			// echo "Berhasil";
			$id_jurnal  = $this->input->post('id', true);			
			$idAkun 	= $this->input->post('idAkun[]', true);
			$nilai1 	= $this->input->post('nilai_debit[]', true);
			$nilai2 	= $this->input->post('nilai_kredit[]', true);

			$hide 		= array("Rp", ".", " ");
			$nilai_debit 	= str_replace($hide, "", $nilai1);
			$nilai_kredit 	= str_replace($hide, "", $nilai2);

			// memproses transaksi ini tipe kas atau bukan
			$result = array();
			$debit_sum = 0;
			$kredit_sum = 0;
		   	foreach($idAkun as $key => $val){
			   	if($nilai_debit[$key] != "") {
			   		// debit
			     	$result[] = array(
				        'id_akun'		=> $_POST['idAkun'][$key],
				        'id_perkiraan'	=> 1, 
				        'nilai'			=> $nilai_debit[$key], 
			      	);
					$debit_sum += $nilai_debit[$key];
			   	} else {
			   		// kredit
			     	$result[] = array(
				        'id_akun'		=> $_POST['idAkun'][$key],
				        'id_perkiraan'	=> 2, 
				        'nilai'			=> $nilai_kredit[$key], 
			      	);

					$kredit_sum += $nilai_kredit[$key];
			   	}
		    };

			// ===== UPLOAD BUKTI TRANSAKSI (SAFE) =====
			$bukti_transaksi = $data['dt_byID']['bukti']; // default = old file

			if (!empty($_FILES['bukti']['name'])) {

				$config['upload_path']   = FCPATH . 'assets/file/bukti/';
				$config['allowed_types'] = 'pdf|jpg|jpeg|png';
				$config['max_size']      = 5048;
				$config['encrypt_name']  = TRUE; // safer than time()+rand

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('bukti')) {

					$uploadData = $this->upload->data();
					$new_file   = $uploadData['file_name'];

					// Delete OLD file only if exists
					if (!empty($data['dt_byID']['bukti'])) {
						$old_path = FCPATH . 'assets/file/bukti/' . $data['dt_byID']['bukti'];
						if (file_exists($old_path)) {
							unlink($old_path);
						}
					}

					$bukti_transaksi = $new_file;

				} else {
					$this->session->set_flashdata('message',
						'<div class="alert alert-danger">'.$this->upload->display_errors().'</div>');
					redirect('journal/edit/'.$id);
					return;
				}
			}
			
		    $error = array();
		    // jika terdapat error

			function findDuplicates($count) {
		        return $count > 1;
		    }

			if(count($idAkun) > 1){
				//akun tidak boleh sama
				$na_duplicat = array_unique(array_filter(array_count_values($idAkun), "findDuplicates"));

				if (!$na_duplicat){
					//jika melewati semuanya
					if($debit_sum == $kredit_sum && ($debit_sum != 0 || $kredit_sum != 0)) {
						// $array = array($id, $tglTransaksi, $noTransaksi, $keterangan, $idAkun, $nominal, $tipe_kas);
						// echo json_encode($array);
						$this->journal->changeJournal($id_jurnal, $bukti_transaksi);
						$this->journal->detailInsertJournal($id_jurnal, $idAkun, $result);

						$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Data transaksi berhasil di update!
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    	<span aria-hidden="true">&times;</span>
						  	</button></div>');
						redirect('journal');
					}

					//jika nominal debit 0 dan kredit 0
					if($debit_sum == 0 || $kredit_sum == 0){
						$error[] = "Jumlah data debit maupun kredit tidak boleh 0!";
					}

					//jika jumlah debit tidak balance dengan kredit
					if($debit_sum != $kredit_sum){
						$error[] = "Jumlah debit harus sama dengan jumlah kredit!";
					}

				}
				else {
					$error[] = "Data akun yang digunakan tidak boleh sama!";
				}
			} else {
				//jika hanya ada satu data yang dikirim
				$error[] = "Akun yang dimasukkan minimal 2 akun!";
			};

			if(!empty($error)) {
			    foreach ($error as $value) {
			    	$this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">'.$value.'
			    		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    	<span aria-hidden="true">&times;</span>
					  	</button></div>');
			    	redirect('journal/edit/'.$id);
			    }
			}
		}
	}
	
	public function delete_bukti()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('No direct access allowed', 403);
		}

		$file = trim($this->input->post('file'));
		$path = FCPATH . 'assets/file/bukti/' . $file;

		if ($file && file_exists($path)) {
			unlink($path);

			$this->db->where('bukti', $file);
			$this->db->update('jurnal', ['bukti' => null]);
		}
	}

	public function printgeneraljournal()
	{
		$tglAwal 	= convertDateToDbdate($this->input->post('tglAwal', true));
		$tglAkhir 	= convertDateToDbdate($this->input->post('tglAkhir', true));

		$this->load->model('Journal_model','journal');
		$data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();
		$data['journal'] =  $this->pemrosesan_report($tglAwal,$tglAkhir);
		$data['tglAwal'] = $tglAwal;
		$data['tglAkhir'] = $tglAkhir;

		$this->load->library('fpdf');
		$this->load->view('report/pdf/general_journal', $data);
	}

	function pemrosesan_report($tglAwal,$tglAkhir)
	{
		$this->load->model('Setting_model','setting');
		date_default_timezone_set('Asia/Jakarta');
		$now = date("Y-m-d");
		$tahun = date('Y', strtotime($now));
        $bulan = date('m', strtotime($now));

		$dt_transaksi = $this->journal->reportJournal($tglAwal,$tglAkhir);
		$result = array();
		foreach($dt_transaksi as $row) {
			$setJu = $this->setting->getPenomoranByIdMenu($row['type']); 
			$no_trans = sprintf('%0'.$setJu['panjang_nomor'].'d', (int)$row['no_trans']).'/'.$setJu['prefix'].'/'.$bulan.'/'.$tahun;
			$row['no_trans'] = $no_trans;

			$result[] = $row;
		}

		return $result;
	}

	public function delete($id = null)
	{
		if ($id == null) {
			show_404();
		}

		// cek apakah data ada
		$cek = $this->db->get_where('jurnal', ['id_jurnal' => $id])->row();

		if (!$cek) {
			show_404();
		}

		// panggil model untuk delete
		$this->journal->delete_journal($id);

		$this->session->set_flashdata(
			'message',
			'<div class="alert alert-success" role="alert">
			Data jurnal berhasil dihapus!
			<button type="button" class="close" data-dismiss="alert">
			<span>&times;</span>
			</button></div>'
		);

		redirect('journal');
	}
}


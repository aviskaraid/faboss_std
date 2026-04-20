<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neracasaldo extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Neracasaldo_model', 'neracasaldo');
        $this->load->model('Admin_model','admin');
    }

    public function index()
    {
        $data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

        $data['title'] = 'Laporan Neraca Saldo';

        // ================= USER LOGIN =================
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        date_default_timezone_set('Asia/Jakarta');
        $data['now'] = date("Y-m-d");

        // ================= LIST BULAN =================
        $data['bulan_list'] = [
            ['id'=>'01','nama'=>'Januari'],
            ['id'=>'02','nama'=>'Februari'],
            ['id'=>'03','nama'=>'Maret'],
            ['id'=>'04','nama'=>'April'],
            ['id'=>'05','nama'=>'Mei'],
            ['id'=>'06','nama'=>'Juni'],
            ['id'=>'07','nama'=>'Juli'],
            ['id'=>'08','nama'=>'Agustus'],
            ['id'=>'09','nama'=>'September'],
            ['id'=>'10','nama'=>'Oktober'],
            ['id'=>'11','nama'=>'November'],
            ['id'=>'12','nama'=>'Desember']
        ];

        // ================= LIST TAHUN =================
        $tahun_sekarang = date('Y');
        $data['tahun_list'] = range($tahun_sekarang - 5, $tahun_sekarang + 1);

        // ================= VALIDASI INPUT =================
        $this->form_validation->set_rules('bulan', 'Bulan', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required|numeric');

        if ($this->form_validation->run() == false) {
            // default jika belum submit
            $data['bulan'] = date('m');
            $data['tahun'] = date('Y');
        } else {            
            // ambil dari form
            $data['bulan'] = str_pad($this->input->post('bulan', true), 2, '0', STR_PAD_LEFT);
            $data['tahun'] = (int) $this->input->post('tahun', true);
        }

        // ================= AMBIL NAMA BULAN =================        
        $nama_bulan = getBulan($data['bulan']);
        $data['periode'] = $nama_bulan . ' ' . $data['tahun'];


        // ================= INFO PERIODE UNTUK HEADER LAPORAN =================
        $data['akhir_periode'] = date(
            "d F Y",
            strtotime($data['tahun'].'-'.$data['bulan'].'-01')
        );

        // ================= AMBIL DATA NERACA =================
        $data['neracasaldo'] = $this->neracasaldo->getNeracaSaldo($data['bulan'], $data['tahun']);

        // ================= LOAD VIEW =================
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('neracasaldo/index', $data);
        $this->load->view('templates/footer');
    }

    public function print()
    {
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (!$bulan || !$tahun) {
            show_error("Periode tidak valid");
        }

        // Pastikan format bulan 2 digit
        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $data['bulan']  = $bulan;
        $data['tahun']  = $tahun;
        $data['profile'] = $this->admin->companyProfile();

        // ================= PERIODE (SINGLE SOURCE) =================
        $nama_bulan = getBulan($bulan);
        $data['periode'] = $nama_bulan . ' ' . $tahun;

        // ================= DATA =================
        $data['neracasaldo'] = $this->neracasaldo->getNeracaSaldo($bulan, $tahun);

        // ================= OUTPUT =================
        if (isset($_POST['pdf'])) {
            $this->load->library('PDF_MC_Table');
            $this->load->view('neracasaldo/print_pdf', $data);
        } 
        else if (isset($_POST['excel'])) {
            $this->load->view('neracasaldo/print_excel', $data);
        }
    }
    
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neraca extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Neraca_model', 'neraca');
        $this->load->model('Admin_model','admin');
    }

    public function index()
    {
        $data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

        $data['title'] = 'Laporan Neraca';

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

        // ================= INFO PERIODE UNTUK HEADER LAPORAN =================
        $data['akhir_periode'] = date(
            "d F Y",
            strtotime($data['tahun'].'-'.$data['bulan'].'-01')
        );

        // ================= AMBIL NAMA BULAN =================        
        $nama_bulan = getBulan($data['bulan']);
        $data['periode'] = $nama_bulan . ' ' . $data['tahun'];

        // ================= AMBIL DATA NERACA =================
        $data['neraca'] = $this->neraca->getNeracaPeriode($data['bulan'], $data['tahun']);

        // ================= LOAD VIEW =================
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('neraca/index', $data);
        $this->load->view('templates/footer');
    }

    public function print()
    {
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (!$bulan || !$tahun) {
            show_error("Periode tidak valid");
        }

        $data['bulan']  = $bulan;
        $data['tahun']  = $tahun;
        $data['profile'] = $this->admin->companyProfile();

        // Ambil data neraca
        $data['neraca'] = $this->neraca->getNeracaPeriode($bulan, $tahun);

        // Hitung total
        $total_aset = 0;
        $total_liabilitas = 0;
        $total_ekuitas = 0;

        foreach ($data['neraca'] as $n) {
            if ($n['tipe'] == 'A') {
                $total_aset += $n['subtotal'];
            } else {
                if (stripos($n['nama_kel_akun'], 'ekuitas') !== false || stripos($n['nama_kel_akun'], 'modal') !== false) {
                    $total_ekuitas += $n['subtotal'];
                } else {
                    $total_liabilitas += $n['subtotal'];
                }
            }
        }

        $data['total_aset'] = $total_aset;
        $data['total_liabilitas'] = $total_liabilitas;
        $data['total_ekuitas'] = $total_ekuitas;

        $nama_bulan = getBulan($data['bulan']);
        $data['periode'] = $nama_bulan . ' ' . $data['tahun'];

        // ================= PDF =================
        if (isset($_POST['pdf'])) {
            $this->load->library('PDF_MC_Table');
            $this->load->view('neraca/print_pdf', $data);
        }

        // ================= EXCEL =================
        else if (isset($_POST['excel'])) {
            $this->load->view('neraca/print_excel', $data);
        }
    }

}

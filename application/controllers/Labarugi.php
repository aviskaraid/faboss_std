<?php
class Labarugi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Labarugi_model', 'labarugi');
        $this->load->model('Admin_model','admin');
    }

    public function index()
    {
        $data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

        $data['title'] = 'Laporan Laba Rugi';

        // ================= USER LOGIN =================
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        date_default_timezone_set('Asia/Jakarta');
        $data['now'] = date("Y-m-d");


        // ================= FILTER =================
        $data['bulan'] = $this->input->post('bulan') ?? date('m');
        $data['tahun'] = $this->input->post('tahun') ?? date('Y');
        $data['mode_periode'] = $this->input->post('mode_periode') ?? 'bulan';

        $data['bulan_list'] = [
            ['id'=>1,'nama'=>'Januari'],['id'=>2,'nama'=>'Februari'],
            ['id'=>3,'nama'=>'Maret'],['id'=>4,'nama'=>'April'],
            ['id'=>5,'nama'=>'Mei'],['id'=>6,'nama'=>'Juni'],
            ['id'=>7,'nama'=>'Juli'],['id'=>8,'nama'=>'Agustus'],
            ['id'=>9,'nama'=>'September'],['id'=>10,'nama'=>'Oktober'],
            ['id'=>11,'nama'=>'November'],['id'=>12,'nama'=>'Desember']
        ];
        $data['tahun_list'] = range(date('Y')-5, date('Y')+1);

        // ================= AMBIL NAMA BULAN =================                
        $mode = $data['mode_periode'];
        $bulan = (int)$data['bulan'];
        $tahun = (int)$data['tahun'];

        if ($mode == 'sd_bulan') {
            $data['periode'] = 'Januari s/d '.getBulan($bulan).' '.$tahun;
        } else {
            $data['periode'] = getBulan($bulan).' '.$tahun;
        }

        // ================= AMBIL DATA MODEL =================
        $raw = $this->labarugi
            ->getLabaRugiBaru($data['bulan'], $data['tahun'], $data['mode_periode']);

        // ================= AMBIL DATA KELOMPOK HPP =================
        $data_set_akun = $this->labarugi->getSetAkun();    
        $id_kel_hpp = $data_set_akun['id_kel_hpp'];

        // ================= KELOMPOKKAN UNTUK VIEW =================
        $data['pendapatan'] = [];
        $data['hpp']        = [];
        $data['beban']      = [];

        $total_pendapatan = 0;
        $total_hpp        = 0;
        $total_beban      = 0;

        foreach ($raw as $id_kel => $group) {

            // KELOMPOK PENDAPATAN (tipe L)
            if ($group['tipe'] == 'L') {
                $data['pendapatan'][] = $group;
                $total_pendapatan += $group['subtotal'];
            }

            // KELOMPOK HPP
            elseif ($id_kel == $id_kel_hpp) {
                $data['hpp'][] = $group;
                $total_hpp += abs($group['subtotal']); // HPP selalu dianggap beban
            }

            // KELOMPOK BEBAN LAINNYA
            elseif ($group['tipe'] == 'B') {
                $data['beban'][] = $group;
                $total_beban += abs($group['subtotal']);
            }
        }

        // ================= HITUNG LABA =================
        $data['total_pendapatan'] = $total_pendapatan;
        $data['total_hpp']        = $total_hpp;
        $data['laba_kotor']       = $total_pendapatan - $total_hpp;
        $data['total_beban']      = $total_beban;
        $data['laba_bersih']      = $data['laba_kotor'] - $total_beban;

        // ================= LOAD VIEW =================
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('labarugi/index', $data); // pakai index baru
        $this->load->view('templates/footer');
    }

    public function print()
    {
        $bulan  = (int)$this->input->post('bulan', true);
        $tahun  = (int)$this->input->post('tahun', true);
        $mode   = $this->input->post('mode_periode', true) ?: 'bulan';

        // ================= PROFIL =================
        $data['profile'] = $this->admin->companyProfile();

        // ================= PERIODE TEXT =================
        if ($mode == 'sd_bulan') {
            $data['periode'] = 'Januari s/d '.getBulan($bulan).' '.$tahun;
        } else {
            $data['periode'] = getBulan($bulan).' '.$tahun;
        }

        $data['bulan'] = getBulan($bulan);  // untuk nama file
        $data['tahun'] = $tahun;

        // ================= AMBIL DATA MODEL BARU =================
        $this->load->model('Labarugi_model');

        $raw = $this->Labarugi_model->getLabaRugiBaru($bulan,$tahun,$mode);

        // 🔹 Pisahkan sesuai struktur laporan baru
        $data['pendapatan'] = [];
        $data['hpp']        = [];
        $data['beban']      = [];

        foreach($raw as $id => $g){
            if($g['tipe']=='L') $data['pendapatan'][] = $g;
            if($id == 7)        $data['hpp'][]        = $g; // HPP
            if($g['tipe']=='B' && $id != 7) $data['beban'][] = $g;
        }

        // ================= OUTPUT =================
        if (isset($_POST['pdf'])) {
            $this->load->library('PDF_MC_Table');
            $this->load->view('labarugi/labarugi_pdf', $data);
        } elseif (isset($_POST['excel'])) {
            $this->load->view('labarugi/labarugi_excel', $data);
        } else {
            show_error('Jenis output tidak dikenali');
        }
    }

}
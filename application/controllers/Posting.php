<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posting extends CI_Controller 
{ 
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->model('Posting_model','posting');
    }

    // tutup buku
    public function index()
    {
        $data['title'] = 'Posting/Unposting Transaksi';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

        $tahun = date('Y');
        $data['tahun'] = $tahun;
        $data['dtPeriodeDetail'] = $this->posting->get_periode_posting($tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('posting/index', $data);
        $this->load->view('templates/footer');
    }

    public function validasi_posting()
    {
        $bln   = $this->input->post('bln');
        $tahun = $this->input->post('tahun');

        $result = $this->posting->validasiPosting($bln, $tahun);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function posting_bulanan()
    {
        $id_periode_tutup = $this->input->post('id_periode_tutup');
        $bulan_posting = $this->input->post('bulan_posting');
        $tahun_posting = $this->input->post('tahun_posting');

        date_default_timezone_set('Asia/Jakarta');
        $tgl_posting = date("Y-m-d H:i:s");

        // update posting di table jurnal
        $this->posting->update_jurnal_posting($bulan_posting, $tahun_posting, $tgl_posting);

        // ubah posting di table periode_tutup
        $this->posting->update_periode_posting($id_periode_tutup);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Posting transaksi bulanan berhasil diproses!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect('posting');
    }

    public function validasi_unposting()
    {
        $bln   = $this->input->post('bln');
        $tahun = $this->input->post('tahun');

        $result = $this->posting->validasiUnposting($bln, $tahun);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function unposting_bulanan()
    {
        $id_periode_tutup = $this->input->post('id_periode_tutup');
        $bulan_posting = $this->input->post('bulan_posting');
        $tahun_posting = $this->input->post('tahun_posting');

        date_default_timezone_set('Asia/Jakarta');
        $tgl_posting = date("Y-m-d H:i:s");

        // update posting di table jurnal
        $this->posting->update_jurnal_unposting($bulan_posting, $tahun_posting);

        // ubah posting di table periode_tutup
        $this->posting->update_periode_unposting($id_periode_tutup);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Unposting transaksi bulanan berhasil diproses!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect('posting');
    }

}
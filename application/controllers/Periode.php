<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode extends CI_Controller 
{ 
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->model('Periode_model','periode');
    }

    // tutup buku
    public function index()
    {
        $data['title'] = 'Periode Akuntansi';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

        $data['periode'] = $this->periode->get_data_periode();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('periode/index', $data);
        $this->load->view('templates/footer');
    }

    public function open_periode($id)
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $dt_periode = $this->periode->get_data_periode_by_id($id);

        $this->periode->edit_table('user','id_user', $data['user']['id_user'], 'id_periode', $dt_periode['tahun']);
        $this->periode->edit_table('periode','id_periode', $id, 'stts', 1);

        $dt_periode_close = $this->periode->get_data_periode_close($id);

        foreach($dt_periode_close as $row) {
            $this->periode->edit_table('periode','id_periode', $row['id_periode'], 'stts', 0);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
                redirect('periode');

    }

    public function tambah_periode_akuntansi()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // mendapatkan tahun
        $tahun = $this->input->post('tahun');

        // menambah tahun
        $id_periode = $this->periode->add_periode($tahun);

        $bln = [1,2,3,4,5,6,7,8,9,10,11,12];

        foreach($bln as $row) {
            $data = array(
                'periode_id' => $id_periode,
                'bln' => $row,
                'stts' => 0,
            );

            $this->periode->add_periode_tutup($data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
                redirect('periode');
    }

    public function hapus_periode_akuntansi($id_periode)
    {
        // cek jika ada transaksi tidak bisa dihapus
        $dt_by_id = $this->periode->get_data_periode_by_id($id_periode);
        $this->load->model('Admin_model','admin');
        $tgl = date('Y-m-d', strtotime('1/1/'.$dt_by_id['tahun']));
        $dt_transaksi = $this->admin->getAllByTableById2('jurnal', 'tgl', $tgl);
        if(!empty($dt_transaksi)) {
            $this->periode->delete_periode($id_periode);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                    redirect('periode');
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal. terdapat Transaksi pada periode akuntansi!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                    redirect('periode');
        }
    }




    public function buka_periode_tahunan($id)
    {
        $dt_periode_tutup = $this->periode->get_data_periode_tutup_by_id($id);
        $this->load->model('Admin_model','admin');
        $dtArray = array(
            'stts' => 0,
            'tgl_tutup' => null,
        );
        $this->admin->updateDatabyTable('periode','id_periode', $id, $dtArray);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Status tutup buku tahunan kembali dibuka!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect('periode/');
    }

    public function tutup_periode_tahunan()
    {
        // cek dahulu periode bulana sudah ditutup semua atau belum, jika belum maka diharuskan ditutup
        $id = $this->input->post('id_periode');
        $dt_detail_periode = $this->periode->get_data_periode_tutup($id);
        $dt_bln_belum_ditutup = array();
        foreach($dt_detail_periode as $row) {
            if($row['stts'] == 0){
                $dt_bln_belum_ditutup[] = $row['bln'];
            }
        }

        if(empty($dt_bln_belum_ditutup)) {

            // jika sudah bisa diproses tutup buku tahunan
            $dt_periode_tutup = $this->periode->get_data_periode_tutup_by_id($id);

            $this->load->model('Admin_model','admin');
            date_default_timezone_set('Asia/Jakarta');
            $tgl_tutup = date("Y-m-d H:i:s");

            $dtArray = array(
                'stts' => 1,
                'tgl_tutup' => $tgl_tutup,
            );
            $this->admin->updateDatabyTable('periode','id_periode', $id, $dtArray);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Tutup buku tahunan berhasil diproses!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                redirect('periode/');
        } else {

            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Terdapat periode bulanan belum ditutup!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                redirect('periode/');

        }
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Periode Akuntansi';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['profile'] = $this->db->get_where('profile', ['id_profile' => 1])->row_array();

        date_default_timezone_set('Asia/Jakarta');
        $data['tgl_tutup'] = date("Y-m-d");

        $data['dtPeriodeAkun'] = $this->periode->get_data_periode_by_id($id);
        $data['dtPeriodeAkunDetail'] = $this->periode->get_data_periode_tutup($id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('periode/detail', $data);
        $this->load->view('templates/footer');
    }
    
    public function buka_periode_bulanan($id)
    {
        $dt_periode_tutup = $this->periode->get_data_periode_tutup_by_id($id);
        $dt_periode = $this->periode->get_data_periode_by_id($dt_periode_tutup['periode_id']);
        // hapus jurnal depresiasi
        $tahun = $dt_periode['tahun'];
        $bln = $dt_periode_tutup['bln'];

        $tgl_set = date($tahun.'-'.$bln.'-15');

        $tgl_awal = date('Y-m-01', strtotime($tgl_set));
        $tgl_akhir = date('Y-m-t', strtotime($tgl_set));

        // mencari jurnal umum yang typenya 5
        $this->load->model('Admin_model','admin');
        $this->load->model('Journal_model','journal');

        $this->db->where(array('jurnal.tgl >=' => $tgl_awal, 'jurnal.tgl <=' => $tgl_akhir));
        $dt_transaksi_jurnal_umum = $this->admin->getAllByTableById2('jurnal', 'type', 5);

        foreach($dt_transaksi_jurnal_umum as $row) {
            // hapus jurnal transaksi
            $this->journal->delete_journal($row['id_jurnal']);

        }

        // ubah status periode bulanan
        $dtArray = array(
            'stts' => 0,
            'tgl_tutup' => null,
        );
        $this->admin->updateDatabyTable('periode_tutup','id_periode_tutup', $id, $dtArray);


        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Status tutup buku bulanan kembali dibuka!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect('periode/detail/'.$dt_periode_tutup['periode_id']);
    }

    public function tutup_periode_bulanan()
    {
        $dt_user = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('Asset_model','asset');
        $this->load->model('Setting_model','setting');
        $this->load->model('Journal_model','journal');

        $id = $this->input->post('id_periode');
        $dt_periode_tutup = $this->periode->get_data_periode_tutup_by_id($id);
        $dt_periode = $this->periode->get_data_periode_by_id($dt_periode_tutup['periode_id']);

        // ubah status periode
        $this->load->model('Admin_model','admin');
        date_default_timezone_set('Asia/Jakarta');
        $tgl_tutup = date("Y-m-d H:i:s");
        $tahun = date('Y', strtotime($tgl_tutup));
        $bulan = date('m', strtotime($tgl_tutup));

        $tgl_depresiasi_aset = date("Y-m-t", strtotime(date($dt_periode['tahun']."-".$dt_periode_tutup['bln']."-15")));

        // membuat jurnal depresiasi aset tetap
        // beban depresiasi aset tetap pada akumulasi depresiasi aset tetap
        $dt_aset_all = $this->asset->getAllData();
        $data['dtAll'] = $this->asset->dataAllAsetTetap($dt_aset_all, $tgl_depresiasi_aset);

        // melakukan penjurnalan depresiasi aset tetap
        $type_dep = 5;
        foreach($data['dtAll'] as $row) {
            $deskripsi = "Penyusutan aset tetap ".$row['nama'];
            $nominal = $row['peny'];
            $id_akun_debit = $row['id_biaya_peny'];
            $id_akun_kredit = $row['id_akum_peny'];


            $set_number_auto = $this->setting->getPenomoranByIdMenu($type_dep); 
            $no_urut = $this->journal->generate_no_trans($set_number_auto['reset_nomor'], $type_dep, $tgl_tutup);
            $no_trans = sprintf('%0'.$set_number_auto['panjang_nomor'].'d', (int)$no_urut).'/'.$set_number_auto['prefix'].'/'.$bulan.'/'.$tahun;
            
            // menjurnal transaksi dahulu
            $id_jurnal = $this->journal->insertJournalTransaction($no_urut, $tgl_depresiasi_aset, $deskripsi, $dt_user['id_user'], $type_dep);

            // menjurnal detail transaksi   
            $idAkun = array($id_akun_debit, $id_akun_kredit);  
            $result = array(
                array(
                    'id_akun'       => $id_akun_debit,
                    'id_perkiraan'  => 1, 
                    'nilai'         => $nominal, 
                ),
                array(
                    'id_akun'       => $id_akun_kredit,
                    'id_perkiraan'  => 2, 
                    'nilai'         => $nominal, 
                ),
            );
            $this->journal->detailInsertJournalTransaction($id_jurnal, $idAkun, $result);
            // simpan data di transaksi kas
        }

        // ubah status periode ditutup
        $dtArray = array(
            'stts' => 1,
            'tgl_tutup' => $tgl_tutup,
        );
        $this->admin->updateDatabyTable('periode_tutup','id_periode_tutup', $id, $dtArray);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Tutup buku bulanan berhasil diproses!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>');
            redirect('periode/detail/'.$dt_periode_tutup['periode_id']);
    }
    // tutup tutup buku
    
    // cek periode
    public function cek_periode()
    {
        $tgl = $this->input->post('tgl');

        $isClosed = $this->periode->check_tgl_with_periode($tgl);

        echo json_encode([
            'closed' => $isClosed
        ]);
    }


}
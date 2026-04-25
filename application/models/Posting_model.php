<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posting_model extends CI_Model
{

    public function get_periode_posting($thn)
    {
        $this->db->select('d.id_periode_tutup, p.tahun, p.tgl_tutup, d.bln, d.stts, d.posted');
        $this->db->from('periode_tutup d');
        $this->db->join('periode p', 'd.periode_id = p.id_periode');
        $this->db->where('p.tahun', $thn);
        $this->db->order_by('d.bln');
        return $this->db->get()->result_array();
    }

    public function validasiPosting($bln, $tahun)
    {
        $id_periode = $this->db->get_where('periode', ['tahun' => $tahun])->row_array()['id_periode'];   
        
        $response = [
            'status' => true,
            'messages' => [],
            'jumlah_jurnal' => 0
        ];

        // 1. cek status tutup buku
        $periode = $this->db->get_where('periode_tutup', [
            'bln' => $bln,
            'periode_id' => $id_periode
        ])->row_array();

        if ($periode && $periode['stts'] == 1) {
            $response['status'] = false;
            $response['messages'][] = 'Periode sudah tutup buku';
        }

        // 2. cek bulan sebelumnya belum posting
        if ($bln == 1) {
            $bln_lalu = 12;
            $tahun_lalu = $tahun - 1;
            $id_periode = $this->db->get_where('periode', ['tahun' => $tahun_lalu])->row_array()['id_periode'];
            $this->db->where('periode_id', $id_periode);
            $this->db->where('bln <', $bln_lalu);
            $this->db->where('posted', 0);
            $belumPosting = $this->db->get('periode_tutup')->num_rows();
        } else {
            $this->db->where('periode_id', $id_periode);
            $this->db->where('bln <', $bln);
            $this->db->where('posted', 0);
            $belumPosting = $this->db->get('periode_tutup')->num_rows();
        }
        

        if ($belumPosting > 0) {
            $response['status'] = false;
            $response['messages'][] = 'Masih ada periode sebelumnya yang belum posting';
        }

        // 3. hitung jurnal belum posting
        $this->db->where('MONTH(tgl)', $bln);
        $this->db->where('YEAR(tgl)', $tahun);
        $this->db->where('posted', 0);
        $jumlah = $this->db->count_all_results('jurnal');

        $response['jumlah_jurnal'] = $jumlah;

        return $response;
    }

    public function update_jurnal_posting($bulan, $tahun, $tgl_posting)
    {
        $tgl_posting = date("Y-m-d H:i:s");
        $id_user = $this->session->userdata('id_user');
        $this->db->set('posted', 1);
        $this->db->set('tgl_posted', $tgl_posting);
        $this->db->set('id_user_posted', $id_user);
        $this->db->where('MONTH(tgl)', $bulan);
        $this->db->where('YEAR(tgl)', $tahun);
        $this->db->update('jurnal');

        return $this->db->affected_rows();
    }

    public function update_periode_posting($id_periode_tutup)
    {
        $tgl_posting = date("Y-m-d H:i:s");
        $id_user = $this->session->userdata('id_user');
        $this->db->set('posted', 1);
        $this->db->set('tgl_posted', $tgl_posting);
        $this->db->set('id_user_posted', $id_user);
        $this->db->where('id_periode_tutup', $id_periode_tutup);
        $this->db->update('periode_tutup');

        return $this->db->affected_rows();
    }    

    public function validasiUnposting($bln, $tahun)
    {
        $id_periode = $this->db->get_where('periode', ['tahun' => $tahun])->row_array()['id_periode'];
              
        $response = [
            'status' => true,
            'messages' => [],
            'jumlah_jurnal' => 0
        ];

        // 1. cek status tutup buku
        $periode = $this->db->get_where('periode_tutup', [
            'bln' => $bln,
            'periode_id' => $id_periode
        ])->row_array();

        if ($periode && $periode['stts'] == 1) {
            $response['status'] = false;
            $response['messages'][] = 'Periode sudah tutup buku';
        }

        // 3. hitung jurnal sudah posting
        $this->db->where('MONTH(tgl)', $bln);
        $this->db->where('YEAR(tgl)', $tahun);
        $this->db->where('posted', 1);
        $jumlah = $this->db->count_all_results('jurnal');

        $response['jumlah_jurnal'] = $jumlah;

        return $response;
    }

    public function update_jurnal_unposting($bulan, $tahun)
    {
        $this->db->set('posted', 0);
        $this->db->set('tgl_posted', null);
        $this->db->set('id_user_posted', null);
        $this->db->where('MONTH(tgl)', $bulan);
        $this->db->where('YEAR(tgl)', $tahun);
        $this->db->update('jurnal');
        return $this->db->affected_rows();
    }    
    
    public function update_periode_unposting($id_periode_tutup)
    {
        $this->db->set('posted', 0);
        $this->db->set('tgl_posted', null);
        $this->db->set('id_user_posted', null);
        $this->db->where('id_periode_tutup', $id_periode_tutup);
        $this->db->update('periode_tutup');
        return $this->db->affected_rows();
    }    
}
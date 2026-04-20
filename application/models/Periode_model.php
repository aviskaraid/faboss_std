<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode_model extends CI_Model
{

    public function get_all()
    {
        $this->db->select('*');
        $this->db->from('periode a');
        $this->db->join('periode_tutup b', 'a.id_periode = b.periode_id');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
    }

    public function check_tgl_with_periode($tgl)
    {
        // ambil periode yang SUDAH DITUTUP (misal stts = 1)
        $this->db->where('b.stts', 1);
        $dt_periode = $this->get_all();

        foreach ($dt_periode as $row) {

            $tgl_awal  = $row['tahun'].'-'.$row['bln'].'-01';
            $tgl_akhir = date('Y-m-t', strtotime($tgl_awal));

            // jika tanggal transaksi ADA DI DALAM periode tertutup
            if ($tgl >= $tgl_awal && $tgl <= $tgl_akhir) {
                return true; // DITOLAK
            }
        }

        return false; // BOLEH DISIMPAN
    }

    
    
    
    public function get_data_periode()
    {
        $this->db->select('*');
        $this->db->from('periode');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_data_periode_by_id($id)
    {
        return $this->db->get_where('periode', ['id_periode' => $id])->row_array();
    }

    public function get_data_periode_tutup_by_id($id)
    {
        return $this->db->get_where('periode_tutup', ['id_periode_tutup' => $id])->row_array();
    }

    public function get_data_periode_close($id)
    {
        $this->db->select('*');
        $this->db->from('periode');
        $this->db->where('id_periode !=',$id);
        return $this->db->get()->result_array();
    }
    
    public function edit_table($table, $id_primary, $value_primary, $column, $value_column)
    {
        $data = [
            $column => $value_column
        ];

        $this->db->where($id_primary, $value_primary);
        $this->db->update($table, $data);
    }

    public function get_data_periode_tutup($id)
    {
        $this->db->select('*');
        $this->db->from('periode_tutup');
        $this->db->where_in('periode_id', $id);
        $this->db->order_by('bln', 'DESC');
        return $this->db->get()->result_array();
    }

    public function add_periode($tahun) 
    {
        $data = array(
            'tahun' => $tahun,
            'stts' => 0,
            'is_active' => 0,
        );

        $this->db->insert('periode', $data);

        return $this->db->insert_id();
    }

    public function add_periode_tutup($data) 
    {
        $this->db->insert('periode_tutup', $data);
    }

    public function delete_periode($id)
    {
        return $this->db->delete('periode', ['id_periode' => $id]);
    }
    
    // id_header1
    
    public function get_data_header_account_by_id($id_cab)
    {
        return $this->db->get_where('setting_account', ['id_cab' => $id_cab])->row_array();
    }
}
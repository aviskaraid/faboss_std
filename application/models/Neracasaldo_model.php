<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neracasaldo_model extends CI_Model {

    /* ===================== NERACA UTAMA ===================== */
    public function getNeracaSaldo($bulan, $tahun)
    {
        $date = new DateTime("$tahun-$bulan-01");

        $tgl_awal  = $date->format('Y-m-01');
        $tgl_akhir = $date->format('Y-m-t');

        // ================= AMBIL AKUN SYSTEM =================
        $akun_laba_ditahan  = $this->getSystemAkun('LABA_DITAHAN');
        $akun_laba_berjalan = $this->getSystemAkun('LABA_BERJALAN');

        $id_exclude_1 = $akun_laba_ditahan['id_akun'];
        $id_exclude_2 = $akun_laba_berjalan['id_akun'];

        // ================= QUERY SALDO NERACA =================
        $sql = "
            SELECT 
                x.*,
                (x.sa_debit + x.m_awal_debit) AS saldo_awal_debit,
                (x.sa_credit + x.m_awal_credit) AS saldo_awal_credit,
                (x.sa_debit + x.m_awal_debit + x.mutasi_debit) AS saldo_akhir_debit,
                (x.sa_credit + x.m_awal_credit + x.mutasi_credit) AS saldo_akhir_credit
            FROM (
                SELECT 
                    a.id_perkiraan,
                    a.id_kelompok_akun,
                    k.tipe,
                    a.id_akun,
                    a.noakun,
                    a.nama,
                    a.saldo_awal,

                    CASE WHEN a.id_perkiraan = '1' THEN a.saldo_awal ELSE 0 END AS sa_debit,
                    CASE WHEN a.id_perkiraan = '2' THEN a.saldo_awal ELSE 0 END AS sa_credit,

                    SUM(CASE 
                        WHEN j.tgl < ? AND jd.id_perkiraan = 1 THEN jd.nilai 
                        ELSE 0 
                    END) AS m_awal_debit,

                    SUM(CASE 
                        WHEN j.tgl < ? AND jd.id_perkiraan = 2 THEN jd.nilai 
                        ELSE 0 
                    END) AS m_awal_credit,

                    SUM(CASE 
                        WHEN j.tgl BETWEEN ? AND ? AND jd.id_perkiraan = 1 THEN jd.nilai 
                        ELSE 0 
                    END) AS mutasi_debit,

                    SUM(CASE 
                        WHEN j.tgl BETWEEN ? AND ? AND jd.id_perkiraan = 2 THEN jd.nilai 
                        ELSE 0 
                    END) AS mutasi_credit

                FROM akun a
                LEFT JOIN jurnal_detail jd ON jd.id_akun = a.id_akun
                LEFT JOIN jurnal j ON j.id_jurnal = jd.id_jurnal
                LEFT JOIN kelompok_akun k ON a.id_kelompok_akun = k.id_kelompok_akun
                WHERE a.id_akun NOT IN (?, ?)
                AND j.posted = 1
                GROUP BY 
                    a.id_akun, a.noakun, a.nama, a.saldo_awal, 
                    a.id_perkiraan, a.id_kelompok_akun, k.tipe
            ) x
            ORDER BY x.noakun ASC
        ";

        $result = $this->db->query($sql, [
            $tgl_awal, // m_awal_debit
            $tgl_awal, // m_awal_credit
            $tgl_awal, // mutasi_debit start
            $tgl_akhir,// mutasi_debit end
            $tgl_awal, // mutasi_credit start
            $tgl_akhir,// mutasi_credit end
            $id_exclude_1,
            $id_exclude_2
        ])->result_array();
        return $result;

    }

    private function getSystemAkun($kode)
    {
        return $this->db->select('a.id_akun, a.noakun, a.nama, a.id_kelompok_akun')
            ->from('set_account_system s')
            ->join('akun a','a.id_akun=s.id_akun')
            ->where('s.kode_setting',$kode)
            ->get()->row_array();
    }

}

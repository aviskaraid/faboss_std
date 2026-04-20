<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neraca_model extends CI_Model {

    /* ===================== NERACA UTAMA ===================== */
    public function getNeracaPeriode($bulan, $tahun)
    {
        $akhir_bulan = date("Y-m-t", strtotime("$tahun-$bulan-01"));

        // ================= AMBIL AKUN SYSTEM =================
        $akun_laba_ditahan  = $this->getSystemAkun('LABA_DITAHAN');
        $akun_laba_berjalan = $this->getSystemAkun('LABA_BERJALAN');

        $id_exclude_1 = $akun_laba_ditahan['id_akun'];
        $id_exclude_2 = $akun_laba_berjalan['id_akun'];

        // ================= QUERY SALDO NERACA =================
        $sql = "
            SELECT 
                x.*,
                (x.saldo_awal + x.mutasi) AS saldo
            FROM (
                SELECT 
                    a.id_akun,
                    a.noakun,
                    a.nama,
                    a.saldo_awal,
                    a.id_perkiraan,
                    a.id_kelompok_akun,
                    k.tipe,

                    SUM(
                        CASE
                            WHEN j.tgl <= ? THEN
                                CASE
                                    WHEN k.tipe = 'A' AND jd.id_perkiraan = 1 THEN jd.nilai
                                    WHEN k.tipe = 'A' AND jd.id_perkiraan = 2 THEN jd.nilai * -1
                                    WHEN k.tipe = 'P' AND jd.id_perkiraan = 1 THEN jd.nilai * -1
                                    WHEN k.tipe = 'P' AND jd.id_perkiraan = 2 THEN jd.nilai
                                    ELSE 0
                                END
                            ELSE 0
                        END
                    ) AS mutasi

                FROM akun a
                LEFT JOIN jurnal_detail jd ON jd.id_akun = a.id_akun
                LEFT JOIN jurnal j ON j.id_jurnal = jd.id_jurnal
                LEFT JOIN kelompok_akun k ON a.id_kelompok_akun = k.id_kelompok_akun
                WHERE k.tipe IN ('A','P')
                AND a.id_akun NOT IN (?, ?)
                GROUP BY 
                    a.id_akun, a.noakun, a.nama, a.saldo_awal, 
                    a.id_perkiraan, a.id_kelompok_akun, k.tipe
            ) x
            ORDER BY x.noakun ASC
        ";

        $akun = $this->db->query($sql, [$akhir_bulan, $id_exclude_1, $id_exclude_2])->result_array();

        // ================= KELOMPOKKAN PER KELOMPOK AKUN =================
        $kelompok_data = [];

        foreach ($akun as $a) {
            $id_kel = $a['id_kelompok_akun'];

            if (!isset($kelompok_data[$id_kel])) {
                $kelompok_data[$id_kel] = [
                    'akun' => [],
                    'subtotal' => 0,
                    'tipe' => $a['tipe']
                ];
            }

            $kelompok_data[$id_kel]['akun'][] = $a;
            $kelompok_data[$id_kel]['subtotal'] += $a['saldo'];
        }

        // ================= AMBIL DAFTAR KELOMPOK =================
        $kelompok = $this->db->where_in('tipe', ['A','P'])
            ->order_by('tipe','ASC')
            ->order_by('id_kelompok_akun','ASC')
            ->get('kelompok_akun')
            ->result_array();

        $result = [];

        foreach ($kelompok as $k) {
            $id_kel = $k['id_kelompok_akun'];

            $result[] = [
                'id_kelompok_akun' => $id_kel,
                'nama_kel_akun'    => $k['nama_kel_akun'],
                'tipe'             => $k['tipe'],
                'akun'             => $kelompok_data[$id_kel]['akun'] ?? [],
                'subtotal'         => $kelompok_data[$id_kel]['subtotal'] ?? 0
            ];
        }

        // ================= HITUNG LABA =================
        $laba_ditahan  = $this->getLabaDitahan($tahun);
        $laba_berjalan = $this->getLabaBerjalan($bulan, $tahun);

        // ================= MASUKKAN KE KELOMPOK EKUITAS =================
        foreach ($result as &$kel) {
            if ($kel['id_kelompok_akun'] == $akun_laba_ditahan['id_kelompok_akun']) {

                $kel['akun'][] = [
                    'noakun' => $akun_laba_ditahan['noakun'],
                    'nama'   => $akun_laba_ditahan['nama'],
                    'saldo'  => $laba_ditahan
                ];

                $kel['akun'][] = [
                    'noakun' => $akun_laba_berjalan['noakun'],
                    'nama'   => $akun_laba_berjalan['nama'],
                    'saldo'  => $laba_berjalan
                ];

                $kel['subtotal'] += ($laba_ditahan + $laba_berjalan);
            }
        }

        return $result;
    }


    /* ===================== LABA RUGI BERJALAN ===================== */
    private function getLabaBerjalan($bulan, $tahun)
    {
        $this->db->select("
            COALESCE(SUM(
                CASE 
                    WHEN k.tipe = 'L' THEN d.nilai
                    WHEN k.tipe = 'B' THEN -d.nilai
                    ELSE 0
                END
            ),0) as laba_berjalan
        ", false);

        $this->db->from('jurnal_detail d');
        $this->db->join('jurnal j', 'd.id_jurnal = j.id_jurnal', 'left');
        $this->db->join('akun a', 'd.id_akun = a.id_akun', 'left');
        $this->db->join('kelompok_akun k', 'a.id_kelompok_akun = k.id_kelompok_akun', 'left');

        // Tahun yang dipilih
        $this->db->where('YEAR(j.tgl)', $tahun);

        // Bulan <= bulan yang dipilih (KUMULATIF)
        $this->db->where('MONTH(j.tgl) <=', $bulan);

        // Hanya akun Laba & Beban
        $this->db->where_in('k.tipe', ['L','B']);

        $row = $this->db->get()->row_array();

        return (float) $row['laba_berjalan'];
    }


    /* ===================== LABA DITAHAN ===================== */
    private function getLabaDitahan($tahun)
    {
        $this->db->select("
            SUM(CASE WHEN k.tipe = 'L' THEN d.nilai ELSE 0 END) -
            SUM(CASE WHEN k.tipe = 'B' THEN d.nilai ELSE 0 END) as laba_ditahan
        ", false);

        $this->db->from('jurnal_detail d');
        $this->db->join('jurnal j', 'd.id_jurnal=j.id_jurnal','left');
        $this->db->join('akun a', 'd.id_akun=a.id_akun','left');
        $this->db->join('kelompok_akun k', 'a.id_kelompok_akun=k.id_kelompok_akun','left');

        $this->db->where('YEAR(j.tgl) <', $tahun);
        $this->db->where_in('k.tipe', ['L','B']);

        return (float) $this->db->get()->row()->laba_ditahan;
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

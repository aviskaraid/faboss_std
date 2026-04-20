<?php
class Labarugi_model extends CI_Model
{
    public function getLabaRugi($bulan, $tahun, $mode)
    {
        if ($mode == 'sd_bulan') {
            $periode_where = "YEAR(j.tgl) = $tahun AND MONTH(j.tgl) <= $bulan";
        } else {
            $periode_where = "YEAR(j.tgl) = $tahun AND MONTH(j.tgl) = $bulan";
        }

        $query = $this->db->query("
            SELECT 
                k.id_kelompok_akun,
                k.nama_kel_akun,
                k.tipe,
                a.id_akun,
                a.noakun,
                a.nama,

                COALESCE(SUM(
                    CASE 
                        WHEN $periode_where THEN
                            CASE
                                WHEN k.tipe = 'L' THEN d.nilai          -- Pendapatan +
                                WHEN k.tipe = 'B' THEN d.nilai * -1     -- Beban -
                                ELSE 0
                            END
                        ELSE 0
                    END
                ),0) AS saldo

            FROM akun a
            JOIN kelompok_akun k 
                ON a.id_kelompok_akun = k.id_kelompok_akun
            LEFT JOIN jurnal_detail d 
                ON a.id_akun = d.id_akun
            LEFT JOIN jurnal j 
                ON d.id_jurnal = j.id_jurnal
            WHERE k.tipe IN ('L','B')
            GROUP BY 
                k.id_kelompok_akun,
                k.nama_kel_akun,
                k.tipe,
                a.id_akun,
                a.noakun,
                a.nama
            ORDER BY k.id_kelompok_akun, a.noakun
        ")->result_array();

        // Strukturkan sesuai kebutuhan view
        $hasil = [];
        foreach ($query as $row) {
            $id_kel = $row['id_kelompok_akun'];

            if (!isset($hasil[$id_kel])) {
                $hasil[$id_kel] = [
                    'nama_kel_akun' => $row['nama_kel_akun'],
                    'tipe' => $row['tipe'], // 🔥 INI YANG SEBELUMNYA TIDAK ADA
                    'akun' => [],
                    'subtotal' => 0
                ];
            }

            $hasil[$id_kel]['akun'][] = [
                'noakun' => $row['noakun'],
                'nama'   => $row['nama'],
                'saldo'  => (float)$row['saldo']
            ];

            $hasil[$id_kel]['subtotal'] += (float)$row['saldo'];
        }

        return $hasil;
    }

    public function getSetAkun()
    {
		return $this->db->get_where('set_account', ['id_sa' => 1])->row_array();
    }

    public function getLabaRugiBaru($bulan, $tahun, $mode_periode)
    {
        if ($mode_periode == 'sd_bulan') {
            $periode = "YEAR(j.tgl) = $tahun AND MONTH(j.tgl) <= $bulan";
        } else {
            $periode = "YEAR(j.tgl) = $tahun AND MONTH(j.tgl) = $bulan";
        }

        $sql = "
            SELECT 
                k.id_kelompok_akun,
                k.nama_kel_akun,
                k.tipe,
                a.noakun,
                a.nama,
                SUM(
                    CASE 
                        WHEN $periode THEN
                            CASE
                                WHEN k.tipe = 'L' THEN d.nilai
                                WHEN k.tipe = 'B' THEN d.nilai * -1
                                ELSE 0
                            END
                        ELSE 0
                    END
                ) AS saldo
            FROM akun a
            LEFT JOIN kelompok_akun k ON a.id_kelompok_akun = k.id_kelompok_akun
            LEFT JOIN jurnal_detail d ON a.id_akun = d.id_akun
            LEFT JOIN jurnal j ON d.id_jurnal = j.id_jurnal
            WHERE k.tipe IN ('L','B')
            GROUP BY k.id_kelompok_akun, a.id_akun
            ORDER BY k.id_kelompok_akun, a.noakun
        ";

        $result = $this->db->query($sql)->result_array();

        $data = [];
        foreach ($result as $r) {
            $id = $r['id_kelompok_akun'];

            if (!isset($data[$id])) {
                $data[$id] = [
                    'nama_kel_akun' => $r['nama_kel_akun'],
                    'tipe' => $r['tipe'],
                    'akun' => [],
                    'subtotal' => 0
                ];
            }

            $data[$id]['akun'][] = $r;
            $data[$id]['subtotal'] += $r['saldo'];
        }

        return $data;
    }

}

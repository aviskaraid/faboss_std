<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get monthly cash/bank transaction summary
     *
     * @param int $tahun
     * @return array
     */
    public function get_kas_bank_bulanan($tahun)
    {
        $sql = "
            SELECT 
                MONTH(c.tgl) AS bulan,
                SUM(CASE WHEN b.id_perkiraan = 1 THEN b.nilai ELSE 0 END) AS terima,
                SUM(CASE WHEN b.id_perkiraan = 2 THEN b.nilai ELSE 0 END) AS bayar
            FROM jurnal_detail b
            INNER JOIN set_account_kas a
                ON a.id_akun = b.id_akun
            INNER JOIN jurnal c
                ON b.id_jurnal = c.id_jurnal
            WHERE YEAR(c.tgl) = ?
            GROUP BY MONTH(c.tgl)
            ORDER BY MONTH(c.tgl)
        ";

        return $this->db->query($sql, [$tahun])->result();
    }
}

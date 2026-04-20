-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table akunting.akun
CREATE TABLE IF NOT EXISTS `akun` (
  `id_akun` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `noakun` varchar(20) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `id_kelompok_akun` int(10) unsigned NOT NULL,
  `id_perkiraan` int(11) NOT NULL,
  `saldo_awal` bigint(20) NOT NULL,
  `keterangan` text NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_akun`),
  KEY `kelompok_akun_id` (`id_kelompok_akun`),
  KEY `id_perkiraan` (`id_perkiraan`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.akun: ~29 rows (approximately)
INSERT INTO `akun` (`id_akun`, `noakun`, `nama`, `id_kelompok_akun`, `id_perkiraan`, `saldo_awal`, `keterangan`, `id_user`) VALUES
	(1, '110001', 'Kas Tunai', 1, 1, 5000000, '', 30),
	(2, '111002', 'Bank Mandiri', 1, 1, 0, '', 28),
	(3, '121001', 'Peralatan', 2, 1, 0, '', 28),
	(4, '111003', 'Bank BRI', 1, 1, 0, '', 28),
	(5, '112001', 'Piutang ', 1, 1, 0, '', 28),
	(6, '113001', 'Piutang Karyawan', 1, 1, 500000, '', 28),
	(7, '114001', 'Persediaan', 1, 1, 5000000, '', 28),
	(8, '121002', 'Akum. Peny. Peralatan', 2, 2, 0, '', 28),
	(9, '211001', 'Hutang Usaha', 3, 2, 0, '', 28),
	(10, '221001', 'Hutang Bank', 4, 2, 0, '', 28),
	(11, '311001', 'Modal Usaha', 5, 2, 10500000, '', 30),
	(12, '311002', 'Prive', 5, 1, 0, '', 28),
	(13, '311003', 'Laba/Rugi Ditahan', 5, 2, 0, '', 28),
	(14, '311004', 'Laba/Rugi Berjalan', 5, 2, 0, '', 30),
	(15, '411001', 'Pendapatan Usaha', 6, 2, 0, '', 28),
	(16, '421001', 'Pendapatan Bank', 9, 2, 0, '', 30),
	(17, '511001', 'Beban  Gaji Karyawan', 8, 1, 0, '', 30),
	(18, '511002', 'Beban Komisi', 8, 1, 0, '', 28),
	(19, '511003', 'Premi Asuransi', 8, 1, 0, '', 28),
	(20, '511004', 'PDAM & Listrik', 8, 1, 0, '', 30),
	(21, '511005', 'Beban Konsumsi ', 8, 1, 0, '', 28),
	(22, '511006', 'Beban Internet & Pulsa', 8, 1, 0, '', 30),
	(23, '511007', 'Beban Penyusutan', 8, 1, 0, '', 28),
	(24, '521001', 'Beban Admin Bank', 9, 1, 0, '', 30),
	(25, '511008', 'Beban Pajak', 8, 1, 0, '', 28),
	(26, '111004', 'Bank Jatim', 1, 1, 0, '', 28),
	(27, '111005', 'Bank BSI', 1, 1, 0, '', 28),
	(31, '510001', 'Harga Pokok Penjualan', 7, 1, 0, '', 30),
	(32, '810001', 'Beban Pajak PPh Badan Tahunan', 10, 1, 0, '', 30),
	(33, '421002', 'Pendapatan Lain-lain', 9, 2, 0, '', 30);

-- Dumping structure for table akunting.akun_sumber
CREATE TABLE IF NOT EXISTS `akun_sumber` (
  `id_akun_sumber` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  PRIMARY KEY (`id_akun_sumber`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_akun` (`id_akun`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.akun_sumber: ~27 rows (approximately)
INSERT INTO `akun_sumber` (`id_akun_sumber`, `id_kategori`, `id_akun`) VALUES
	(10, 26, 5),
	(11, 26, 6),
	(12, 26, 7),
	(13, 26, 9),
	(14, 26, 17),
	(15, 26, 18),
	(16, 26, 19),
	(17, 26, 20),
	(18, 26, 21),
	(19, 26, 22),
	(20, 26, 25),
	(21, 26, 24),
	(22, 27, 3),
	(23, 28, 3),
	(24, 29, 10),
	(25, 29, 11),
	(26, 30, 10),
	(27, 30, 12),
	(28, 25, 5),
	(29, 25, 6),
	(30, 25, 7),
	(31, 25, 9),
	(32, 25, 11),
	(33, 25, 13),
	(34, 25, 14),
	(35, 25, 15),
	(36, 25, 16);

-- Dumping structure for table akunting.aset
CREATE TABLE IF NOT EXISTS `aset` (
  `id_aset` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(288) NOT NULL,
  `kode` varchar(288) NOT NULL,
  `lokasi` varchar(288) DEFAULT NULL,
  `tgl` date NOT NULL,
  `nilai` bigint(20) NOT NULL,
  `umur` int(11) NOT NULL,
  `id_biaya_peny` int(11) NOT NULL,
  `id_akum_peny` int(11) NOT NULL,
  `gambar` text DEFAULT NULL,
  PRIMARY KEY (`id_aset`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.aset: ~5 rows (approximately)
INSERT INTO `aset` (`id_aset`, `nama`, `kode`, `lokasi`, `tgl`, `nilai`, `umur`, `id_biaya_peny`, `id_akum_peny`, `gambar`) VALUES
	(5, 'Server Lenovo', 'S01', 'Ruang Server', '2026-01-02', 25000000, 5, 23, 8, '2ef34c78c9ad9dfa170c216c06095b6c.png'),
	(6, 'Printer Canon', 'P01', 'Sekretariat', '2026-01-01', 2000000, 4, 23, 8, '97e7ee864f250d22023186ed82a1fd76.jpg'),
	(8, 'Laptop Lenovo', 'L02', 'Kantor', '2026-01-30', 3500000, 5, 23, 8, 'ca39cdcc47568d6351ae398f6286a38c.jpg');

-- Dumping structure for table akunting.aset_nonaktif
CREATE TABLE IF NOT EXISTS `aset_nonaktif` (
  `id_nonaktif` int(11) NOT NULL AUTO_INCREMENT,
  `id_aset` int(11) NOT NULL,
  `nama` varchar(288) NOT NULL,
  `kode` varchar(288) NOT NULL,
  `lokasi` varchar(288) DEFAULT NULL,
  `tgl` date NOT NULL,
  `nilai` bigint(20) NOT NULL,
  `umur` int(11) NOT NULL,
  `id_biaya_peny` int(11) NOT NULL,
  `id_akum_peny` int(11) NOT NULL,
  `gambar` text DEFAULT NULL,
  `tgl_nonaktif` date DEFAULT NULL,
  PRIMARY KEY (`id_nonaktif`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table akunting.aset_nonaktif: ~0 rows (approximately)
INSERT INTO `aset_nonaktif` (`id_nonaktif`, `id_aset`, `nama`, `kode`, `lokasi`, `tgl`, `nilai`, `umur`, `id_biaya_peny`, `id_akum_peny`, `gambar`, `tgl_nonaktif`) VALUES
	(1, 2, 'Laptop Acer', 'L01', 'Kantor', '2025-11-26', 3000000, 4, 23, 8, 'a836bc8d0691fa296e5a201053f0195f.jpg', '2026-02-03');

-- Dumping structure for table akunting.biaya
CREATE TABLE IF NOT EXISTS `biaya` (
  `id_biaya` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(288) NOT NULL,
  `nm` varchar(288) NOT NULL,
  `akun_id` int(11) NOT NULL,
  PRIMARY KEY (`id_biaya`),
  KEY `akun_id` (`akun_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.biaya: ~1 rows (approximately)
INSERT INTO `biaya` (`id_biaya`, `kode`, `nm`, `akun_id`) VALUES
	(3, 'BYA_001', 'Beban Konsumsi ', 21),
	(5, 'BYA_002', 'Biaya Gaji', 17);

-- Dumping structure for table akunting.import
CREATE TABLE IF NOT EXISTS `import` (
  `id_import` int(11) NOT NULL AUTO_INCREMENT,
  `ket` text NOT NULL,
  `date_created` text NOT NULL,
  PRIMARY KEY (`id_import`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.import: ~0 rows (approximately)

-- Dumping structure for table akunting.import_detail
CREATE TABLE IF NOT EXISTS `import_detail` (
  `id_importdetail` int(11) NOT NULL AUTO_INCREMENT,
  `id_import` int(11) NOT NULL,
  `id_jurnal` int(11) NOT NULL,
  PRIMARY KEY (`id_importdetail`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.import_detail: ~0 rows (approximately)

-- Dumping structure for table akunting.jurnal
CREATE TABLE IF NOT EXISTS `jurnal` (
  `id_jurnal` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_trans` varchar(20) NOT NULL,
  `type` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `keterangan` text NOT NULL,
  `bukti` text DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_jurnal`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.jurnal: ~22 rows (approximately)
INSERT INTO `jurnal` (`id_jurnal`, `no_trans`, `type`, `tgl`, `keterangan`, `bukti`, `id_user`) VALUES
	(117, '1', 3, '2026-01-19', 'Penjualan A', '', 30),
	(118, '1', 4, '2026-01-19', 'Pinjaman A', '', 30),
	(121, '1', 2, '2026-01-19', 'tess--', '', 30),
	(122, '1', 5, '2025-11-30', 'Penyusutan aset tetap Laptop Acer', '', 30),
	(123, '1', 5, '2025-12-31', 'Penyusutan aset tetap Laptop Acer', '', 30),
	(129, '2', 2, '2026-01-19', 'testp1-', '', 30),
	(130, '3', 2, '2026-01-21', 'testp2', '', 30),
	(133, '4', 2, '2025-12-22', 'test back date', '', 30),
	(135, '2', 4, '2026-01-23', 'test', '', 30),
	(139, '3', 4, '2026-01-22', 'test back date', '', 30),
	(142, '4', 2, '2025-11-01', 'test', '', 30),
	(146, '4', 2, '2026-01-27', 'test biaya dan upload', '', 30),
	(147, '5', 2, '2026-01-27', 'test pendapatan', '', 30),
	(148, '6', 2, '2026-01-27', 'test pembayaran piutang', '', 30),
	(149, '7', 2, '2026-01-27', 'test pembayaran utang', '', 30),
	(152, '4', 1, '2026-01-28', 'tes upload bukti', '1769566686867691740.jpg', 30),
	(154, '8', 2, '2026-01-28', 'test upload', '', 30),
	(155, '9', 2, '2026-01-28', 'test upload', '', 30),
	(156, '5', 1, '2026-01-29', 'test jurnal upload', NULL, 30),
	(159, '6', 1, '2026-01-30', 'Jurnal HPP', '', 30),
	(161, '11', 2, '2026-02-10', 'test biaya konsumsi', '', 30);

-- Dumping structure for table akunting.jurnal_detail
CREATE TABLE IF NOT EXISTS `jurnal_detail` (
  `id_jurnal_detail` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_jurnal` int(10) unsigned NOT NULL,
  `id_akun` int(10) unsigned NOT NULL,
  `tipe_kas` int(11) NOT NULL,
  `id_perkiraan` int(11) NOT NULL,
  `nilai` bigint(20) NOT NULL,
  PRIMARY KEY (`id_jurnal_detail`),
  KEY `jurnal_id` (`id_jurnal`),
  KEY `akun_id` (`id_akun`),
  KEY `debit_kredit` (`id_perkiraan`),
  CONSTRAINT `jurnal_detail_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=480 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.jurnal_detail: ~44 rows (approximately)
INSERT INTO `jurnal_detail` (`id_jurnal_detail`, `id_jurnal`, `id_akun`, `tipe_kas`, `id_perkiraan`, `nilai`) VALUES
	(326, 117, 5, 0, 1, 2000000),
	(327, 117, 15, 0, 2, 2000000),
	(328, 118, 1, 1, 1, 1000000),
	(329, 118, 9, 1, 2, 1000000),
	(336, 122, 23, 0, 1, 62500),
	(337, 122, 8, 0, 2, 62500),
	(338, 123, 23, 0, 1, 62500),
	(339, 123, 8, 0, 2, 62500),
	(370, 129, 4, 1, 1, 10000),
	(371, 129, 16, 1, 2, 10000),
	(376, 133, 27, 1, 1, 100000),
	(377, 133, 33, 1, 2, 100000),
	(380, 135, 9, 1, 1, 200000),
	(381, 135, 4, 1, 2, 200000),
	(388, 139, 9, 1, 1, 300000),
	(389, 139, 27, 1, 2, 300000),
	(394, 142, 21, 1, 1, 15000),
	(395, 142, 1, 1, 2, 15000),
	(406, 130, 2, 1, 1, 15000),
	(407, 130, 16, 1, 2, 15000),
	(408, 121, 21, 1, 1, 11000),
	(409, 121, 1, 1, 2, 11000),
	(412, 147, 1, 1, 1, 15000),
	(413, 147, 33, 1, 2, 15000),
	(414, 148, 27, 1, 1, 500000),
	(415, 148, 5, 1, 2, 500000),
	(416, 149, 9, 1, 1, 100000),
	(417, 149, 27, 1, 2, 100000),
	(426, 152, 3, 1, 1, 50000),
	(427, 152, 1, 1, 2, 50000),
	(436, 146, 21, 1, 1, 10000),
	(437, 146, 1, 1, 2, 10000),
	(440, 155, 1, 1, 1, 100000),
	(441, 155, 33, 1, 2, 100000),
	(448, 154, 21, 1, 1, 11000),
	(449, 154, 1, 1, 2, 11000),
	(450, 156, 6, 1, 1, 20000),
	(451, 156, 1, 1, 2, 20000),
	(474, 159, 31, 0, 1, 1000000),
	(475, 159, 7, 0, 2, 1000000),
	(478, 161, 21, 1, 1, 50000),
	(479, 161, 26, 1, 2, 50000);

-- Dumping structure for table akunting.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` text NOT NULL,
  `id_kelompok_aktivitas` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_kategori`),
  KEY `id_kelompok_aktivitas` (`id_kelompok_aktivitas`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.kategori: ~6 rows (approximately)
INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `id_kelompok_aktivitas`, `keterangan`, `date`, `id_user`) VALUES
	(25, 'Kenaikan Kas dari aktivitas operasi', 1, '1', '2023-07-13', 28),
	(26, 'Penurunan kas dari aktivitas operasi', 1, '0', '2023-07-13', 28),
	(27, 'Kenaikan kas dari aktivitas Investasi', 2, '1', '2023-07-13', 28),
	(28, 'Penurunan kas dari aktivitas Investasi', 2, '0', '2023-07-13', 28),
	(29, 'Kenaikan kas dari aktivitas Pendanaan', 3, '1', '2023-07-13', 28),
	(30, 'Penurunan kas dari aktivitas Pendanaan', 3, '0', '2023-07-13', 28);

-- Dumping structure for table akunting.kelompok_aktivitas
CREATE TABLE IF NOT EXISTS `kelompok_aktivitas` (
  `id_kelompok_aktivitas` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(128) NOT NULL,
  PRIMARY KEY (`id_kelompok_aktivitas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.kelompok_aktivitas: ~3 rows (approximately)
INSERT INTO `kelompok_aktivitas` (`id_kelompok_aktivitas`, `nama`) VALUES
	(1, 'Aktivitas Operasi'),
	(2, 'Aktivitas Investasi'),
	(3, 'Aktivitas Pendanaan');

-- Dumping structure for table akunting.kelompok_akun
CREATE TABLE IF NOT EXISTS `kelompok_akun` (
  `id_kelompok_akun` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipe` varchar(50) NOT NULL,
  `nama_kel_akun` varchar(128) NOT NULL,
  `kel_akun` int(11) NOT NULL,
  PRIMARY KEY (`id_kelompok_akun`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.kelompok_akun: ~10 rows (approximately)
INSERT INTO `kelompok_akun` (`id_kelompok_akun`, `tipe`, `nama_kel_akun`, `kel_akun`) VALUES
	(1, 'A', 'Aktiva Lancar', 110),
	(2, 'A', 'Aktiva Tetap', 120),
	(3, 'P', 'Liabilitas Jangka Pendek', 210),
	(4, 'P', 'Liabilitas Jangka Panjang', 220),
	(5, 'P', 'Ekuitas', 310),
	(6, 'L', 'Penjualan', 410),
	(7, 'B', 'Harga Pokok Penjualan', 510),
	(8, 'B', 'Beban Operasional', 610),
	(9, 'L', 'Pendapatan Dan Beban Lainnya', 710),
	(10, 'B', 'Pajak', 810);

-- Dumping structure for table akunting.pendapatan
CREATE TABLE IF NOT EXISTS `pendapatan` (
  `id_pendapatan` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(288) NOT NULL,
  `nm` varchar(288) NOT NULL,
  `akun_id` int(11) NOT NULL,
  PRIMARY KEY (`id_pendapatan`),
  KEY `akun_id` (`akun_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.pendapatan: ~2 rows (approximately)
INSERT INTO `pendapatan` (`id_pendapatan`, `kode`, `nm`, `akun_id`) VALUES
	(1, 'PEN_001', 'Pendapatan Bunga', 16),
	(2, 'PEN_002', 'Pendapatan Lain-lain', 33);

-- Dumping structure for table akunting.periode
CREATE TABLE IF NOT EXISTS `periode` (
  `id_periode` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` varchar(50) NOT NULL,
  `is_active` int(11) NOT NULL,
  `stts` int(11) NOT NULL,
  `tgl_tutup` datetime DEFAULT NULL,
  PRIMARY KEY (`id_periode`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.periode: ~2 rows (approximately)
INSERT INTO `periode` (`id_periode`, `tahun`, `is_active`, `stts`, `tgl_tutup`) VALUES
	(2, '2025', 0, 0, NULL),
	(4, '2026', 0, 1, NULL);

-- Dumping structure for table akunting.periode_tutup
CREATE TABLE IF NOT EXISTS `periode_tutup` (
  `id_periode_tutup` int(11) NOT NULL AUTO_INCREMENT,
  `periode_id` int(11) NOT NULL,
  `bln` int(11) NOT NULL,
  `stts` int(11) NOT NULL,
  `tgl_tutup` datetime DEFAULT NULL,
  PRIMARY KEY (`id_periode_tutup`),
  KEY `periode_id` (`periode_id`),
  CONSTRAINT `periode_tutup_ibfk_1` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id_periode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.periode_tutup: ~24 rows (approximately)
INSERT INTO `periode_tutup` (`id_periode_tutup`, `periode_id`, `bln`, `stts`, `tgl_tutup`) VALUES
	(1, 2, 1, 1, '2026-01-19 12:32:40'),
	(2, 2, 2, 1, '2026-01-19 12:32:44'),
	(3, 2, 3, 1, '2026-01-19 12:32:49'),
	(4, 2, 4, 1, '2026-01-19 12:32:52'),
	(5, 2, 5, 1, '2026-01-19 12:32:55'),
	(6, 2, 6, 1, '2026-01-19 12:32:57'),
	(7, 2, 7, 1, '2026-01-19 12:33:00'),
	(8, 2, 8, 1, '2026-01-19 12:33:02'),
	(9, 2, 9, 1, '2026-01-19 12:33:05'),
	(10, 2, 10, 1, '2026-01-19 12:33:07'),
	(11, 2, 11, 1, '2026-01-19 23:13:30'),
	(12, 2, 12, 1, '2026-01-19 23:13:31'),
	(25, 4, 1, 0, NULL),
	(26, 4, 2, 0, NULL),
	(27, 4, 3, 0, NULL),
	(28, 4, 4, 0, NULL),
	(29, 4, 5, 0, NULL),
	(30, 4, 6, 0, NULL),
	(31, 4, 7, 0, NULL),
	(32, 4, 8, 0, NULL),
	(33, 4, 9, 0, NULL),
	(34, 4, 10, 0, NULL),
	(35, 4, 11, 0, NULL),
	(36, 4, 12, 0, NULL);

-- Dumping structure for table akunting.perkiraan
CREATE TABLE IF NOT EXISTS `perkiraan` (
  `id_perkiraan` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(7) NOT NULL,
  PRIMARY KEY (`id_perkiraan`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.perkiraan: ~2 rows (approximately)
INSERT INTO `perkiraan` (`id_perkiraan`, `nama`) VALUES
	(1, 'Debit'),
	(2, 'Kredit');

-- Dumping structure for table akunting.piutang
CREATE TABLE IF NOT EXISTS `piutang` (
  `id_piutang` int(11) NOT NULL AUTO_INCREMENT,
  `nilai` bigint(20) NOT NULL,
  `jurnal_id` int(10) unsigned NOT NULL,
  `no_ref` varchar(256) DEFAULT NULL,
  `jt_tempo` date DEFAULT NULL,
  PRIMARY KEY (`id_piutang`),
  KEY `jurnal_id` (`jurnal_id`),
  CONSTRAINT `relasi_tabel_jurnal_piutang` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.piutang: ~0 rows (approximately)
INSERT INTO `piutang` (`id_piutang`, `nilai`, `jurnal_id`, `no_ref`, `jt_tempo`) VALUES
	(9, 2000000, 117, NULL, NULL);

-- Dumping structure for table akunting.piutang_bayar
CREATE TABLE IF NOT EXISTS `piutang_bayar` (
  `id_piutang_bayar` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` date NOT NULL,
  `piutang_id` int(11) NOT NULL,
  `kas_id` int(11) NOT NULL,
  `nilai` bigint(20) NOT NULL,
  `ket` text NOT NULL,
  `jurnal_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_piutang_bayar`),
  KEY `piutang_id` (`piutang_id`),
  KEY `jurnal_id` (`jurnal_id`),
  CONSTRAINT `relasi_tabel_jurnal_piutang_bayar` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.piutang_bayar: ~0 rows (approximately)
INSERT INTO `piutang_bayar` (`id_piutang_bayar`, `tgl`, `piutang_id`, `kas_id`, `nilai`, `ket`, `jurnal_id`) VALUES
	(14, '2026-01-27', 9, 8, 500000, 'test pembayaran piutang', 148);

-- Dumping structure for table akunting.profile
CREATE TABLE IF NOT EXISTS `profile` (
  `id_profile` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `logo` text NOT NULL,
  `deskripsi` text NOT NULL,
  `alamat` text DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id_profile`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.profile: ~0 rows (approximately)
INSERT INTO `profile` (`id_profile`, `name`, `logo`, `deskripsi`, `alamat`, `telp`, `npwp`, `id_user`) VALUES
	(1, 'Aviskara Perdana Inovasi', '', '<p>&nbsp;Application Developer</p>\r\n', 'Gedung Wirausaha Lantai 1,\r\nJalan HR Rasuna Said Kav. C-5, Karet, Setia Budi, Jakarta Selatan 12920', '+622199887766', '1234567890', 7);

-- Dumping structure for table akunting.set_account
CREATE TABLE IF NOT EXISTS `set_account` (
  `id_sa` int(11) NOT NULL AUTO_INCREMENT,
  `id_modal` int(11) NOT NULL,
  `id_lb_ditahan` int(11) NOT NULL,
  `id_lb_sebelum` int(11) NOT NULL,
  `id_kel_hpp` int(11) NOT NULL,
  PRIMARY KEY (`id_sa`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.set_account: ~1 rows (approximately)
INSERT INTO `set_account` (`id_sa`, `id_modal`, `id_lb_ditahan`, `id_lb_sebelum`, `id_kel_hpp`) VALUES
	(1, 11, 13, 14, 7);

-- Dumping structure for table akunting.set_account_kas
CREATE TABLE IF NOT EXISTS `set_account_kas` (
  `id_sak` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(288) NOT NULL,
  `desk` text NOT NULL,
  `id_akun` int(11) NOT NULL,
  `date_created` text NOT NULL,
  PRIMARY KEY (`id_sak`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.set_account_kas: ~5 rows (approximately)
INSERT INTO `set_account_kas` (`id_sak`, `nm`, `desk`, `id_akun`, `date_created`) VALUES
	(1, 'Kas Tunai', 'Uang Tunai di Kantor', 1, '1689140187'),
	(2, 'Bank BRI', 'Uang di bank BRI', 4, '1689248686'),
	(3, 'Bank Mandiri', 'Uang di bank Mandiri', 2, '1689248705'),
	(6, 'Bank Jatim', 'Uang di bank Jatim', 26, '1704701659'),
	(8, 'Bank BSI', 'Uang di bank BSI', 27, '1704795569');

-- Dumping structure for table akunting.set_account_system
CREATE TABLE IF NOT EXISTS `set_account_system` (
  `id_setting` int(11) NOT NULL AUTO_INCREMENT,
  `kode_setting` varchar(50) NOT NULL,
  `id_akun` int(11) unsigned NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_setting`),
  UNIQUE KEY `kode_setting` (`kode_setting`),
  KEY `id_akun_fk` (`id_akun`),
  CONSTRAINT `id_akun_fk` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.set_account_system: ~3 rows (approximately)
INSERT INTO `set_account_system` (`id_setting`, `kode_setting`, `id_akun`, `keterangan`) VALUES
	(1, 'LABA_DITAHAN', 13, NULL),
	(2, 'LABA_BERJALAN', 14, NULL);

-- Dumping structure for table akunting.set_nomor_otomatis
CREATE TABLE IF NOT EXISTS `set_nomor_otomatis` (
  `id_set_no_auto` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  `reset_nomor` int(11) NOT NULL,
  `panjang_nomor` int(11) NOT NULL,
  PRIMARY KEY (`id_set_no_auto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.set_nomor_otomatis: ~5 rows (approximately)
INSERT INTO `set_nomor_otomatis` (`id_set_no_auto`, `id_menu`, `prefix`, `reset_nomor`, `panjang_nomor`) VALUES
	(1, 1, 'JU', 2, 3),
	(2, 2, 'KAS', 2, 3),
	(3, 3, 'PIUT', 2, 3),
	(4, 4, 'HUT', 2, 3),
	(5, 5, 'DEP', 2, 3);

-- Dumping structure for table akunting.transaksi_biaya
CREATE TABLE IF NOT EXISTS `transaksi_biaya` (
  `id_trans_biaya` int(11) NOT NULL AUTO_INCREMENT,
  `nilai` bigint(20) NOT NULL,
  `biaya_id` int(11) NOT NULL,
  `kas_id` int(11) NOT NULL,
  `jurnal_id` int(10) unsigned NOT NULL,
  `bukti` text DEFAULT NULL,
  PRIMARY KEY (`id_trans_biaya`) USING BTREE,
  KEY `biaya_id` (`biaya_id`) USING BTREE,
  KEY `jurnal_id` (`jurnal_id`) USING BTREE,
  CONSTRAINT `transaksi_biaya_ibfk_1` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table akunting.transaksi_biaya: ~6 rows (approximately)
INSERT INTO `transaksi_biaya` (`id_trans_biaya`, `nilai`, `biaya_id`, `kas_id`, `jurnal_id`, `bukti`) VALUES
	(10, 11000, 3, 1, 121, NULL),
	(13, 15000, 3, 1, 142, NULL),
	(14, 10000, 3, 1, 146, 'db707990f54f9815340658e1f8459acc.pdf'),
	(17, 11000, 3, 1, 154, ''),
	(20, 50000, 3, 6, 161, '');

-- Dumping structure for table akunting.transaksi_pendapatan
CREATE TABLE IF NOT EXISTS `transaksi_pendapatan` (
  `id_trans_pendapatan` int(11) NOT NULL AUTO_INCREMENT,
  `nilai` bigint(20) NOT NULL,
  `pendapatan_id` int(11) NOT NULL,
  `kas_id` int(11) NOT NULL,
  `jurnal_id` int(10) unsigned NOT NULL,
  `bukti` text DEFAULT NULL,
  PRIMARY KEY (`id_trans_pendapatan`) USING BTREE,
  KEY `jurnal_id` (`jurnal_id`) USING BTREE,
  KEY `biaya_id` (`pendapatan_id`) USING BTREE,
  CONSTRAINT `transaksi_pendapatan_ibfk_1` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table akunting.transaksi_pendapatan: ~4 rows (approximately)
INSERT INTO `transaksi_pendapatan` (`id_trans_pendapatan`, `nilai`, `pendapatan_id`, `kas_id`, `jurnal_id`, `bukti`) VALUES
	(14, 10000, 1, 2, 129, NULL),
	(15, 15000, 1, 3, 130, NULL),
	(17, 100000, 2, 8, 133, NULL),
	(18, 15000, 2, 1, 147, NULL),
	(20, 100000, 2, 1, 155, '048979e227fde7d532c1fbf78ae7c3db.png');

-- Dumping structure for table akunting.type_transaksi
CREATE TABLE IF NOT EXISTS `type_transaksi` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.type_transaksi: ~4 rows (approximately)
INSERT INTO `type_transaksi` (`type_id`, `kode`, `nama`) VALUES
	(1, 'JU', 'Jurnal Umum'),
	(2, 'KAS', 'Kas / Bank'),
	(3, 'PIU', 'Piutang'),
	(4, 'HUT', 'Hutang'),
	(5, 'PNY', 'Penyusutan');

-- Dumping structure for table akunting.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `id_role` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `id_periode` int(11) NOT NULL,
  `id_bln` int(11) NOT NULL,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.user: ~3 rows (approximately)
INSERT INTO `user` (`id_user`, `name`, `email`, `image`, `password`, `id_role`, `is_active`, `id_periode`, `id_bln`, `date_created`) VALUES
	(7, 'Administrator', 'administrator@example.com', 'default1.jpg', '$2y$10$dvxapXO8sYaYfiATY4Us0O4c2KChVxzGWRWmTY7nLl3P9BRa309dG', 1, 1, 2026, 1, 1768622950),
	(29, 'Keuangan', 'keuangan@example.com', 'default.jpg', '$2y$10$56BsLLEnwAjG2MGqvrxrreLQpJFSYRuAsDgso54oPHcWSUKS6G/oC', 3, 1, 2025, 1, 1768626288),
	(30, 'Admin', 'admin@example.com', 'default.jpg', '$2y$10$dvxapXO8sYaYfiATY4Us0O4c2KChVxzGWRWmTY7nLl3P9BRa309dG', 2, 1, 2026, 1, 1768622748);

-- Dumping structure for table akunting.user_access_menu
CREATE TABLE IF NOT EXISTS `user_access_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `sub_menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`sub_menu_id`),
  CONSTRAINT `relasi_tabel_submenu` FOREIGN KEY (`sub_menu_id`) REFERENCES `user_sub_menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.user_access_menu: ~210 rows (approximately)
INSERT INTO `user_access_menu` (`id`, `role_id`, `sub_menu_id`) VALUES
	(1, 2, 1),
	(2, 2, 2),
	(13, 2, 15),
	(14, 2, 16),
	(15, 2, 17),
	(16, 2, 18),
	(17, 2, 19),
	(18, 2, 20),
	(19, 2, 21),
	(20, 2, 22),
	(21, 2, 23),
	(22, 2, 24),
	(23, 2, 25),
	(24, 2, 90),
	(25, 2, 89),
	(26, 2, 88),
	(27, 2, 87),
	(28, 2, 86),
	(29, 2, 85),
	(30, 2, 84),
	(31, 2, 83),
	(32, 2, 82),
	(33, 2, 81),
	(34, 2, 80),
	(35, 2, 78),
	(36, 2, 77),
	(37, 2, 76),
	(38, 2, 75),
	(39, 2, 74),
	(40, 2, 73),
	(41, 2, 72),
	(42, 2, 71),
	(43, 2, 70),
	(44, 2, 69),
	(45, 2, 68),
	(46, 2, 67),
	(47, 2, 66),
	(48, 2, 65),
	(49, 2, 64),
	(50, 2, 63),
	(51, 2, 62),
	(52, 2, 61),
	(53, 2, 60),
	(54, 2, 59),
	(55, 2, 58),
	(56, 2, 57),
	(57, 2, 55),
	(58, 2, 56),
	(59, 2, 54),
	(60, 2, 53),
	(61, 2, 52),
	(62, 2, 51),
	(63, 2, 50),
	(64, 2, 49),
	(65, 2, 48),
	(66, 2, 47),
	(67, 2, 46),
	(68, 2, 45),
	(69, 1, 1),
	(70, 1, 2),
	(71, 1, 5),
	(72, 1, 6),
	(73, 1, 7),
	(74, 1, 8),
	(75, 1, 9),
	(76, 1, 10),
	(77, 1, 12),
	(78, 1, 11),
	(79, 1, 13),
	(80, 1, 14),
	(81, 1, 45),
	(82, 1, 46),
	(83, 1, 47),
	(84, 1, 48),
	(85, 1, 49),
	(86, 1, 50),
	(87, 1, 51),
	(88, 1, 52),
	(89, 1, 53),
	(90, 1, 15),
	(91, 1, 16),
	(92, 1, 17),
	(93, 1, 18),
	(94, 1, 19),
	(95, 1, 20),
	(96, 1, 21),
	(97, 1, 22),
	(98, 2, 26),
	(99, 2, 27),
	(100, 2, 28),
	(101, 2, 29),
	(102, 2, 30),
	(103, 2, 31),
	(104, 2, 32),
	(105, 2, 33),
	(106, 2, 34),
	(107, 2, 35),
	(108, 2, 37),
	(109, 2, 36),
	(110, 2, 38),
	(111, 2, 39),
	(112, 2, 40),
	(113, 2, 41),
	(114, 2, 42),
	(115, 2, 43),
	(116, 2, 44),
	(117, 1, 86),
	(118, 1, 87),
	(119, 1, 88),
	(120, 1, 89),
	(121, 1, 90),
	(122, 1, 83),
	(123, 1, 84),
	(124, 1, 85),
	(125, 1, 82),
	(126, 1, 81),
	(127, 1, 80),
	(128, 1, 78),
	(129, 1, 77),
	(130, 1, 76),
	(131, 1, 75),
	(132, 1, 74),
	(133, 1, 73),
	(134, 1, 72),
	(135, 1, 61),
	(136, 1, 63),
	(137, 1, 62),
	(138, 1, 64),
	(139, 1, 65),
	(140, 1, 66),
	(141, 1, 67),
	(142, 1, 68),
	(143, 1, 69),
	(144, 1, 70),
	(145, 1, 71),
	(146, 1, 59),
	(147, 1, 60),
	(148, 1, 54),
	(149, 1, 55),
	(150, 1, 57),
	(151, 1, 56),
	(152, 1, 58),
	(153, 1, 38),
	(154, 1, 39),
	(155, 1, 40),
	(156, 1, 41),
	(157, 1, 42),
	(158, 1, 43),
	(159, 1, 44),
	(160, 1, 34),
	(161, 1, 35),
	(162, 1, 36),
	(163, 1, 37),
	(164, 1, 23),
	(165, 1, 24),
	(166, 1, 25),
	(167, 1, 27),
	(168, 1, 26),
	(169, 1, 28),
	(170, 1, 29),
	(171, 1, 30),
	(172, 1, 31),
	(173, 1, 32),
	(174, 1, 33),
	(175, 1, 91),
	(176, 3, 54),
	(177, 3, 55),
	(178, 3, 56),
	(179, 3, 57),
	(180, 3, 58),
	(181, 3, 78),
	(182, 3, 80),
	(183, 3, 81),
	(184, 3, 82),
	(185, 3, 86),
	(186, 3, 87),
	(187, 3, 88),
	(188, 3, 89),
	(189, 3, 90),
	(191, 3, 83),
	(192, 3, 84),
	(193, 3, 85),
	(197, 1, 93),
	(198, 2, 93),
	(199, 1, 95),
	(200, 2, 95),
	(201, 3, 95),
	(202, 1, 94),
	(203, 2, 94),
	(204, 3, 94),
	(205, 2, 96),
	(206, 2, 97),
	(207, 2, 98),
	(208, 2, 99),
	(209, 1, 96),
	(210, 1, 97),
	(211, 1, 98),
	(212, 1, 99),
	(213, 3, 96),
	(214, 3, 97),
	(215, 3, 98),
	(216, 3, 99),
	(229, 1, 104),
	(230, 2, 104),
	(239, 1, 105),
	(240, 1, 106),
	(241, 1, 107),
	(242, 1, 108),
	(243, 2, 105),
	(244, 2, 106),
	(245, 2, 107),
	(246, 2, 108),
	(247, 3, 105),
	(248, 3, 106),
	(249, 3, 107),
	(250, 3, 108),
	(251, 1, 109),
	(252, 1, 110),
	(253, 1, 111),
	(254, 1, 112),
	(255, 2, 109),
	(256, 2, 110),
	(257, 2, 111),
	(258, 2, 112),
	(259, 3, 109),
	(260, 3, 110),
	(261, 3, 111),
	(262, 3, 112),
	(263, 1, 113),
	(264, 2, 113),
	(265, 3, 113),
	(266, 1, 114),
	(267, 2, 114),
	(268, 3, 114),
	(269, 1, 115),
	(270, 2, 115),
	(271, 3, 115),
	(272, 1, 116),
	(273, 2, 116),
	(274, 1, 117),
	(275, 2, 117),
	(277, 1, 118),
	(278, 2, 118),
	(279, 1, 119),
	(280, 2, 119),
	(281, 1, 120),
	(282, 2, 120),
	(283, 1, 121),
	(284, 1, 122),
	(285, 1, 123),
	(286, 1, 124),
	(287, 2, 121),
	(288, 2, 122),
	(289, 2, 123),
	(290, 2, 124);

-- Dumping structure for table akunting.user_menu
CREATE TABLE IF NOT EXISTS `user_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(228) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.user_menu: ~17 rows (approximately)
INSERT INTO `user_menu` (`id`, `menu`) VALUES
	(1, 'Dashboard'),
	(3, 'Admin'),
	(4, 'Menu'),
	(5, 'Asset'),
	(6, 'Biaya'),
	(7, 'Journal'),
	(8, 'Kas'),
	(9, 'Masterdata'),
	(10, 'Periode'),
	(11, 'Piutang'),
	(12, 'Profilecompany'),
	(13, 'Report'),
	(14, 'Setting'),
	(15, 'Transaksibiaya'),
	(16, 'User'),
	(17, 'Utang'),
	(18, 'Pendapatan'),
	(21, 'Transaksipendapatan'),
	(22, 'Laporankas'),
	(23, 'Kelompokakun');

-- Dumping structure for table akunting.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(128) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table akunting.user_role: ~3 rows (approximately)
INSERT INTO `user_role` (`id_role`, `role`) VALUES
	(1, 'Administrator'),
	(2, 'Akunting'),
	(3, 'Keuangan');

-- Dumping structure for table akunting.user_sub_menu
CREATE TABLE IF NOT EXISTS `user_sub_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `title` varchar(228) NOT NULL,
  `url` text NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `relasi_tabel_menu` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.user_sub_menu: ~97 rows (approximately)
INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `is_active`) VALUES
	(1, 1, 'dashboard', 'dashboard/', 1),
	(2, 1, 'ubah_periode_aktif', 'dashboard/ubah_periode_aktif', 1),
	(5, 3, 'user_management', 'admin/user_management', 1),
	(6, 3, 'edit_user_management', 'admin/edit_user_management', 1),
	(7, 3, 'delete_user_management', 'admin/delete_user_management', 1),
	(8, 3, 'access_user', 'admin/access_user', 1),
	(9, 4, 'index', 'menu/', 1),
	(10, 4, 'editmenu', 'menu/editmenu', 1),
	(11, 4, 'deletemenu', 'menu/deletemenu', 1),
	(12, 4, 'submenu', 'menu/submenu', 1),
	(13, 4, 'editsubmenu', 'menu/editsubmenu', 1),
	(14, 4, 'deletesubmenu', 'menu/deletesubmenu', 1),
	(15, 5, 'index', 'asset/', 1),
	(16, 5, 'insert', 'asset/insert', 1),
	(17, 5, 'update', 'asset/update', 1),
	(18, 5, 'delete', 'asset/delete', 1),
	(19, 6, 'index', 'biaya/', 1),
	(20, 6, 'insert', 'biaya/insert', 1),
	(21, 6, 'update', 'biaya/update', 1),
	(22, 6, 'delete', 'biaya/delete', 1),
	(23, 7, 'index', 'journal/', 1),
	(24, 7, 'ajax_list_journal', 'journal/ajax_list_journal', 1),
	(25, 7, 'detail', 'journal/detail', 1),
	(26, 7, 'add', 'journal/add', 1),
	(27, 7, 'name_account', 'journal/name_account', 1),
	(28, 7, 'auto_account_id', 'journal/auto_account_id', 1),
	(29, 7, 'auto_name', 'journal/auto_name', 1),
	(30, 7, 'auto_journal', 'journal/auto_journal', 1),
	(31, 7, 'edit', 'journal/edit', 1),
	(32, 7, 'delete', 'journal/delete', 1),
	(33, 7, 'printgeneraljournal', 'journal/printgeneraljournal', 1),
	(34, 8, 'index', 'kas/', 1),
	(35, 8, 'insert', 'kas/insert', 1),
	(36, 8, 'update', 'kas/update', 1),
	(37, 8, 'delete', 'kas/delete', 1),
	(38, 9, 'index', 'masterdata/', 1),
	(39, 9, 'realtime', 'masterdata/realtime', 1),
	(40, 9, 'add', 'masterdata/add', 1),
	(41, 9, 'edit', 'masterdata/edit', 1),
	(42, 9, 'delete', 'masterdata/delete', 1),
	(43, 9, 'account_group', 'masterdata/account_group', 1),
	(44, 9, 'export_data_akun', 'masterdata/export_data_akun', 1),
	(45, 10, 'index', 'periode/', 1),
	(46, 10, 'open_periode', 'periode/open_periode', 1),
	(47, 10, 'tambah_periode_akuntansi', 'periode/tambah_periode_akuntansi', 1),
	(48, 10, 'hapus_periode_akuntansi', 'periode/hapus_periode_akuntansi', 1),
	(49, 10, 'buka_periode_tahunan', 'periode/buka_periode_tahunan', 1),
	(50, 10, 'tutup_periode_tahunan', 'periode/tutup_periode_tahunan', 1),
	(51, 10, 'detail', 'periode/detail', 1),
	(52, 10, 'buka_periode_bulanan', 'periode/buka_periode_bulanan', 1),
	(53, 10, 'tutup_periode_bulanan', 'periode/tutup_periode_bulanan', 1),
	(54, 11, 'index', 'piutang/', 1),
	(55, 11, 'print', 'piutang/print', 1),
	(56, 11, 'tambah', 'piutang/tambah', 1),
	(57, 11, 'pembayaran', 'piutang/pembayaran', 1),
	(58, 11, 'detail_pelunasan', 'piutang/detail_pelunasan', 1),
	(59, 12, 'index', 'profilecompany/', 1),
	(60, 12, 'edit_profile_perusahaan', 'profilecompany/edit_profile_perusahaan', 1),
	(61, 13, 'index', 'report/', 1),
	(62, 13, 'printledger', 'report/printledger', 1),
	(63, 13, 'trialbalance', 'report/trialbalance', 1),
	(64, 13, 'printtrialbalance', 'report/printtrialbalance', 1),
	(65, 13, 'incomestatement', 'report/incomestatement', 1),
	(66, 13, 'printincomestatement', 'report/printincomestatement', 1),
	(67, 13, 'balancesheet', 'report/balancesheet', 1),
	(68, 13, 'printbalancesheet', 'report/printbalancesheet', 1),
	(69, 13, 'cashflowstatement', 'report/cashflowstatement', 1),
	(70, 13, 'printcashflowstatement', 'report/printcashflowstatement', 1),
	(71, 13, 'setting', 'report/setting', 1),
	(72, 13, 'delete', 'report/delete', 1),
	(73, 13, 'data_aktivitas', 'report/data_aktivitas', 1),
	(74, 13, 'update_katagori', 'report/update_katagori', 1),
	(75, 14, 'index', 'setting/', 1),
	(76, 14, 'update', 'setting/update', 1),
	(77, 14, 'update_nomor_otomatis', 'setting/update_nomor_otomatis', 1),
	(78, 15, 'index', 'transaksibiaya/', 1),
	(80, 15, 'insert', 'transaksibiaya/insert', 1),
	(81, 15, 'update', 'transaksibiaya/update', 1),
	(82, 15, 'delete', 'transaksibiaya/delete', 1),
	(83, 16, 'index', 'user/', 1),
	(84, 16, 'edit', 'user/edit', 1),
	(85, 16, 'changePassword', 'user/changePassword', 1),
	(86, 17, 'index', 'utang/', 1),
	(87, 17, 'print', 'utang/print', 1),
	(88, 17, 'tambah', 'utang/tambah', 1),
	(89, 17, 'pembayaran', 'utang/pembayaran', 1),
	(90, 17, 'detail_pelunasan', 'utang/detail_pelunasan', 1),
	(91, 3, 'changeAccess', 'admin/changeAccess', 1),
	(93, 10, 'cek_periode', 'periode/cek_periode', 1),
	(94, 11, 'hapus_pelunasan', 'piutang/hapus_pelunasan', 1),
	(95, 17, 'hapus_pelunasan', 'utang/hapus_pelunasan', 1),
	(96, 18, 'delete', 'pendapatan/delete', 1),
	(97, 18, 'update', 'pendapatan/update', 1),
	(98, 18, 'insert', 'pendapatan/insert', 1),
	(99, 18, 'index', 'pendapatan/', 1),
	(104, 5, 'print', 'asset/print', 1),
	(105, 21, 'index', 'transaksipendapatan/', 1),
	(106, 21, 'insert', 'transaksipendapatan/insert', 1),
	(107, 21, 'update', 'transaksipendapatan/update', 1),
	(108, 21, 'delete', 'transaksipendapatan/delete', 1),
	(109, 22, 'delete', 'laporankas/delete', 1),
	(110, 22, 'update', 'laporankas/update', 1),
	(111, 22, 'insert', 'laporankas/insert', 1),
	(112, 22, 'index', 'laporankas/', 1),
	(113, 22, 'print', 'laporankas/print', 1),
	(114, 15, 'print', 'transaksibiaya/print', 1),
	(115, 21, 'print', 'transaksipendapatan/print', 1),
	(116, 5, 'nonaktif', 'asset/nonaktif', 1),
	(117, 13, 'neraca', 'neraca/index', 1),
	(118, 13, 'printneraca', 'neraca/print', 1),
	(119, 13, 'labarugi', 'labarugi/index', 1),
	(120, 13, 'printlabarugi', 'labarugi/print', 1),
	(121, 23, 'index', 'kelompokakun/', 1),
	(122, 23, 'insert', 'kelompokakun/insert', 1),
	(123, 23, 'update', 'kelompokakun/update', 1),
	(124, 23, 'delete', 'kelompokakun/delete', 1);

-- Dumping structure for table akunting.utang
CREATE TABLE IF NOT EXISTS `utang` (
  `id_utang` int(11) NOT NULL AUTO_INCREMENT,
  `nilai` bigint(20) NOT NULL,
  `jurnal_id` int(10) unsigned DEFAULT NULL,
  `no_ref` int(11) DEFAULT NULL,
  `jt_tempo` date DEFAULT NULL,
  PRIMARY KEY (`id_utang`),
  KEY `jurnal_id` (`jurnal_id`),
  CONSTRAINT `relasi_tabel_jurnal_utang` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.utang: ~0 rows (approximately)
INSERT INTO `utang` (`id_utang`, `nilai`, `jurnal_id`, `no_ref`, `jt_tempo`) VALUES
	(4, 1000000, 118, NULL, NULL);

-- Dumping structure for table akunting.utang_bayar
CREATE TABLE IF NOT EXISTS `utang_bayar` (
  `id_utang_bayar` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` date NOT NULL,
  `utang_id` int(11) NOT NULL,
  `kas_id` int(11) NOT NULL,
  `ket` text NOT NULL,
  `nilai` bigint(20) NOT NULL,
  `jurnal_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_utang_bayar`),
  KEY `jurnal_id` (`jurnal_id`),
  KEY `utang_id` (`utang_id`),
  CONSTRAINT `relasi_tabel_jurnal_utang_bayar` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id_jurnal`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table akunting.utang_bayar: ~2 rows (approximately)
INSERT INTO `utang_bayar` (`id_utang_bayar`, `tgl`, `utang_id`, `kas_id`, `ket`, `nilai`, `jurnal_id`) VALUES
	(6, '2026-01-23', 4, 2, 'test', 200000, 135),
	(7, '2026-01-22', 4, 8, 'test back date', 300000, 139),
	(8, '2026-01-27', 4, 8, 'test pembayaran utang', 100000, 149);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

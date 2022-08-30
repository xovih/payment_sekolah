-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 30 Agu 2022 pada 02.19
-- Versi server: 5.7.31
-- Versi PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sekolah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `actors`
--

DROP TABLE IF EXISTS `actors`;
CREATE TABLE IF NOT EXISTS `actors` (
  `actor_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(128) NOT NULL,
  PRIMARY KEY (`actor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `actors`
--

INSERT INTO `actors` (`actor_id`, `role`) VALUES
(1, 'Super Administrator'),
(5, 'Admin Tata Usaha');

-- --------------------------------------------------------

--
-- Struktur dari tabel `actor_details`
--

DROP TABLE IF EXISTS `actor_details`;
CREATE TABLE IF NOT EXISTS `actor_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `actor_id` (`actor_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `actor_details`
--

INSERT INTO `actor_details` (`detail_id`, `actor_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(6, 1, 6),
(15, 5, 1),
(16, 5, 6),
(18, 5, 12),
(19, 5, 10),
(20, 5, 11),
(21, 5, 13),
(22, 1, 10),
(23, 1, 11),
(24, 1, 13),
(26, 1, 12),
(27, 1, 14),
(28, 1, 15),
(29, 1, 16),
(30, 1, 17);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_pembayaran`
--

DROP TABLE IF EXISTS `jenis_pembayaran`;
CREATE TABLE IF NOT EXISTS `jenis_pembayaran` (
  `id_akun` int(11) NOT NULL AUTO_INCREMENT,
  `kode_akun` varchar(8) NOT NULL,
  `nama_akun` varchar(128) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_akun`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jenis_pembayaran`
--

INSERT INTO `jenis_pembayaran` (`id_akun`, `kode_akun`, `nama_akun`, `is_active`) VALUES
(1, 'P01', 'Biaya Modul', 1),
(2, 's1', 'ssiaaa', 0),
(3, 'P02', 'Infaq', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

DROP TABLE IF EXISTS `kelas`;
CREATE TABLE IF NOT EXISTS `kelas` (
  `id_kelas` int(11) NOT NULL AUTO_INCREMENT,
  `tingkat` enum('7','8','9') NOT NULL DEFAULT '7',
  `label` char(1) NOT NULL,
  PRIMARY KEY (`id_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `tingkat`, `label`) VALUES
(1, '7', 'A'),
(2, '7', 'B'),
(3, '7', 'C'),
(4, '8', 'A'),
(7, '7', 'D'),
(8, '8', 'D');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(128) NOT NULL,
  `active_class` varchar(64) DEFAULT NULL,
  `icon` varchar(86) DEFAULT NULL,
  `label` varchar(128) NOT NULL,
  `type` enum('single','parent','child') NOT NULL DEFAULT 'single',
  `category` enum('top','master','transaksi','laporan','pengaturan') NOT NULL DEFAULT 'master',
  `parent_id` int(11) DEFAULT NULL,
  `order_no` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`menu_id`, `link`, `active_class`, `icon`, `label`, `type`, `category`, `parent_id`, `order_no`) VALUES
(1, 'dashboard', 'dashboard', 'fas fa-window-restore', 'Dashboard', 'single', 'top', NULL, 1),
(2, 'menus', 'menus', 'fas fa-cube', 'Menu Sistem', 'single', 'pengaturan', NULL, 1),
(3, 'actors', 'actors', 'fas fa-user-secret', 'Hak Akses Pengguna', 'single', 'pengaturan', NULL, 2),
(4, 'users', 'users', 'fas fa-user', 'Pengguna Sistem', 'single', 'pengaturan', NULL, 3),
(6, 'siswa', 'siswa', 'fas fa-id-badge', 'Data Siswa', 'single', 'master', NULL, 2),
(10, 'kelas', 'kelas', 'fas fa-sitemap', 'Data Kelas', 'single', 'master', NULL, 0),
(11, 'paymentlist', 'paymentlist', 'fas fa-book', 'Manajemen Pembayaran', 'single', 'master', NULL, 3),
(12, 'tagihan', 'tagihan', 'fas fa-tag', 'Tagihan', 'parent', 'transaksi', NULL, 1),
(13, 'pembayaran', 'pembayaran', 'fas fa-marker', 'Pembayaran Tagihan', 'single', 'transaksi', NULL, 2),
(14, 'tagihan/index', 'tagihan', 'far fa-circle', 'Data Tagihan', 'child', 'transaksi', 12, 1),
(15, 'tagihan/add', 'tagihan', 'far fa-circle', 'Input Tagihan', 'child', 'transaksi', 12, 2),
(16, 'pembayaran/index', 'pembayaran', 'far fa-circle', 'Riwayat Pembayaran', 'child', 'transaksi', 13, 1),
(17, 'pembayaran/add', 'pembayaran', 'far fa-circle', 'Input Pembayaran', 'child', 'transaksi', 13, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_tagihan` int(11) DEFAULT NULL,
  `nominal` int(11) NOT NULL,
  `tunai` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sisa` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `waktu_transaksi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_petugas` int(11) DEFAULT NULL,
  `catatan` text NOT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_tagihan` (`id_tagihan`),
  KEY `id_petugas` (`id_petugas`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_tagihan`, `nominal`, `tunai`, `sisa`, `waktu_transaksi`, `id_petugas`, `catatan`) VALUES
(6, 1, 25000, 150000, 125000, '2022-08-15 12:09:43', 1, 'Cicilan ke 1'),
(7, 2, 15000, 15000, 0, '2022-08-15 12:16:03', 1, 'Cicilan pertama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_kelas`
--

DROP TABLE IF EXISTS `riwayat_kelas`;
CREATE TABLE IF NOT EXISTS `riwayat_kelas` (
  `id_riwayat` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `waktu_perubahan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_riwayat`),
  KEY `id_siswa` (`id_siswa`),
  KEY `id_kelas` (`id_kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

DROP TABLE IF EXISTS `siswa`;
CREATE TABLE IF NOT EXISTS `siswa` (
  `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `no_induk` varchar(64) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL DEFAULT 'L',
  `id_kelas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_siswa`),
  KEY `id_kelas` (`id_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `no_induk`, `nama`, `jenis_kelamin`, `id_kelas`) VALUES
(1, '2022.06.0001', 'Riris Rovika', 'P', 1),
(3, '2022.061.0002', 'Fernanda Karel', 'L', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

DROP TABLE IF EXISTS `tagihan`;
CREATE TABLE IF NOT EXISTS `tagihan` (
  `id_tagihan` int(11) NOT NULL AUTO_INCREMENT,
  `id_akun` int(11) DEFAULT NULL,
  `catatan` text NOT NULL,
  `nominal` int(11) NOT NULL,
  `tenggat_waktu` date NOT NULL,
  `waktu_pembuatan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `waktu_perubahan` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_pembuat` int(11) DEFAULT NULL,
  `user_perubah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tagihan`),
  KEY `id_akun` (`id_akun`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tagihan`
--

INSERT INTO `tagihan` (`id_tagihan`, `id_akun`, `catatan`, `nominal`, `tenggat_waktu`, `waktu_pembuatan`, `waktu_perubahan`, `user_pembuat`, `user_perubah`) VALUES
(1, 1, 'Modul Gak Maen-Maen', 25000, '2022-10-25', '2022-08-15 08:29:07', '2022-08-15 08:29:07', 1, 1),
(2, 3, 'Infaq opo', 25000, '2022-08-15', '2022-08-15 12:14:56', '2022-08-15 12:14:56', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan_detail`
--

DROP TABLE IF EXISTS `tagihan_detail`;
CREATE TABLE IF NOT EXISTS `tagihan_detail` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_tagihan` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_tagihan` (`id_tagihan`),
  KEY `id_siswa` (`id_siswa`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tagihan_detail`
--

INSERT INTO `tagihan_detail` (`id_detail`, `id_tagihan`, `id_siswa`) VALUES
(1, 1, 1),
(2, 1, 3),
(3, 2, 1),
(4, 2, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  KEY `actor_id` (`actor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `fullname`, `photo`, `actor_id`, `is_active`) VALUES
(1, 'sora', '$2y$12$hqkumeSPFuokGghlH5czFu/0rO/qhF7BD.c0L/zflRN.rYrvm4aKe', 'Sofi Rahmatulloh', 'https://ess.pindad.com/assets/images/foto_pegawai_bumn/05817.jpg', 1, 1),
(4, 'admintu', '$2y$12$mi7cIc2EPVYL90CAuomJle1Ir9n2EnjFnI6b/0Rh7k/joiC9wmz1O', 'Admin Tata Usaha', 'https://ess.pindad.com/assets/images/foto_pegawai_bumn/05219.jpg', 5, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('ua1j60sibm7jbii1iosl90877mv0cg4e', '192.168.197.197', 1661824976, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313832343933363b757365725f69647c733a313a2234223b757365726e616d657c733a373a2261646d696e7475223b66756c6c6e616d657c733a31363a2241646d696e2054617461205573616861223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353231392e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2235223b726f6c657c733a31363a2241646d696e2054617461205573616861223b),
('khsubol1p6prgg8st6avmm0cd8geia1f', '192.168.197.197', 1661823725, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313832333732353b757365725f69647c733a313a2234223b757365726e616d657c733a373a2261646d696e7475223b66756c6c6e616d657c733a31363a2241646d696e2054617461205573616861223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353231392e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2235223b726f6c657c733a31363a2241646d696e2054617461205573616861223b),
('v9e8ininflo0vpms2uri4qrpues3sa9q', '192.168.197.197', 1661824936, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313832343933363b757365725f69647c733a313a2234223b757365726e616d657c733a373a2261646d696e7475223b66756c6c6e616d657c733a31363a2241646d696e2054617461205573616861223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353231392e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2235223b726f6c657c733a31363a2241646d696e2054617461205573616861223b),
('8sr03acoh8acs4h81t4i8lsu5pnuitv4', '::1', 1661822715, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313832323635393b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('9ckmksdojfhs86jlp8ckf455psjba19m', '::1', 1661762480, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313736323437393b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('c47nsqi8vmo0d9tkudv0dca12s4fedu8', '::1', 1661762479, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313736323437393b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('eiibqegk13fkl28e3v7tv03vgfekk6c5', '::1', 1661757705, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636313735373730353b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('s2d1sc4rgjrqga599l887dt6srvkddlc', '::1', 1660545023, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303534303233333b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('rtuqv7kulnh95gp94hkpgrcb8gln9klt', '::1', 1660539935, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303533393831343b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('nj6timgbls5ira4budovd0ci0cahr9ts', '::1', 1660539814, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303533393831343b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('mdlg3s3fv3pt959k7hmku4s2ach4ppsb', '::1', 1660525694, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532353639343b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('punee98v5s0n0pun5bvo60fbupmhci87', '::1', 1660526033, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532363033333b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('58kj3u4d8nksos1bdhq76tcul5soetbt', '::1', 1660526426, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532363432363b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('d12e50h0009dvn40rrkcjmfeasjb5f3j', '::1', 1660526953, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532363935333b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('pt8r2r3ipas5e32jdtdmvc43pdvsd5t0', '::1', 1660527320, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532373332303b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('dfl4t7knk079opoc3os83jtd54h1asae', '::1', 1660527629, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532373632393b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('ad3e710mki8dbtnfihgrj1kmko9ds9ee', '::1', 1660529685, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303532393638353b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('34s2vhm90gopptbqfe1fnu50qcbbc8op', '::1', 1660530063, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303533303036333b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('nc3qafm11vjks5a8iqi042e83u06oroa', '::1', 1660536427, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303533363432373b757365725f69647c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('d894bqoi196rk1usu5gsvetoiupqm5gc', '::1', 1660538366, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303533383336363b7c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b),
('lp0cab5tf8umh2pb9qi8esjggl1s92he', '::1', 1660539097, 0x5f5f63695f6c6173745f726567656e65726174657c693a313636303533393039373b7c733a313a2231223b757365726e616d657c733a343a22736f7261223b66756c6c6e616d657c733a31363a22536f6669205261686d6174756c6c6f68223b6176617461727c733a36343a2268747470733a2f2f6573732e70696e6461642e636f6d2f6173736574732f696d616765732f666f746f5f706567617761695f62756d6e2f30353831372e6a7067223b69735f6c6f67696e7c623a313b6163746f725f69647c733a313a2231223b726f6c657c733a31393a2253757065722041646d696e6973747261746f72223b);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_actor_menus`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_actor_menus`;
CREATE TABLE IF NOT EXISTS `v_actor_menus` (
`detail_id` int(11)
,`actor_id` int(11)
,`role` varchar(128)
,`menu_id` int(11)
,`link` varchar(128)
,`active_class` varchar(64)
,`icon` varchar(86)
,`label` varchar(128)
,`type` enum('single','parent','child')
,`category` enum('top','master','transaksi','laporan','pengaturan')
,`parent_id` int(11)
,`order_no` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_login`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_login`;
CREATE TABLE IF NOT EXISTS `v_login` (
`user_id` int(11)
,`username` varchar(32)
,`fullname` varchar(128)
,`photo` varchar(255)
,`actor_id` int(11)
,`password` varchar(255)
,`role` varchar(128)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_pembayaran`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_pembayaran`;
CREATE TABLE IF NOT EXISTS `v_pembayaran` (
`id_pembayaran` int(11)
,`jumlah_bayar` int(11)
,`waktu_transaksi` datetime
,`id_petugas` int(11)
,`id_detail` int(11)
,`id_tagihan` int(11)
,`id_akun` int(11)
,`catatan` text
,`nominal` int(11)
,`tunai` int(10) unsigned
,`sisa` int(10) unsigned
,`catatan_transaksi` text
,`tenggat_waktu` date
,`waktu_pembuatan` datetime
,`waktu_perubahan` datetime
,`user_pembuat` int(11)
,`user_perubah` int(11)
,`kode_akun` varchar(8)
,`nama_akun` varchar(128)
,`id_siswa` int(11)
,`no_induk` varchar(64)
,`nama` varchar(128)
,`jenis_kelamin` enum('L','P')
,`id_kelas` int(11)
,`kelas_tingkat` enum('7','8','9')
,`kelas_label` char(1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_siswa`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_siswa`;
CREATE TABLE IF NOT EXISTS `v_siswa` (
`id_siswa` int(11)
,`no_induk` varchar(64)
,`nama` varchar(128)
,`jenis_kelamin` enum('L','P')
,`id_kelas` int(11)
,`kelas_tingkat` enum('7','8','9')
,`kelas_label` char(1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_tagihan`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_tagihan`;
CREATE TABLE IF NOT EXISTS `v_tagihan` (
`id_tagihan` int(11)
,`id_akun` int(11)
,`catatan` text
,`nominal` int(11)
,`tenggat_waktu` date
,`waktu_pembuatan` datetime
,`waktu_perubahan` datetime
,`user_pembuat` int(11)
,`user_perubah` int(11)
,`kode_akun` varchar(8)
,`nama_akun` varchar(128)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_tagihan_detail`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_tagihan_detail`;
CREATE TABLE IF NOT EXISTS `v_tagihan_detail` (
`id_detail` int(11)
,`id_tagihan` int(11)
,`id_akun` int(11)
,`catatan` text
,`nominal` int(11)
,`tenggat_waktu` date
,`waktu_pembuatan` datetime
,`waktu_perubahan` datetime
,`user_pembuat` int(11)
,`user_perubah` int(11)
,`kode_akun` varchar(8)
,`nama_akun` varchar(128)
,`id_siswa` int(11)
,`no_induk` varchar(64)
,`nama` varchar(128)
,`jenis_kelamin` enum('L','P')
,`id_kelas` int(11)
,`kelas_tingkat` enum('7','8','9')
,`kelas_label` char(1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_users`
-- (Lihat di bawah untuk tampilan aktual)
--
DROP VIEW IF EXISTS `v_users`;
CREATE TABLE IF NOT EXISTS `v_users` (
`user_id` int(11)
,`username` varchar(32)
,`fullname` varchar(128)
,`photo` varchar(255)
,`actor_id` int(11)
,`password` varchar(255)
,`role` varchar(128)
,`is_active` tinyint(1)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_actor_menus`
--
DROP TABLE IF EXISTS `v_actor_menus`;

DROP VIEW IF EXISTS `v_actor_menus`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_actor_menus`  AS  select `dt`.`detail_id` AS `detail_id`,`ac`.`actor_id` AS `actor_id`,`ac`.`role` AS `role`,`mn`.`menu_id` AS `menu_id`,`mn`.`link` AS `link`,`mn`.`active_class` AS `active_class`,`mn`.`icon` AS `icon`,`mn`.`label` AS `label`,`mn`.`type` AS `type`,`mn`.`category` AS `category`,`mn`.`parent_id` AS `parent_id`,`mn`.`order_no` AS `order_no` from ((`actors` `ac` join `actor_details` `dt`) join `menus` `mn`) where ((`ac`.`actor_id` = `dt`.`actor_id`) and (`dt`.`menu_id` = `mn`.`menu_id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_login`
--
DROP TABLE IF EXISTS `v_login`;

DROP VIEW IF EXISTS `v_login`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_login`  AS  select `u`.`user_id` AS `user_id`,`u`.`username` AS `username`,`u`.`fullname` AS `fullname`,`u`.`photo` AS `photo`,`u`.`actor_id` AS `actor_id`,`u`.`password` AS `password`,`act`.`role` AS `role` from (`users` `u` join `actors` `act`) where ((`u`.`actor_id` = `act`.`actor_id`) and (`u`.`is_active` = TRUE)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_pembayaran`
--
DROP TABLE IF EXISTS `v_pembayaran`;

DROP VIEW IF EXISTS `v_pembayaran`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pembayaran`  AS  select `b`.`id_pembayaran` AS `id_pembayaran`,`b`.`nominal` AS `jumlah_bayar`,`b`.`waktu_transaksi` AS `waktu_transaksi`,`b`.`id_petugas` AS `id_petugas`,`t`.`id_detail` AS `id_detail`,`t`.`id_tagihan` AS `id_tagihan`,`t`.`id_akun` AS `id_akun`,`t`.`catatan` AS `catatan`,`t`.`nominal` AS `nominal`,`b`.`tunai` AS `tunai`,`b`.`sisa` AS `sisa`,`b`.`catatan` AS `catatan_transaksi`,`t`.`tenggat_waktu` AS `tenggat_waktu`,`t`.`waktu_pembuatan` AS `waktu_pembuatan`,`t`.`waktu_perubahan` AS `waktu_perubahan`,`t`.`user_pembuat` AS `user_pembuat`,`t`.`user_perubah` AS `user_perubah`,`t`.`kode_akun` AS `kode_akun`,`t`.`nama_akun` AS `nama_akun`,`t`.`id_siswa` AS `id_siswa`,`t`.`no_induk` AS `no_induk`,`t`.`nama` AS `nama`,`t`.`jenis_kelamin` AS `jenis_kelamin`,`t`.`id_kelas` AS `id_kelas`,`t`.`kelas_tingkat` AS `kelas_tingkat`,`t`.`kelas_label` AS `kelas_label` from (`v_tagihan_detail` `t` join `pembayaran` `b` on((`t`.`id_detail` = `b`.`id_tagihan`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_siswa`
--
DROP TABLE IF EXISTS `v_siswa`;

DROP VIEW IF EXISTS `v_siswa`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_siswa`  AS  select `s`.`id_siswa` AS `id_siswa`,`s`.`no_induk` AS `no_induk`,`s`.`nama` AS `nama`,`s`.`jenis_kelamin` AS `jenis_kelamin`,`s`.`id_kelas` AS `id_kelas`,`k`.`tingkat` AS `kelas_tingkat`,`k`.`label` AS `kelas_label` from (`siswa` `s` join `kelas` `k` on((`s`.`id_kelas` = `k`.`id_kelas`))) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_tagihan`
--
DROP TABLE IF EXISTS `v_tagihan`;

DROP VIEW IF EXISTS `v_tagihan`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tagihan`  AS  select `t`.`id_tagihan` AS `id_tagihan`,`t`.`id_akun` AS `id_akun`,`t`.`catatan` AS `catatan`,`t`.`nominal` AS `nominal`,`t`.`tenggat_waktu` AS `tenggat_waktu`,`t`.`waktu_pembuatan` AS `waktu_pembuatan`,`t`.`waktu_perubahan` AS `waktu_perubahan`,`t`.`user_pembuat` AS `user_pembuat`,`t`.`user_perubah` AS `user_perubah`,`j`.`kode_akun` AS `kode_akun`,`j`.`nama_akun` AS `nama_akun` from (`tagihan` `t` join `jenis_pembayaran` `j` on((`t`.`id_akun` = `j`.`id_akun`))) where (`j`.`is_active` = TRUE) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_tagihan_detail`
--
DROP TABLE IF EXISTS `v_tagihan_detail`;

DROP VIEW IF EXISTS `v_tagihan_detail`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_tagihan_detail`  AS  select `d`.`id_detail` AS `id_detail`,`t`.`id_tagihan` AS `id_tagihan`,`t`.`id_akun` AS `id_akun`,`t`.`catatan` AS `catatan`,`t`.`nominal` AS `nominal`,`t`.`tenggat_waktu` AS `tenggat_waktu`,`t`.`waktu_pembuatan` AS `waktu_pembuatan`,`t`.`waktu_perubahan` AS `waktu_perubahan`,`t`.`user_pembuat` AS `user_pembuat`,`t`.`user_perubah` AS `user_perubah`,`t`.`kode_akun` AS `kode_akun`,`t`.`nama_akun` AS `nama_akun`,`s`.`id_siswa` AS `id_siswa`,`s`.`no_induk` AS `no_induk`,`s`.`nama` AS `nama`,`s`.`jenis_kelamin` AS `jenis_kelamin`,`s`.`id_kelas` AS `id_kelas`,`s`.`kelas_tingkat` AS `kelas_tingkat`,`s`.`kelas_label` AS `kelas_label` from ((`v_tagihan` `t` join `tagihan_detail` `d`) join `v_siswa` `s`) where ((`t`.`id_tagihan` = `d`.`id_tagihan`) and (`d`.`id_siswa` = `s`.`id_siswa`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_users`
--
DROP TABLE IF EXISTS `v_users`;

DROP VIEW IF EXISTS `v_users`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_users`  AS  select `u`.`user_id` AS `user_id`,`u`.`username` AS `username`,`u`.`fullname` AS `fullname`,`u`.`photo` AS `photo`,`u`.`actor_id` AS `actor_id`,`u`.`password` AS `password`,`act`.`role` AS `role`,`u`.`is_active` AS `is_active` from (`users` `u` join `actors` `act` on((`u`.`actor_id` = `act`.`actor_id`))) ;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `actor_details`
--
ALTER TABLE `actor_details`
  ADD CONSTRAINT `actor_details_ibfk_1` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`actor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actor_details_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_tagihan`) REFERENCES `tagihan_detail` (`id_detail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `jenis_pembayaran` (`id_akun`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tagihan_detail`
--
ALTER TABLE `tagihan_detail`
  ADD CONSTRAINT `tagihan_detail_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagihan_detail_ibfk_2` FOREIGN KEY (`id_tagihan`) REFERENCES `tagihan` (`id_tagihan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`actor_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Sep 2021 pada 18.40
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_signature`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_akun`
--

CREATE TABLE `tbl_akun` (
  `id_akun` int(11) NOT NULL,
  `id_user_akun` varchar(12) NOT NULL,
  `email_akun` varchar(150) NOT NULL,
  `password_akun` varchar(150) NOT NULL,
  `level_akun` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_akun`
--

INSERT INTO `tbl_akun` (`id_akun`, `id_user_akun`, `email_akun`, `password_akun`, `level_akun`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'USR0001', 'test@example.com', '$2y$10$R6slJi4XP2fc8lx2GH5zT.NkjGAaEDo7c8G.OCF7Zt5V6kmLpTSdS', 1, '2021-09-16 08:32:43', 'sistem', NULL, NULL, NULL, NULL),
(6, 'USR0002', 'user@example.com', '$2y$10$zGjnPs5GMH3tfcLdsZzVJur8ofjk.9Zr6P.nXGlsr0Q7rD22AM.nC', 2, '2021-09-16 08:32:43', 'TEST', '2021-08-19 07:03:15', 'USERRR', NULL, NULL),
(7, 'MHS0001', 'mhs@example.com', '$2y$10$ZOPskw8ybGb9.b8KUws8F.mVVAfVY3gZJkpn56vqYBJPelwow9qS6', 3, '2021-09-16 08:32:43', 'TEST', '2021-08-19 07:44:19', 'mahasiswa', NULL, NULL),
(8, 'USR0003', 'prodi@example.com', '$2y$10$LMyWWxydYQSY.cVm9yOHWOMc8pmjoVgiy.GYRxruaFa6odeNQeobK', 2, '2021-09-16 08:32:43', 'TEST', NULL, NULL, NULL, NULL),
(9, 'USR0004', 'wd3@example.com', '$2y$10$GFAyU9.sz84Kj0n0xgf/u.lKqU0qkL8CeToP1KnrjyZXFTAU3Qpn6', 2, '2021-09-16 08:32:43', 'TEST', NULL, NULL, NULL, NULL),
(0, 'USR0005', 'afit@gmail.com', '$2y$10$9fmFfQOJToNp/rX/.WS9je34LYtJKekwvuMRSU3vh6xk/Wm5MptoC', 1, '2021-09-16 08:36:53', 'TEST', NULL, NULL, NULL, NULL),
(0, 'USR0006', 'mhs01@gmail.com', '$2y$10$J2xrKUMy.qzKWrqPdagSAu7afsOrYYewQyWoxMj/nA0lI3pFtoWCq', 3, '2021-09-16 08:50:22', 'afit', NULL, NULL, NULL, NULL),
(0, 'USR0007', 'elan@gmail.com', '$2y$10$EfpOIUmm7j7Nx4FUXutUG.2dBP/pHpwwfDYWts9/JkEnr8VCUY5Ce', 3, '2021-09-16 10:51:05', 'TEST', NULL, NULL, NULL, NULL),
(0, 'USR0008', 'dosen@gmail.com', '$2y$10$jUYTt6yRu7pX/W2X3JfBquyX0run3GqBLAMd.K5iWPz/5TMbI6yJ2', 2, '2021-09-16 19:15:52', 'TEST', NULL, NULL, NULL, NULL),
(0, 'USR0009', 'afit01@gmail.com', '$2y$10$eXzR6chZRTgr.Tx2HgwYV.OUSqKu/rRjimxzlZznoph2MvMUEif9C', 3, '2021-09-16 20:38:44', 'TEST', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_level`
--

CREATE TABLE `tbl_level` (
  `id_level` int(11) NOT NULL,
  `nama_level` varchar(150) NOT NULL,
  `keterangan_level` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_level`
--

INSERT INTO `tbl_level` (`id_level`, `nama_level`, `keterangan_level`) VALUES
(1, 'Akademik', '--'),
(2, 'Pengesah', '-'),
(3, 'Mahasiswa', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_log`
--

CREATE TABLE `tbl_log` (
  `id_log` int(11) NOT NULL,
  `id_user_log` varchar(20) NOT NULL,
  `nama_aktor_log` varchar(150) NOT NULL,
  `aksi_log` varchar(255) NOT NULL,
  `kegiatan_log` varchar(50) NOT NULL,
  `status_log` int(11) NOT NULL,
  `tanggal_log` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_log`
--

INSERT INTO `tbl_log` (`id_log`, `id_user_log`, `nama_aktor_log`, `aksi_log`, `kegiatan_log`, `status_log`, `tanggal_log`) VALUES
(6, '', 'TEST', 'Menambahkan User dan Akun', '', 0, '2021-09-16 08:08:35'),
(7, '', 'TEST', 'Hapus Sementara User dan Akun', '', 0, '2021-09-16 08:08:35'),
(8, '', 'TEST', 'Restore Data User dengan ID USR0002 dan Akun', '', 0, '2021-09-16 08:08:35'),
(9, '', 'TEST', 'Menambahkan Mahasiswa denga ID MHS0001 dan Akunnya', '', 0, '2021-09-16 08:08:35'),
(10, '', 'TEST', 'Hapus Sementara User dengan IDMHS0001 dan Akunnya', '', 0, '2021-09-16 08:08:35'),
(11, '', 'TEST', 'Restore Data User dengan ID MHS0001 dan Akun', '', 0, '2021-09-16 08:08:35'),
(12, '', 'user', 'Edit User', '', 0, '2021-09-16 08:08:35'),
(13, '', 'user', 'Edit Akun', '', 0, '2021-09-16 08:08:35'),
(14, '', 'user', 'Edit User', '', 0, '2021-09-16 08:08:35'),
(15, '', 'user', 'Edit Akun', '', 0, '2021-09-16 08:08:35'),
(16, '', 'USER', 'Edit User', '', 0, '2021-09-16 08:08:35'),
(17, '', 'USER', 'Edit Akun', '', 0, '2021-09-16 08:08:35'),
(18, '', 'USERRR', 'Edit User dengan ID USR0002 beserta akunnya', '', 0, '2021-09-16 08:08:35'),
(19, '', 'USER', 'ubah password dengan ID USR0002', '', 0, '2021-09-16 08:08:35'),
(20, '', 'mahasiswa', 'Edit Mahasiswa dengan ID MHS0001 beserta Akunnya', '', 0, '2021-09-16 08:08:35'),
(21, '', 'mahasiswa', 'ubah password dengan ID MHS0001', '', 0, '2021-09-16 08:08:35'),
(22, '', 'TEST', 'Menambahkan User dengan ID USR0003dan Akunnya ', '', 0, '2021-09-16 08:08:35'),
(23, '', 'TEST', 'Menambahkan User dengan ID USR0004dan Akunnya ', '', 0, '2021-09-16 08:08:35'),
(24, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(25, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Edit Pengajuan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(26, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Edit Pengajuan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(27, '', 'TEST', 'Hapus Sementara Pengaduan dengan ID 2', '', 0, '2021-09-16 08:08:35'),
(28, '', 'TEST', 'Hapus Sementara Pengaduan dengan ID 2', '', 0, '2021-09-16 08:08:35'),
(29, '', 'TEST', 'Hapus Sementara Pengaduan dengan ID 2', '', 0, '2021-09-16 08:08:35'),
(30, '', 'TEST', 'Restore Data Pengajuan dengan ID 2', '', 0, '2021-09-16 08:08:35'),
(31, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(32, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(33, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(34, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(35, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(36, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(37, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(38, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(39, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda tangan kegiatan A', '', 0, '2021-09-16 08:08:35'),
(40, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(41, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(42, '', 'USER', 'User USER Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(43, '', 'prodi', 'User prodi Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(44, '', 'prodi', 'User prodi Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(45, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(46, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(47, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(48, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(49, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(50, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(51, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(52, '', 'prodi', 'User prodi Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(53, '', 'prodi', 'User prodi Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(54, '', 'prodi', 'User prodi Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(55, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(56, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(57, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(58, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(59, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(60, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(61, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(62, '', 'TEST', 'User dengan ID USR0001 MENOLAK pengajuan dengan perihal Tanda Tangan kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(63, '', 'mahasiswa', 'Mahasiswa denga ID MHS0001 Mengajukan berkas dengan perihal Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(64, '', 'TEST', 'User dengan ID USR0001 MENERIMA pengajuan dengan perihal Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(65, '', 'USER', 'User USER Melakukan Tanda Tangan pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(66, '', 'prodi', 'User prodi Melakukan Tanda Tangan pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(67, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(68, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(69, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(70, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(71, '', 'WD 3', 'User WD 3 Melakukan Tanda Tangan pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(72, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(73, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(74, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(75, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(76, '', 'mahasiswa', 'User mahasiswa Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(77, '', 'mahasiswa', 'User mahasiswa Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(78, '', 'mahasiswa', 'User mahasiswa Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(79, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(80, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(81, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(82, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(83, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(84, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(85, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(86, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(87, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(88, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(89, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(90, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(91, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(92, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(93, '', 'TEST', 'User TEST Melakukan verifikasi pada pengajuan Tanda Tangan Kegiatan Seminar', '', 0, '2021-09-16 08:08:35'),
(94, 'USR0001', 'TEST', 'User TEST Melakukan verifikasi Tanda Tangan pada pengajuan Tanda Tangan Kegiatan Seminar dan tanda tangan ter VERIFIED', 'verify', 1, '2021-09-16 08:08:35'),
(95, '', 'TEST', 'Menambahkan User dengan ID USR0005dan Akunnya ', '', 0, '2021-09-16 08:36:53'),
(96, '', 'afit', 'Menambahkan User dengan ID USR0006dan Akunnya ', '', 0, '2021-09-16 08:50:22'),
(97, '', 'TEST', 'Menambahkan User dengan ID USR0007dan Akunnya ', '', 0, '2021-09-16 10:51:05'),
(98, '', 'TEST', 'Menambahkan User dengan ID USR0008dan Akunnya ', '', 0, '2021-09-16 19:15:52'),
(99, '', 'TEST', 'Hapus Sementara User dengan IDUSR0006 dan Akunnya', '', 0, '2021-09-16 20:37:02'),
(100, '', 'TEST', 'Menambahkan User dengan ID USR0009dan Akunnya ', '', 0, '2021-09-16 20:38:44'),
(101, '', 'TEST', 'Restore Data User dengan ID USR0006 dan Akun', '', 0, '2021-09-16 20:39:56'),
(102, '', 'elan', 'Mahasiswa denga ID USR0007 Mengajukan berkas dengan perihal tanda tangan', '', 0, '2021-09-16 20:43:01'),
(103, '', 'elan', 'Mahasiswa denga ID USR0007 Mengajukan berkas dengan perihal dokumen', '', 0, '2021-09-19 16:28:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengajuan`
--

CREATE TABLE `tbl_pengajuan` (
  `id_pengajuan` int(11) NOT NULL,
  `id_lkm_pengajuan` varchar(20) NOT NULL,
  `perihal_pengajuan` varchar(150) NOT NULL,
  `deskripsi_pengajuan` text NOT NULL,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_selesai_pengajuan` datetime DEFAULT NULL,
  `nama_file_pengajuan` varchar(150) NOT NULL,
  `nama_file_verified_pengajuan` varchar(250) DEFAULT NULL,
  `qr_pengajuan` varchar(250) DEFAULT NULL,
  `private_key_pengajuan` varchar(150) NOT NULL,
  `pesan_pengajuan` text DEFAULT NULL,
  `status_pengajuan` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(150) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(150) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_pengajuan`
--

INSERT INTO `tbl_pengajuan` (`id_pengajuan`, `id_lkm_pengajuan`, `perihal_pengajuan`, `deskripsi_pengajuan`, `tanggal_pengajuan`, `tanggal_selesai_pengajuan`, `nama_file_pengajuan`, `nama_file_verified_pengajuan`, `qr_pengajuan`, `private_key_pengajuan`, `pesan_pengajuan`, `status_pengajuan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(14, 'MHS0001', 'Tanda Tangan Kegiatan Seminar', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2021-09-16 08:08:36', NULL, 'Isi_Artikel_185328192270.pdf', 'Isi_Artikel_185328192270_verified_29_08_2021.pdf', 'Isi_Artikel_185328192270_verified_29_08_2021.png', 'e80721793c24ae14edfca9b26ad406a9815cd3ff.pem', '-', 2, '2021-09-16 08:08:36', 'mahasiswa', NULL, NULL, NULL, NULL),
(15, 'USR0007', 'tanda tangan', 'tanda tangan', '2021-09-16 20:43:01', NULL, 'Skripsi_Bab_1-4_Lestari_Aghnia_Rahma_41155050170078_v1.pdf', NULL, NULL, 'e80721793c24ae14edfca9b26ad406a9815cd3ff.pem', NULL, 0, '2021-09-16 20:43:01', 'elan', NULL, NULL, NULL, NULL),
(16, 'USR0007', 'dokumen', 'lalalaal', '2021-09-19 16:28:15', NULL, '2021-09-17_093512.pdf', '-', NULL, 'e80721793c24ae14edfca9b26ad406a9815cd3ff.pem', NULL, 0, '2021-09-19 16:28:15', 'elan', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengajuan_detail`
--

CREATE TABLE `tbl_pengajuan_detail` (
  `id_pengajuan_detail` int(11) NOT NULL,
  `id_pengajuan` int(11) NOT NULL,
  `id_pengesah` varchar(20) NOT NULL,
  `digital_signature` text DEFAULT NULL,
  `qr_code` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_pengajuan_detail`
--

INSERT INTO `tbl_pengajuan_detail` (`id_pengajuan_detail`, `id_pengajuan`, `id_pengesah`, `digital_signature`, `qr_code`, `status`, `deleted_at`, `deleted_by`) VALUES
(31, 14, 'USR0002', 'fWS1IeAzacWjtYjJptG0YRbAZI0KqI68n7u3ArWkua4xgPsjT2EII/VwER3KZXu2kuLtcsjWduQRYNsOd0YGCMr4E9x8+aZaVLOQLi+8mqqWWZDPKJRX4qOMkhjVPlCBocL2r0x7GskU0q7NZawsSA7mCZXLQD4vMlRZ8WqFp46P84rjSlIs8A6nKVzuDzk1NtooUe4bfuFP6CaVzIsIPPQLg7lZmTYULA4ekKjzJ/3+kFXPCLYTS9FyLJhlSV52ZIZujPNZejdjVs6tdP0lyAxYKoJk+SgftttE65zkGiusd1JSqctV148amkc60UkDgLUejLmW7nyjTt5A4cF8cA==', '185c7ea5a4c185be2721c7b5d4b859c01279e9b3.png', 2, NULL, NULL),
(32, 14, 'USR0003', 'B0VAIHvOyGW40usk8YWto3GFMGDnKya9Awc2K0U9Z8Q9n6y4KCCLXwGURr8tXdCmdyVCbnDxDuuc9l/bu1RdZIRK9HRTYv3gPX8lP8eiQ0XzBhSmi04yleaeyWLAu98500VFUABtStu6pZJfAw2pOO6Cqr0XF8DavFgOQHvBMyQcQE18NQWD7BA66O0fJksKxsDwUG9dGoe1/MkDCSPKkXzLVpHnVSPIHK5J8WmJeSBE/kEvq3uZKBBPfMXSBIDWaFoQGBT/40RKH/loRrySWyuCTw+RQFrC/0c2MWvgbw0JbIW80aEkv9EsKB9IvdkMhpQXmKsw5e5wLUti1WOpoQ==', 'ac6b3a69ffd41b82ddb4213defe7cf47be121c04.png', 2, NULL, NULL),
(33, 14, 'USR0004', 'Nyzrer3R6wZtQfD8+qlB68XAS/AjBaV10+aTeaKW6rE51BvFaRwEVzGmYjV5BMze8fcHLLWB/z2S7Iw6ORAQVuRUAPFQC3wSLSCDxeaFIIhY/0A1Uq81N1tF9S3AvA3dpvKCUXqYT85J7inas2tz7ngF8p2CzdXtbWxJnejXlUuSJw/Dx8jT9fnjUdoIS98ikbTRs0C5F/R77iqk6BUOMmPLyHLK6rnJYa940gEu2tUVF59m9u3ENIsCZ3kIdfZrjqekHXtDNYspAaB6ttqSqZEosArnDTfy33JdL+e863Q2+gqkf7OQoop1eJoB/LDTAWduatwJegzgXUk+6wqV3w==', 'c0afc15211b59fb9b02e25f7db1f6f3ef58f969b.png', 2, NULL, NULL),
(34, 15, 'USR0008', NULL, NULL, 0, NULL, NULL),
(35, 16, 'USR0002', NULL, NULL, 0, NULL, NULL),
(36, 16, 'USR0003', NULL, NULL, 0, NULL, NULL),
(37, 16, 'USR0004', NULL, NULL, 0, NULL, NULL),
(38, 16, 'USR0008', NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` varchar(12) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `nip_user` varchar(20) NOT NULL,
  `jabatan_user` varchar(159) NOT NULL,
  `no_hp_user` varchar(12) NOT NULL,
  `digital_signature_user` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `nama_user`, `nip_user`, `jabatan_user`, `no_hp_user`, `digital_signature_user`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('USR0001', 'TEST', '123456789', 'test', '087654325', NULL, '2021-09-16 08:08:36', '', NULL, NULL, NULL, NULL),
('USR0002', 'USER', '123123123123', 'Dekan', '34534543', NULL, '2021-09-16 08:08:36', 'TEST', '2021-08-19 07:03:15', 'USERRR', NULL, NULL),
('USR0003', 'prodi', '1212121212', 'Kaprodi', '08978787878', NULL, '2021-09-16 08:08:36', 'TEST', NULL, NULL, NULL, NULL),
('USR0004', 'WD 3', '999999', 'Wakil Dekan 3', '087545565', NULL, '2021-09-16 08:08:36', 'TEST', NULL, NULL, NULL, NULL),
('USR0005', 'afit', '11222', 'ketua', '212212', NULL, '2021-09-16 08:36:53', 'TEST', NULL, NULL, NULL, NULL),
('USR0006', 'mhs01', '111111', 'mahasiswa', '434242', NULL, '2021-09-16 08:50:22', 'afit', NULL, NULL, NULL, NULL),
('USR0007', 'elan', '43434343', 'mahasiswa', '2323232', NULL, '2021-09-16 10:51:05', 'TEST', NULL, NULL, NULL, NULL),
('USR0008', 'dosen', '232323', 'dosen', '3332222', NULL, '2021-09-16 19:15:52', 'TEST', NULL, NULL, NULL, NULL),
('USR0009', 'afit', '93019301', 'mahasiswa', '93109301', NULL, '2021-09-16 20:38:43', 'TEST', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_level`
--
ALTER TABLE `tbl_level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indeks untuk tabel `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `tbl_pengajuan_detail`
--
ALTER TABLE `tbl_pengajuan_detail`
  ADD PRIMARY KEY (`id_pengajuan_detail`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_level`
--
ALTER TABLE `tbl_level`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_log`
--
ALTER TABLE `tbl_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengajuan_detail`
--
ALTER TABLE `tbl_pengajuan_detail`
  MODIFY `id_pengajuan_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

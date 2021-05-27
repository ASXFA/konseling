-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2021 at 03:43 AM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `konseling`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_akun`
--

CREATE TABLE `tbl_akun` (
  `id_akun` int(5) NOT NULL,
  `username_akun` varchar(30) NOT NULL,
  `password_akun` varchar(150) NOT NULL,
  `induk_akun` varchar(15) NOT NULL,
  `role_akun` int(1) NOT NULL,
  `status_akun` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_akun`
--

INSERT INTO `tbl_akun` (`id_akun`, `username_akun`, `password_akun`, `induk_akun`, `role_akun`, `status_akun`) VALUES
(1, 'testing', '$2y$10$2TQaGv0qXJB.tRlFSlOkweurB4bclw58aOwNWU2MwNGaIs.DK6/Tq', '12345', 1, 1),
(2, 'test2', '$2y$10$udqZTjhjy4LQpcm3nt06zenF8WVx2oUYklTjFwm3TZtbg5dQkUARi', '121212121212121', 3, 1),
(4, '11111', '$2y$10$JBzg7AqJrTFXxjVLLT867uGBAVzvtFsiIu5vHJoycn84nGa4IsSL2', '2222', 2, 0),
(5, '333', '$2y$10$7dq3y5ltiw16UkyDP4SnbefBcmbkcURhKDXnedo4ALOrTv8rD.vum', '999', 3, 0),
(6, '123', '$2y$10$2ziprjjr1mKYIt2rY//e5eP3Lm8fRMC2d8C2RoLauuqiukixRjFba', '123', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id_guru` int(5) NOT NULL,
  `induk_guru` varchar(15) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `jabatan_guru` int(3) NOT NULL,
  `telp_guru` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_guru`
--

INSERT INTO `tbl_guru` (`id_guru`, `induk_guru`, `nama_guru`, `jabatan_guru`, `telp_guru`) VALUES
(3, '2222', 'anu', 2, '08767444'),
(4, '12345', 'testing', 1, '081234123');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jabatan`
--

CREATE TABLE `tbl_jabatan` (
  `id_jabatan` int(3) NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jabatan`
--

INSERT INTO `tbl_jabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'Guru BK'),
(2, 'Guru'),
(3, 'Siswa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jenis_pelanggaran`
--

CREATE TABLE `tbl_jenis_pelanggaran` (
  `id_jenis_pelanggaran` int(3) NOT NULL,
  `kode_jenis_pelanggaran` varchar(20) NOT NULL,
  `nama_jenis_pelanggaran` varchar(150) NOT NULL,
  `kategori_jenis_pelanggaran` varchar(1) NOT NULL,
  `poin_jenis_pelanggaran` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jenis_pelanggaran`
--

INSERT INTO `tbl_jenis_pelanggaran` (`id_jenis_pelanggaran`, `kode_jenis_pelanggaran`, `nama_jenis_pelanggaran`, `kategori_jenis_pelanggaran`, `poin_jenis_pelanggaran`) VALUES
(1, 'P0001', 'Datang terlambat Lebih dari 10 menitt', 'A', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` int(5) NOT NULL,
  `kode_kelas` varchar(20) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `keterangan_kelas` varchar(150) NOT NULL,
  `id_guru_kelas` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `kode_kelas`, `nama_kelas`, `keterangan_kelas`, `id_guru_kelas`) VALUES
(1, 'K0001', '10 AK-3', 'test', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sanksi`
--

CREATE TABLE `tbl_sanksi` (
  `id_sanksi` int(3) NOT NULL,
  `nama_sanksi` varchar(150) NOT NULL,
  `jumlah_poin_sanksi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sanksi`
--

INSERT INTO `tbl_sanksi` (`id_sanksi`, `nama_sanksi`, `jumlah_poin_sanksi`) VALUES
(2, 'Peringatan Langsung  !', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `id_siswa` int(5) NOT NULL,
  `induk_siswa` varchar(15) NOT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `jk_siswa` varchar(15) NOT NULL,
  `alamat_siswa` varchar(150) NOT NULL,
  `id_kelas_siswa` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id_siswa`, `induk_siswa`, `nama_siswa`, `jk_siswa`, `alamat_siswa`, `id_kelas_siswa`) VALUES
(1, '121212121212121', 'testing2', 'Laki-laki', 'jalan ', 1),
(2, '999', 're', 'Perempuan', 'ewrew', 1),
(3, '123', 'siswa', 'Laki-laki', 'kacau\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_walimurid`
--

CREATE TABLE `tbl_walimurid` (
  `id_walimurid` int(3) NOT NULL,
  `nama_walimurid` varchar(200) NOT NULL,
  `induk_siswa_walimurid` varchar(520) NOT NULL,
  `telp_walimurid` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_walimurid`
--

INSERT INTO `tbl_walimurid` (`id_walimurid`, `nama_walimurid`, `induk_siswa_walimurid`, `telp_walimurid`) VALUES
(1, 'asd', '999', '23423423'),
(3, 'xzczx', '121212121212121', '0909');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indexes for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `tbl_jenis_pelanggaran`
--
ALTER TABLE `tbl_jenis_pelanggaran`
  ADD PRIMARY KEY (`id_jenis_pelanggaran`);

--
-- Indexes for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tbl_sanksi`
--
ALTER TABLE `tbl_sanksi`
  ADD PRIMARY KEY (`id_sanksi`);

--
-- Indexes for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `tbl_walimurid`
--
ALTER TABLE `tbl_walimurid`
  ADD PRIMARY KEY (`id_walimurid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  MODIFY `id_akun` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_guru`
--
ALTER TABLE `tbl_guru`
  MODIFY `id_guru` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_jabatan`
--
ALTER TABLE `tbl_jabatan`
  MODIFY `id_jabatan` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_jenis_pelanggaran`
--
ALTER TABLE `tbl_jenis_pelanggaran`
  MODIFY `id_jenis_pelanggaran` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_sanksi`
--
ALTER TABLE `tbl_sanksi`
  MODIFY `id_sanksi` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id_siswa` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_walimurid`
--
ALTER TABLE `tbl_walimurid`
  MODIFY `id_walimurid` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

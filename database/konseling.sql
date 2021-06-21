-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2021 at 02:19 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
-- Table structure for table `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(5) NOT NULL,
  `id_siswa_absensi` int(5) NOT NULL,
  `nilai_absensi` int(5) DEFAULT NULL,
  `id_param_kehadiran_absensi` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `id_siswa_absensi`, `nilai_absensi`, `id_param_kehadiran_absensi`) VALUES
(1, 1, 100, 1),
(2, 2, 65, 2),
(3, 3, 30, 3),
(4, 4, 85, 1),
(5, 5, 65, 2);

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
(1, 'mytest', '$2y$10$vZJhrbgHVvZVLUyZ32n/p.QnrCQ70yxSlt5ubAEDIXqnsmXs17Bne', '12345', 1, 1),
(2, 'test2', '$2y$10$udqZTjhjy4LQpcm3nt06zenF8WVx2oUYklTjFwm3TZtbg5dQkUARi', '121212121212121', 3, 1),
(4, '11111', '$2y$10$GKVLLgpPTaPuIY6GSGQZWO8c0ncj7gpOvTATO1xQoSXKLEytrSRPa', '2222', 2, 1),
(5, '333', '$2y$10$7dq3y5ltiw16UkyDP4SnbefBcmbkcURhKDXnedo4ALOrTv8rD.vum', '999', 3, 0),
(6, '123', '$2y$10$2ziprjjr1mKYIt2rY//e5eP3Lm8fRMC2d8C2RoLauuqiukixRjFba', '123', 3, 1),
(7, '54321', '$2y$10$xXgc5spO93SiIZUL2vct9uOXFJYfxXtxnNVlN/u70iR72ZaOOQaK.', '54321', 3, 0),
(8, '15555', '$2y$10$PjCE9a1rhhVY.thJQ55XGemxFjZsJT794HLrKcN4hlpXmHPLjK35S', '00000', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_catatan_kasus`
--

CREATE TABLE `tbl_catatan_kasus` (
  `id_catatan_kasus` int(5) NOT NULL,
  `id_pelanggaran_catatan_kasus` int(5) NOT NULL,
  `penyelesaian_catatan_kasus` text NOT NULL,
  `evaluasi_catatan_kasus` text NOT NULL,
  `tanggal_catatan_kasus` date NOT NULL,
  `pihak_catatan_kasus` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_catatan_kasus`
--

INSERT INTO `tbl_catatan_kasus` (`id_catatan_kasus`, `id_pelanggaran_catatan_kasus`, `penyelesaian_catatan_kasus`, `evaluasi_catatan_kasus`, `tanggal_catatan_kasus`, `pihak_catatan_kasus`) VALUES
(2, 1, 'Diberi peringatan langsung', 'Tidak akan mengulangi lagi', '2021-06-09', 'wali kelas dan guru bk'),
(3, 2, 'Memberikan peringatan langsung kepada siswa ybs', 'Tidak akan menggunakan perhiasan secara berlebihan', '2021-06-13', 'Guru bk, walikelas'),
(4, 3, 'Memberi peringatan Langsung', 'Berjanji untuk tidak terlambat lagi', '2021-06-13', 'Guru Bk'),
(5, 4, 'Memberikan peringatan Langsung', 'Tidak akan mengulanginya lagi', '2021-06-13', 'Guru bk'),
(6, 18, 'diberi peringatan', 'tidak akan mengulangi', '2021-06-20', 'guru, guru bk ');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `id_guru` int(5) NOT NULL,
  `induk_guru` varchar(15) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `jabatan_guru` int(3) NOT NULL,
  `telp_guru` varchar(12) NOT NULL,
  `foto_guru` varchar(200) NOT NULL DEFAULT 'default-photo.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_guru`
--

INSERT INTO `tbl_guru` (`id_guru`, `induk_guru`, `nama_guru`, `jabatan_guru`, `telp_guru`, `foto_guru`) VALUES
(3, '2222', 'anu', 2, '08767444', 'chadengle.jpg'),
(4, '12345', 'my tests', 1, '0213245332', 'jm_denis.jpg');

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
(2, 'JPBK0001', 'Datang terlambat Lebih dari 10 menit', 'A', 5),
(3, 'JPBK0002', 'Membuat Gaduh dalam kelas', 'A', 5),
(4, 'JPBK0003', 'Mengenakan Perhiasan secara berlebihan', 'A', 5),
(5, 'JPBK0004', 'Keluar kelas saat ganti pelajaran', 'A', 5),
(6, 'JPBK0005', 'Tidak Melaksanakan Piket Kelas', 'A', 5),
(7, 'JPBK0006', 'Baju tidak dimasukkan', 'A', 10),
(8, 'JPBK0007', 'Mencoret Pakaian Seragam', 'A', 10),
(9, 'JPBK0008', 'Rambut tidak diikat bagi perempuan', 'A', 10),
(10, 'JPBK0009', 'Pakaian tidak sesuai dengan ketentuan', 'A', 15);

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
(2, 'K0001', 'Kelas 1A', 'asda', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_param_kehadiran`
--

CREATE TABLE `tbl_param_kehadiran` (
  `id_param_kehadiran` int(5) NOT NULL,
  `nilai_max_param_kehadiran` int(5) NOT NULL,
  `keterangan_param_kehadiran` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_param_kehadiran`
--

INSERT INTO `tbl_param_kehadiran` (`id_param_kehadiran`, `nilai_max_param_kehadiran`, `keterangan_param_kehadiran`) VALUES
(1, 70, 'Baik'),
(2, 55, 'Cukup'),
(3, 54, 'Buruk');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_param_poin`
--

CREATE TABLE `tbl_param_poin` (
  `id_param_poin` int(5) NOT NULL,
  `nilai_max_param_poin` int(5) NOT NULL,
  `keterangan_param_poin` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_param_poin`
--

INSERT INTO `tbl_param_poin` (`id_param_poin`, `nilai_max_param_poin`, `keterangan_param_poin`) VALUES
(1, 30, 'Baik'),
(2, 50, 'Cukup'),
(3, 50, 'Buruk\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelanggaran`
--

CREATE TABLE `tbl_pelanggaran` (
  `id_pelanggaran` int(5) NOT NULL,
  `kode_pelanggaran` varchar(20) NOT NULL,
  `induk_siswa_pelanggaran` varchar(20) NOT NULL,
  `kode_jenis_pel_pelanggaran` varchar(20) NOT NULL,
  `tanggal_pelanggaran` date NOT NULL,
  `keterangan_pelanggaran` text NOT NULL,
  `status_pelanggaran` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pelanggaran`
--

INSERT INTO `tbl_pelanggaran` (`id_pelanggaran`, `kode_pelanggaran`, `induk_siswa_pelanggaran`, `kode_jenis_pel_pelanggaran`, `tanggal_pelanggaran`, `keterangan_pelanggaran`, `status_pelanggaran`) VALUES
(1, 'PSBK0001', '123', 'JPBK0002', '2021-06-09', 'berisik', 1),
(2, 'PSBK0002', '999', 'JPBK0003', '2021-06-13', 'memakai gelang emas\r\n', 1),
(3, 'PSBK0003', '123', 'JPBK0001', '2021-06-13', 'Terlambat 30 menit\r\n', 1),
(4, 'PSBK0004', '123', 'JPBK0003', '2021-06-13', 'Menggunakan kalung emas\r\n', 1),
(5, 'PSBK0005', '121212121212121', 'JPBK0006', '2021-06-14', 'Baju tidak dimasukan', 0),
(6, 'PSBK0006', '123', 'JPBK0007', '2021-06-14', 'curat coret pakaian seragam\r\n', 0),
(7, 'PSBK0007', '999', 'JPBK0008', '2021-06-14', 'rambut digerai\r\n', 0),
(8, 'PSBK0008', '123', 'JPBK0009', '2021-06-14', 'pakaian terlalu kecil', 0),
(9, 'PSBK0009', '123', 'JPBK0009', '2021-06-14', 'celana pencil', 0),
(10, 'PSBK0010', '123', 'JPBK0001', '2021-06-17', 'plo\r\n', 0),
(11, 'PSBK0011', '54321', 'JPBK0009', '2021-06-17', 'xx', 0),
(12, 'PSBK0012', '999', 'JPBK0009', '2021-06-17', 'xzzxc', 0),
(17, 'PSBK0013', '999', 'JPBK0009', '2021-06-17', 'asdas', 0),
(18, 'PSBK0014', '15555', 'JPBK0006', '2021-06-20', 'hjghghg', 1);

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
(2, 'Peringatan Langsung  !', 100),
(3, 'Peringatan tertulis kepada Orangtua / Walimurid', 100),
(4, 'Menghadirkan Orangtua / Walimurid ke sekolah', 150);

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
  `foto_siswa` varchar(200) NOT NULL DEFAULT 'default-siswa.png',
  `id_kelas_siswa` int(3) NOT NULL,
  `poin_siswa` int(5) NOT NULL,
  `status_sanksi_siswa` int(5) NOT NULL,
  `id_param_poin_siswa` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`id_siswa`, `induk_siswa`, `nama_siswa`, `jk_siswa`, `alamat_siswa`, `foto_siswa`, `id_kelas_siswa`, `poin_siswa`, `status_sanksi_siswa`, `id_param_poin_siswa`) VALUES
(1, '121212121212121', 'testing2', 'Laki-laki', 'jalan ', 'default-siswa.png', 1, 10, 2, 1),
(2, '999', 're', 'Perempuan', 'ewrew', 'default-siswa.png', 1, 45, 2, 2),
(3, '123', 'siswaas', 'Perempuan', 'kacau\r\nasdasda', 'arashmil2.jpg', 2, 60, 2, 3),
(4, '54321', 'qwert', 'Laki-laki', 'as', 'default-siswa.png', 2, 15, 2, 1),
(5, '15555', 'contoh', 'Laki-laki', 'alamat', 'default-siswa.png', 2, 10, 2, 1);

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
-- Indexes for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indexes for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indexes for table `tbl_catatan_kasus`
--
ALTER TABLE `tbl_catatan_kasus`
  ADD PRIMARY KEY (`id_catatan_kasus`);

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
-- Indexes for table `tbl_param_kehadiran`
--
ALTER TABLE `tbl_param_kehadiran`
  ADD PRIMARY KEY (`id_param_kehadiran`);

--
-- Indexes for table `tbl_param_poin`
--
ALTER TABLE `tbl_param_poin`
  ADD PRIMARY KEY (`id_param_poin`);

--
-- Indexes for table `tbl_pelanggaran`
--
ALTER TABLE `tbl_pelanggaran`
  ADD PRIMARY KEY (`id_pelanggaran`);

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
-- AUTO_INCREMENT for table `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_akun`
--
ALTER TABLE `tbl_akun`
  MODIFY `id_akun` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_catatan_kasus`
--
ALTER TABLE `tbl_catatan_kasus`
  MODIFY `id_catatan_kasus` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id_jenis_pelanggaran` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_param_kehadiran`
--
ALTER TABLE `tbl_param_kehadiran`
  MODIFY `id_param_kehadiran` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_param_poin`
--
ALTER TABLE `tbl_param_poin`
  MODIFY `id_param_poin` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_pelanggaran`
--
ALTER TABLE `tbl_pelanggaran`
  MODIFY `id_pelanggaran` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_sanksi`
--
ALTER TABLE `tbl_sanksi`
  MODIFY `id_sanksi` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `id_siswa` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_walimurid`
--
ALTER TABLE `tbl_walimurid`
  MODIFY `id_walimurid` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

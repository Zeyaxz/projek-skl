-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 05, 2026 at 03:37 PM
-- Server version: 9.6.0
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SKL`
--
CREATE DATABASE IF NOT EXISTS `SKL` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `SKL`;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `Id_Kategori` char(5) NOT NULL,
  `Kategori` enum('Wajib','Opsional') NOT NULL,
  `Sub_Kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`Id_Kategori`, `Kategori`, `Sub_Kategori`) VALUES
('KTG01', 'Wajib', 'Kurikulum Merdeka Project P5'),
('KTG02', 'Opsional', 'Perlombaan / Kejuaraan / Kompetisi'),
('KTG03', 'Opsional', 'Komunitas Kreatif Siswa'),
('KTG04', 'Wajib', 'Ekstrakurikuler'),
('KTG05', 'Opsional', 'TEFA (Teaching Factory)'),
('KTG06', 'Opsional', 'Organisasi Universitas'),
('KTG07', 'Opsional', 'Penalaran/Karya Ilmiah/Akademik'),
('KTG08', 'Opsional', 'SKL Lainnya');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `Id_Kegiatan` int NOT NULL,
  `Jenis_Kegiatan` varchar(255) NOT NULL,
  `Angka_Kredit` int NOT NULL,
  `Id_Kategori` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`Id_Kegiatan`, `Jenis_Kegiatan`, `Angka_Kredit`, `Id_Kategori`) VALUES
(1, 'Project Gaya Hidup Berkelanjutan', 100, 'KTG01'),
(2, 'Project Kebekerjaan', 2, 'KTG01'),
(3, 'Project Bhineka Tunggal Ika', 1, 'KTG01'),
(4, 'Internasional : Juara 1 ', 7, 'KTG02'),
(5, 'Internasional : Juara 2', 5, 'KTG02'),
(6, 'Internasional : Juara 3', 3, 'KTG02'),
(9, 'Ekstrakurikuler Wajib', 2, 'KTG04'),
(10, 'Ekstrakurikuler Pilihan', 2, 'KTG04'),
(11, 'Project Kearifan Lokal', 1, 'KTG01'),
(12, 'Project Kewirausahaan', 1, 'KTG01'),
(13, 'Project Rekayasa dan Teknologi', 1, 'KTG01'),
(14, 'Bekerja dengan Stake Holder', 5, 'KTG05'),
(15, 'Kewirausahaan', 5, 'KTG05'),
(24, 'Internasional : Harapan 1, 2, 3', 2, 'KTG02'),
(25, 'Internasional : Peserta', 1, 'KTG02'),
(26, 'Nasional : Juara 1', 5, 'KTG02'),
(27, 'Nasional : Juara 2', 4, 'KTG02'),
(28, 'Nasional : Juara 3', 3, 'KTG02'),
(29, 'Nasional : Harapan 1, 2, 3', 2, 'KTG02'),
(30, 'Nasional : Peserta', 1, 'KTG02'),
(31, 'Regional : Juara 1', 4, 'KTG02'),
(32, 'Regional : Juara 2', 3, 'KTG02'),
(33, 'Regional : Juara 3', 2, 'KTG02'),
(34, 'Regional : Harapan 1, 2, 3', 1, 'KTG02'),
(35, 'Regional : Peserta', 1, 'KTG02'),
(56, 'Penulisan Karya Ilmiah/Riset/Buletin/Jurnal', 5, 'KTG07'),
(57, 'Peserta (Seminar, Simposium, Lokakarya, Diskusi Panel)', 2, 'KTG07'),
(58, 'Pelatihan (Penulisan Karya Ilmiah, Kewirausahaan)', 2, 'KTG07'),
(59, 'Pengembangan Bahasa Asing (English) dengan Kegiatan International', 2, 'KTG07'),
(60, 'Bakti Sosial', 2, 'KTG08'),
(62, 'Undangan sebagai Nara Sumber Podcast', 2, 'KTG08');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `NIM` int NOT NULL,
  `No_Absen` int NOT NULL,
  `Nama_mahasiswa` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `No_Telp` varchar(15) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Id_Prodi` char(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Kelas` char(10) NOT NULL,
  `Angkatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`NIM`, `No_Absen`, `Nama_mahasiswa`, `No_Telp`, `Email`, `Id_Prodi`, `Kelas`, `Angkatan`) VALUES
(1111, 1, 'asd', '1', 'a@gmail.com', 'J1', 'A', 2026),
(7024, 1, 'Agus Satya Pardede', '+62856555519', 'satya111@gmail.com', 'J1', '2', 2024),
(7025, 2, 'Andin Pratiwi', '+62878555382', 'andin@gmail.com', 'J1', '1', 2024),
(7026, 3, 'Gede Ardi Dharma Putra', '+62878555383', 'ardida863@gmail.com', 'J1', '1', 2024),
(7027, 4, 'Gede Dhairya Aditama', '+62878555384', 'dhair08@gmail.com', 'J1', '1', 2024),
(7028, 5, 'Ghazy Maulana Pratama', '+62878555385', 'maulana@gmail.com', 'J1', '2', 2024),
(7029, 6, 'Gusti Ngurah Agung Setiawan', '+62878555386', 'agungsetiawa4@gmail.com', 'J2', '3', 2024),
(7030, 7, 'I Gusti Ngurah Andhika Diputra', '+62878555387', 'diputra@gmail.com', 'J2', '3', 2023),
(7031, 8, 'I Gusti Ngurah Arya Wiguna', '+62878555388', 'yogi33@gmail.com', 'J3', '1', 2023),
(7032, 9, 'I Kadek Abiyogi Mandala Satyaki', '+62878555389', 'manda24@gmail.com', 'J4', '2', 2023),
(7033, 10, 'I Kadek Bayu Wiradinata', '+62878555390', 'wiraw32@gmail.com', 'J4', '2', 2023),
(1111111, 1, 'zahra cntik', '1', '1@gmail.com', 'J3', '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `Id_Notifikasi` int NOT NULL,
  `Id_Sertifikat` int NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('baru','dibaca') DEFAULT 'baru',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`Id_Notifikasi`, `Id_Sertifikat`, `pesan`, `status`, `created_at`) VALUES
(1, 17, 'Status sertifikat yang anda upload pada tanggal 2025-03-06 telah berubah menjadi Tidak Valid.', 'dibaca', '2025-04-10 14:49:22'),
(2, 19, 'Status sertifikat yang anda upload pada tanggal 2025-04-05 telah berubah menjadi Valid.', 'dibaca', '2025-04-16 04:42:23'),
(3, 20, 'Status sertifikat yang anda upload pada tanggal 2025-04-17 telah berubah menjadi Tidak Valid.', 'dibaca', '2025-04-17 01:44:01'),
(4, 21, 'Status sertifikat yang anda upload pada tanggal 2026-04-03 telah berubah menjadi Valid.', 'dibaca', '2026-04-03 10:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `Nama_Lengkap` varchar(50) NOT NULL,
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`Nama_Lengkap`, `Username`) VALUES
('asdd', 'asd'),
('adsf', 'asdf'),
('awsiu', 'awsiu'),
('qwe', 'qwe'),
('Putu Yenni Suryantari, S.Pd', 'Yenny');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `Id_Pengguna` int NOT NULL,
  `Username` varchar(20) DEFAULT NULL,
  `NIM` int DEFAULT NULL,
  `Password` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`Id_Pengguna`, `Username`, `NIM`, `Password`) VALUES
(1, 'Yenny', NULL, '$2y$10$lpGypTJdUDU3vEhtbPPXLO//Gp3rhFHsH1B.dh/6OG5ngpnWHQdtW'),
(2, NULL, 7024, '$2y$10$oeZaxP/5Vl5rf0l7wHDofeKsYq3IHOznacW1CmV2OTrKaQvFHEJfG'),
(3, NULL, 7025, '$2y$10$/fah/L/EjDnQvNwcFdrWpuj8C/xJfrQWMivJ2QfMUAJuqoWEJmCgi'),
(4, NULL, 7026, '$2y$10$lWw0unLVI53.plPMNzqyQeqzyZvBr9.Gwsqz44tdlOa0rszgqgBQK'),
(5, NULL, 7027, '$2y$10$tJ5uXlRzwnSw1Kwnq4kTX.wM9fe7rmelDx.egMqatFPDw.cu8Eadi'),
(6, NULL, 7028, '$2y$10$3rQTvOrJL.vHnQJpo8wwQOoLw.cKU8rtKPsutibDxP5ZpYiARVZcm'),
(7, NULL, 7029, '$2y$10$epd/5aK.dzdNrstcho7/ceZXhDdmYoGJ7aW.peII9UEMPvQE9MokO'),
(8, NULL, 7030, '$2y$10$f4nYY0hmrh4zW6qFMzKvc.ubkrjaCiT6Ug3uPnex3rkJYH4ewRej6'),
(9, NULL, 7031, '$2y$10$E8Db6DR0otHv7hTbNQ0HSupYd3eIUgyB6o4co/s1rVVPo4Y2u/CR6'),
(10, NULL, 7032, '$2y$10$a3HhwOJA1k5kc5.9t9rP1eLBSGJQXrxWeMldeylxalgnYlMKO8L7a'),
(11, NULL, 7033, '$2y$10$Btn45UJAy9XA6rF6bpYITOLJNsdykOb4HAduPoRrGliIwAGfSTdUO'),
(12, 'awsiu', NULL, '$2y$10$r3Y1c/3Lxpq6Yt7Jw/us1.ShUJWOHXMjmW6v4doFrhOf8mtKmZtUK'),
(13, 'asdf', NULL, '$2y$10$s//zhveCTB2S88MSpCSWmeu2RfbNSkVNDCc6wpjucQidKDLcGN9Uu'),
(14, 'qwe', NULL, '$2y$10$NXOMfgYywNC0y3XmuBbxo.19N8yoiNDTzm5zlD8ltbYMhuhqQG5ga'),
(15, 'asd', NULL, '$2y$10$ofuy.Vej7hlofCwRl/yizOkNqWZzTCdO8D4wfhFy60SL6QJPZIDRW'),
(17, NULL, 1111111, '$2y$10$hE1aqdurORcLIbJgvuCUw.q7BAv9atRb9yGGC4M46.yU5XtPzBpKW'),
(19, NULL, 1111, '$2y$12$TfEjJ3gHsI4FkLt5ryl82ewk3iJ3t1ySdPxlk7NlVXRvRM3F8WSqS');

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `Id_Prodi` char(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `Prodi` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`Id_Prodi`, `Prodi`) VALUES
('J1', 'IF'),
('J2', 'BD'),
('J3', 'RSK'),
('J4', 'DKV');

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `Id_Sertifikat` int NOT NULL,
  `Tanggal_Upload` date NOT NULL,
  `Catatan` varchar(100) DEFAULT NULL,
  `Sertifikat` varchar(255) NOT NULL,
  `Status` enum('Menunggu Validasi','Tidak Valid','Valid') NOT NULL,
  `Tanggal_Status_Berubah` date DEFAULT NULL,
  `NIM` int NOT NULL,
  `Id_Kegiatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`Id_Sertifikat`, `Tanggal_Upload`, `Catatan`, `Sertifikat`, `Status`, `Tanggal_Status_Berubah`, `NIM`, `Id_Kegiatan`) VALUES
(1, '2024-12-22', NULL, 'demo.pdf', 'Valid', '2025-01-11', 7024, 2),
(2, '2024-12-22', NULL, 'demo.pdf', 'Menunggu Validasi', '2025-01-11', 7024, 3),
(3, '2024-12-23', NULL, 'demo.pdf', 'Valid', '2025-01-12', 7025, 2),
(4, '2024-12-24', NULL, 'demo.pdf', 'Valid', '2025-01-13', 7025, 2),
(5, '2024-12-24', NULL, 'demo.pdf', 'Valid', '2025-01-13', 7026, 3),
(6, '2024-12-25', NULL, 'demo.pdf', 'Valid', '2025-03-06', 7027, 1),
(7, '2024-12-25', NULL, 'demo.pdf', 'Valid', '2025-03-05', 7027, 3),
(8, '2024-12-25', NULL, 'demo.pdf', 'Valid', '2025-01-14', 7027, 2),
(9, '2024-12-26', NULL, 'demo.pdf', 'Valid', '2025-01-15', 7028, 2),
(10, '2024-12-27', NULL, 'demo.pdf', 'Valid', '2025-03-05', 7029, 2),
(11, '2025-01-28', NULL, 'demo.pdf', 'Valid', '2025-01-17', 7030, 5),
(12, '2025-01-29', NULL, 'demo.pdf', 'Tidak Valid', '2025-01-18', 7031, 6),
(15, '2025-02-25', NULL, 'demo.pdf', 'Valid', '2025-03-06', 7029, 1),
(16, '2025-02-26', NULL, 'demo.pdf', 'Valid', NULL, 7029, 1),
(17, '2025-03-06', 'rusak', 'demo.pdf', 'Tidak Valid', '2025-04-10', 7029, 62),
(18, '2024-12-22', NULL, 'demo.pdf', 'Valid', '2025-01-11', 7029, 2),
(19, '2025-04-05', NULL, 'demo.pdf', 'Valid', '2025-04-16', 7029, 9),
(20, '2025-04-17', '', '7029-Ekstrakurikuler-EkstrakurikulerWajib-20250417T093646.pdf', 'Tidak Valid', '2025-04-17', 7029, 9),
(21, '2026-04-03', NULL, '1111-KurikulumMerdekaProjectP5-ProjectGayaHidupBerkelanjutan-20260403T180938.pdf', 'Valid', '2026-04-03', 1111, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`Id_Kategori`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`Id_Kegiatan`),
  ADD KEY `Id_Kategori` (`Id_Kategori`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`NIM`),
  ADD KEY `Id_Jurusan` (`Id_Prodi`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`Id_Notifikasi`),
  ADD KEY `notifikasi_ibfk_2` (`Id_Sertifikat`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`Id_Pengguna`),
  ADD KEY `fk_pengguna_username` (`Username`),
  ADD KEY `pengguna_ibfk_2` (`NIM`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`Id_Prodi`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`Id_Sertifikat`),
  ADD KEY `NIS` (`NIM`),
  ADD KEY `Id_Kegiatan` (`Id_Kegiatan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `Id_Kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `Id_Notifikasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `Id_Pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `Id_Sertifikat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`Id_Kategori`) REFERENCES `kategori` (`Id_Kategori`) ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`Id_Prodi`) REFERENCES `prodi` (`Id_Prodi`) ON UPDATE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_2` FOREIGN KEY (`Id_Sertifikat`) REFERENCES `sertifikat` (`Id_Sertifikat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `fk_pengguna_username` FOREIGN KEY (`Username`) REFERENCES `pegawai` (`Username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pengguna_ibfk_2` FOREIGN KEY (`NIM`) REFERENCES `mahasiswa` (`NIM`) ON UPDATE CASCADE;

--
-- Constraints for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `sertifikat_ibfk_3` FOREIGN KEY (`NIM`) REFERENCES `mahasiswa` (`NIM`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sertifikat_ibfk_4` FOREIGN KEY (`Id_Kegiatan`) REFERENCES `kegiatan` (`Id_Kegiatan`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

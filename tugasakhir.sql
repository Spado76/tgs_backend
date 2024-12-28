-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Des 2024 pada 18.45
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tugasakhir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kanbanprogres`
--

CREATE TABLE `kanbanprogres` (
  `id_item` int(11) NOT NULL,
  `NIM` varchar(15) NOT NULL,
  `nama_item` varchar(255) NOT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_akhir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kanbanprogres`
--

INSERT INTO `kanbanprogres` (`id_item`, `NIM`, `nama_item`, `tgl_mulai`, `tgl_akhir`) VALUES
(20, '123', 'Makan', NULL, NULL),
(21, '123', 'Ngoding Pengajuan', '2024-12-28', '2024-12-29'),
(22, '123', 'Ngoding Proposal', '2024-12-29', NULL),
(23, '500', 'Kerja', NULL, NULL),
(24, '500', 'Ig an', '2024-12-28', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `NIM` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `matkultugasakhir` enum('YA','TIDAK') NOT NULL,
  `totalsks` int(11) NOT NULL CHECK (`totalsks` >= 0),
  `ipk` decimal(3,2) NOT NULL CHECK (`ipk` >= 0.00),
  `dosenpembimbing` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`NIM`, `password`, `nama`, `matkultugasakhir`, `totalsks`, `ipk`, `dosenpembimbing`) VALUES
('123', '123', 'Tirta Suidigma', 'YA', 110, 2.00, 'Emy Susanti'),
('200', '200', 'xinxin', 'TIDAK', 110, 2.00, 'Emy Susanti'),
('225610033', 'awan', 'Ishfaq', 'YA', 118, 3.75, 'Emy Susanti'),
('300', '300', 'Harjo', 'YA', 100, 2.10, 'Emy Susanti'),
('400', '400', 'Sudirman', 'YA', 200, 1.10, 'Emy Susanti'),
('500', '500', 'Dimas Ukin', 'YA', 200, 3.10, 'Roby Cokro Buwono'),
('600', '600', 'Katla Usada', 'YA', 200, 3.10, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuanbimbingan`
--

CREATE TABLE `pengajuanbimbingan` (
  `id_pengajuan` int(11) NOT NULL,
  `NIM` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tgl_bimbingan` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('Belum Disetujui','Disetujui','Ditolak') DEFAULT 'Belum Disetujui',
  `dosenpembimbing` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuanbimbingan`
--

INSERT INTO `pengajuanbimbingan` (`id_pengajuan`, `NIM`, `nama`, `tgl_bimbingan`, `catatan`, `status`, `dosenpembimbing`) VALUES
(1, '123', 'Tirta Suidigma', '2024-12-29', 'adadw', 'Belum Disetujui', 'Emy Susanti'),
(3, '225610033', 'Ishfaq', '2024-12-31', 'ini kenapa tugas kok susah sekali', 'Disetujui', 'Emy Susanti'),
(4, '225610033', 'Ishfaq', '2025-01-02', 'Iya deh', 'Belum Disetujui', 'Emy Susanti'),
(5, '500', 'Dimas Ukin', '2025-01-10', 'Ayo ngopi pak', 'Belum Disetujui', 'Roby Cokro Buwono');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kanbanprogres`
--
ALTER TABLE `kanbanprogres`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `NIM` (`NIM`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`NIM`);

--
-- Indeks untuk tabel `pengajuanbimbingan`
--
ALTER TABLE `pengajuanbimbingan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `NIM` (`NIM`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kanbanprogres`
--
ALTER TABLE `kanbanprogres`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pengajuanbimbingan`
--
ALTER TABLE `pengajuanbimbingan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kanbanprogres`
--
ALTER TABLE `kanbanprogres`
  ADD CONSTRAINT `kanbanprogres_ibfk_1` FOREIGN KEY (`NIM`) REFERENCES `login` (`NIM`);

--
-- Ketidakleluasaan untuk tabel `pengajuanbimbingan`
--
ALTER TABLE `pengajuanbimbingan`
  ADD CONSTRAINT `pengajuanbimbingan_ibfk_1` FOREIGN KEY (`NIM`) REFERENCES `login` (`NIM`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

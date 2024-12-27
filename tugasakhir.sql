-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Des 2024 pada 17.15
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
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `NIM` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `matkultugasakhir` enum('YA','TIDAK') NOT NULL,
  `totalsks` int(11) NOT NULL CHECK (`totalsks` >= 0),
  `ipk` decimal(3,2) NOT NULL CHECK (`ipk` >= 0.00),
  `dosenpembimbing` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`NIM`, `password`, `matkultugasakhir`, `totalsks`, `ipk`, `dosenpembimbing`) VALUES
('123', '123', 'YA', 110, 2.00, 'Emy Susanti'),
('200', '200', 'TIDAK', 110, 2.00, 'Emy Susanti'),
('225610033', 'awan', 'YA', 118, 3.75, 'Emy Susanti'),
('300', '300', 'YA', 100, 2.10, 'Emy Susanti');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`NIM`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

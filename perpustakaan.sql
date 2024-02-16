-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2024 at 10:42 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `perpus_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `perpus_id`, `judul`, `cover`, `penulis`, `penerbit`, `tahun_terbit`, `kategori_id`, `created_at`) VALUES
(7, 0, 'Red Dead Redemption', '', 'Ezzy', 'PT Cahaya ', 2018, 1, '2024-01-29 06:54:05'),
(9, 0, 'Valhalla', '', 'Ezzy', 'PT Cahaya ', 1234, 1, '2024-01-31 09:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `created_ad` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori_buku`
--

INSERT INTO `kategori_buku` (`id`, `nama_kategori`, `created_at`) VALUES
(1, 'Religi', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `koleksi_pribadi`
--

CREATE TABLE `koleksi_pribadi` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `buku` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `buku` int(11) NOT NULL,
  `tanggal_peminjaman` date NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `status_peminjaman` enum('Dipinjam','Dikembalikan','','') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user`, `buku`, `tanggal_peminjaman`, `tanggal_pengembalian`, `status_peminjaman`, `created_at`) VALUES
(1, 1, 1, '2024-01-12', '2024-01-13', 'Dikembalikan', '2024-01-17 02:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `perpustakaan`
--

CREATE TABLE `perpustakaan` (
  `id` int(11) NOT NULL,
  `nama_perpus` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_tlp` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `perpustakaan`
--

INSERT INTO `perpustakaan` (`id`, `nama_perpus`, `alamat`, `no_tlp`, `created_at`) VALUES
(1, 'Perpustakaan Banjar', 'Depan Terminal', '0999022332', '2024-01-17 02:50:30');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan_buku`
--

CREATE TABLE `ulasan_buku` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `buku` int(11) NOT NULL,
  `ulasan` text NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ulasan_buku`
--

INSERT INTO `ulasan_buku` (`id`, `user`, `buku`, `ulasan`, `rating`, `created_at`) VALUES
(16, 16, 7, 'tttt', 1, '2024-01-31 09:54:00'),
(17, 16, 9, 'kiii', 4, '2024-01-31 10:01:49'),
(18, 16, 9, 'yftufyug', 4, '2024-02-01 00:20:47'),
(19, 16, 9, 'tyyuyu', 2, '2024-02-01 00:36:15'),
(20, 16, 7, '&lt;!-- alaahahahahaa', 2, '2024-02-01 03:40:06'),
(21, 16, 9, '&lt;!-- waduhhhhh', 5, '2024-02-01 03:40:31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `perpus_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `role` enum('admin','petugas','peminjam','') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `perpus_id`, `username`, `password`, `email`, `nama_lengkap`, `alamat`, `role`, `created_at`) VALUES
(1, 0, 'iweng', '$2a$12$lzrnN..vMjHnA0VCWa8yFuGT2MeAbulEZXQCKPF4KvF3zjlnC1rR.', 'ridwanbanjar1122@gmail.com', 'Ridwan', 'Pintusinga Banjar', 'admin', '2024-01-17 02:40:22'),
(2, 1, 'adit', '$2y$10$tqyRF.LLf9VP7750L/VC4OhgwVKPEV2E5N59aXPYb4YR1ugaUqUrm', 'adit12@gmail.com', 'Raditya', 'Pintusinga Banjar', 'peminjam', '2024-01-17 03:31:10'),
(3, 1, 'adam', '$2y$10$zmNTg3JeHtPOyt0Veb27qOytoW8tA7lGt3qbIUXlUtt/FRzxn7ZwG', 'adamsp12@gmail.com', 'Adam Suradi', 'Pintusinga Banjar', 'petugas', '2024-01-17 03:31:55'),
(11, 1, 'test', '$2y$10$yezmqa/N4l82KeJRhPcFvOc1GIhtNsjB1iscC3hisU/TGCuHye4Hq', 'sdfdf@gmail.com', 'zahwan', 'waf', 'peminjam', '2024-01-17 03:07:04'),
(13, 1, 'agus', '$2y$10$rM86ZqX5fR/OTgRwW20y9.aFoAfvelbTQK6PipYbxBCLYKrWt2iZ.', 'gusmet@gmail.com', 'Agus selamet', 'Banjar Kolot', 'petugas', '2024-01-17 10:13:46'),
(15, 0, 'Yos', '$2y$10$1ob7/6rxkFERxQs91/20CexmnntVgemtb4MtmndffNTUbD29pW3dO', 'ezi22@gmail.com', 'Yos Sudarso', 'Sumedang', 'peminjam', '2024-01-24 00:36:45'),
(16, 0, 'Ciel', '$2y$10$rxw54H2KW.tDp0OQ9UrRfuKuYhcARenAkcjkOHsvgxNHAH1Z6ZeKK', 'ciel@gmail.com', 'Ciel Dreia', 'Froia', 'admin', '2024-01-24 03:10:02'),
(17, 0, 'zahwan', '$2y$10$PfNpLD7oNylhLNG8Nt.ZwOXyTe5owKiUpDgAYN9hFg3nYtSG7GL3G', 'skylake@gmail.com', 'Sidqi', 'Cikbar', 'petugas', '2024-01-25 01:59:54'),
(18, 0, 'Vanth', '$2y$10$vP/SEQPmuRA7xSEXttyvE.6RMM946/.7zcow5d3CQSvO0z6QPo5Mm', 'vanth@gmail.com', 'Vanth', 'Bulan', 'petugas', '2024-01-25 02:00:27'),
(19, 0, 'Higuruma', '$2y$10$ZpqNDWSfNTbasNtpN1JY6uZK6k7V6JOszraOxtv0lGzL.eVONibUC', 'higuu122@gmail.com', 'Higu', 'Shibuya', 'petugas', '2024-01-26 23:42:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `koleksi_pribadi`
--
ALTER TABLE `koleksi_pribadi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perpustakaan`
--
ALTER TABLE `perpustakaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan_buku`
--
ALTER TABLE `ulasan_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `koleksi_pribadi`
--
ALTER TABLE `koleksi_pribadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `perpustakaan`
--
ALTER TABLE `perpustakaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ulasan_buku`
--
ALTER TABLE `ulasan_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

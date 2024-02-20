-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2024 at 07:57 AM
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
  `deskripsi` text NOT NULL,
  `cover` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `tempat` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `perpus_id`, `judul`, `deskripsi`, `cover`, `penulis`, `penerbit`, `tahun_terbit`, `stok`, `tempat`, `kategori_id`, `created_at`) VALUES
(18, 0, 'Mushoku Tensei', '', '81WEVQiuExL._AC_UF1000,1000_QL80_.jpg', 'Rifujin na Magonote', 'Media Factory', 2013, 0, 0, 2, '2024-02-19 04:01:27'),
(19, 0, 'Ngutang dapet pacar', '', 'FqLba5AWYAMvV9Q.jpg', 'Unknown', 'PT Cahaya ', 2018, 0, 0, 1, '2024-02-12 04:18:32'),
(20, 0, 'Oregairu', '', 'My_Teen_Romantic_Comedy_SNAFU_cover.jpg', 'Hamba Allah', 'Shueisha', 2222, 1, 0, 1, '2024-02-16 06:36:44'),
(21, 0, 'Mashle', '', 'mashle-magic-and-muscles-01e6b0.jpg', 'Honobu Yonezawa', 'Haru', 2018, 0, 0, 1, '2024-02-19 04:03:54'),
(22, 0, 'Roshidere', '', 'Roshidere_light_novel_volume_1_cover.jpg', 'Unknown', 'PT Cahaya ', 9999, 0, 0, 1, '2024-02-12 06:19:55'),
(23, 0, 'Hyouka', '', 'Hyouka_English_poster.jpg', 'Unknown', 'Haru', 1234, 1, 0, 1, '2024-02-10 00:25:13');

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
(1, 'Religi', '0000-00-00 00:00:00'),
(2, 'Romansa', '2024-02-10 05:46:08'),
(3, 'Komedi', '2024-02-10 05:46:08'),
(4, 'Gore', '2024-02-10 06:11:44');

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

--
-- Dumping data for table `koleksi_pribadi`
--

INSERT INTO `koleksi_pribadi` (`id`, `user`, `buku`, `created_at`) VALUES
(31, 20, 18, '2024-02-09 07:04:02'),
(33, 15, 19, '2024-02-19 04:36:41');

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
(23, 15, 18, '2024-02-09', '2024-02-09', 'Dikembalikan', '2024-02-09 06:24:00'),
(24, 15, 18, '2024-02-09', '2024-02-09', 'Dikembalikan', '2024-02-09 06:31:11'),
(25, 15, 18, '2024-02-09', '2024-02-09', 'Dikembalikan', '2024-02-09 06:32:08'),
(26, 15, 18, '2024-02-09', '2024-02-09', 'Dikembalikan', '2024-02-09 06:39:35'),
(27, 15, 18, '2024-02-09', '2024-02-09', 'Dikembalikan', '2024-02-09 06:54:55'),
(28, 15, 18, '2024-02-09', '2024-02-09', 'Dikembalikan', '2024-02-09 06:55:04'),
(29, 15, 18, '2024-02-09', '2024-02-10', 'Dikembalikan', '2024-02-10 00:55:26'),
(30, 15, 19, '2024-02-10', '2024-02-10', 'Dikembalikan', '2024-02-10 00:43:22'),
(31, 15, 19, '2024-02-10', '2024-02-10', 'Dikembalikan', '2024-02-10 00:43:22'),
(32, 15, 19, '2024-02-12', '0000-00-00', 'Dipinjam', '2024-02-12 04:18:32'),
(33, 15, 22, '2024-02-12', '0000-00-00', 'Dipinjam', '2024-02-12 06:19:55'),
(34, 15, 20, '2024-02-13', '2024-02-16', 'Dikembalikan', '2024-02-16 06:36:44'),
(35, 15, 21, '2024-02-19', '0000-00-00', 'Dipinjam', '2024-02-19 04:03:54');

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
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(22) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_code` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`id`, `email`, `reset_code`) VALUES
(1, 'albiafabiansya@gmail.com', '0'),
(2, 'albiafabiansya@gmail.com', 'bea67dc0e009c9c6'),
(3, 'natsukisubaru073@gmail.com', '279174'),
(4, 'natsukisubaru073@gmail.com', '279174'),
(5, 'natsukisubaru073@gmail.com', '279174'),
(6, 'natsukisubaru073@gmail.com', '279174'),
(7, 'natsukisubaru073@gmail.com', '279174'),
(8, 'natsukisubaru073@gmail.com', '279174'),
(9, 'albiafabiansya@gmail.com', '13eb4d2071ef732f'),
(10, 'natsukisubaru073@gmail.com', '279174'),
(11, 'natsukisubaru073@gmail.com', '279174'),
(12, 'natsukisubaru073@gmail.com', '279174'),
(13, 'natsukisubaru073@gmail.com', '279174'),
(14, 'natsukisubaru073@gmail.com', '279174'),
(15, 'natsukisubaru073@gmail.com', '279174'),
(16, 'natsukisubaru073@gmail.com', '279174'),
(17, 'natsukisubaru073@gmail.com', '279174'),
(18, 'albiafabiansya@gmail.com', 'e286442dee91'),
(19, 'natsukisubaru073@gmail.com', '279174'),
(20, 'albiafabiansya@gmail.com', '153975'),
(21, 'natsukisubaru073@gmail.com', '279174'),
(22, 'natsukisubaru073@gmail.com', '279174'),
(23, 'natsukisubaru073@gmail.com', '279174'),
(24, 'natsukisubaru073@gmail.com', '279174'),
(25, 'natsukisubaru073@gmail.com', '279174'),
(26, 'natsukisubaru073@gmail.com', '279174'),
(27, 'natsukisubaru073@gmail.com', '279174'),
(28, 'natsukisubaru073@gmail.com', '279174'),
(29, 'natsukisubaru073@gmail.com', '279174'),
(30, 'albiafabiansyah@gmail.com', '447950'),
(31, 'natsukisubaru073@gmail.com', '279174'),
(32, 'natsukisubaru073@gmail.com', '279174'),
(33, 'natsukisubaru073@gmail.com', '279174'),
(34, 'natsukisubaru073@gmail.com', '279174'),
(35, 'natsukisubaru073@gmail.com', '279174'),
(36, 'natsukisubaru073@gmail.com', '279174');

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
(33, 16, 18, 'aaaa', 3, '2024-02-10 00:25:41'),
(34, 16, 18, 'aaaa', 4, '2024-02-10 00:25:53'),
(35, 16, 18, 'xxxxx', 2, '2024-02-10 00:26:01'),
(36, 16, 18, '1ads', 2, '2024-02-10 00:26:11'),
(37, 16, 18, 'bling bang bang bling bang bang born', 3, '2024-02-10 00:27:58'),
(38, 16, 19, 'jajja', 3, '2024-02-10 00:29:37'),
(39, 16, 19, 'aoako', 5, '2024-02-10 00:29:44'),
(40, 16, 19, 'aku mw', 5, '2024-02-10 00:29:50'),
(41, 16, 19, 'kpn yh', 5, '2024-02-10 00:29:56'),
(42, 16, 19, 'aksdoak', 3, '2024-02-10 00:30:08'),
(43, 16, 20, 'adwad', 3, '2024-02-10 00:30:39'),
(44, 16, 20, 'aefa', 2, '2024-02-10 00:30:47'),
(45, 16, 20, 'afqfec', 1, '2024-02-10 00:30:53'),
(46, 16, 20, 'caca', 1, '2024-02-10 00:30:58'),
(47, 16, 20, 'APA APAAN INI', 1, '2024-02-10 00:31:09'),
(48, 16, 21, 'adadw', 3, '2024-02-10 00:32:00'),
(49, 16, 21, 'f422', 3, '2024-02-10 00:32:08'),
(50, 16, 21, 'acacw', 2, '2024-02-10 00:32:14'),
(51, 16, 21, 'acawc', 2, '2024-02-10 00:32:21'),
(52, 16, 21, 'acaec', 2, '2024-02-10 00:32:27');

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
(15, 0, 'Yos', '$2a$12$2Udg8Y9tbLPXqAJ3bkChYuo6ZOaQzMfCU3tWxcKf69/hPe1XuZUaW', 'ezi22@gmail.com', 'Yos Sudarso', 'Sumedang', 'peminjam', '2024-02-03 06:25:35'),
(16, 0, 'Ciel', '$2y$10$rxw54H2KW.tDp0OQ9UrRfuKuYhcARenAkcjkOHsvgxNHAH1Z6ZeKK', 'ciel@gmail.com', 'Ciel Dreia', 'Froia', 'admin', '2024-01-24 03:10:02'),
(17, 0, 'zahwan', '$2y$10$PfNpLD7oNylhLNG8Nt.ZwOXyTe5owKiUpDgAYN9hFg3nYtSG7GL3G', 'skylake@gmail.com', 'Sidqi', 'Cikbar', 'petugas', '2024-01-25 01:59:54'),
(18, 0, 'Vanth', '$2y$10$vP/SEQPmuRA7xSEXttyvE.6RMM946/.7zcow5d3CQSvO0z6QPo5Mm', 'vanth@gmail.com', 'Vanth', 'Bulan', 'petugas', '2024-01-25 02:00:27'),
(19, 0, 'Higuruma', '$2y$10$ZpqNDWSfNTbasNtpN1JY6uZK6k7V6JOszraOxtv0lGzL.eVONibUC', 'higuu122@gmail.com', 'Higu', 'Shibuya', 'petugas', '2024-01-26 23:42:08'),
(20, 0, 'Vriel', '$2y$10$VZrMSwQg5S8jtCgFvRIAieFHaxRkYmbFjvmxlJaSdYvT7ImgjaE4y', 'vrees@gmail.com', 'vrrrrrrrrrrrr', 'gtw', 'peminjam', '2024-02-04 06:04:09'),
(21, 0, 'albia', '$2y$10$7ale0SCPRsRNMaWbn/erNO2jxcVSXAfqfDf6XQtelpKJWl15QMVcC', 'albiafabiansya@gmail.com', 'Albia Fabiansyah', 'Gang Bawang', 'peminjam', '2024-02-19 14:24:14'),
(22, 0, 'subaru', '$2y$10$ljMqnUKDKYIuz32hhp1lF.vMDKXtigoG/gi5FjI4xp.a2lFrFEQ2S', 'natsukisubaru073@gmail.com', 'Natsuki Subaru', 'Isekai', 'peminjam', '2024-02-20 03:12:25');

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
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `koleksi_pribadi`
--
ALTER TABLE `koleksi_pribadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `perpustakaan`
--
ALTER TABLE `perpustakaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `ulasan_buku`
--
ALTER TABLE `ulasan_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

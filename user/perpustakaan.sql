-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2024 at 07:21 AM
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
  `pdf` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `tahun_terbit` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `perpus_id`, `judul`, `deskripsi`, `cover`, `pdf`, `penulis`, `penerbit`, `tahun_terbit`, `stok`, `kategori_id`, `created_at`) VALUES
(11, 0, 'Otonari No Tenshi', 'Menceritakan seorang siswa SMA yang memberi payung kepada seorang gadis di ayunan', 'otonari.jpg', '', 'Uknown', 'PT Cahaya ', 2019, 3, 4, '2024-02-28 09:43:18'),
(13, 0, 'Janshen', 'Menceritakan seorang anak kecil bernama &quot;Janshen&quot;', '9786022203896.jpg', '', 'Risa', 'Bukune', 2018, 2, 1, '2024-02-28 09:32:56'),
(14, 0, 'William', 'Menceritakan seorang anak kecil bernama &quot;William&quot;', 'William.jpg', '', 'Risa', 'Bukune', 2019, 0, 1, '2024-02-29 03:30:11'),
(15, 0, 'Lord of the ring', 'Fantasy', '72.jpg', '', 'Tolkien', 'PT Cahaya ', 2013, 2, 4, '2024-02-29 03:19:52'),
(16, 0, 'Otonari no Ginga', 'Menceritakan kisah romansa seorang mangaka dengan asistennya', '135041.jpg', '', 'Amagakure Gido', 'Kodansha', 2022, 2, 2, '2024-02-29 06:52:37');

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
(1, 'Horror', '2024-02-21 00:44:19'),
(2, 'Romansa', '2024-02-21 02:36:21'),
(3, 'Komedi', '2024-02-21 02:58:52'),
(4, 'Fantasy', '2024-02-22 01:16:32');

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
(34, 2, 5, '2024-02-24 23:12:24'),
(38, 2, 15, '2024-02-29 01:50:02'),
(40, 2, 14, '2024-02-29 06:13:51');

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
(1, 'natsukisubaru073@gmail.com', '308395'),
(2, 'albiafabiansya@gmail.com', '877853');

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
(18, 1, 11, 'sfgryh', 1, '2024-03-01 02:41:25'),
(19, 2, 11, 'anime nya sangat bagus, bikin diabetes aseli no hoax', 5, '2024-03-01 02:57:50');

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
(1, 1, 'Albia', '$2y$10$2bimVnpPvlEsVbGAlZSc1e5lBO/ZAm0TwEeGQ3awxLNYGy5vohERC', 'albiafabiansya@gmail.com', 'Albia Fabiansyah', 'Banjar', 'admin', '2024-02-29 06:19:00'),
(2, 0, 'Ezzi', '$2y$10$sb9DOI7ESa6oP9eUFrTPFuf.rnsDD70q2znU9j0VsJ/iQLTdCanAS', 'ezi22@gmail.com', 'Fahrezi', 'Sumedang', 'peminjam', '2024-02-21 02:59:53'),
(3, 0, 'ilham ganteng', '$2y$10$YEvqSkR8ODwx9fg6U686qODq1SEn1UlpMd6mnJWdrt0fLSDvnoUEq', 'ciel@gmail.com', 'afgan', 'banjar', 'petugas', '2024-02-21 04:30:27'),
(4, 0, 'MFPB', '$2y$10$DpEY3DHdiSCk5.jPjKxSo.cwOpxPrpT67fjeHczSth4RW19CL.GAW', 'rezzasukma@gmail.com', 'Fahreza', 'duka', 'peminjam', '2024-02-28 02:18:50');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perpustakaan`
--
ALTER TABLE `perpustakaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ulasan_buku`
--
ALTER TABLE `ulasan_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

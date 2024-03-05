-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2024 at 09:56 AM
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
(15, 0, 'Lord of the ring', 'Menceritakan kisah seorang pejuang', '72.jpg', 'The Lord of the Rings 1.pdf', 'Tolkien', 'PT Cahaya ', 2013, 1, 4, '2024-03-05 07:34:43'),
(17, 0, 'Nozomanu Fushi no Boukensha', 'Menceritakan seorang petualang yang mati oleh skeleton dragon di dungeon, dia pikir dia mati di dungeon tapi ternyata dia hidup kembali menajdi undead. Ini adalah kisah seorang petualang yang terpaksa hidup sebagai undead', 'The-Unwanted-Undead-Adventurer.jpeg', '[ Meganei ] Nozom.Fush.no.Bouken VOLUME 001.pdf', 'Yuu Okanu', 'Shōsetsuka ni Narō', 2016, 4, 4, '2024-03-04 04:06:30'),
(18, 0, 'Web Security for Developers: Real Threats, Practical Defense ', 'Web Security for Developers: Real Threats, Practical Defense ', '81AB6UJRLdL._SY425_.jpg', 'Web Security for Developers Real Threats, Practical Defense (Malcolm McDonald) (Z-Library).pdf', 'Malcolm McDonald ', 'Malcolm McDonald ', 2020, 3, 5, '2024-03-05 08:53:45'),
(21, 0, 'Otonari No Tenshi', 'Menceritakan seorang siswa SMA yang memberikan payung pada perempuan yang kehujanan di ayunan', 'otonari.jpg', 'Otonari Tenshi Volume 1 - Kaito Novel - CSNovel.Blogspo.com.pdf', 'Saekisan', 'Media Factory', 2019, 2, 2, '2024-03-05 07:36:05'),
(22, 0, 'Django API with react', 'Cara memulai django API dengan react Full Stack Applications', '41ytrAW0bEL._SX342_SY445_.jpg', 'Beginning Django API with React Build Django 4 Web APIs with React Full Stack Applications (Correa, Daniel  Lim, Greg) (Z-Library).pdf', 'Greg Lim, Daniel Correa', 'Greg Lim, Daniel Correa', 2022, 1, 5, '2024-03-05 08:02:40'),
(23, 0, 'Beginning Rust', 'Belajar pemrograman dengan Rust dengan cara yang mudah dan langkah demi langkah di Unix, shell Linux, macOS, dan baris perintah Windows. Ketika Anda membaca buku ini, Anda akan membangun pengetahuan yang Anda peroleh di bab-bab sebelumnya dan melihat apa yang ditawarkan Rust, mulai dari dasar-dasar Rust, termasuk cara memberi nama objek, mengontrol aliran eksekusi, dan menangani tipe primitif.', '61Jcd5gI-JL._SY425_.jpg', 'Beginning Rust From Novice to Professional (Carlo Milanesi) (Z-Library).pdf', 'Carlo Milaner', 'Apress', 2018, 4, 5, '2024-03-05 08:43:51'),
(24, 0, 'Building REST APIs with Flask', 'Panduan untuk membangun REST APIs dengan library flask python', '611+uPgPOVL._SY425_.jpg', 'Building REST APIs with Flask Create Python Web Services with MySQL (Kunal Relan) (Z-Library).pdf', 'Kunal Relan', 'Apress', 2019, 3, 5, '2024-03-05 08:46:52'),
(25, 0, 'Django Standalone Apps', 'Mengembangkan aplikasi Django mandiri untuk digunakan sebagai blok bangunan yang dapat digunakan kembali untuk proyek Django yang lebih besar. Buku ini mengeksplorasi praktik terbaik untuk menerbitkan aplikasi-aplikasi ini, dengan pertimbangan khusus untuk menguji aplikasi-aplikasi Django, dan strategi untuk mengekstraksi fungsionalitas yang ada ke dalam paket terpisah.', '61JjSsG4IyL._SY425_.jpg', 'Django Standalone Apps Learn to Develop Reusable Django Libraries (Ben Lopatin) (Z-Library).pdf', 'Ben Lopatin', 'Apress', 2020, 2, 5, '2024-03-05 08:48:47'),
(26, 0, 'Fullstack Rust', 'Fullstack Rust memecahkan masalah itu. Dalam buku ini kami menunjukkan kepada Anda cara menggunakan Rust untuk membangun server web yang sangat cepat, membuat alat baris perintah, dan mengkompilasi aplikasi untuk dijalankan di browser dengan Web Assembly (WASM)', 's_hero2x.jpg', 'Fullstack Rust (Nate Murray) (Z-Library).pdf', 'Andrew Weiss, Nate Murray', 'Newline', 2020, 3, 5, '2024-03-05 08:50:59'),
(27, 0, 'Data Structures and Algorithms with Golang', 'Golang adalah salah satu bahasa pemrograman yang paling cepat berkembang di industri perangkat lunak. Kecepatan, kesederhanaan, dan keandalannya menjadikannya pilihan yang sempurna untuk membangun aplikasi yang kuat. Hal ini membuat Anda harus memiliki dasar yang kuat dalam struktur data dan algoritma dengan Go untuk membangun aplikasi yang dapat diskalakan. Dilengkapi dengan tutorial praktis, buku ini akan memandu Anda dalam menggunakan struktur data dan', '61ceIDOCCNL._SY425_.jpg', 'Learn Data Structures and Algorithms with Golang Level up your Go programming skills to develop faster and more efficient code (Bhagvan Kommadi) (Z-Library).pdf', 'Bhagvan Kommadi', 'Pactk', 2019, 5, 5, '2024-03-05 08:52:37');

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
(1, 'Thriller', '2024-03-05 04:04:36'),
(2, 'Romansa', '2024-02-21 02:36:21'),
(3, 'Komedi', '2024-02-21 02:58:52'),
(4, 'Fantasy', '2024-02-22 01:16:32'),
(5, 'Pendidikan', '2024-03-01 22:11:55'),
(6, 'Adventure', '2024-03-05 03:45:13');

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
(44, 2, 17, '2024-03-04 06:05:01'),
(45, 2, 18, '2024-03-05 00:31:52');

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
(4, 0, 'MFPB', '$2y$10$DpEY3DHdiSCk5.jPjKxSo.cwOpxPrpT67fjeHczSth4RW19CL.GAW', 'rezzasukma@gmail.com', 'Fahreza', 'duka', 'peminjam', '2024-02-28 02:18:50'),
(5, 0, 'ciel', '$2y$10$GmOUfO87x3mjMk.JJamstO2SnkDOe7Se83g8JcAYlKDe69uDim3Jy', 'natsukisubaru073@gmail.com', 'Ciel Dreia', 'Banjar', 'peminjam', '2024-03-04 02:24:55'),
(6, 0, 'Vanth', '$2y$10$PmONc63XnRJ.TQiRewZ2lOwZjDtqLsxHuI5.Q7JHVmAPMeNM1.BRC', 'vanth@gmail.com', 'Vanth', 'aaaaa', 'petugas', '2024-03-04 03:55:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `koleksi_pribadi`
--
ALTER TABLE `koleksi_pribadi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

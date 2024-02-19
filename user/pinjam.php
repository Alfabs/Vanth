<?php
session_start();

include '../config.php';
include '../function/func.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); 
    exit();
}

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
$userId = getLoggedInUserID($conn, $username);
checkUserRole($userRole);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $userId = $userId;
    
    // Masukkan tanggal peminjaman (hari ini)
    $tanggalPeminjaman = date('Y-m-d');

    // Set tanggal pengembalian default menjadi 0
    $tanggalPengembalian = '0000-00-00';

    // Cek stok buku sebelum melakukan peminjaman
    $getBookQuery = "SELECT stok FROM buku WHERE id = $bookId";
    $getBookResult = mysqli_query($conn, $getBookQuery);

    if ($getBookResult) {
        $bookData = mysqli_fetch_assoc($getBookResult);
        $stok = $bookData['stok'];

        // Pastikan stok mencukupi sebelum melakukan peminjaman
        if ($stok > 0) {
            // Kurangi stok buku
            $updateStokQuery = "UPDATE buku SET stok = stok - 1 WHERE id = $bookId";
            mysqli_query($conn, $updateStokQuery);

            // Masukkan entri baru ke dalam tabel peminjaman
            $insertPeminjamanQuery = "INSERT INTO `peminjaman` (`user`, `buku`, `tanggal_peminjaman`, `tanggal_pengembalian`, `status_peminjaman`, `created_at`) VALUES ($userId, $bookId, '$tanggalPeminjaman', '$tanggalPengembalian', 'Dipinjam', current_timestamp())";

            if (mysqli_query($conn, $insertPeminjamanQuery)) {
                // Peminjaman berhasil, alihkan kembali pengguna ke halaman utama atau berikan pesan konfirmasi
                header("Location: index.php");
                exit();
            } else {
                // Jika terjadi kesalahan saat melakukan peminjaman, berikan pesan kesalahan atau tangani sesuai kebutuhan
                echo "Error: " . $insertPeminjamanQuery . "<br>" . mysqli_error($conn);
            }
        } else {
            // Jika stok buku tidak mencukupi
            echo "Stok buku tidak mencukupi.";
        }
    } else {
        // Jika gagal mengambil data buku
        echo "Gagal mengambil data buku.";
    }
}

?>

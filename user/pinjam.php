<?php
session_start();

include '../config.php';
include '../function/func.php';

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Tolong login terlebih dahulu'); window.location.href = '../login.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
$userId = getLoggedInUserID($conn, $username);
checkUserRole($userRole);

// Fungsi untuk menghitung jumlah peminjaman yang sudah dilakukan oleh pengguna
function countUserBorrowedBooks($conn, $userId) {
    $countQuery = "SELECT COUNT(*) AS total FROM peminjaman WHERE user = $userId AND status_peminjaman = 'Dipinjam'";
    $result = mysqli_query($conn, $countQuery);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $userId = $userId;
    
    // Periksa apakah pengguna telah mencapai batas maksimal peminjaman (3)
    $borrowedBooksCount = countUserBorrowedBooks($conn, $userId);
    if ($borrowedBooksCount >= 3) {
        echo "Anda telah mencapai batas maksimal peminjaman (3).";
        exit();
    }
    
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

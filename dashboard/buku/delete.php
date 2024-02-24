<?php
// Include file konfigurasi database
include '../../config.php';
include '../../function/func.php';

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);

dataBuku($userRole);
// Cek apakah parameter id telah ada
if (isset($_GET['id'])) {
    $id_buku = $_GET['id'];

    // Query untuk mendapatkan informasi buku sebelum dihapus
    $get_book_query = "SELECT * FROM buku WHERE id = '$id_buku'";
    $get_book_result = mysqli_query($conn, $get_book_query);

    if ($get_book_result) {
        $book_data = mysqli_fetch_assoc($get_book_result);

        // Hapus file cover buku jika ada
        if (!empty($book_data['cover'])) {
            $cover_path = "cover/" . $book_data['cover'];
            unlink($cover_path);
        }

        // Query untuk menghapus buku
        $delete_query = "DELETE FROM buku WHERE id = '$id_buku'";
        $delete_result = mysqli_query($conn, $delete_query);

        // Query untuk menghapus ulasan buku yang terkait
        $delete_reviews_query = "DELETE FROM ulasan_buku WHERE buku = '$id_buku'";
        $delete_reviews_result = mysqli_query($conn, $delete_reviews_query);

        if ($delete_result && $delete_reviews_result) {
            // Redirect kembali ke halaman buku setelah menghapus
            header("Location: index.php");
            exit();
        } else {
            echo "Gagal menghapus buku atau ulasan buku.";
        }
    } else {
        echo "Gagal mendapatkan informasi buku.";
    }
} else {
    echo "ID buku tidak valid.";
}
?>

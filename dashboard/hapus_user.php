<?php
session_start();

include '../config.php';

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
}

// Check if the kategori ID is provided in the URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "User ID tidak ditemukan";
    header("Location: index.php");
    exit();
}

// Get the kategori ID from the URL
$id = $_GET['id'];

// Delete the kategori from the database
$delete_query = "DELETE FROM user WHERE id = $id";
if (mysqli_query($conn, $delete_query)) {
    header("Location: user.php");
    $_SESSION['success'] = "User berhasil dihapus";
    exit();
} else {
    $_SESSION['error'] = "Gagal menghapus user: " . mysqli_error($conn);
}



?>

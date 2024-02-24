<?php
session_start();

include '../../function/func.php';
include '../../config.php';

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch user role from the database based on the username in the session
$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
checkAdminRole($userRole);

// Check if a review ID is provided
if (!isset($_GET['id'])) {
    // Redirect or handle the case when no review ID is provided
    header("Location: ulasan.php");
    exit();
}

$reviewId = $_GET['id'];
$bookId = $_GET['book_id'];

// Perform deletion of the review
$queryDeleteReview = "DELETE FROM ulasan_buku WHERE id = $reviewId";
$resultDeleteReview = mysqli_query($conn, $queryDeleteReview);

if ($resultDeleteReview) {
    // If review deleted successfully, redirect back to the review page with success message
    header("Location: lihat-ulasan.php?id=".$bookId);
    exit();
} else {
    // If deletion failed, redirect back to the review page with error message
    header("Location: ulasan.php?error=1");
    exit();
}
?>

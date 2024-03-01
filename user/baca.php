<?php
session_start();

// Include necessary configuration and function files
include '../config.php';
include '../function/func.php';

// Ensure user has logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); 
    exit();
}

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
checkUserRole($userRole);

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $bookId = $_GET['id'];

        // Query to get book information
        $bookQuery = "SELECT * FROM buku WHERE id = $bookId";
        $bookResult = mysqli_query($conn, $bookQuery);

        // Ensure the book is found
        if (mysqli_num_rows($bookResult) > 0) {
            $bookData = mysqli_fetch_assoc($bookResult);
            $pdfPath = '../dashboard/buku/pdf/' . $bookData['pdf']; // Adjust the path to the PDF file

            // Ensure the PDF file exists
            if (file_exists($pdfPath)) {
                // Set headers to display the PDF inline
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $bookData['judul'] . '.pdf"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');

                // Read and output the PDF file
                @readfile($pdfPath);
                exit();
            } else {
                // If the PDF file is not found, show an error message
                echo "File PDF tidak ditemukan.";
                exit();
            }
        } else {
            // If the book is not found, show an error message
            echo "Buku tidak ditemukan.";
            exit();
        }
    } else {
        // If the id parameter is missing, show an error message
        echo "Parameter id tidak ditemukan.";
        exit();
    }
} else {
    // If the request method is not GET, show an error message
    echo "Metode yang diterima hanya GET.";
    exit();
}
?>
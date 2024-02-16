<?php
require('../../vendor/setasign/fpdf/fpdf.php');
include '../../config.php';

// $username = $_SESSION['username'];
// $userRole = getUserRole($conn, $username);
// dataBuku($userRole);


// Ambil ID peminjaman dari URL
$peminjaman_id = $_GET['id'];

// Buat objek PDF
$pdf = new FPDF();
$pdf->AddPage();

// Tulis konten ke PDF
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Laporan Peminjaman', 0, 1, 'L'); // Align left

// Query untuk mengambil data peminjaman berdasarkan ID
$query = "SELECT p.*, b.judul AS judul_buku, u.nama_lengkap AS nama_peminjam, b.cover 
          FROM peminjaman p
          JOIN buku b ON p.buku = b.id
          JOIN user u ON p.user = u.id
          WHERE p.id = $peminjaman_id";
$result = mysqli_query($conn, $query);

// Cek apakah data peminjaman ditemukan
if (mysqli_num_rows($result) > 0) {
    // Ambil data peminjaman
    $row = mysqli_fetch_assoc($result);

    // Tulis data peminjaman dari database ke PDF
    $pdf->Ln(10); // spasi 10 pt
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(40,10,'Data Peminjaman dengan ID: '.$peminjaman_id, 0, 1, 'L'); // Align left
    $pdf->Ln(5); // spasi 5 pt
    $pdf->Cell(40,8,'ID                                   :', 0, 0, 'L');
    $pdf->Cell(60,8,'       '.$row['id'], 0, 1, 'L');
    $pdf->Cell(40,8,'Judul Buku                     :', 0, 0, 'L');
    $pdf->Cell(60,8,'       '.$row['judul_buku'], 0, 1, 'L');
    $pdf->Cell(40,8,'Nama Peminjam            :', 0, 0, 'L');
    $pdf->Cell(60,8,'       '.$row['nama_peminjam'], 0, 1, 'L');
    $pdf->Cell(40,8,'Tanggal Peminjaman     :', 0, 0, 'L');
    $pdf->Cell(60,8,'       '.$row['tanggal_peminjaman'], 0, 1, 'L');
    $pdf->Cell(40,8,'Tanggal Pengembalian  : ', 0, 0, 'L');
    $pdf->Cell(60,8,'       '.$row['tanggal_pengembalian'], 0, 1, 'L');
    $pdf->Cell(40,8,'Status                             :', 0, 0, 'L');
    $pdf->Cell(60,8,'       '.$row['status_peminjaman'], 0, 1, 'L');
} else {
    $pdf->Ln(10); // spasi 10 pt
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(40,10,'Data peminjaman dengan ID '.$peminjaman_id.' tidak ditemukan.', 0, 1, 'L'); // Align left
}

// Output PDF
$pdf->Output();
?>

<?php
include '../../config.php';

if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];

    // Query to count total rows
    $count_query = "SELECT COUNT(*) AS total_rows
                    FROM peminjaman p
                    JOIN buku b ON p.buku = b.id
                    JOIN user u ON p.user = u.id
                    WHERE b.judul LIKE '%$keyword%' OR u.nama_lengkap LIKE '%$keyword%'";
    $count_result = mysqli_query($conn, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_rows = $count_row['total_rows'];

    // Set limit and offset for pagination
    $limit = 3;
    $total_pages = ceil($total_rows / $limit);
    $page = isset($_POST['page']) && $_POST['page'] > 0 ? $_POST['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Query to search peminjaman data with pagination
    $query = "SELECT p.*, b.judul AS judul_buku, u.nama_lengkap AS nama_peminjam, b.cover 
              FROM peminjaman p
              JOIN buku b ON p.buku = b.id
              JOIN user u ON p.user = u.id
              WHERE b.judul LIKE '%$keyword%' OR u.nama_lengkap LIKE '%$keyword%'
              LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Tampilkan hasil pencarian dalam bentuk tabel
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td><img src="../buku/cover/'.$row['cover'].'" alt="Cover" style="max-width: 100px; max-height: 100px;"></td>';
            echo '<td>'.$row['judul_buku'].'</td>';
            echo '<td>'.$row['nama_peminjam'].'</td>';
            echo '<td>'.$row['tanggal_peminjaman'].'</td>';
            echo '<td>'.$row['tanggal_pengembalian'].'</td>';
            echo '<td>'.($row['status_peminjaman'] == 'Dikembalikan' ? $row['status_peminjaman'] : 'Belum Dikembalikan').'</td>';
            echo '<td><a target="_blank" href="generate-peminjaman.php?id='.$row['id'].'" class="btn btn-success">Generate PDF</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8" class="text-center">Tidak ada data yang ditemukan.</td></tr>';
    }

    // Output pagination HTML
    if ($total_pages > 1) {
        echo '<tr><td colspan="8" class="text-center">';
        echo '<ul class="pagination justify-content-center">';
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<li class="page-item"><a class="page-link" href="#" onclick="searchWithPagination('.$i.')">'.$i.'</a></li>';
        }
        echo '</ul>';
        echo '</td></tr>';
    }
}
?>

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
checkUserRole($userRole);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && isset($_GET['action'])) {
        $bookId = $_GET['id'];
        $action = $_GET['action'];

        // Validasi action
        if ($action !== 'add' && $action !== 'delete') {
            // Jika action tidak valid, bisa ditangani sesuai kebutuhan (contoh: berikan pesan)
            echo "Action tidak valid.";
            exit();
        }

        // Validasi apakah buku dengan ID tertentu ada di database
        // Logika Bookmark
        $checkBookQuery = "SELECT * FROM buku WHERE id = $bookId";
        $checkBookResult = mysqli_query($conn, $checkBookQuery);

        if (mysqli_num_rows($checkBookResult) > 0) {
            // Buku ditemukan, lanjutkan proses bookmark
            if ($action == 'add') {
                // Jika action=add, tambahkan buku ke koleksi pribadi
                $insertQuery = "INSERT INTO koleksi_pribadi (user, buku) VALUES ((SELECT id FROM user WHERE username = '$username'), $bookId)";
                mysqli_query($conn, $insertQuery);
            } elseif ($action == 'delete') {
                // Jika action=delete, hapus buku dari koleksi pribadi
                $deleteQuery = "DELETE FROM koleksi_pribadi WHERE user = (SELECT id FROM user WHERE username = '$username') AND buku = $bookId";
                mysqli_query($conn, $deleteQuery);
            }

            // Redirect kembali ke halaman utama setelah bookmark berhasil ditambahkan atau dihapus
            header("Location: bookmark.php");
            exit();
        } else {
            // Jika buku dengan ID tertentu tidak ditemukan, bisa ditangani sesuai kebutuhan (contoh: berikan pesan)
            echo "Buku dengan ID $bookId tidak ditemukan.";
            exit();
        }
    }
} else {
    // Jika bukan metode GET, bisa ditangani sesuai kebutuhan (contoh: berikan pesan)
    echo "Metode yang diterima hanya GET.";
    exit();
}

// Query untuk mengambil data buku yang di-bookmark oleh user
$query = "SELECT buku.* FROM buku
          INNER JOIN koleksi_pribadi ON buku.id = koleksi_pribadi.buku
          INNER JOIN user ON koleksi_pribadi.user = user.id
          WHERE user.username = '$username'";

$result = mysqli_query($conn, $query);

$checkPeminjamanQuery = "SELECT * FROM peminjaman WHERE user = (SELECT id FROM user WHERE username = '$username')";
$checkPeminjamanResult = mysqli_query($conn, $checkPeminjamanQuery);
$peminjaman = [];
while ($row = mysqli_fetch_assoc($checkPeminjamanResult)) {
    $peminjaman[$row['buku']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bookmark</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="../dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
</head>

<body id="page-top">
    <!-- Page Wrapper -->

    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control bg-light border-0 small"
                                placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['username']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="index.php">
                                    <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Home
                                </a>
                                <a class="dropdown-item" href="peminjaman.php">
                                    <i class="fas fa-handshake fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Peminjaman
                                </a>
                                <a class="dropdown-item" href="bookmark.php">
                                    <i class="far fa-solid fa-heart fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Bookmark
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../dashboard/logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div id="noResultsMessage" class="mt-5 text-center" style="display: none;">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <p>Buku yang Anda cari tidak ditemukan.</p>
                    </div>
                    <div class="row">
                        <?php while ($row = mysqli_fetch_assoc($result)) : 
                            $checkQuery = "SELECT * FROM koleksi_pribadi WHERE user = (SELECT id FROM user WHERE username = '$username') AND buku = {$row['id']}";
                            $checkResult = mysqli_query($conn, $checkQuery);

                            // Cek apakah buku sedang dipinjam
                            $isBorrowed = isset($peminjaman[$row['id']]) && $peminjaman[$row['id']]['status_peminjaman'] === 'Dipinjam';

                            // Tentukan apakah tombol "Pinjam" atau "Kembalikan" yang harus ditampilkan
                            $buttonText = $isBorrowed ? 'Kembalikan' : 'Pinjam';
                            $buttonClass = $isBorrowed ? 'btn-danger' : 'btn-primary';?>
                            <div class="col-lg-3 mb-4 searchable">
                                <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);"  class="card search-result">
                                    <img src="../dashboard/buku/cover/<?php echo $row['cover']; ?>"
                                        style="width: 100%; height: 410px; object-fit: cover;"
                                        class="card-img-top img-fluid" alt="Cover Buku">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $row['judul']; ?></h5>
                                        <p class="card-text"><?php echo $row['penulis']; ?></p>
                                        <p class="card-text"><?php echo $row['penerbit']; ?></p>
                                        <p class="card-text">Tahun Terbit: <?php echo $row['tahun_terbit']; ?></p>
                                        <!-- Tombol Pinjam/Kembalikan -->
                                        <?php if ($isBorrowed) : ?>
                                            <a href="balikin.php?id=<?= $row['id']; ?>&action=return" class="btn <?= $buttonClass; ?>"><?= $buttonText; ?></a>
                                        <?php else : ?>
                                            <a href="<?= $row['stok'] > 0 ? 'pinjam.php?id=' . $row['id'] : '#'; ?>" class="btn <?= $row['stok'] > 0 ? 'btn-primary' :  'btn-secondary'; ?>"><?= $buttonText; ?></a>
                                        <?php endif; ?>
                                        <!-- Tombol Ulasan (hanya muncul jika buku tidak dipinjam) -->
                                        <?php if (!$isBorrowed) : ?>
                                            <a href="lihat-ulasan.php?id=<?= $row['id']; ?>" class="btn btn-success">Lihat Ulasan</a>
                                        <?php endif; ?>
                                        <!-- Tombol Lihat Ulasan (hanya muncul jika buku sudah dipinjam) -->
                                        <?php if ($isBorrowed) : ?>
                                            <a href="ulasan.php?id=<?= $row['id']; ?>" class="btn btn-success">Ulasan</a>
                                        <?php endif; ?>
                                        <a href="bookmark.php?id=<?= $row['id']; ?>&action=delete"
                                            class="btn btn-secondary" onclick="return confirmDelete()">
                                            <i class="fas fa-solid fa-heart"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../dashboard/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../dashboard/vendor/jquery/jquery.min.js"></script>
    <script src="../dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../dashboard/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../dashboard/js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="../dashboard/vendor/chart.js/Chart.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../dashboard/js/demo/chart-area-demo.js"></script>
    <script src="../dashboard/js/demo/chart-pie-demo.js"></script>
    <!-- Custom script for automatic search -->
    <script>
        $(document).ready(function () {
            // Add an input event listener to the search input
            $("#searchInput").on("input", function () {
                let searchTerm = $(this).val().toLowerCase(); // Get the value of the input and convert to lowercase

                // Keep track if any results are found
                let resultsFound = false;

                // Loop through each searchable card
                $(".searchable").each(function () {
                    let cardText = $(this).text().toLowerCase(); // Get the text content of the card and convert to lowercase

                    // Check if the card text contains the search term
                    if (cardText.includes(searchTerm)) {
                        $(this).show(); // If yes, show the card
                        resultsFound = true; // Mark that results are found
                    } else {
                        $(this).hide(); // If no, hide the card
                    }
                });

                // Show/hide the no results message based on resultsFound
                if (resultsFound) {
                    $("#noResultsMessage").hide();
                } else {
                    $("#noResultsMessage").show();
                }
            });
        });
    </script>
</body>

</html>

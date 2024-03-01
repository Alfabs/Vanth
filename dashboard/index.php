<?php
session_start();

include '../function/func.php';
include '../config.php';


if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); 
    exit();
}


$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
dataBuku($userRole);

$query = "SELECT id FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $query);


if ($result) {

    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    
} else {
    echo "Error fetching user ID from the database.";
}

// Hitung jumlah total buku
$queryTotalBuku = "SELECT COUNT(id) AS total_buku FROM buku";
$resultTotalBuku = mysqli_query($conn, $queryTotalBuku);
$totalBuku = 0;
if ($resultTotalBuku) {
    $rowTotalBuku = mysqli_fetch_assoc($resultTotalBuku);
    $totalBuku = $rowTotalBuku['total_buku'];
} else {
    echo "Error fetching total number of books from the database.";
}

// Hitung jumlah total user
$queryTotalUser = "SELECT COUNT(id) AS total_user FROM user";
$resultTotalUser = mysqli_query($conn, $queryTotalUser);
$totalUser = 0;
if ($resultTotalUser) {
    $rowTotalUser = mysqli_fetch_assoc($resultTotalUser);
    $totalUser = $rowTotalUser['total_user'];
} else {
    echo "Error fetching total number of users from the database.";
}

// Hitung jumlah total peminjaman
$queryTotalPeminjaman = "SELECT COUNT(id) AS total_peminjaman FROM peminjaman";
$resultTotalPeminjaman = mysqli_query($conn, $queryTotalPeminjaman);
$totalPeminjaman = 0;
if ($resultTotalPeminjaman) {
    $rowTotalPeminjaman = mysqli_fetch_assoc($resultTotalPeminjaman);
    $totalPeminjaman = $rowTotalPeminjaman['total_peminjaman'];
} else {
    echo "Error fetching total number of borrowings from the database.";
}

// Hitung jumlah total ulasan
$queryTotalUlasan = "SELECT COUNT(id) AS total_ulasan FROM ulasan_buku";
$resultTotalUlasan = mysqli_query($conn, $queryTotalUlasan);
$totalUlasan = 0;
if ($resultTotalUlasan) {
    $rowTotalUlasan = mysqli_fetch_assoc($resultTotalUlasan);
    $totalUlasan = $rowTotalUlasan['total_ulasan'];
} else {
    echo "Error fetching total number of reviews from the database.";
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM buku LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Total number of books
$total_books_query = "SELECT COUNT(*) as total FROM buku";
$total_books_result = mysqli_query($conn, $total_books_query);
$total_books_row = mysqli_fetch_assoc($total_books_result);
$total_books = $total_books_row['total'];

// Total pages
$total_pages = ceil($total_books / $limit);


$current_date = date("Y-m-d");

// Query untuk mengambil jumlah buku yang harus dikembalikan hari ini
$queryBooksToReturn = "SELECT COUNT(id) AS total_books_to_return FROM peminjaman WHERE tanggal_pengembalian = '$current_date' AND status_peminjaman = 'dipinjam'";
$resultBooksToReturn = mysqli_query($conn, $queryBooksToReturn);
$rowBooksToReturn = mysqli_fetch_assoc($resultBooksToReturn);
$totalBooksToReturn = $rowBooksToReturn['total_books_to_return'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <?php if($userRole == "petugas") :?>
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Petugas</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Pendataan
            </div>

            <!-- Nav Item - Pendataan Barang atau Buku -->
            <li class="nav-item">
                <a class="nav-link" href="buku/index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Pendataan Buku</span>
                </a>
            </li>

            <!-- Nav Item - Peminjaman -->
            <li class="nav-item">
                <a class="nav-link" href="peminjaman/">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="peminjaman/laporan.php">
                    <i class="fas fa-print"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a data-toggle="modal" data-target="#logoutModal" class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        
        <?php elseif($userRole == "admin") :?>

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Admin</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Pendataan
            </div>

            <!-- Nav Item - Pendataan Barang atau Buku -->
            <li class="nav-item">
                <a class="nav-link" href="buku/">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Pendataan Buku</span>
                </a>
            </li>

            <!-- Nav Item - Peminjaman -->
            <li class="nav-item">
                <a class="nav-link" href="peminjaman/">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="peminjaman/laporan.php">
                    <i class="fas fa-print"></i>
                    <span>Laporan</span>
                </a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                List Buku
            </div>

            <!-- Nav Item - List Buku -->
            <li class="nav-item">
                <a class="nav-link" href="list/">
                    <i class="fas fa-fw fa-list"></i>
                    <span>List Buku</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Heading -->
            <div class="sidebar-heading">
                Account
            </div>

            <!-- Nav Item - List Buku -->
            <li class="nav-item">
                <a class="nav-link" href="registrasi.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Registrasi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="user.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>

            <li class="nav-item">
                <a data-toggle="modal" data-target="#logoutModal" class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


            <?php endif;?>

        </ul>
        <!-- End of Sidebar -->


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $_SESSION['username']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>

                </nav>


                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Important !</h1>
                    </div>
                    <div class="row">
                         <!-- Buku yang harus dikembalikan hari ini Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Buku yang harus dikembalikan hari ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBooksToReturn ?> buku</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="font-weight-bold text-primary" href="pengembalian.php">Details</a>
                                    <div class="small text-gray-500"><i class="fas fa-chevron-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                    <!-- Pendataan Buku Card Example -->
                        <div  class="col-xl-3 col-md-6 mb-4">
                            <div  class="card border-left-primary shadow h-100 py-2">
                                <div  class="card-body">
                                    <div  class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Buku</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalBuku ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Peminjaman Card Example -->
                        <!-- Content Row -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Peminjaman</div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPeminjaman ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Laporan Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-info text-uppercase mb-1">Total
                                                Ulasan</div>
                                            <div
                                                class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalUlasan ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- List Buku Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total User</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalUser ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <!-- Page Heading -->
                    <h1 class="h3 ml-3 mb-3 text-gray-800">Buku</h1>
    <div class="col-lg-12 mx-auto">
        <table class="table table-hover ">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Cover</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Pengarang</th>
                    <th scope="col">Tahun Terbit</th>
                    <th scope="col">Stok</th>
                    <!-- Tambahkan kolom sesuai dengan struktur tabel buku -->
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) :?>
                    <tr>
                        <td><?=$row['id'];?></td>
                        <td><img src="buku/cover/<?=$row['cover'];?>" alt="Cover" style="max-width: 100px; max-height: 100px;"></td>
                        <td><?=$row['judul'];?></td>
                        <td><?=$row['penulis'];?></td>
                        <td><?=$row['tahun_terbit'];?></td>
                        <td><?=$row['stok'];?></td>
                        <td class="text-center">
                            <a class="badge badge-danger" onclick="return confirm('Yakin Mau Hapus buku?')" href="buku/delete.php?id=<?=$row['id'];?>">Delete</a>
                            <a class="badge badge-success" href="buku/edit.php?id=<?=$row['id'];?>">Edit</a>
                            <a class="badge badge-primary" href="buku/detail.php?id=<?=$row['id'];?>">Detail</a>
                        </td>
                    </tr>
                <?php endwhile;?>
            </tbody>
        </table>
       <!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="justify-content-center pagination">
        <!-- Previous Page Button -->
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?= ($page <= 1) ? 1 : ($page - 1); ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Page Buttons -->
        <?php
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);

        for ($i = $start_page; $i <= $end_page; $i++) :
        ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next Page Button -->
        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
            <a class="page-link" href="?page=<?= ($page >= $total_pages) ? $total_pages : ($page + 1); ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<!-- End Pagination -->


                    

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
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
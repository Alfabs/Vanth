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
dataBuku($userRole);

// Inisialisasi variabel filter dari URL
$status = isset($_GET['status']) ? $_GET['status'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

// Query to fetch peminjaman data joined with buku and user data
$query = "SELECT p.*, b.judul AS judul_buku, u.nama_lengkap AS nama_peminjam, b.cover 
          FROM peminjaman p
          JOIN buku b ON p.buku = b.id
          JOIN user u ON p.user = u.id
          WHERE 1 ";

// Menambahkan filter ke query jika ada
if (!empty($status)) {
    $query .= "AND status_peminjaman = '$status' ";
}

if (!empty($startDate) && !empty($endDate)) {
    $query .= "AND tanggal_peminjaman BETWEEN '$startDate' AND '$endDate' ";
}


// Query untuk menghitung total jumlah hasil setelah diterapkan filter
$countQuery = "SELECT COUNT(*) as total FROM ($query) AS subquery";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalResults = $countRow['total'];

// Tambahkan LIMIT dan OFFSET untuk pagination
$query .= "LIMIT $limit OFFSET $offset";

// Lakukan query SQL
$result = mysqli_query($conn, $query);

// Total pages
$totalPages = ceil($totalResults / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Buku</title>

    <!-- Custom fonts for this template-->
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

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
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
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
                <a class="nav-link" href="../buku/">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Pendataan Barang/Buku</span>
                </a>
            </li>

            <!-- Nav Item - Peminjaman -->
            <li class="nav-item active">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="laporan.php">
                    <i class="fas fa-print"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a data-toggle="modal" data-target="#logoutModal" class="nav-link" href="../logout.php">
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
            <li class="nav-item ">
                <a class="nav-link" href="../index.php">
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
                <a class="nav-link" href="../buku/index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Pendataan Buku</span>
                </a>
            </li>

            <!-- Nav Item - Peminjaman -->
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="laporan.php">
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
                <a class="nav-link" href="../list/">
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
                <a class="nav-link" href="../registrasi.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Registrasi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="../user.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Pengguna</span>
                </a>
            </li>

            <li class="nav-item">
                <a data-toggle="modal" data-target="#logoutModal" class="nav-link" href="../logout.php">
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

                    <!-- Topbar Search -->
                    

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
                                        <input id="searchInput" type="text" class="form-control bg-light border-0 small"
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['username'];?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
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

              <div class="container-fluid">
                <h1 class="h3 mb-3 text-gray-800">Peminjaman</h1>
                <!-- Form untuk filter tanggal -->
                



                <div class="row">
                    <div class="col-lg-12">
                        <!-- Filter Form -->
                    <form method="GET" class="form-row align-items-end" action="">

                            <div class="form-group col-lg-3 mb-3">
                                <label for="">Pilih Status</label>
                                <select class="form-control" name="status">
                                    <option value=""></option>
                                    <option value="Dipinjam" <?php if ($status == 'Dipinjam') echo 'selected'; ?>>Dipinjam</option>
                                    <option value="Dikembalikan" <?php if ($status == 'Dikembalikan') echo 'selected'; ?>>Dikembalikan</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 mb-3">
                                <label for="">Tanggal Peminjaman / Awal</label>
                                <input type="date" class="form-control" name="start_date" value="<?= $startDate ?>">
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal Pengembalian / Akhir</label>
                                <input type="date" class="form-control" name="end_date" value="<?= $endDate ?>">
                            </div>
                            <div class="form-group col-lg-2 mb-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>

                    </form>

                        <table  id="peminjamanTable" class="table table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Cover</th>
                                    <th>Nama Buku</th>
                                    <th>Peminjam</th>
                                    <th>Tgl Peminjaman</th>
                                    <th>Tgl Pengembalian</th>
                                    <th>Status</th>
                                    <th >Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr class="text-center">
                                        <td><?= $row['id']; ?></td>
                                        <td><img src="../buku/cover/<?= $row['cover']; ?>" alt="Cover" style="max-width: 100px; max-height: 100px;"></td>
                                        <td><?= $row['judul_buku']; ?></td>
                                        <td><?= $row['nama_peminjam']; ?></td>
                                        <td><?= $row['tanggal_peminjaman']; ?></td>
                                        <td><?= $row['tanggal_pengembalian']; ?></td>
                                        <td>
                                            <?php if ($row['status_peminjaman'] == 'Dikembalikan') : ?>
                                                <?= $row['status_peminjaman']; ?>
                                            <?php else : ?>
                                                Belum Dikembalikan
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a target="_blank" href="generate-peminjaman.php?id=<?= $row['id']; ?>" class="btn btn-success">Generate PDF</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php if ($totalResults > $limit) : ?>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <!-- Previous Page Button -->
                                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                        <a class="page-link" href="?page=<?= ($page <= 1) ? 1 : ($page - 1); ?>&status=<?= $status ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <!-- Page Buttons -->
                                    <?php
                                    $start_page = max(1, $page - 2);
                                    $end_page = min($totalPages, $page + 2);

                                    for ($i = $start_page; $i <= $end_page; $i++) :
                                    ?>
                                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                            <a class="page-link" href="?page=<?= $i; ?>&status=<?= $status ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>"><?= $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Next Page Button -->
                                    <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                                        <a class="page-link" href="?page=<?= ($page >= $totalPages) ? $totalPages : ($page + 1); ?>&status=<?= $status ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>

    </div>
</div>
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
                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
    <script>
    
</script>




</script>

</body>

</html>
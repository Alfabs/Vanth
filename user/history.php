<?php
session_start();

include '../config.php';
include '../function/func.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); 
    exit();
}

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
checkUserRole($userRole);

$userId = getLoggedInUserID($conn, $username);

// Query untuk mengambil data peminjaman berdasarkan user
$peminjamanQuery = "SELECT buku.id, buku.kategori_id, buku.judul, buku.deskripsi, buku.cover, buku.penulis, buku.penerbit, buku.tahun_terbit, 
                           peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian, peminjaman.status_peminjaman, peminjaman.buku,
                           COUNT(peminjaman.id) as jumlah_peminjaman
                           FROM peminjaman
                           INNER JOIN buku ON peminjaman.buku = buku.id
                           WHERE peminjaman.user = $userId AND peminjaman.status_peminjaman = 'Dikembalikan'
                           GROUP BY buku.id";
// Eksekusi query
$peminjamanResult = mysqli_query($conn, $peminjamanQuery);


// Eksekusi query
$peminjamanResult = mysqli_query($conn, $peminjamanQuery);

// Query to fetch book categories
$categoryQuery = "SELECT id, nama_kategori FROM kategori_buku";
$categoryResult = mysqli_query($conn, $categoryQuery);

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Home</title>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="../dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> -->

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
                <nav style="position: fixed; width: 100%; z-index: 1000; " class=" navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Filter Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Kategori
                        </button>
                        <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);" class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <button class="dropdown-item" onclick="filterBooks(null)">All</button>
                            <?php while ($category = mysqli_fetch_assoc($categoryResult)) : ?>
                                <button class="dropdown-item" onclick="filterBooks(<?php echo $category['id']; ?>)"><?php echo $category['nama_kategori']; ?></button>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Topbar Search -->
                    <form 
                        class=" d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" id="searchInput" class=" form-control bg-light border small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['username'];?></span>
                                <img class="img-profile rounded-circle"
                                    src="../dashboard/img/undraw_profile.svg">
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
                                <a class="dropdown-item" href="history.php">
                                    <i class="fas fa-history fa-sm fa-fw mr-2 text-gray-400"></i>
                                    History
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                
                <!-- End of Topbar -->



                <div class="container-fluid">
                    <h1 style="margin-top: 90px;" class="h3 text-gray-800 text-center">History Buku</h1>

                    <div class="row justify-content-center">
                        <?php while ($row = mysqli_fetch_assoc($peminjamanResult)) : ?>
                            <div class="col-md-8 mb-4"> <!-- Menambahkan kolom untuk membatasi lebar card dan memberikan margin bottom -->
                                <div class="searchable card" data-category-id="<?php echo $row['kategori_id']; ?>" style="box-shadow: 0 4px 20px 0 rgba(0,0,0,0.4);">
                                    <div class="row no-gutters">
                                        <div class="col-md-4">
                                            <img src="../dashboard/buku/cover/<?= $row['cover'];?>" class="card-img" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body mt-4"> <!-- Mengurangi margin top -->
                                                <h5 class="card-title font-weight-bold">Judul : <?=$row['judul'];?></h5>
                                                <p class="card-text">Penulis : <?=$row['penulis'];?></p>
                                                <p class="card-text">Tahun Terbit : <?=$row['tahun_terbit'];?></p>
                                                <p class="card-text">Anda Pernah Meminjam buku ini sebanyak <?=$row['jumlah_peminjaman'];?> kali</p>
                                                <p class="card-text">Deskripsi : <br><?=$row['deskripsi'];?></p>
                                                <a href="detail_history.php?id=<?=$row['id'];?>" class="btn btn-outline-primary me-2">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>


                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            
        </div>
        <!-- End of Content Wrapper -->
        <!-- Footer -->
             <footer style="box-shadow: 0 -7px 17px 0 rgba(0,0,0,0.4);" class="mt-5 footer bg-light text-center py-4 border-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="font-weight-bold">Tentang Kami</h5>
                            <p>Aplikasi perpustakaan digital ini adalah Aplikasi berbasis website 
                                untuk membaca buku secara online dan menyimpan buku secara online</p>
                        </div>
                        <div class="col-md-4">
                            <h5  class="font-weight-bold">Contact Us</h5>
                            <p>Email: aivoice725@gmail.com<br>Phone: +1234567890</p>
                        </div>
                        <div class="col-md-4">
                                <h5 class="font-weight-bold">Account</h5>
                                <a data-toggle="modal" data-target="#logoutModal" href="../dashboard/logout.php" class="btn btn-outline-primary me-2">Logout</a>
                        </div>   
                    </div>
                </div>
                <div class="container mt-3">
                    <p>&copy; 2024 Your Website. All rights reserved.</p>
                </div>
            </footer>
            <!-- End of Footer -->

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
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Custom script for automatic search -->
<script>
    $(document).ready(function(){
        // Add an input event listener to the search input
        $("#searchInput").on("input", function() {
            let searchTerm = $(this).val().toLowerCase(); // Get the value of the input and convert to lowercase

            // Keep track if any results are found
            let resultsFound = false;

            // Loop through each searchable card
            $(".searchable").each(function() {
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



<script>

function filterBooks(categoryId) {
        $(".searchable").each(function() {
            if (categoryId === null || $(this).data('category-id') === categoryId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        // Tampilkan kembali rekomendasi buku setelah melakukan filter
        $(".recommendation").show();
    }

</script>

</body>

</html>
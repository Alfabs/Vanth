<?php
session_start();

include 'config.php';
include 'function/func.php';

if (!isset($_SESSION['username'])) {
} else {
    echo "<script>alert('Anda Sudah login'); window.location.href = 'user/index.php';</script>";
    exit();

}



//Query untuk mengambil data buku
$query = "SELECT * FROM buku";
$result = mysqli_query($conn, $query);



// Query untuk mendapatkan rekomendasi buku dengan rating rata-rata tertinggi
$recommendationQuery = "SELECT buku.id, buku.judul, buku.cover, buku.penerbit, buku.tahun_terbit, buku.stok, buku.penulis, AVG(ulasan_buku.rating) AS avg_rating
                        FROM buku
                        INNER JOIN ulasan_buku ON buku.id = ulasan_buku.buku
                        GROUP BY buku.id
                        ORDER BY avg_rating DESC
                        LIMIT 4";

// Eksekusi query
$recommendationResult = mysqli_query($conn, $recommendationQuery);


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

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> -->

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="dashboard/css/sb-admin-2.min.css" rel="stylesheet">
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

                                <a href="login.php" class="btn btn-primary">Login</a>
                                

                           
                        </li>

                    </ul>

                </nav>

                
                <!-- End of Topbar -->



                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <!-- Tampilkan Rekomendasi Buku -->
                    <div style="margin-top: 100px;" class="row mb-4">
                        <div class="col">
                            <h2 class="text-center">Rekomendasi Buku</h2>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <?php while ($row = mysqli_fetch_assoc($recommendationResult)) : ?>
                            <div class="col-lg-3 mb-3 recommendation">
                                <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);" class="card search-result">
                                    <img src="dashboard/buku/cover/<?php echo $row['cover']; ?>" style="width: 100%; height: 370px; object-fit: cover;" class="card-img-top" alt="Cover Buku">
                                    <div class="card-body">
                                        <h5 class="font-weight-bold card-title"><?php echo $row['judul']; ?></h5>
                                        <p class="card-text"><?php echo $row['penulis']; ?></p>
                                        <p class="card-text"><?php echo $row['penerbit']; ?></p>
                                        <p class="card-text">Tahun Terbit: <?php echo $row['tahun_terbit']; ?></p>
                                        <!-- Tombol Pinjam/Kembalikan -->
                                        <a href="user/pinjam.php?id=<?$row['id'];?>" class="btn btn-primary">Pinjam</a>
                                        <a href="user/ulasan.php?id=<?= $row['id']; ?>" class="btn btn-success">Ulasan</a>
                                        <a class="btn btn-secondary" href="user/bookmark.php?id=<?= $row['id'];?>">
                                            <i class="far fa-bookmark"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <!-- End Rekomendasi Buku -->
                    <!-- Divider -->
                    <hr class="mb-4">
                    <!-- Divider -->
                    <div class=" mb-4">
                        <button type="button" class="btn btn-primary" onclick="filterBooks(null)">All</button>
                        <?php while ($category = mysqli_fetch_assoc($categoryResult)) : ?>
                            <button type="button" class="btn btn-primary" onclick="filterBooks(<?php echo $category['id']; ?>)"><?php echo $category['nama_kategori']; ?></button>
                        <?php endwhile; ?>
                    </div>
                    <div id="noResultsMessage" class="mt-5 text-center" style="display: none; margin-top: 100px;">
                        <i class="fas fa-book fa-3x mb-3"></i>
                        <p>Buku yang Anda cari tidak ditemukan.</p>
                    </div>
                    <div class="row">
                        <!-- Filter buttons -->
                        <!-- Message for no search results -->
                        <?php while ($row = mysqli_fetch_assoc($result)) :?>
                            <div class="col-lg-3 mb-3 searchable" data-category-id="<?php echo $row['kategori_id']; ?>">
                                <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);" class="card search-result">
                                    <img src="dashboard/buku/cover/<?php echo $row['cover']; ?>" style="width: 100%; height: 370px; object-fit: cover;" class="card-img-top" alt="Cover Buku">
                                    <div class="card-body">
                                        <h5 class="font-weight-bold card-title"><?php echo $row['judul']; ?></h5>
                                        <p class="card-text"><?php echo $row['penulis']; ?></p>
                                        <p class="card-text"><?php echo $row['penerbit']; ?></p>
                                        <p class="card-text">Tahun Terbit: <?php echo $row['tahun_terbit']; ?></p>
                                        <a class="btn btn-primary" href="user/pinjam.php?id=<?=$row['id'];?>">Pinjam</a>
                                        <a href="user/ulasan.php?id=<?= $row['id']; ?>" class="btn btn-success">Ulasan</a>
                                        <a class="btn btn-secondary" href="user/bookmark.php?id=<?= $row['id'];?>">
                                            <i class="far fa-bookmark"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;?>
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
                    <a class="btn btn-primary" href="dashboard/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="dashboard/vendor/jquery/jquery.min.js"></script>
    <script src="dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="dashboard/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="dashboard/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="dashboard/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="dashboard/js/demo/chart-area-demo.js"></script>
    <script src="dashboard/js/demo/chart-pie-demo.js"></script>
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
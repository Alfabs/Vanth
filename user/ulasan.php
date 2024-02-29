<?php
include '../config.php';
include '../function/func.php';

session_start();


// Check apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Tolong login terlebih dahulu'); window.location.href = '../login.php';</script>";
    exit();
}




$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
checkUserRole($userRole);

$query = "SELECT id FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    // Fetch the user ID from the result set
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    // Output the user ID (you can use it as needed)
    
} else {
    // Handle the case when the query fails
    echo "Error fetching user ID from the database.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $id_buku = htmlspecialchars($_POST["id_buku"]);
    $id = $userId;
    $ulasan = htmlspecialchars($_POST["ulasan"]);
    $rating = intval($_POST["rating"]); // Mengonversi rating menjadi integer

    // Validasi rating tidak boleh lebih dari 5
    if ($rating > 5) {
        $error_message = "Rating tidak boleh lebih dari 5.";
    } else {
        // Lakukan penyimpanan ulasan dan rating ke database
        $insert_review_query = "INSERT INTO ulasan_buku (buku, user, ulasan, rating, created_at) 
                                VALUES ('$id_buku', '$id', '$ulasan', '$rating', current_timestamp())";

        $insert_review_result = mysqli_query($conn, $insert_review_query);

        if ($insert_review_result) {
            // Setelah penyimpanan berhasil
            $success_message = "Ulasan berhasil dikirim";
        } else {
            // Terdapat kesalahan saat query
            $error_message = "Terjadi kesalahan. Ulasan dan rating tidak dapat disimpan.";
        }
    }
}

// Ambil nilai id_buku dari URL
$id_buku = isset($_GET['id']) ? $_GET['id'] : '';

// Query untuk memeriksa apakah pengguna sudah meminjam buku tersebut
$query_check_borrowed = "SELECT * FROM peminjaman WHERE user = '$userId' AND buku = '$id_buku'";
$result_check_borrowed = mysqli_query($conn, $query_check_borrowed);

// Jika pengguna belum meminjam buku, redirect kembali ke halaman index
if (!$result_check_borrowed || mysqli_num_rows($result_check_borrowed) == 0) {
    echo "<script>alert('Anda belum meminjam buku ini.'); window.location.href = 'index.php';</script>";
    exit();
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

    <title>Ulasan</title>

    <!-- Custom fonts for this template-->
    <link href="../dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">

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
            <div class="row justify-content-center">
                 <div class="col-lg-3">
                            <?php
                            // Query untuk mendapatkan informasi buku
                            $query_buku = "SELECT * FROM buku WHERE id = $id_buku";
                            $result_buku = mysqli_query($conn, $query_buku);

                            if ($result_buku && mysqli_num_rows($result_buku) > 0) {
                                $row_buku = mysqli_fetch_assoc($result_buku);
                                ?>
                                <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);" class="card mb-4">
                                    <img src="../dashboard/buku/cover/<?php echo $row_buku['cover']; ?>"
                                        class="card-img-top " style="width: 100%; height: 410px; object-fit: cover;" alt="Cover Buku">
                                    <div class="card-body">
                                        <h5 class="font-weight-bold card-title"><?php echo $row_buku['judul']; ?></h5>
                                        <p class="card-text">Penulis: <?php echo $row_buku['penulis']; ?></p>
                                        <p class="card-text">Penerbit: <?php echo $row_buku['penerbit']; ?></p>
                                        <p class="card-text">Tahun Terbit: <?php echo $row_buku['tahun_terbit']; ?></p>
                                        
                                    </div>
                                </div>
                            <?php
                            } else {
                                echo "Informasi buku tidak ditemukan.";
                            }
                            ?>
                        </div>
                <div class="col-lg-6">
                    
                    <h1 class="h3 mb-0 text-gray-800">Ulasan dan Rating</h1>
                
            
                    <form class="user" method="POST" action="ulasan.php?id=<?= $id_buku;?>" enctype="multipart/form-data">
                    <!-- Tambahkan pesan sukses jika ada -->
                        <!-- Tambahkan pesan sukses jika ada -->
                        <?php if (isset($success_message)) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $success_message; ?>
                            </div>
                        <?php } ?>
                        <!-- Tambahkan pesan error jika ada -->
                        <?php if (isset($error_message)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php } ?>
                        <input type="hidden" name="id_buku" value="<?php echo $id_buku; ?>">
                        <input type="hidden" name="nama_buku" value="<?php echo $nama_buku; ?>">

                        <div class="form-group">
                            <label for="ulasan">Ulasan:</label>
                            <textarea class="form-control rounded" id="ulasan" name="ulasan" placeholder="Ulasan" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating (1-5):</label>
                            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user ">
                            Kirim Ulasan dan Rating
                        </button>
                        <a href="lihat-ulasan.php?id=<?= $id_buku; ?>" class="btn btn-success btn-user ">
                            Lihat ulasan lain
                        </a>
                        
                        <hr>
                    </form>
                </div>
                       
            </div>

        </div>
        <!-- End Page Content -->


                    

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

</body>

</html>
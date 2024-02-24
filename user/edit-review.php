<?php
session_start();

include '../function/func.php';
include '../config.php';

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Tolong login terlebih dahulu'); window.location.href = '../login.php';</script>";
    exit();
}

// Fetch user role from the database based on the username in the session
$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
checkUserRole($userRole);



$userId = getLoggedInUserID($conn, $username);
$selectedBookId = $_GET['id'];
$selectedReviewId = $_GET['review_id'];




// Fetch review details
$queryReviewDetails = "SELECT * FROM ulasan_buku WHERE id = $selectedReviewId AND user = $userId";

 // Add this line to dump the query details
$resultReviewDetails = mysqli_query($conn, $queryReviewDetails);
$reviewDetails = mysqli_fetch_assoc($resultReviewDetails);

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $ulasan = htmlspecialchars($_POST['ulasan']);
    $rating = htmlspecialchars($_POST['rating']);

    $update = "UPDATE ulasan_buku SET ulasan = '$ulasan', rating = '$rating' WHERE id = $selectedReviewId";
    $updateResult = mysqli_query($conn, $update);

    if ($updateResult) {
        // Pembaruan buku berhasil
        header('location: lihat-ulasan.php?id='.$selectedBookId);
        $success_message = "Pembaruan berhasil";
    } else {
        // Terdapat kesalahan saat query
        $error_message = "Terjadi kesalahan. Silakan coba lagi.";
    }

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
                            $query_buku = "SELECT * FROM buku WHERE id = $selectedBookId";
                            $result_buku = mysqli_query($conn, $query_buku);
                            
                            if ($result_buku && mysqli_num_rows($result_buku) > 0) {
                                $row_buku = mysqli_fetch_assoc($result_buku);
                                
                                ?>
                                <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);" class="card mb-4">
                                    <img src="../dashboard/buku/cover/<?php echo $row_buku['cover']; ?>"
                                        class="card-img-top " style="width: 100%; height: 370px; object-fit: cover;" alt="Cover Buku">
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
                
            
                    <form class="user" method="POST" action="edit-review.php?id=<?= $selectedBookId ?>&review_id=<?= $selectedReviewId; ?>" enctype="multipart/form-data">
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
                        <input type="hidden" name="id_buku" value="<?php echo $selectedBookId; ?>">


                        <div class="form-group">
                            <label for="ulasan">Ulasan:</label>
                            <textarea class="form-control rounded" id="ulasan" name="ulasan" placeholder="Ulasan" required><?php echo $reviewDetails['ulasan']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating (1-5):</label>
                            <input type="number" value="<?php echo $reviewDetails['rating']; ?>" class="form-control" id="rating" name="rating" min="1" max="5" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user ">
                            Kirim Ulasan dan Rating
                        </button>
                        <a href="lihat-ulasan.php?id=<?= $selectedBookId; ?>" class="btn btn-success btn-user ">
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
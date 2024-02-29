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

// Check if a book ID is provided
if (!isset($_GET['id'])) {
    // Redirect or handle the case when no book ID is provided
    header("Location: ulasan.php");
    exit();
}

$userId = getLoggedInUserID($conn, $username);
$selectedBookId = $_GET['id'];

// Fetch book details
$queryBookDetails = "SELECT judul, penulis, penerbit FROM buku WHERE id = $selectedBookId";
$resultBookDetails = mysqli_query($conn, $queryBookDetails);
$bookDetails = mysqli_fetch_assoc($resultBookDetails);

// Check if book details are available
if (!$bookDetails) {
    // Redirect or handle the case when no book details are found
    header("Location: ../../index.php");
    exit();
}

// Fetch book reviews with pagination
$reviewsPerPage = 2;
$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentpage - 1) * $reviewsPerPage;

// Check if the "Ulasan Saya" filter is applied
if (isset($_GET['user_reviews']) && $_GET['user_reviews'] == 1) {
    $queryReviews = "SELECT user.id, user.nama_lengkap, ulasan_buku.user, ulasan_buku.ulasan, ulasan_buku.rating, ulasan_buku.id
                     FROM ulasan_buku
                     INNER JOIN user ON ulasan_buku.user = user.id
                     WHERE ulasan_buku.buku = $selectedBookId AND ulasan_buku.user = $userId
                     LIMIT $offset, $reviewsPerPage";

    // Set the button text to "Reset" since the filter is applied
    $buttonText = "Reset";
} else {
    // Fetch all reviews if no filter is applied
    $queryReviews = "SELECT user.id, user.nama_lengkap, ulasan_buku.user, ulasan_buku.ulasan, ulasan_buku.rating, ulasan_buku.id
                     FROM ulasan_buku
                     INNER JOIN user ON ulasan_buku.user = user.id
                     WHERE ulasan_buku.buku = $selectedBookId
                     LIMIT $offset, $reviewsPerPage";

    // Set the button text to "Ulasan Saya" since the filter is not applied
    $buttonText = "Ulasan Saya";
}

$resultReviews = mysqli_query($conn, $queryReviews);

// Get total reviews
if (isset($_GET['user_reviews']) && $_GET['user_reviews'] == 1) {
    // If "My Reviews" filter is applied
    $queryTotalReviews = "SELECT COUNT(*) AS total FROM ulasan_buku WHERE buku = $selectedBookId AND user = $userId";
} else {
    // If no filter is applied
    $queryTotalReviews = "SELECT COUNT(*) AS total FROM ulasan_buku WHERE buku = $selectedBookId";
}

$resultTotalReviews = mysqli_query($conn, $queryTotalReviews);
$rowTotalReviews = mysqli_fetch_assoc($resultTotalReviews);
$totalReviews = $rowTotalReviews['total'];

// Calculate total pages
$totalPages = ceil($totalReviews / $reviewsPerPage);

// // Query to check if the user has borrowed the book
// $query_check_borrowed = "SELECT * FROM peminjaman WHERE user = '$userId' AND buku = '$selectedBookId' AND status_peminjaman = 'Dipinjam'";
// $result_check_borrowed = mysqli_query($conn, $query_check_borrowed);

// // If the user has not borrowed the book, redirect back to the index page
// if (!$result_check_borrowed || mysqli_num_rows($result_check_borrowed) == 0) {
//     echo "<script>alert('Anda belum meminjam buku ini.'); window.location.href = 'index.php';</script>";
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lihat Ulasan</title>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
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
    <div class="container">
        <!-- Row for Book Details and Cover -->
        <div class="row justify-content-center ">
            <div class="col-lg-3">
                
                <?php
                // Fetch book details
                $queryBookDetails = "SELECT judul, penulis, penerbit, deskripsi, cover FROM buku WHERE id = $selectedBookId";
                $resultBookDetails = mysqli_query($conn, $queryBookDetails);
                if ($resultBookDetails && mysqli_num_rows($resultBookDetails) > 0) {
                    $rowBookDetails = mysqli_fetch_assoc($resultBookDetails);
                    ?>
                    <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.4);" class="card mb-4">
                        <img src="../dashboard/buku/cover/<?php echo $rowBookDetails['cover']; ?>"
                            class="card-img-top" style="width: 100%; height: 370px; object-fit: cover;" alt="Cover Buku">
                        <div class="card-body ">
                            <h5 class="font-weight-bold card-title"><?php echo $rowBookDetails['judul']; ?></h5>
                            <p class="card-text">Deskripsi : <br> <?php echo $rowBookDetails['deskripsi']; ?></p>

                        </div>
                    </div>
                <?php
                } else {
                    echo "Informasi buku tidak ditemukan.";
                }
                ?>
            </div>
            <!-- Column for Book Reviews -->
            <div class="col-lg-5">
                <h1 class="h3 mb-4 text-gray-800"><?= $bookDetails['judul']; ?></h1>
                <p class="mb-4 ">
                    <strong>Penulis:</strong> <?= $bookDetails['penulis']; ?> |
                    <strong>Penerbit:</strong> <?= $bookDetails['penerbit']; ?>
                </p>
                
                <!-- Filter button -->
                <a href="?id=<?= $selectedBookId ?>&user_reviews=<?= isset($_GET['user_reviews']) && $_GET['user_reviews'] == 1 ? 0 : 1; ?>"
                    class="btn btn-primary mb-2"><?= $buttonText; ?></a>

                <!-- Reviews Section -->
<?php while ($rowReview = mysqli_fetch_assoc($resultReviews)) : ?>
    <div style="box-shadow: 0 4px 17px 0 rgba(0,0,0,0.3);" class="card mb-4 ">
        <div class="card-body">
            <!-- Check if the review belongs to the logged-in user -->
            <?php if ($rowReview['user'] == $userId) : ?>
                <!-- Edit and delete buttons -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title"><?= $rowReview['nama_lengkap']; ?></h5>
                    <div>
                        <a href="edit-review.php?id=<?= $selectedBookId ?>&review_id=<?= $rowReview['id']; ?>" class="btn btn-sm btn-primary mr-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button data-review-id="<?= $rowReview['id']; ?>" data-book-id="<?= $selectedBookId; ?>" class="delete-review-btn btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php else : ?>
                <!-- Show user's name -->
                <h5 class="card-title"><?= $rowReview['nama_lengkap']; ?></h5>
            <?php endif; ?>
            <p class="card-text"><?= $rowReview['ulasan']; ?></p>
            <p class="card-text"><strong>Rating:</strong> <?= $rowReview['rating']; ?></p>
        </div>
    </div>
<?php endwhile; ?>

                <ul class="pagination justify-content-center">
    <?php if ($currentpage > 1) : ?>
        <li class="page-item">
            <a class="page-link" href="?id=<?= $selectedBookId ?>&page=<?= $currentpage - 1; ?>&user_reviews=<?= isset($_GET['user_reviews']) ? $_GET['user_reviews'] : '' ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    <?php endif; ?>
    <?php
    $startPage = max(1, $currentpage - 2);
    $endPage = min($startPage + 4, $totalPages);
    for ($i = $startPage; $i <= $endPage; $i++) : ?>
        <li class="page-item <?php echo ($i == $currentpage) ? 'active' : ''; ?>">
            <a class="page-link" href="?id=<?= $selectedBookId ?>&page=<?= $i; ?>&user_reviews=<?= isset($_GET['user_reviews']) ? $_GET['user_reviews'] : '' ?>">
                <?= $i; ?>
            </a>
        </li>
    <?php endfor; ?>
    <?php if ($currentpage < $totalPages) : ?>
        <li class="page-item">
            <a class="page-link" href="?id=<?= $selectedBookId ?>&page=<?= $currentpage + 1; ?>&user_reviews=<?= isset($_GET['user_reviews']) ? $_GET['user_reviews'] : '' ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    <?php endif; ?>
</ul>

                
        </div>
    </div>
</div>
<!-- End Page Content -->

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
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Page level custom scripts -->
    <script src="../dashboard/js/demo/chart-area-demo.js"></script>
    <script src="../dashboard/js/demo/chart-pie-demo.js"></script>
    <script>
    // Ketika tombol hapus diklik
    $('.delete-review-btn').click(function() {
        // Dapatkan ID ulasan yang akan dihapus
        let reviewId = $(this).data('review-id');
        let bookId = $(this).data('book-id');

        // Tampilkan konfirmasi SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Ulasan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // Jika pengguna menekan tombol Ya, hapus
            if (result.isConfirmed) {
                // Redirect to delete_review.php with the review ID and book ID as parameters
                window.location.href = 'delete-review.php?id='+ bookId +'&review_id=' + reviewId;
            }
        });
    });
</script>
</body>

</html>
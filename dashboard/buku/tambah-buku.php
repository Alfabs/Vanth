<?php
include '../../config.php';
include '../../function/func.php';

session_start();

// Check apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php"); // Redirect ke halaman login jika belum login
    exit();
}

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);
dataBuku($userRole);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $judul = htmlspecialchars($_POST["judul"]);
    $penulis = htmlspecialchars($_POST["penulis"]);
    $penerbit = htmlspecialchars($_POST["penerbit"]);
    $tahun_terbit = htmlspecialchars($_POST["tahun_terbit"]);
    $kategori_id = htmlspecialchars($_POST["kategori_id"]);
    $stok = htmlspecialchars($_POST["stok"]);
    $deskripsi = htmlspecialchars($_POST["deskripsi"]);
    $tempat = htmlspecialchars($_POST["tempat"]);

    // Lakukan validasi dan penambahan buku
if (!empty($judul) && !empty($penulis) && !empty($penerbit) && !empty($tahun_terbit) && !empty($kategori_id)) {
    // Query untuk memeriksa apakah buku sudah ada
    $check_book_query = "SELECT * FROM `buku` WHERE `judul` = '$judul' AND `penulis` = '$penulis' AND `penerbit` = '$penerbit' AND `tahun_terbit` = '$tahun_terbit' AND `kategori_id` = '$kategori_id'";
    $result_check_book = mysqli_query($conn, $check_book_query);
    
    if (mysqli_num_rows($result_check_book) > 0) {
        // Buku sudah ada, tampilkan pesan error
        $error_message = "Buku ini sudah ada.";
    } else {
        // Buku belum ada, lanjutkan dengan penambahan buku
        $perpus_id = 0; 
        $cover = $_FILES['cover'];
        $cover_path = 'cover/';

        if (move_uploaded_file($cover['tmp_name'], $cover_path . $cover['name'])) {
            // File cover berhasil diunggah, simpan data buku ke database
            $cover_filename = $cover['name'];

        $add_book_query = "INSERT INTO `buku` (`perpus_id`, `judul`, `deskripsi`, `cover`, `penulis`, `penerbit`, `tahun_terbit`, `stok`, `tempat`, `kategori_id`, `created_at`)
                               VALUES ('$perpus_id', '$judul', '$deskripsi', '$cover_filename', '$penulis', '$penerbit', '$tahun_terbit', '$stok', '$tempat', '$kategori_id',  current_timestamp())";

            // Eksekusi query dan tampilkan pesan sukses atau error
            if (mysqli_query($conn, $add_book_query)) {
                header("Location: index.php");
                $_SESSION['success'] = "Buku berhasil ditambahkan.";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        } else {
            $error_message = "Error uploading cover file.";
        }
    }
} else {
    $error_message = "Semua kolom harus diisi.";
}

}

// Query untuk mengambil data kategori buku dari database
$get_kategori_query = "SELECT * FROM kategori_buku";
$result_kategori = mysqli_query($conn, $get_kategori_query);

// Inisialisasi variabel untuk menyimpan opsi kategori buku
$options_kategori = '';

// Memproses hasil query kategori buku menjadi opsi HTML
if (mysqli_num_rows($result_kategori) > 0) {
    while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
        $options_kategori .= '<option value="' . $row_kategori['id'] . '">' . $row_kategori['nama_kategori'] . '</option>';
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

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
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
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Pendataan Buku</span>
                </a>
            </li>

            <!-- Nav Item - Peminjaman -->
            <li class="nav-item">
                <a class="nav-link" href="../peminjaman/">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="../peminjaman/laporan.php">
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
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Pendataan Buku</span>
                </a>
            </li>

            <!-- Nav Item - Peminjaman -->
            <li class="nav-item">
                <a class="nav-link" href="../peminjaman/">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="../peminjaman/laporan.php">
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

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $_SESSION['username']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
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
    <!-- Form Tambah Buku -->
<div class="container-fluid col-lg-6">
    <div class="d-sm-flex justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Buku</h1>
    </div>

    <form class="user" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
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
        
        <div class="form-group">
            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="judul" name="deskripsi" placeholder="Deskripsi" required>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="judul" name="tempat" placeholder="Tempat" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Penulis" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Penerbit" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Tahun Terbit" required>
        </div>
        <div class="form-group">
            <label for="kategori_id">Kategori:</label>
            <select class="form-control" id="kategori_id" name="kategori_id">
                <?php echo $options_kategori; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="stok">Stok Buku</label>
            <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" required>
        </div>
        <div class="form-group">
            <label for="cover">Cover Buku:</label>
            <div class="custom-file">
                <input id="cover" name="cover" accept="image/*" required type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
            </div>
            <!-- Untuk menampilkan preview cover -->
            <img id="cover-preview" class="mt-2" style="max-width: 200px; max-height: 200px; display: none;">
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            Tambah Buku
        </button>
        <hr>
    </form>
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
                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
    // Function untuk menampilkan preview cover
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#cover-preview').attr('src', e.target.result).show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Event listener untuk input file
    $("#cover").change(function () {
        readURL(this);
    });
</script>


</body>

</html>
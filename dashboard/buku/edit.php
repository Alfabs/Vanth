<?php
include '../../config.php';
include '../../function/func.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

$username = $_SESSION['username'];
$userRole = getUserRole($conn, $username);

dataBuku($userRole);

// If book ID is sent via URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve book data from the database
    $get_book_query = "SELECT * FROM buku WHERE id = $id";
    $get_book_result = mysqli_query($conn, $get_book_query);

    if ($get_book_result) {
        $book_data = mysqli_fetch_assoc($get_book_result);
    } else {
        $error_message = "Failed to retrieve book data.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data sent from the form
    $judul = htmlspecialchars($_POST["judul"]);
    $penulis = htmlspecialchars($_POST["penulis"]);
    $penerbit = htmlspecialchars($_POST["penerbit"]);
    $tahun_terbit = htmlspecialchars($_POST["tahun_terbit"]);
    $kategori_id = htmlspecialchars($_POST["kategori_id"]);
    $stok = htmlspecialchars($_POST["stok"]);
    $deskripsi = htmlspecialchars($_POST["deskripsi"]);

    // Process uploading new cover image if available
    if ($_FILES['cover']['name']) {
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_name = $_FILES['cover']['name'];
        $cover_path = "cover/" . $cover_name;

        // Remove previous cover image if exists
        if (!empty($book_data['cover'])) {
            unlink("cover/" . $book_data['cover']);
        }

        move_uploaded_file($cover_tmp, $cover_path);
    }

    // Process uploading new PDF file if available
    if ($_FILES['pdf']['name']) {
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_path = "pdf/" . $pdf_name;

        // Remove previous PDF file if exists
        if (!empty($book_data['pdf'])) {
            unlink("pdf/" . $book_data['pdf']);
        }

        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    // Update book data with new cover and PDF if uploaded
    $update_book_query = "UPDATE buku
                          SET judul = '$judul', deskripsi = '$deskripsi', penulis = '$penulis', penerbit = '$penerbit',
                              tahun_terbit = '$tahun_terbit', kategori_id = '$kategori_id', stok = '$stok'";

    // Add cover and PDF data to query if uploaded
    if ($_FILES['cover']['name']) {
        $update_book_query .= ", cover = '$cover_name'";
    }
    if ($_FILES['pdf']['name']) {
        $update_book_query .= ", pdf = '$pdf_name'";
    }

    $update_book_query .= " WHERE id = $id";

    $update_book_result = mysqli_query($conn, $update_book_query);

    if ($update_book_result) {
        // If the update is successful
        // Redirect to the index page
        header('location: index.php');
        $_SESSION['success'] = "Buku berhasil diperbarui";
    } else {
        // If there is an error in the query
        $error_message = "An error occurred. Please try again.";
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

    <title>Edit Buku</title>

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
            <div class="sidebar-brand-text mx-3">Admin</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
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
                <a class="nav-link" href="registrasi.php">
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
        <div class="container-fluid col-lg-6">

            <!-- Page Heading -->
            <div class="d-sm-flex justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Edit Buku</h1>
            </div>

            <form class="user" action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
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
                    <label for="">Judul :</label>
                    <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" required value="<?php echo $book_data['judul']; ?>">
                </div>
                <div class="form-group">
                    <label for="">Penulis :</label>
                    <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Penulis" required value="<?php echo $book_data['penulis']; ?>">
                </div>
                <div class="form-group">
                    <label for="">Penerbit :</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Penerbit" required value="<?php echo $book_data['penerbit']; ?>">
                </div>
                <div class="form-group">
                    <label for="">Deskripsi :</label>
                    <textarea type="text" class="form-control rounded" id="judul" name="deskripsi" placeholder="Deskripsi" required><?php echo $book_data['deskripsi']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="pdf">PDF File:</label>
                    <div class="custom-file">
                        <input id="pdf" name="pdf" accept="application/pdf" type="file" class="custom-file-input" id="inputGroupFile02" aria-describedby="inputGroupFileAddon02" onchange="updateFileName(this)">
                        <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Tahun Terbit :</label>
                    <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Tahun Terbit" required value="<?php echo $book_data['tahun_terbit']; ?>">
                </div>
                <div class="form-group">
                    <label for="kategori_id">Kategori:</label>
                    <select class="form-control" id="kategori_id" name="kategori_id">
                         <?php
                        // Query untuk mengambil data kategori buku dari database
                        $get_kategori_query = "SELECT * FROM kategori_buku";
                        $result_kategori = mysqli_query($conn, $get_kategori_query);
                        // Memproses hasil query kategori buku menjadi opsi HTML
                        if (mysqli_num_rows($result_kategori) > 0) {
                            while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                // Jika kategori buku saat ini sesuai dengan yang dipilih, tambahkan atribut selected
                                $selected = ($row_kategori['id'] == $book_data['kategori_id']) ? 'selected' : '';
                                echo '<option value="' . $row_kategori['id'] . '" ' . $selected . '>' . $row_kategori['nama_kategori'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok">Stok Buku</label>
                    <input type="number" value="<?php echo $book_data['stok']; ?>" class="form-control" id="stok" name="stok" placeholder="Stok" required>
                </div>
                <!-- Tampilkan gambar cover buku sebelumnya -->
                <div class="mb-3">
                    <label>Gambar Cover Buku Sebelumnya:</label><br>
                    <img src="cover/<?php echo $book_data['cover']; ?>" alt="Cover Buku" style="max-width: 200px; max-height: 200px;">
                </div>
                <!-- Tambahkan input file untuk mengunggah gambar baru -->
                <div class="form-group">
                    <label for="cover">Cover Buku Baru:</label>
                    <div class="custom-file">
                        <input id="cover" name="cover" accept="image/*" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>
                    <!-- Untuk menampilkan preview cover -->
                    <img id="cover-preview" class="mt-2" style="max-width: 200px; max-height: 200px; display: none;">
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    Edit Buku
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
                    <a class="btn btn-primary" href="../../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Modal-->
    <div class="modal fade" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="kategoriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kategoriModalLabel">Daftar Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tabel kategori akan ditampilkan di sini -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Kategori</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kategoriTableBody">
                            <!-- Data kategori akan dimasukkan di sini melalui AJAX -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Tombol-tombol tambahan jika diperlukan -->
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

<script>
    function updateFileName(input) {
        var fileName = input.files[0].name;
        var label = input.nextElementSibling;
        label.innerText = fileName;
    }
</script>

</body>

</html>
<?php
// Pastikan file konfigurasi database sudah di-include di sini

// File konfigurasi database (ganti sesuai konfigurasi Anda)
include 'config.php';
include 'function/func.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT); // Hash password
    $nama_lengkap = htmlspecialchars($_POST["nama_lengkap"]);
    $alamat = htmlspecialchars($_POST["alamat"]);

    // Lakukan validasi dan pendaftaran
    if (!empty($username) && !empty($email) && !empty($password) && !empty($nama_lengkap) && !empty($alamat)) {
        // Query untuk memeriksa apakah email sudah terdaftar
        $check_email_query = "SELECT * FROM `user` WHERE `email` = '$email'";
        $check_email_result = mysqli_query($conn, $check_email_query);

        if (mysqli_num_rows($check_email_result) == 0) {
            // Jika email belum terdaftar, lakukan pendaftaran
            $role = "peminjam"; // Atur peran sebagai peminjam
            $perpus_id = 0; // Sesuai dengan logika Anda

            // Query untuk memasukkan data user baru ke dalam database
            $register_query = "INSERT INTO `user` (`perpus_id`, `username`, `password`, `email`, `nama_lengkap`, `alamat`, `role`, `created_at`)
                              VALUES ('$perpus_id', '$username', '$password', '$email', '$nama_lengkap', '$alamat', '$role', current_timestamp())";

            $register_result = mysqli_query($conn, $register_query);

            if ($register_result) {
                // Pendaftaran berhasil
                $success_message = "Registrasi berhasil. Silakan login.";
            } else {
                // Terdapat kesalahan saat query
                $error_message = "Terjadi kesalahan. Silakan coba lagi.";
            }
        } else {
            // Email sudah terdaftar
            $error_message = "Email sudah terdaftar. Silakan gunakan email lain.";
        }
    } else {
        // Form tidak lengkap
        $error_message = "Semua field harus diisi.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include bagian head sesuai kebutuhan Anda -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Registrasi Peminjam</title>

    <!-- Custom fonts for this template-->
    <link href="dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="dashboard/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 35%, rgba(0,212,255,1) 100%);
        }

        .container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: 0;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-10">
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Registrasi Peminjam</h1>
                                </div>
                                <form class="user" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                                        <input type="text" class="form-control form-control-user" id="username"
                                            name="username" placeholder="Username" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email"
                                            name="email" placeholder="Email Address" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="nama_lengkap"
                                            name="nama_lengkap" placeholder="Full Name" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control form-control-user rounded" id="alamat" name="alamat"
                                            placeholder="Address" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Register Account
                                    </button>
                                    <hr>
                                </form>
                                <div class="text-center">
                                    <a class="small" href="login.php">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
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
</body>

</html>

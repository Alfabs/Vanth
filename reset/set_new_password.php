<?php
session_start();
// Include file konfigurasi database
include '../config.php';

// Inisialisasi variabel untuk menyimpan pesan kesalahan atau kesuksesan
$message = '';

// Jika metode request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil email dari query string
    $email = $_GET['email'];

    // Ambil password baru dan konfirmasi password dari form
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Periksa apakah password dan konfirmasi password cocok
    if ($new_password === $confirm_password) {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password baru ke dalam database
        $sql = "UPDATE user SET password='$hashed_password' WHERE email='$email'";

        if ($conn->query($sql) === TRUE) {
            // Redirect pengguna ke halaman login atau halaman lainnya setelah berhasil mengatur password baru
            header('location: ../login.php');
        } else {
            // Tampilkan pesan kesalahan jika gagal mengupdate password
            $message = 'Failed to set new password. Please try again.';
        }
    } else {
        // Password dan konfirmasi password tidak cocok
        $message = 'Password tidak cocok. Silakan coba lagi.';
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

    <title>Set New Password</title>

    <!-- Custom fonts for this template-->
    <link href="../dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../dashboard/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(9, 9, 121, 1) 35%, rgba(0, 212, 255, 1) 100%);
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
                                    <h1 class="h4 text-gray-900 mb-4">Set New Password</h1>
                                    <?php if (!empty($message)) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $message; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if(isset($_SESSION['success'])) { ;?>
                                    <div class="alert alert-success" role="alert"><?=$_SESSION['success'];?></div>
                                    <?php   
                                        unset($_SESSION['success']);
                                    } ;?>
                                </div>
                                <form class="user" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?email=' . $_GET['email']; ?>">
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="new_password" name="new_password" aria-describedby="newPasswordHelp"
                                            placeholder="Enter New Password..." required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            id="confirm_password" name="confirm_password" aria-describedby="confirmPasswordHelp"
                                            placeholder="Confirm New Password..." required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Submit
                                    </button>
                                    <hr>
                                </form>
                            </div>
                        </div>
                    </div>
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

</body>

</html>

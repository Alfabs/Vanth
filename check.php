<?php
// Include library PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Sesuaikan dengan path PHPMailer Anda

include 'config.php';
include 'function/func.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil email dari form
    $email = $_POST['email'];

    // Periksa apakah email sudah ada di tabel reset_password
    $check_email_query = "SELECT * FROM reset_password WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_query);

    if ($check_email_result->num_rows > 0) {
        // Email sudah ada di tabel reset_password, update reset_code

        // Generate reset code
        $reset_code = '';

        // Menghasilkan 6 karakter acak
        for ($i = 0; $i < 6; $i++) {
            $reset_code .= rand(0, 9); // Menghasilkan angka acak dari 0 hingga 9
        }

        // Update reset code di database
        $update_reset_code_query = "UPDATE reset_password SET reset_code = '$reset_code' WHERE email = '$email'";
        if ($conn->query($update_reset_code_query) === TRUE) {
            // Kirim email menggunakan fungsi sendResetPasswordEmail
            if (sendResetPasswordEmail($email, $reset_code)) {
                // Redirect ke halaman reset password dengan mengirim email sebagai parameter
                header('location: reset/reset_password.php?email=' . urlencode($email));
            } else {
                // Jika gagal mengirim email, tampilkan pesan error
                $error_message = "Failed to send reset password email.";
            }
        } else {
            // Redirect atau tampilkan pesan error
            echo "Error updating reset code: " . $conn->error;
        }
    } else {
        // Email belum ada di tabel reset_password, tambahkan data baru

        // Generate reset code
        $reset_code = '';

        // Menghasilkan 6 karakter acak
        for ($i = 0; $i < 6; $i++) {
            $reset_code .= rand(0, 9); // Menghasilkan angka acak dari 0 hingga 9
        }

        // Simpan reset code ke database
        $sql = "INSERT INTO reset_password (email, reset_code) VALUES ('$email', '$reset_code')";

        if ($conn->query($sql) === TRUE) {
            // Kirim email menggunakan fungsi sendResetPasswordEmail
            if (sendResetPasswordEmail($email, $reset_code)) {
                // Redirect ke halaman reset password dengan mengirim email sebagai parameter
                header('location: reset/reset_password.php?email=' . urlencode($email));
            } else {
                // Jika gagal mengirim email, tampilkan pesan error
                $error_message = "Failed to send reset password email.";
            }
        } else {
            // Redirect atau tampilkan pesan error
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
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

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
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
                                    <h1 class="h4 text-gray-900 mb-4">Reset Password</h1>
                                </div>
                                <form class="user" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <!-- Tambahkan pesan error jika ada -->
                                    <?php if (isset($error_message)) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                            id="exampleInputEmail" name="email" aria-describedby="emailHelp"
                                            placeholder="Enter Email Address..." required>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">    
                                            <a href="registrasi.php" class="" for="customCheck">Daftar akun peminjam?</a>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Kirim
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
    <script src="dashboard/vendor/jquery/jquery.min.js"></script>
    <script src="dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="dashboard/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="dashboard/js/sb-admin-2.min.js"></script>
</body>

</html>

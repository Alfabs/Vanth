<?php
// Pastikan file konfigurasi database sudah di-include di sini
include 'config.php';

session_start();




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Lakukan validasi dan autentikasi
    if (!empty($email) && !empty($password)) {
        // Query untuk mengambil data user dari database berdasarkan email
        $query = "SELECT * FROM `user` WHERE `email` = '$email'";
        $result = mysqli_query($conn, $query);

            if ($result) {
            $row = mysqli_fetch_assoc($result);

            // Check if a user with the given email exists
            if ($row) {
                // Verifikasi password menggunakan password_verify
                if (password_verify($password, $row['password'])) {
                    // Set session untuk menyimpan informasi user yang login
                    $_SESSION['user_id'] = $row['userID'];
                    $_SESSION['username'] = $row['username'];

                    // Check user role
                    $userRole = $row['role'];
                    if ($userRole == 'peminjam') {
                        header("Location: user/index.php"); // Redirect to peminjam's dashboard
                    } else {
                        header("Location: dashboard/index.php"); // Redirect to other roles' dashboard
                    }
                    exit();
                } else {
                    // Password tidak valid
                    $error_message = "Email atau Password salah!";
                }
            } else {
                // User dengan email tersebut tidak ditemukan
                $error_message = "User dengan email tersebut tidak ditemukan.";
            }
        } else {
            // Terdapat kesalahan saat query
            $error_message = "Terjadi kesalahan. Silakan coba lagi.";
        }
    } else {
        // Form tidak lengkap
        $error_message = "Email dan Password harus diisi.";
}
}



?>
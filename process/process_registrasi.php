<?php
// Pastikan file konfigurasi database sudah di-include di sini

// File konfigurasi database (ganti sesuai konfigurasi Anda)
include '../config.php';
include '../function/func.php';

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
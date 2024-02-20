<?php
// Include library PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




function getUserRole($conn, $username) {
    $userRole = '';  // Default value

    $query = "SELECT role FROM `user` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $userRole = $row['role'];
    }

    return $userRole;
}

function checkAdminRole($role) {
    if ($role !== "admin") {
        header("Location: blocked.php");
        exit();
    }
}

function dataBuku($role){
    if ($role == "admin" || $role == "petugas") {
    } else {
        header("Location: blocked.php");
        exit();

    }
}

function checkPetugasRole($role) {
    if ($role !== "petugas") {
        header("Location: blocked.php");
        exit();
    }
}

function checkUserRole($role) {
    if ($role !== "peminjam") {
        header("Location: blocked.php");
        exit();
    }
}


function getLoggedInUserID($conn, $username) {
    $query = "SELECT id FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    return $userId;
}


function sendResetPasswordEmail($email, $reset_code) {
    // Kirim email menggunakan PHPMailer
    
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'aivoice725@gmail.com'; // Ganti dengan email Anda
        $mail->Password = ''; // Ganti dengan password email Anda
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Siapkan email
        $mail->setFrom('aivoice725@gmail.com', 'admin'); // Ganti dengan email dan nama Anda
        $mail->addAddress($email); // Tambahkan penerima
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';
        $mail->Body = 'Code Verifikasi Password : ' . $reset_code;

        // Kirim email
        $mail->send();

        return true; // Email berhasil dikirim
    } catch (Exception $e) {
        // Email gagal dikirim, kembalikan false dan tampilkan pesan error
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
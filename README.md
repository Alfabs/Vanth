# Aplikasi Perpustakaan Digital

Aplikasi Perpustakaan Digital ini adalah aplikasi perpustakaan digital berbasis website untuk mengelola buku buku perpustakaan secara online menggunakan php native.

## Fitur

- Registrasi & Login
- Mengelola Data Buku Perpustakaan (Create, Read, Update, Delete)
- Meminjam dan Mengembalikan buku sebagai peminjam
- Bookmark bagi peminjam
- Memberi rating dan ulasan buku
- Generate Laporan (PDF & Excel)
- Lupa Password melalui email

## Get Started

1. Download zip project ini atau clone repository ini
```
git clone https://github.com/Alfabs/Vanth.git
```

2. Pastikan xampp atau client server sudah terdownload dan kunjungi [http/localhostphp](http://localhost/phpmyadmin/)http://localhost/phpmyadmin/ untuk xampp, bagi yang lainnya menyesuaikan. Jika ingin mendowload xampp https://www.apachefriends.org/download.html
   
3. Buat database "perpustakaan", lalu Import database perpustakaan.sql ke database yang sudah dibuat
   
4. Buka index.php pada folder perpustakaan, maka aplikasi siap dijalankan

## Library yang digunakan

- [FPDF](https://github.com/Setasign/FPDF)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- [PHPOffice](https://github.com/PHPOffice/PhpSpreadsheet)

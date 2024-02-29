<?php
include 'config.php';

$sql = "SELECT b.judul, b.cover, b.penerbit, AVG(ub.rating) AS avg_rating
        FROM ulasan_buku ub
        JOIN buku b ON ub.buku = b.id
        GROUP BY ub.buku
        ORDER BY avg_rating DESC
        LIMIT 3";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
   
    <link href="../dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
</head>

<body>

    <!-- Navigation -->
    <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
          <h4>Perpustakaan</h4>
        </a>
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="index.php" class="nav-link px-2 link-secondary">Home</a></li>
        <li><a href="guest.php" class="nav-link px-2">Catalog</a></li>

      </ul>

      <div class="col-md-3 text-end">
        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
        <a href="registrasi.php" class="btn btn-primary">Sign-up</a>
      </div>
    </header>
  </div>

    <!-- Hero Section -->
    <section class="hero d-flex justify-content-center align-items-center">
        <div class="mt-5 container">
            <div class="row">
                <div class="col-md-3">
                    <img src="assets/img/6888606.jpg" alt="Left Book Image" class="img-fluid">
                </div>
                <div class="col-md-6 text-center">
                    <div class="mt-5 text-center">
                        <h1>Perpustakaan Digital</h1>
                        <p>Tempat dimana anda bisa menyimpan buku buku yang anda inginkan</p>
                        <a href="login.php" class="btn btn-primary btn-lg">Get Started</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <img src="assets/img/6888606.jpg" alt="Right Book Image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>




    <!-- Featured Books Section -->
    <section class="featured-books py-5">
        <div class="mt-5 container">
            <h2 class="text-center mb-4">Rekomendasi Buku</h2>
            <div class="row">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <img src="dashboard/buku/cover/<?=$row['cover'];?>" alt="">
                            <div class="card-body">

                                <h5 class="card-title"><?php echo $row['judul']; ?></h5>
                                <p class="card-text"><?= $row['penerbit'];?></p>
                                <p class="card-text">Rating: <?php echo number_format($row['avg_rating'], 1); ?></p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-white text-dark border-top text-center py-4">
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qg8mH0Mm3g7gMzIhUHJFZ/xBbfBmbKTEzB1OJaYL6PF7hj2A/0RGqqj7YfmgJwFf"
        crossorigin="anonymous"></script>
</body>

</html>

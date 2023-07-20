<?php
  include "config.php";
  session_start();
  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>LieLien Shop</title>
</head>
<body>
  <video autoplay muted loop id="myVideo">
    <source src="img/background.mp4" type="video/mp4">
  </video>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid" style="background-color: white;">
          <a class="navbar-brand" href="admin.php"><img src="img/logo.jpg" class="logo"><strong class="brand-text">LIELIEN SHOP</strong></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="admin.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="stock.php">Stock</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="templates/test.php">Category Prediction</a>
              </li>
              <li class="nav-item">
                <a class="nav-link"href="logout.php">Logout</a>
              </li>
              <li class="nav-item">
             <?php if (isset($_SESSION["name"])) {
              $username = $_SESSION["name"];
              if ($username=="admin"){
              $query = "SELECT nama FROM pengguna WHERE name = '$username'";
              echo ' <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
              <a class="nav-link"> Welcome '.$username.'</a>';
              }else {header("location: indexlogin.php");}} else {
              // Pengguna belum login, alihkan ke halaman login
              header("Location: login.php");
              exit();
              } ?>
            </li>
            </ul>
        </div>
        </div>
      </nav>

<div class="col-sm-12 md-3">

    <h1 style="text-align: center;padding-top: 5%;">TOKO AKSESORIS<div style=" word-wrap: break-word;">TERMURAH</div> </h1>   
</div>
<div class="col-sm-12 md-3">
    <img src="img/halaman depan.png" class="halaman_depan">
</div>
<!-- Footer -->
<footer class="text-center text-lg-start bg-white text-muted">
  <!-- Section: Social media -->
  <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
    <!-- Left -->
    <div class="me-5 d-none d-lg-block">
      <span>Get connected with us on social networks:</span>
    </div>
    <!-- Left -->

    <!-- Right -->
    <div>
      <a href="" class="me-4 link-secondary">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="" class="me-4 link-secondary">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="" class="me-4 link-secondary">
        <i class="fab fa-google"></i>
      </a>
      <a href="" class="me-4 link-secondary">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="" class="me-4 link-secondary">
        <i class="fab fa-linkedin"></i>
      </a>
      <a href="" class="me-4 link-secondary">
        <i class="fab fa-github"></i>
      </a>
    </div>
    <!-- Right -->
  </section>
  <!-- Section: Social media -->

  <!-- Section: Links  -->
  <section class="">
    <div class="container text-center text-md-start mt-3 mb-3">
      <!-- Grid row -->
      <div class="row mt-3">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            <i class="fas fa-gem me-6 text-secondary"></i>LIELIEN SHOP
          </h6>
          <p>
            Sejak 2018 telah berdiri sebagai toko aksesoris serta bahan mentah aksesoris.
            Memiliki kualitas premium.
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Products
          </h6>
          <p>
            <a href="#!" class="text-reset">Gelang</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Kalung</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Cincin</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Bahan mentah</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            Useful links
          </h6>
          <p>
            <a href="#!" class="text-reset">Pricing</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Settings</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Orders</a>
          </p>
          <p>
            <a href="#!" class="text-reset">Help</a>
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-3">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
          <p><i class="fas fa-home me-6 text-secondary"></i> JL.RA Kartini GG. Manggis Raya No. 36</p>
          <p>
            <i class="fas fa-envelope me-6 text-secondary"></i>
            Lielienshop@gmail.com
          </p>
          <p><i class="fas fa-phone me-6 text-secondary"></i> +62812645782</p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-2" style="background-color: rgba(0, 0, 0, 0.025);">
    © 2023 Copyright:
    <a class="text-reset fw-bold" href="#">Lielienshop.com</a>
  </div>
  <!-- Copyright -->
  <div class="text-end">
</footer>

<!-- Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
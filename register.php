<?php

include 'config.php';

if(isset($_POST['submit'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
  $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

  // Validasi nama, email, dan password
  $errors = array();

if (empty($name)) {
  $errors['name'] = 'Nama harus diisi.';
}

if (empty($email)) {
  $errors['email'] = 'Email harus diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors['email'] = 'Format email tidak valid.';
}

if (empty($pass)) {
  $errors['password'] = 'Password harus diisi.';
} elseif (strlen($pass) < 6) {
  $errors['password'] = 'Password minimal harus terdiri dari 6 karakter.';
}

if ($pass !== $cpass) {
  $errors['cpassword'] = 'Konfirmasi password tidak cocok.';
}
  if(empty($errors)){
      $select = mysqli_query($conn, "SELECT * FROM `pengguna` WHERE email = '$email'") or die('query failed');

      if(mysqli_num_rows($select) > 0){
         $errors[] = 'User already exists!';
      } else {
         mysqli_query($conn, "INSERT INTO `pengguna`(name, email, password) VALUES('$name', '$email', '$pass')") or die('query failed');
         $message[] = 'Registered successfully!';
         header('location: login.php');
      }
  }
}


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
          <a class="navbar-brand" href="index.php"><img src="img/logo.jpg" class="logo"><strong class="brand-text">LIELIEN SHOP</strong></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="catalog.php">Catalogue</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="test.php">Category Prediction</a>
              </li>
              <li class="nav-item">
                <a class="nav-link"href="login.php">Login</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active"href="register.php">Register</a>
              </li>
            </ul>
        </div>
        </div>
      </nav>
<!--BODY-->
<?php

?>
<section class="vh-90 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-info-subtle text-dark" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">

              <h2 class="fw-bold mb-2 text-uppercase">Register</h2>
              <p class="text-dark-50 mb-5">Enter your data!</p>
              <form method="POST">
              <div class="form-outline form-white mb-4">
  <input type="text" id="name" class="form-control form-control-lg" name="name" />
  <label class="form-label" for="name">Name</label>
</div>
<?php
if (!empty($errors) && !empty($errors['name'])) {
  echo '<div class="error-message">' . $errors['name'] . '</div>';
}
?>

<div class="form-outline form-white mb-4">
  <input type="email" id="email" class="form-control form-control-lg" name="email" />
  <label class="form-label" for="email">Email</label>
</div>
<?php
if (!empty($errors) && !empty($errors['email'])) {
  echo '<div class="error-message">' . $errors['email'] . '</div>';
}
?>

<div class="form-outline form-white mb-4">
  <input type="password" id="password" class="form-control form-control-lg" name="password" />
  <label class="form-label" for="password">Password</label>
</div>
<?php
if (!empty($errors) && !empty($errors['password'])) {
  echo '<div class="error-message">' . $errors['password'] . '</div>';
}
?>

<div class="form-outline form-white mb-4">
  <input type="password" id="cpassword" class="form-control form-control-lg" name="cpassword" />
  <label class="form-label" for="cpassword">Confirm Password</label>
</div>
<?php
if (!empty($errors) && !empty($errors['cpassword'])) {
  echo '<div class="error-message">' . $errors['cpassword'] . '</div>';
}
?>

              <button class="btn btn-outline-dark btn-lg px-5" type="submit" id="submit" name="submit">Register</button>
            </div>
</form>
            <div>
              <p class="mb-0">Already have an account? <a href="login.html" class="text-primary fw-bold">Login here!</a>
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--BODY-->

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
          </h6> <p>
            <a href="catalog.php?search=gelang" class="text-reset">Gelang</a>
          </p>
          <p>
            <a href="catalog.php?search=kalung" class="text-reset">Kalung</a>
          </p>
          <p>
            <a href="catalog.php?search=cincin" class="text-reset">Cincin</a>
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
</footer>
<!-- Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
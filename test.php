<?php
// Koneksi ke database
$host = 'localhost';
$username = 'admin';
$password = '123';
$database = 'lielien';
$mysqli = new mysqli($host, $username, $password, $database);

session_start()
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
                <a class="nav-link" aria-current="page" href="indexlogin.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="catalog.php">Catalogue</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="test.php">Category Prediction</a>
              </li>
              <?php if (isset($_SESSION["name"])) {
              $username = $_SESSION["name"];
              $query = "SELECT nama FROM pengguna WHERE name = '$username'";
              echo ' <li class="nav-item"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
              <a class="nav-link"> Welcome '.$username.'</a></li>';
              } else {
                 echo '<li class="nav-item">';
                  echo '<a class="nav-link"href="login.php">Login</a>
                  </li>';
                  echo '<li class="nav-item">
                    <a class="nav-link"href="register.php">Register</a>
                  </li>';}?>
            </ul>
        </div>
        </div>
      </nav>
      <?php // Start or resume the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data from the form
    $nama_produk = $_POST['nama_produk'];
    $deskripsi_produk = $_POST['deskripsi_produk'];

    // Create JSON payload
    $data = array(
        'nama_produk' => $nama_produk,
        'deskripsi_produk' => $deskripsi_produk
    );
    $payload = json_encode($data);

    // Set the URL of the Flask app
    $url = 'http://localhost:8000/predict';

    // Set the HTTP headers
    $headers = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    );

    // Create a new cURL resource
    $curl = curl_init($url);

    // Set the cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // Execute the cURL request
    $response = curl_exec($curl);

    // Close cURL resource
    curl_close($curl);

    // Decode the JSON response
    $prediction = json_decode($response)->prediction;

    // Store the prediction in the session variable
    $_SESSION['prediction'] = $prediction;

    // Redirect to the same page to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Check if prediction is available in session variable
$prediction = '';
if (isset($_SESSION['prediction'])) {
    $prediction = $_SESSION['prediction'];
    unset($_SESSION['prediction']);
}
?>
<div class="container my-5 text-center" >
<h1>Product Category Prediction</h1>
<form action="" method="post">
    <label for="nama_produk">Nama Produk:</label><br>
    <input type="text" id="nama_produk" name="nama_produk" required><br><br>
    <label for="deskripsi_produk">Deskripsi Produk:</label><br>
    <textarea id="deskripsi_produk" name="deskripsi_produk" rows="2" cols="50" required></textarea><br><br>
    <button class="btn btn-primary" type="submit" value="Predict">Predict</button>
</form>

<?php if (!empty($prediction)) : ?>
    <h2>Prediction:</h2>
    <p><?php echo $prediction; ?></p>
<?php endif; ?>
</div>
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
  <div class="text-end">
</footer>

<!-- Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
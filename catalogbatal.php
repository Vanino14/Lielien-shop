<?php
include "config.php";
session_start();
// Lakukan proses pencarian atau query ke database



if (isset($_SESSION['name'])) {
    $user = $_SESSION['name'];

    if (isset($_POST['addToCart'])) {
      $productId = $_POST['productId'];
      $quantity = $_POST['quantity'];

      // Check if the entered quantity is greater than the available stock
      $queryStock = "SELECT jumlah FROM stock WHERE id_produk = '$productId'";
      $resultStock = mysqli_query($conn, $queryStock);
      $rowStock = mysqli_fetch_assoc($resultStock);
      $stock = $rowStock['jumlah'];

      if ($quantity > $stock) {
          // Display an error message or handle the situation as per your requirements
          $_SESSION['error_message'] = "Insufficient stock.";
          header("Location: catalog.php");
          exit();
      }

        // Check if the product is already in the cart
        if (isset($_SESSION['keranjang'][$productId])) {
            // Update the quantity if the product is already in the cart
            $_SESSION['keranjang'][$productId]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $query = "INSERT INTO keranjang (id_pengguna, id_produk, quantity, tanggal) VALUES ((SELECT id_pengguna FROM pengguna WHERE name = '$user'), '$productId', '$quantity', NOW())";
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Cart item added successfully
                $_SESSION['success_message'] = "Item added to cart successfully.";
                header("Location: catalog.php");
                exit();
            } else {
                // Handle the error, display an error message or redirect to an error page
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!-- Display success message if it exists -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success-message">
        <?php echo $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>



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
                <a class="nav-link active" href="catalog.php">Catalogue</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="test.php">Category Prediction</a>
              </li>
              <li class="nav-item">
                <a class="nav-link"href="logout.php">Logout</a>
              </li>
             <li> <?php if (isset($_SESSION["name"])) {
              $username = $_SESSION["name"];
              $query = "SELECT nama FROM pengguna WHERE name = '$username'";
              echo ' <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
              <a class="nav-link"> Welcome '.$username.'</a>';
              } else {
             // Pengguna belum login, alihkan ke halaman login
             header("Location: login.php");
            exit();
            } ?> </li>
            </ul>
        </div>
        </div>
      </nav>
<!--BODY-->
 <!-- Form pencarian --><div class="row">
    <div class="col-sm-6 offset-lg-3 my-5">
        <form class="form-inline" action="" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Cari produk...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
// Konfigurasi koneksi database
$servername = "localhost";
$username = "admin";
$password = "123";
$dbname = "lielien";

try {
    // Buat koneksi database menggunakan PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set opsi untuk menampilkan error SQL
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek apakah ada parameter pencarian
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];

        // Query pencarian berdasarkan nama_produk
        $stmt = $conn->prepare("SELECT * FROM produk WHERE nama_produk LIKE :searchTerm");
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
        $stmt->execute();

        // Ambil hasil pencarian
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Query untuk menampilkan semua produk
        $query = "SELECT produk.*, stock.jumlah AS stock FROM produk LEFT JOIN stock ON produk.id_produk = stock.id_produk";
        $result = $conn->query($query);
        $results = $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tampilkan hasil pencarian atau semua produk
    ?>
    <div class="row mx-5">
        <?php foreach ($results as $row) : ?>
            <div class="col-lg-3 col-md-4 col-sm-6 my-2">
                <div class="card" style="width: auto; height: 33rem;">
                    <img src="data:image/jpeg;base64, <?php echo base64_encode($row['gambar']); ?>" style="height: 18rem; object-fit: cover;" class="card-img-top" alt="Produk 1">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nama_produk'] ?></h5>
                        <?php $formattedNumber = number_format($row['harga_produk'], 0, ',', '.'); ?>
                        <h6 class="card-title">Rp. <?php echo $formattedNumber ?></h6>
                        <p class="card-text"><?php echo $row['deskripsi_produk'] ?></p>
                        <form method="post" action="catalog.php">
                            <div class="input-group">
                                <input type="number" class="form-control" value="1" min="1" name="quantity" onchange="validateQuantity(this)">
                            </div>
                            <input type="hidden" name="productId" value="<?php echo $row['id_produk']; ?>">
                            <button class="btn btn-primary mt-3" name="addToCart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Tutup koneksi database
$conn = null;
?>



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
</footer>
<!-- Footer -->
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
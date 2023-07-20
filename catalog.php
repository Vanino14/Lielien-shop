<?php
// Koneksi ke database
include "config.php";
$host = 'localhost';
$username = 'admin';
$password = '123';
$database = 'lielien';
$mysqli = new mysqli($host, $username, $password, $database);

session_start();
$query = "SELECT p.id_produk, p.prediksi, SUM(t.quantity) AS total_penjualan 
FROM transaksi t
INNER JOIN produk p ON t.id_produk = p.id_produk
GROUP BY p.id_produk, p.prediksi";
$result = mysqli_query($mysqli, $query);

// Simpan total penjualan setiap produk dalam array asosiatif berdasarkan kategori
$produkTerlaris = array();
while ($row = mysqli_fetch_assoc($result)) {
  $kategori = $row['prediksi'];
  $produkId = $row['id_produk'];
  $totalPenjualan = $row['total_penjualan'];

  if (!isset($produkTerlaris[$kategori])) {
    $produkTerlaris[$kategori] = array();
  }

  $produkTerlaris[$kategori][$produkId] = $totalPenjualan;
}
if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $namaPengguna = $_SESSION['name'];

  // Query untuk mendapatkan id_pengguna berdasarkan nama pengguna
  $query1 = "SELECT id_pengguna FROM pengguna WHERE name = '$namaPengguna'";
  $hasil = mysqli_query($mysqli, $query1);
  
  // Pastikan query berhasil dan ambil hasilnya
  if ($hasil && $hasil->num_rows > 0) {
    $row = $hasil->fetch_assoc();
    $userId = $row['id_pengguna'];
    
    $activity="search kata kunci : $search";
    $queryLog = "INSERT INTO activity_log (timestamp, id_pengguna, activity_type, activity_description)
                 VALUES (NOW(), $userId, '$search', '$activity')";
    mysqli_query($mysqli, $queryLog);
  }
  

  // Mengambil daftar produk berdasarkan pencarian
  $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%' OR deskripsi_produk LIKE '%$search%' OR prediksi LIKE '%$search%'";
} else {
  // Mendapatkan semua produk jika tidak ada pencarian
  $query = "SELECT * FROM produk";
}

// Execute the product query
$result = $mysqli->query($query);

// Get the total number of items
$totalItems = $result->num_rows;

// Set the number of items per page
$itemsPerPage = 12;

// Calculate the total number of pages
$totalPages = ceil($totalItems / $itemsPerPage);

// Get the current page number from the query parameters
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting offset for the items
$offset = ($currentPage - 1) * $itemsPerPage;

// Retrieve the products based on the current page
$query .= " LIMIT $offset, $itemsPerPage";
$result = $mysqli->query($query);

if (isset($_SESSION['name'])) {
  $user = $_SESSION['name'];

  if (isset($_POST['addToCart'])) {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];

    // Retrieve product name
    $queryprodname = "SELECT nama_produk FROM produk WHERE id_produk = '$productId'";
    $resultprodname = mysqli_query($mysqli, $queryprodname);
    $rowprodname = mysqli_fetch_assoc($resultprodname);
    $prodname = $rowprodname['nama_produk'];

    // Retrieve user ID
    $queryUserId = "SELECT id_pengguna FROM pengguna WHERE name = '$user'";
    $resultUserId = mysqli_query($mysqli, $queryUserId);
    $rowUserId = mysqli_fetch_assoc($resultUserId);
    $userId = $rowUserId['id_pengguna'];

    // Insert activity log
    $queryLog = "INSERT INTO activity (timestamp, id_pengguna, additem, kategori_produk)
                 VALUES (NOW(), '$userId', '$prodname', (SELECT prediksi FROM produk WHERE id_produk = '$productId'))";
    mysqli_query($mysqli, $queryLog);

    // Check if the entered quantity is greater than the available stock
    $queryStock = "SELECT jumlah FROM stock WHERE id_produk = '$productId'";
    $resultStock = mysqli_query($mysqli, $queryStock);
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
                    <a class="nav-link" href="register.php">Register</a>
                  </li>';
              }?>
            </ul>
        </div>
        </div>
      </nav>
      <div class="container my-5 text-center">
    <h1><strong>LIST PRODUK</strong></h1>
</div>

<div class="container">
    <div class="row mx-5">
        <!-- Form pencarian -->
        <form action="catalog.php" method="GET" class="mb-4">
            <div id="searchRecommendations" class="input-group">
                <input type="text" class="form-control" placeholder="Cari produk..." name="search">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <div class="row">
        <?php
        if (isset($_GET['search'])) {
            $search = $_GET['search'];

            // Melakukan permintaan POST ke Flask API untuk memprediksi kategori
            $data = array(
                'nama_produk' => $search,
                'deskripsi_produk' => $search
            );

            $ch = curl_init('http://localhost:8000/predict');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            curl_close($ch);

            // Mendapatkan hasil prediksi dari respons JSON
            $prediction = json_decode($response, true)['prediction'];

            if (isset($produkTerlaris[$prediction])) {
                // Mendapatkan produk terlaris dalam kategori prediksi
                $produkTerlarisKategori = $produkTerlaris[$prediction];

                // Urutkan produk berdasarkan total penjualan secara menurun
                arsort($produkTerlarisKategori);

                // Mendapatkan daftar ID produk terlaris dalam kategori prediksi
                $produkTerlarisIds = array_keys($produkTerlarisKategori);
                // Mengambil 4 produk terlaris
    $produkTerlarisIds = array_slice($produkTerlarisIds, 0, 4);

                // Menampilkan produk terlaris dalam kategori prediksi
                echo '<h3>Produk Terlaris dalam Kategori ' . $prediction . '</h3>';

                foreach ($produkTerlarisIds as $productId) {
                  // Mengambil informasi produk dari database berdasarkan ID produk
                  $queryProduk = "SELECT p.*, SUM(t.quantity) AS total_terjual
                                  FROM produk p
                                  LEFT JOIN transaksi t ON p.id_produk = t.id_produk
                                  WHERE p.id_produk = '$productId'
                                  GROUP BY p.id_produk";
                  $resultProduk = mysqli_query($mysqli, $queryProduk);
                  $rowProduk = mysqli_fetch_assoc($resultProduk);
                  ?>
              
                  <div class="col-lg-3 col-md-4 col-sm-6 my-2">
                      <!-- Tampilkan informasi produk dan kategori -->
                      <div class="card text-white bg-info" style="width: auto; height: 37rem;">
                          <img src="data:image/jpeg;base64, <?php echo base64_encode($rowProduk['gambar']); ?>" style="height: 18rem; object-fit: cover;" class="card-img-top" alt="Produk 1">
                          <div class="card-body">
                              <h5 class="card-title"><?php echo $rowProduk['nama_produk'] ?></h5>
                              <?php $formattedNumber = number_format($rowProduk['harga_produk'], 0, ',', '.'); ?>
                              <h6 class="card-title">Rp. <?php echo $formattedNumber ?></h6>
                              <p class="card-text"><?php echo $rowProduk['deskripsi_produk'] ?></p>
                              <p class="card-text"><strong>Produk Terjual: <?php echo $rowProduk['total_terjual'] ?></strong></p>
                                <form method="post" action="catalog.php"> <!-- Add the form element here -->
                                    <div class="input-group">
                                        <input type="number" class="form-control" value="1" min="1" name="quantity" onchange="validateQuantity(this)">
                                    </div>
                                    <input type="hidden" name="productId" value="<?php echo $rowProduk['id_produk']; ?>">
                                    <button class="btn btn-primary mt-3" name="addToCart" type="submit">Add to Cart</button> <!-- Add the type="submit" attribute to the button -->
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
        }

        // Looping untuk setiap baris produk
        while ($row = $result->fetch_assoc()) {
            // Mendapatkan nama dan deskripsi produk
            $nama_produk = $row['nama_produk'];
            $deskripsi_produk = $row['deskripsi_produk'];

            // Melakukan permintaan POST ke Flask API untuk memprediksi kategori
            $data = array(
                'nama_produk' => $nama_produk,
                'deskripsi_produk' => $deskripsi_produk
            );

            $ch = curl_init('http://localhost:8000/predict');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            curl_close($ch);

            // Mendapatkan hasil prediksi dari respons JSON
            $prediction = json_decode($response, true)['prediction'];
            ?>

            <div class="col-lg-3 col-md-4 col-sm-6 my-2">
                <!-- Tampilkan informasi produk dan kategori -->
                <div class="card" style="width: auto; height: 37rem;">
                    <img src="data:image/jpeg;base64, <?php echo base64_encode($row['gambar']); ?>" style="height: 18rem; object-fit: cover;" class="card-img-top" alt="Produk 1">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nama_produk'] ?></h5>
                        <?php $formattedNumber = number_format($row['harga_produk'], 0, ',', '.'); ?>
                        <h6 class="card-title">Rp. <?php echo $formattedNumber ?></h6>
                        <p class="card-text"><?php echo $row['deskripsi_produk'] ?></p>
                        <p class="card-text"><strong>Prediksi Kategori: <?php echo $prediction ?></strong></p>
                        <form method="post" action="catalog.php"> <!-- Add the form element here -->
                            <div class="input-group">
                                <input type="number" class="form-control" value="1" min="1" name="quantity" onchange="validateQuantity(this)">
                            </div>
                            <input type="hidden" name="productId" value="<?php echo $row['id_produk']; ?>">
                            <button class="btn btn-primary mt-3" name="addToCart" type="submit">Add to Cart</button> <!-- Add the type="submit" attribute to the button -->
                        </form>
                    </div>
                </div>
            </div>
    <?php
        }
    ?>
    </div>
</div>


<!-- Pagination links -->
<div class="pagination justify-content-center my-5">
  <ul class="pagination">
    <?php
    // Calculate the total number of pages
    $totalPages = ceil($totalItems / $itemsPerPage);

    // Get the current page number from the query parameters
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    // Display the previous page link
    if ($currentPage > 1) {
      echo '<li class="page-item"><a class="page-link" href="catalog.php?page=' . ($currentPage - 1) . '">Previous</a></li>';
    }

    // Display the page links
    for ($i = 1; $i <= $totalPages; $i++) {
      $activeClass = ($i == $currentPage) ? 'active' : '';
      echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="catalog.php?page=' . $i . '">' . $i . '</a></li>';
    }

    // Display the next page link
    if ($currentPage < $totalPages) {
      echo '<li class="page-item"><a class="page-link" href="catalog.php?page=' . ($currentPage + 1) . '">Next</a></li>';
    }
    ?>
  </ul>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Fungsi untuk menampilkan rekomendasi pencarian
  function showSearchRecommendations(recommendations) {
    var recommendationList = '<ul>';
    recommendations.forEach(function(recommendation) {
      recommendationList += '<li>' + recommendation + '</li>';
    });
    recommendationList += '</ul>';
    $('#searchRecommendations').html(recommendationList);
  }

  // Fungsi untuk mendapatkan rekomendasi pencarian dari server
  function getSearchRecommendations(input) {
    // Ganti URL pada baris berikut sesuai dengan URL yang sesuai untuk mendapatkan rekomendasi dari server
    var url = 'get_search_recommendations.php?search=' + encodeURIComponent(input);
    $.get(url, function(data) {
      showSearchRecommendations(data);
    });
  }

  // Event handler saat input kolom pencarian diubah
  $('#searchInput').on('input', function() {
    var input = $(this).val();
    if (input.length > 0) {
      getSearchRecommendations(input);
    } else {
      $('#searchRecommendations').empty();
    }
  });
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
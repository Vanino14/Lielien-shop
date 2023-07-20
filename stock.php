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
          <a class="navbar-brand" href="index.php"><img src="img/logo.jpg" class="logo"><strong class="brand-text">LIELIEN SHOP</strong></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="admin.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="stock.php">Stock</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="test.php">Category Prediction</a>
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
<!--BODY-->
<div class="container col-lg-12 col-md-6 col-sm-3">
  <?php
  // Include your database connection code here

  // Function to sanitize user inputs
  function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
  }

  // Add Item
  if (isset($_POST['add'])) {
    // Sanitize user inputs
    $nama_produk = sanitize($_POST['nama_produk']);
    $harga_produk = sanitize($_POST['harga_produk']);
    $deskripsi_produk = sanitize($_POST['deskripsi_produk']);
    $file_size = $_FILES['gambar']['size'];
    $file_type = $_FILES['gambar']['type'];
    $jumlah = sanitize($_POST['jumlah']);
    if ($file_size < 2048000 and ($file_type =='image/jpeg' || $file_type == 'image/png'))
    {
    $image=addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
    $query = "INSERT INTO produk (id_produk, nama_produk, harga_produk, deskripsi_produk, gambar) VALUES ('','$nama_produk', '$harga_produk', '$deskripsi_produk', '$image')";
    mysqli_query($conn,$query);
    $id_produk = mysqli_insert_id($conn); // Ambil ID produk yang baru saja ditambahkan
    $query_stock = "INSERT INTO stock (id_produk, jumlah) VALUES ('$id_produk', '$jumlah')";
    mysqli_query($conn, $query_stock);
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
  }

  // Delete Item
  if (isset($_POST['delete'])) {
    // Sanitize user input
    $id_produk = sanitize($_POST['id_produk']);
    
    // Delete data from "produk" table
    $query_produk = "DELETE FROM produk WHERE id_produk='$id_produk'";
    mysqli_query($conn, $query_produk);
    
    // Delete data from "stock" table
    $query_stock = "DELETE FROM stock WHERE id_produk='$id_produk'";
    mysqli_query($conn, $query_stock);

    // Redirect to the same page to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


  // Fetch and display records from the database
  $query = "SELECT produk.*, stock.jumlah AS stock FROM produk
  LEFT JOIN stock ON produk.id_produk = stock.id_produk";
  $result = mysqli_query($conn, $query);
  ?>
  <h2 class="text-center my-4"><strong>DATA PRODUK LIELIEN SHOP</strong></h2>
  <div class="row">
    <div class="col-lg-12 col-md-4 col-sm-4">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <div class="form-group">
          <label for="nama_produk">Nama Produk</label>
          <input type="text" class="form-control" name="nama_produk" required>
        </div>
        <div class="form-group">
          <label for="harga_produk">Harga</label>
          <input type="number" class="form-control" name="harga_produk" required>
        </div>
        <div class="form-group">
          <label for="deskripsi_produk">Deskripsi</label>
          <textarea class="form-control" name="deskripsi_produk" rows="3" required></textarea>
        </div>
        <div class="form-group">
          <label for="gambar">Gambar</label>
          <input type="file" class="form-control" name="gambar" required>
        </div>
        <div class="form-group">
          <label for="jumlah">Stock</label>
          <input type="number" class="form-control" name="jumlah" required>
        </div>
        <button class="btn btn-danger my-3" type="submit" name="add">Add Item</button>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="table-responsive">
  <?php
  echo '<table class="table table-striped table-dark">';
  echo '<thead class="thead-dark">';
  echo '<tr><th class="col">No.</th><th class="col">NAMA PRODUK</th><th class="col">HARGA</th><th class="col">DESKRIPSI</th><th class="col">GAMBAR</th><th>STOCK</th><th>EDIT</th><th>DELETE</th>';
  echo '</thead>';
  echo '</tr>';
  $no=1;
  while ($row = mysqli_fetch_array($result)) {
    echo '<tr>';
    echo '<td>' . $no++ . '</td>';
    echo '<td>' . $row['nama_produk'] . '</td>';
    echo '<td>' . $row['harga_produk'] . '</td>';
    echo '<td>' . $row['deskripsi_produk'] . '</td>';
    echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['gambar']) . '" style="width:3rem;height:3rem;"></td>';
    if (isset($row['stock'])) {
      echo '<td>' . $row['stock'] . '</td>';
  } else {
      echo '<td>0</td>'; // Set a default value if 'jumlah' key is not defined
  }
  
  echo '<td>
  <form id="editForm'.$row['id_produk'].'" method="POST" action="form/editform.php" style="display: inline;">
    <input type="hidden" name="id_produk" value="'.$row['id_produk'].'">
    <button class="btn btn-info btn-sm editButton" data-form-id="'.$row['id_produk'].'">Edit</button>
  </form>
</td>';

    echo '<td>
            <form method="POST" action="' . $_SERVER['PHP_SELF'] . '" style="display: inline;">
              <input type="hidden" name="id_produk" value="' . $row['id_produk'] . '">
              <button class="btn btn-info btn-sm" type="submit" name="delete">Delete</button>
            </form>
          </td>';
    echo '</tr>';
  }
  echo '</table>';
  ?>
  </div>
</div>
</div>



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
            <a href="catalog.php?search=gelang" class="text-reset">Gelang</a>
          </p>
          <p>
            <a href="catalog.php?search=kalung" class="text-reset">Kalung</a>
          </p>
          <p>
            <a href="catalog.php?search=cincin" class="text-reset">Cincin</a>
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
  <script>
  // Ambil semua elemen dengan kelas 'editButton'
  const editButtons = document.querySelectorAll('.editButton');

  // Loop melalui setiap tombol 'Edit' dan tambahkan event listener
  editButtons.forEach(button => {
    button.addEventListener('click', function(event) {
      event.preventDefault(); // Mencegah tindakan default

      // Ambil nilai data-form-id dari tombol yang diklik
      const formId = button.getAttribute('data-form-id');

      // Lakukan tindakan yang diinginkan dengan ID produk
      console.log('ID Produk: ' + formId);
      var features = 'width=800,height=600,resizable=yes,scrollbars=yes';
      // Tambahkan kode JavaScript lain yang diperlukan, seperti membuka pop-up window
      // atau melakukan permintaan AJAX untuk mendapatkan data produk

      // Contoh: Buka pop-up window dengan URL yang ditentukan
      const url = 'form/editform.php?id_produk=' + formId;
      window.open(url, '_blank',features);
    });
  });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
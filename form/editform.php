<?php
include "config.php";
session_start();


function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
    }
// Periksa apakah data 'update' telah dikirim melalui POST

    // Periksa apakah ID produk tersedia dalam URL
    if (isset($_GET['id_produk'])) {
        // Dapatkan ID produk dari URL
        $id_produk = $_GET['id_produk'];
        if (isset($_POST['update'])) {
            
            // Sanitasi input pengguna
        // Sanitize user inputs
        $id_produk = sanitize($_POST['id_produk']);
        $nama_produk = sanitize($_POST['nama_produk']);
        $harga_produk = sanitize($_POST['harga_produk']);
        $deskripsi_produk = sanitize($_POST['deskripsi_produk']);
        $jumlah = sanitize($_POST['jumlah']);
        $file_size = $_FILES['gambar']['size'];
        $file_type = $_FILES['gambar']['type'];
        
        if ($file_size < 2048000 and ($file_type =='image/jpeg' || $file_type == 'image/png'))
        {$image=addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
        $query = "UPDATE produk SET nama_produk='$nama_produk', harga_produk='$harga_produk', deskripsi_produk='$deskripsi_produk', gambar='$image' WHERE id_produk='$id_produk'";
        mysqli_query($conn, $query);
        $query_stock = "UPDATE stock SET jumlah=$jumlah WHERE id_produk='$id_produk'";
        mysqli_query($conn, $query_stock);
        }
            // Lakukan pengolahan lebih lanjut sesuai kebutuhan, seperti melakukan pembaruan pada database
    
            // Redirect atau lakukan tindakan lain setelah pembaruan berhasil
    
            // Contoh: Redirect ke halaman lain
            echo '<script>';
            echo 'window.opener.location.reload();'; // Memuat ulang halaman utama
            echo 'window.close();'; // Menutup jendela pop-up
            echo '</script>';
            exit();
        } 
        // Lakukan pengolahan lebih lanjut sesuai kebutuhan, seperti mengambil data produk dari database

        // Tampilkan form untuk mengedit produk
        echo '<div class="row">';
        echo '<div class="col-lg-12 col-md-12 col-sm-12">';
        echo '<form method="POST" action="editform.php?id_produk='.$id_produk.'"enctype="multipart/form-data">';
        echo '<input type="hidden" name="id_produk" value="' . $id_produk . '">';
        echo '<h2 class="text-center my-4"><strong>DATA PRODUK LIELIEN SHOP</strong></h2>';
        echo '<div class="form-group">';
        echo '<label for="nama_produk">Nama Produk</label>';
        echo '<input type="text" class="form-control" name="nama_produk" required>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="harga_produk">Harga</label>';
        echo '<input type="number" class="form-control" name="harga_produk" required>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="deskripsi_produk">Deskripsi</label>';
        echo '<textarea class="form-control" name="deskripsi_produk" rows="3" required></textarea>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="gambar">Gambar</label>';
        echo '<input type="file" class="form-control" name="gambar" required>';
        echo '</div>';
        echo '<div class="form-group">';
        echo '<label for="jumlah">Stock</label>';
        echo '<input type="number" class="form-control" name="jumlah" required>';
        echo '</div>';
        echo '<button class="btn btn-danger my-3" type="submit" name="update">Update Item</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        // Jika ID produk tidak tersedia dalam URL, tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
        echo 'ID Produk tidak tersedia.';
    }

?>

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
    
</body>
</html>

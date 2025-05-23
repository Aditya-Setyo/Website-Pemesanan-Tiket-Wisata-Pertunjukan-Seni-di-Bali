<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $categori = $_POST['categori'];
    $price = $_POST['price'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $stocktiket = $_POST['stocktiket'];

    $targetDir = "../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $gambar = isset($_FILES['gambar']['name']) ? $_FILES['gambar']['name'] : '';
    $targetFile = $targetDir . basename($gambar);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (isset($_POST['submit'])) {
        if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
            $check = getimagesize($_FILES['gambar']['tmp_name']);
            if ($check === false) {
                echo "File yang diunggah bukan gambar.";
                $uploadOk = 0;
            }
        } else {
            echo "File tidak ditemukan.";
            $uploadOk = 0;
        }
    }

    if (file_exists($targetFile)) {
        echo "File sudah ada.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO destinasi (name, categori, price, lokasi, deskripsi, stocktiket, gambar) 
                    VALUES ('$name', '$categori', '$price', '$lokasi', '$deskripsi', '$stocktiket', '$gambar')";

            if (mysqli_query($con, $sql)) {
                header('Location: ../../indexadmin.php');
                exit();
            } else {
                echo "Error: " . mysqli_error($con);
            }
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Destinasi</title>
    <link rel="stylesheet" href="../css/styleadmincreat.css" />
</head>

<body>
    <div class="form-container">
        <h2>Tambah Destinasi Baru</h2>
        <form action="creat.php" method="POST" enctype="multipart/form-data">
            <label for="name">Nama Destinasi</label>
            <input type="text" id="name" name="name" placeholder="Nama Destinasi" required>

            <label for="categori">categori</label>
            <input type="text" id="categori" name="categori" placeholder="Kategori" required>

            <label for="price">Harga</label>
            <input type="number" step="0.01" id="price" name="price" placeholder="Harga" required>

            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" placeholder="Lokasi" required>

            <label for="stocktiket">Stok Tiket</label>
            <input type="number" step="0.01" id="stocktiket" name="stocktiket" placeholder="Stok Tiket" required>

            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi Destinasi" required></textarea>

            <label for="gambar">Unggah Gambar</label>
            <input type="file" id="gambar" name="gambar" required>

            <button type="submit">Add New</button>
        </form>
    </div>
</body>

</html>
<?php 
require 'koneksi.php';  

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM destinasi WHERE destinasi_id = $id";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Destinasi not found.";
        exit();
    }
} else {
    echo "No destinasi ID provided.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $categori = $_POST['categori'];
    $price = $_POST['price'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $stocktiket = $_POST['stocktiket'];

    $sql = "UPDATE destinasi SET
             name = '$name',
             categori = '$categori',
             price = '$price',
             lokasi = '$lokasi',
             deskripsi = '$deskripsi',
             stocktiket = '$stocktiket'
             WHERE destinasi_id = $id";

    if (mysqli_query($con, $sql)) {
        header('Location: ../../indexadmin.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Product</title>
    <link rel="stylesheet" href="../css/styleadmincreat.css" />
</head>
<body>
    <div class="form-container">
        <h2>Update Product</h2>
        <form action="../php/update.php?id=<?php echo $id; ?>" method="POST">
            <label for="name">Nama Destinasi</label>
            <input type="text" name="name" placeholder="Product Name" value="<?php echo $product['name']; ?>" required>

            <label for="categori">Categori</label>
            <input type="text" name="categori" placeholder="Categori" value="<?php echo $product['categori']; ?>" required>

            <label for="price">Harga</label>
            <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo $product['price']; ?>" required>

            <label for="lokasi">Lokasi</label>
            <input type="text" name="lokasi" placeholder="Lokasi" value="<?php echo $product['lokasi']; ?>" required>

            <label for="stocktiket">Sctok Tiket</label>
            <input type="number" step="0.01" name="stocktiket" placeholder="stocktiket" value="<?php echo $product['stocktiket']; ?>" required>

            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi Destinasi" required><?php echo $product['deskripsi']; ?></textarea>

            <button type="submit">Update Destination</button>
        </form>
    </div>
</body>
</html>

<?php
require 'asset/php/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Destinasi Wisata</title>
    <link rel="stylesheet" href="asset/css/styleadmin.css" />
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                alert(message);
            }
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h1>Pariwisata</h1>
        <div class="categories">
            <h2>Categori</h2>
            <ul>
                <?php
                $sql_categories = "SELECT categori, COUNT(*) as count FROM destinasi GROUP BY categori";
                $result_categories = mysqli_query($con, $sql_categories);

                if (mysqli_num_rows($result_categories) > 0) {
                    while ($row_category = mysqli_fetch_assoc($result_categories)) {
                        echo '<li>' . $row_category['categori'] . ' <span>(' . $row_category['count'] . ')</span></li>';
                    }
                } else {
                    echo '<li>No Founds Categori</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
            <h2>All Destination</h2>
            <a href="asset/php/creat.php"><button>Add New Destination</button></a>
            <a href="index.php"><button>Log Out</button></a>
        </header>

        <div class="product-grid">
            <?php
            $sql_destinasi = "SELECT * FROM destinasi";
            $result_destinasi = mysqli_query($con, $sql_destinasi);

            if (mysqli_num_rows($result_destinasi) > 0) {
                while ($row = mysqli_fetch_assoc($result_destinasi)) {
                    echo '<div class="product-card">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>Lokasi: ' . $row['lokasi'] . '</p>';
                    echo '<p>categori: ' . $row['categori'] . '</p>';
                    echo '<p>Harga: Rp ' . number_format($row['price'], 2, ',', '.') . '</p>';
                    echo '<p>Deskripsi: ' . $row['deskripsi'] . '</p>';
                    echo '<div class="product-actions">';
                    echo '<a href="asset/php/update.php?id=' . $row['destinasi_id'] . '"><button>Edit</button></a>';
                    echo '<form action="asset/php/delet.php" method="POST" style="display:inline;">';
                    echo '<input type="hidden" name="id" value="' . $row['destinasi_id'] . '">';
                    echo '<button type="submit">Hapus</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p style="margin-top: 36px;">No Founds Destinations</p>';
            }
            ?>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>

<?php
require '../asset/php/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        // Registrasi Pengguna
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];

        if ($password === $confirmpassword) {
            $sql = "INSERT INTO user (name, email, password, confirmpassword) 
                    VALUES ('$name', '$email', '$password', '$confirmpassword')";
            if (mysqli_query($con, $sql)) {
                $_SESSION['message'] = "Registrasi berhasil!";
                header('Location: indexadmin.php');
                exit();
            } else {
                $_SESSION['error'] = "Error: " . mysqli_error($con);
            }
        } else {
            $_SESSION['error'] = "Password dan Konfirmasi Password tidak sesuai!";
        }
    }

    if (isset($_POST['login'])) {
        // Login Pengguna
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['user'] = mysqli_fetch_assoc($result);
            header('Location: indexadmin.php');
            exit();
        } else {
            $_SESSION['error'] = "Email atau Password salah!";
        }
    }
}

// Ambil data destinasi dari database
$sql = "SELECT * FROM destinasi";
$result = mysqli_query($con, $sql);

$destinations = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $destinations[] = $row;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($con, $_GET['keyword']);

    // Query untuk mencari tempat wisata berdasarkan nama
    $sql = "SELECT * FROM destinasi WHERE name LIKE ?";
    $stmt = $con->prepare($sql);
    $searchKeyword = "%$keyword%";
    $stmt->bind_param('s', $searchKeyword);
    $stmt->execute();
    $result = $stmt->get_result();

    $destinations = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $destinations[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/css/styleabout.css">
    <title>Travel Website</title>
</head>

<body>
    <div class="navbar">
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="aboutUs.php">About Us</a></li>
            <li><a href="#">Wishlist</a></li>
        </ul>
        <ul class="buttontextlogin">
            <li><button id="open-popup-login">Login</button></li>
            <li><button id="open-popup-regis">Register</button></li>
        </ul>

    </div>
    <!-- conten -->
    <div class="containerheader">
        <header>
            <h1>About Us</h1>
            <p>Welcome to Travelin Aja, your trusted guide to explore the beauty of the paradise island, Bali! We are here to provide the best experience for those who want to explore the cultural charm, natural beauty, and traditional richness that make Bali one of the best tourist destinations in the world.</p>
        </header>
    </div>

    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Contact Information -->
            <div class="contact-info">
                <div class="contact-item">
                    <img src="../asset/image/icontlp.png" alt="Phone" class="icon" />
                    <a href="tel:+1225555018">0857-2905-8285</a>
                </div>
                <div class="contact-item">
                    <img src="../asset/image/iconemail.png" alt="Email" class="icon" />
                    <a href="Gataco.Official@gmail.com">Gataco.Official@gmail.com</a>
                </div>
            </div>

            <!-- Promo Text -->
            <div class="promo-text">Follow Us and get a chance to win 80% off</div>

            <!-- Social Media Links -->
            <div class="social-media">
                <span>Follow Us:</span>
                <a href="https://www.facebook.com/"><img src="../asset/image/iconfb.png" alt="Facebook" class="social-icon" /></a>
                <a href="https://www.instagram.com/"><img src="../asset/image/iconig.png" alt="Instagram" class="social-icon" /></a>
                <a href="https://www.youTube.com/"><img src="../asset/image/iconyt.png" alt="YouTube" class="social-icon" /></a>
            </div>
        </div>
    </footer>

    <!-- Register Popup -->
    <div id="popupRegis" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form method="POST" action="index.php">
                <h2>Register !</h2>
                <input class="form-input" type="text" name="name" placeholder="Name" required />
                <input class="form-input" type="email" name="email" placeholder="Email" required />
                <input class="form-input" type="password" name="password" placeholder="Password" required />
                <input class="form-input" type="password" name="confirmpassword" placeholder="Confirm Password" required />
                <button class="form-button" type="submit" name="register">Register</button>
            </form>
        </div>
    </div>

    <!-- Login Popup -->
    <div id="popupLogin" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form method="POST" action="index.php">
                <h2>Login !</h2>
                <input class="form-input" type="email" name="email" placeholder="Email" required />
                <input class="form-input" type="password" name="password" placeholder="Password" required />
                <button class="form-button" type="submit" name="login">Login</button>
            </form>
        </div>
    </div>

    <div id="successPopup" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Data berhasil disimpan!</p>
        </div>
    </div>



    <script src="asset/javascript/script.js"></script>
</body>

</html>
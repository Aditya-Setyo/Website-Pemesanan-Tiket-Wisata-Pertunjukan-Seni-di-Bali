<?php
require 'asset/php/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $captcha_input = $_POST['captcha'] ?? '';
        if (empty($captcha_input) || $captcha_input !== $_SESSION['captcha']) {
            $_SESSION['error'] = "CAPTCHA tidak valid!";
            header('Location: index.php');
            exit();
        }

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
        $captcha_input = $_POST['captcha'] ?? '';
        if (empty($captcha_input) || $captcha_input !== $_SESSION['captcha']) {
            $_SESSION['error'] = "CAPTCHA tidak valid!";
            header('Location: index.php');
            exit();
        }
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

$allDestinations = [];
$sqlAllDestinations = "SELECT * FROM destinasi";
$resultAllDestinations = mysqli_query($con, $sqlAllDestinations);

if ($resultAllDestinations && mysqli_num_rows($resultAllDestinations) > 0) {
    while ($row = mysqli_fetch_assoc($resultAllDestinations)) {
        $allDestinations[] = $row;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($con, $_GET['keyword']);

    $sql = "SELECT * FROM destinasi WHERE name LIKE ?";
    $stmt = $con->prepare($sql);
    $searchKeyword = "%$keyword%";
    $stmt->bind_param('s', $searchKeyword);
    $stmt->execute();
    $result = $stmt->get_result();

    $searchResults = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($searchResults);
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/css/style.css">
    <title>Destinasi Wisata</title>
</head>

<body>

    <div class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="pages/aboutUs.php">About Us</a></li>
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
            <h1>The whole world awaits.</h1>
            <form id="searchForm" method="GET" action="index.php">
                <input type="text" id="searchKeyword" name="keyword" placeholder="Search destinations..." required>
                <button type="submit">Search</button>
            </form>
        </header>
    </div>

    <div class="destination-overview" style="top: -110px;">
        <div class="destination-content">
            <h2>Bali</h2>
            <p>
                Bali Island, often dubbed as the "Island of the Gods", is a tourist destination that captivates millions of travelers from all over the world. Famous for its natural beauty, rich culture, and friendly people, Bali offers an unforgettable experience for anyone who visits.
            </p>
        </div>
    </div>

    <h2 class="judul">Destination</h2>
    <div class="card-container">
        <?php if (!empty($allDestinations)) : ?>
            <?php foreach ($allDestinations as $destination) : ?>
                <div class="card">
                    <img src="asset/image/<?php echo htmlspecialchars($destination['gambar']); ?>" alt="Destination Image">
                    <div class="card-content">
                        <h3>
                            <a style="text-decoration: none; color: black;"
                                href="pages/haltiket.php?destination=<?php echo isset($destination['id']) ? urlencode($destination['id']) : ''; ?>">

                                <?php echo htmlspecialchars($destination['name']); ?>
                            </a>
                        </h3>
                        <ul>
                            <li>⏱ Deskripsi: 3 hours</li>
                            <li>🚍 Transport Facility</li>
                            <li>👨‍👩‍👧‍👦 Family Plan</li>
                        </ul>
                        <div class="card-footer">
                            <span class="price">Rp <?php echo number_format($destination['price']); ?> <small>per person</small></span>
                            <span class="reviews">⭐ 555 views</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p style="text-align: center; color: red;">Destinasi tidak tersedia saat ini.</p>
        <?php endif; ?>
    </div>



    <div id="destination-popup" class="destination-popup-wrapper" style="display: none;">
        <div class="destination-popup-content">
            <span class="destination-close-btn" onclick="closePopup()">&times;</span>
            <div id="popup-detail">
                <div class="card-container">
                    <?php if (!empty($destinations)) : ?>
                        <?php foreach ($destinations as $destination) : ?>
                            <div class="card">
                                <img src="asset/image/<?php echo htmlspecialchars($destination['gambar']); ?>" alt="Destination Image">
                                <div class="card-content">
                                    <h3>
                                        <a
                                            href="pages/haltiket.php?destination=<?php echo urlencode($destination['id'] ?? ''); ?>"
                                            style="text-decoration: none; color: inherit;">
                                            <?php echo htmlspecialchars($destination['name'] ?? 'Unknown Destination'); ?>
                                        </a>
                                    </h3>
                                    <ul>
                                        <li>⏱ Deskripsi: 3 hours</li>
                                        <li>🚍 Transport Facility</li>
                                        <li>👨‍👩‍👧‍👦 Family Plan</li>
                                    </ul>
                                    <div class="card-footer">
                                        <span class="price">Rp <?php echo number_format($destination['price']); ?> <small>per person</small></span>
                                        <span class="reviews">⭐ 555 views</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p style="text-align: center; color: red;">Destinasi tidak ditemukan.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Contact Information -->
            <div class="contact-info">
                <div class="contact-item">
                    <img src="asset/image/icontlp.png" alt="Phone" class="icon" />
                    <a href="tel:+1225555018">0857-2905-8285</a>
                </div>
                <div class="contact-item">
                    <img src="asset/image/iconemail.png" alt="Email" class="icon" />
                    <a href="Gataco.Official@gmail.com">Gataco.Official@gmail.com</a>
                </div>
            </div>

            <!-- Promo Text -->
            <div class="promo-text">Follow Us and get a chance to win 80% off</div>

            <!-- Social Media Links -->
            <div class="social-media">
                <span>Follow Us:</span>
                <a href="https://www.facebook.com/"><img src="asset/image/iconfb.png" alt="Facebook" class="social-icon" /></a>
                <a href="https://www.instagram.com/"><img src="asset/image/iconig.png" alt="Instagram" class="social-icon" /></a>
                <a href="https://www.youTube.com/"><img src="asset/image/iconyt.png" alt="YouTube" class="social-icon" /></a>
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
                <img src="capctha.php" alt="CAPTCHA">
                <input class="form-input" type="text" name="captcha" placeholder="Enter CAPTCHA" required />
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
                <img src="capctha.php" alt="CAPTCHA">
                <input class="form-input" type="text" name="captcha" placeholder="Enter CAPTCHA" required />
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
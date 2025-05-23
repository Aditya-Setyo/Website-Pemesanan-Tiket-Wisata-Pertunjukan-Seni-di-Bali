<?php
require '../asset/php/koneksi.php'; 
session_start();


$sql_ticket = "SELECT * FROM destinasi LIMIT 1"; 
$result_ticket = mysqli_query($con, $sql_ticket);
$ticket = mysqli_fetch_assoc($result_ticket);


$bookingDetails = null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $quantity = intval($_POST['quantity']);
  $totalprice = floatval($_POST['totalprice']);


  $query = "INSERT INTO bookings (name, totalprice, quantity) VALUES ('$name', '$totalprice', '$quantity')";
  if (mysqli_query($con, $query)) {
    
    $bookingDetails = [
      'name' => $name,
      'quantity' => $quantity,
      'totalprice' => $totalprice,
      'destination' => $ticket['name'],
    ];
  } else {
    echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Website</title>
  <link rel="stylesheet" href="../asset/css/styletiket.css">
  <script>
    // Fungsi untuk menghitung total harga berdasarkan jumlah tiket
    function calculateTotal() {
      const pricePerTicket = parseFloat(document.getElementById('pricePerTicket').value);
      const quantity = parseInt(document.getElementById('quantity').value) || 0;
      const totalPrice = pricePerTicket * quantity;
      document.getElementById('totalprice').value = totalPrice.toFixed(2);
    }

    // Fungsi untuk mencetak tiket
    function printTicket() {
      const ticketDiv = document.getElementById('ticket-details');
      const originalContent = document.body.innerHTML;
      document.body.innerHTML = ticketDiv.innerHTML;
      window.print();
      document.body.innerHTML = originalContent;
    }
  </script>
</head>

<body>


  <div class="navbar">
    <ul>
      <li><a href="../index.php">Home</a></li>
      <li><a href="aboutUs.php">About Us</a></li>
      <li><a href="../index.php">Wishlist</a></li>
    </ul>
    <ul class="buttontextlogin">
      <li><button id="open-popup-login">Login</button></li>
      <li><button id="open-popup-regis">Register</button></li>
    </ul>
  </div>

  <main>
    <div class="container">
      <div class="content">
        <?php if ($ticket): ?>
          <h2><?php echo htmlspecialchars($ticket['name']); ?></h2>
          <p class="location">📍 <?php echo htmlspecialchars($ticket['lokasi']); ?> <span>★ 4.8 (348 reviews)</span></p>
          <div class="image-gallery">
            <div class="main-image">
              <img src="../asset/image/<?php echo htmlspecialchars($ticket['gambar']); ?>" alt="Destination Image">
            </div>
          </div>
        <?php else: ?>
          <h2>Destinasi tidak ditemukan.</h2>
        <?php endif; ?>
      </div>

      <?php if (!$bookingDetails): ?>
        <!-- Form Pemesanan -->
        <aside class="booking-card">
          <h3>Buy Ticketing</h3>
          <form action="" method="POST">
            <!-- Harga tiket dari database -->
            <input type="hidden" id="pricePerTicket" value="<?php echo htmlspecialchars($ticket['price']); ?>">

            <label for="name">Nama</label>
            <input type="text" id="name" name="name" placeholder="Nama Anda" required>

            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" placeholder="Jumlah Tiket" required oninput="calculateTotal()">

            <label for="totalprice">Total Harga (Rp)</label>
            <input type="text" id="totalprice" name="totalprice" readonly placeholder="Total Harga">

            <button type="submit" class="confirm-booking">Buy</button>
          </form>
        </aside>
      <?php else: ?>
        <!-- Detail Tiket -->
        <div id="ticket-details" style="width: 500px; margin: 20px auto; font-family: Arial, sans-serif; border: 2px solid #ccc; border-radius: 10px; background-color: #f9f9f9; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 20px;">
          <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #333;">🎟️ E-Ticket</h2>
            <p style="font-size: 14px; color: #777;">Booking Confirmation</p>
          </div>
          <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr style="background-color: #f1f1f1; text-align: left;">
              <th style="padding: 10px; border: 1px solid #ddd;">Keterangan</th>
              <th style="padding: 10px; border: 1px solid #ddd;">Detail</th>
            </tr>
            <tr>
              <td style="padding: 10px; border: 1px solid #ddd;">Nama</td>
              <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($bookingDetails['name']); ?></td>
            </tr>
            <tr>
              <td style="padding: 10px; border: 1px solid #ddd;">Destinasi</td>
              <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($bookingDetails['destination']); ?></td>
            </tr>
            <tr>
              <td style="padding: 10px; border: 1px solid #ddd;">Jumlah Tiket</td>
              <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($bookingDetails['quantity']); ?></td>
            </tr>
            <tr>
              <td style="padding: 10px; border: 1px solid #ddd;">Total Harga</td>
              <td style="padding: 10px; border: 1px solid #ddd;">Rp <?php echo number_format($bookingDetails['totalprice'], 2, ',', '.'); ?></td>
            </tr>
          </table>
          <div style="text-align: center;">
            <p style="font-size: 14px; color: #777;">Tunjukkan tiket ini di lokasi untuk masuk</p>
            <button onclick="printTicket()" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Cetak Tiket</button>
          </div>
        </div>

      <?php endif; ?>
    </div>
  </main>
</body>

</html>
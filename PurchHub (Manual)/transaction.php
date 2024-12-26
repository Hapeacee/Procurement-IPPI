<?php
// Connection to Database
include 'db_connection.php'; // Pastikan file db_connection.php berisi informasi koneksi yang benar
include 'fetch-data.php';
$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $transaction_type = $_POST['transaction_type'];
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $created_at = $_POST['created_at'];

    // Insert data ke tabel transactions
    $stmt = $conn->prepare("INSERT INTO transactions (transaction_type, item_id, quantity, created_at) VALUES (:transaction_type, :item_id, :quantity, :created_at)");
    $stmt->bindParam(':transaction_type', $transaction_type);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->execute();

    echo "<script type='text/javascript'>
        alert('Data transaksi berhasil dimasukkan!');
        window.location.href= 'transaction.php'; // Redirect ke halaman transaksi setelah alert
      </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transactions | Procurement</title>
  <link rel="stylesheet" href="css/dashboard.css"/>
  <link rel="icon" href="img/IPPI LOGO.png" type="image/icon type">
  <script src="js/dashboard.js" defer></script>
  <script src="js/transaction.js" defer></script>
  <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/transaction.css">

</head>
<body>
<nav class="navbar">
    <div class="logo-brand">
        <img src="img/IPPI LOGO.png" alt="Logo" class="logo">
        <div class="brand-info">
            <h1 class="brand">IPPI</h1>
            <p class="division">Procurement</p>
        </div>
    </div>
    <ul class="navbar-menu">
    <li><a href="dashboard.php">Dashboard</a></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle">
            Data Entry <i class="bx bx-chevron-down"></i>
        </a>
            <ul class="dropdown-menu">
                <li><a href="/Purchhub (Manual)/transaction.php">Input Data</a></li>
                <li><a href="/Purchhub (Manual)/items.php">Items</a></li>
                <li><a href="/Purchhub (Manual)/alldata.php">Data</a></li>
                <!-- <li><a href="/Purchhub (Manual)/ph.php">PH</a></li> --> 
            </ul>
        </li>
        <li><a href="/about">About</a></li>                               
        <li><a href="/setting">Setting</a></li>
    </ul>
    <div class="hamburger">
        <div></div>
        <div></div>
        <div></div>
    </div>
</nav>


  <!-- Form Input Data Transaction -->
  <div class="container">
    <h2 class="section-title">Input Data Transaction</h2>
    <form action="transaction.php" method="POST" class="transaction-form">
        <div class="form-group">
            <label for="transaction_type">Transaction Type:</label>
            <select id="transaction_type" name="transaction_type" required>
                <option value="PR">PR</option>
                <option value="RFQ">RFQ</option>
                <option value="GR">GR</option>
                <option value="PH">PH</option>
            </select>
        </div>

        <div class="form-group">
            <label for="item_id">Item:</label>
            <select id="item_id" name="item_id" required>
                <!-- Data item diisi menggunakan fetch dari database -->
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required placeholder="Enter Quantity" />
        </div>

        <div class="form-group">
            <label for="created_at">Date:</label>
            <input type="date" id="created_at" name="created_at" required />
        </div>

        <button type="submit" class="btn-submit">Submit</button>
    </form>
  </div>
</body>
</html>

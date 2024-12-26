<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];

    $query = "INSERT INTO items (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $item_name, $item_description);

    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add item!');</script>";
    }

    $stmt->close();
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items | Procurement</title>
    <link rel="stylesheet" href="css/dashboard.css"/>
  <link rel="icon" href="img/IPPI LOGO.png" type="image/icon type">
  <!-- Boxicons CSS -->
  <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <script src="js/dashboard.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/items.css">
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

<section class="add-item">
    <h2>Add New Item</h2>
    <form action="items.php" method="POST">
        <label for="item-name">Item Name:</label>
        <input type="text" id="item-name" name="item_name" required />

        <label for="item-description">Description:</label>
        <textarea id="item-description" name="item_description" rows="3"></textarea>

        <button type="submit">Add Item</button>
    </form>
</section>

</body>
</html>
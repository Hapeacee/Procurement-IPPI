<?php
// fetch-data.php
include 'db_connection.php';
include 'fetch-data.php';
$query = "SELECT * FROM items";
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | Procurement</title>
  <link rel="stylesheet" href="css/dashboard.css"/>
  <link rel="icon" href="img/IPPI LOGO.png" type="image/icon type">
  <!-- Boxicons CSS -->
  <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <script src="js/dashboard.js" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
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

<div class="main-content"> 
    <h1>Dashboard</h1>

    <!-- Dropdown Bulan -->
    <div class="dropdown-container">
      <label name="month-dropdown">Filter by Month:</label>
      <select id="monthDropdown" name="month-dropdown" onchange="location.href='dashboard.php?month=' + this.value + '&year=' + document.getElementById('yearDropdown').value">
    <?php for ($m = 1; $m <= 12; $m++): ?>
        <option value="<?php echo $m; ?>" <?php echo ($m == $selected_month) ? 'selected' : ''; ?>>
            <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
        </option>
    <?php endfor; ?>
  </select>
  <br>
  <label for="yearDropdown">Year:</label>
  <select id="yearDropdown" name="year-dropdown" onchange="location.href='dashboard.php?month=' + document.getElementById('monthDropdown').value + '&year=' + this.value">
    <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
        <option value="<?php echo $y; ?>" <?php echo ($y == $selected_year) ? 'selected' : ''; ?>>
            <?php echo $y; ?>
        </option>
    <?php endfor; ?>
  </select>
    </div>

    <!-- Summary Section with Total Quantity per Type -->
    <section class="summary">
        <div class="summary-card">
            <h3>PR</h3>
            <p>Total Items: <span id="pr-total"><?php echo $totals['PR']; ?></span></p> <!-- SUMMARY PR -->
        </div>
        <div class="summary-card">
            <h3>RFQ</h3>
            <p>Total Items: <span id="rfq-total"><?php echo $totals['RFQ']; ?></span></p> <!-- SUMMARY RFQ -->
        </div>
        <div class="summary-card">
            <h3>GR</h3>
            <p>Total Items: <span id="gr-total"><?php echo $totals['GR']; ?></span></p> <!-- SUMMARY GR -->
        </div>
        <div class="summary-card">
            <h3>PH</h3>
            <p>Total Items: <span id="ph-total"><?php echo $totals['PH']; ?></span></p> <!-- SUMMARY PH -->
        </div>
    </section>

    <!-- Items Not Inputted Section -->
    <section class="not-input-summary">
    <h2>Not Inputted Items for <?php echo date('F Y', mktime(0, 0, 0, $selected_month, 1, $selected_year)); ?></h2>
    <div class="not-input-container">

        <!-- PR -->
        <div class="not-input-card">
            <h3>PR</h3>
            <p>Total Not Inputted: <span><?php echo count($not_input_pr_items); ?></span></p>
            <p>Items:</p>
            <ul>
                <?php foreach ($not_input_pr_items as $item): ?>
                    <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- RFQ -->
        <div class="not-input-card">
            <h3>RFQ</h3>
            <p>Total Not Inputted: <span><?php echo count($not_input_rfq_items); ?></span></p>
            <p>Items:</p>
            <ul>
                <?php foreach ($not_input_rfq_items as $item): ?>
                    <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- GR -->
        <div class="not-input-card">
            <h3>GR</h3>
            <p>Total Not Inputted: <span><?php echo count($not_input_gr_items); ?></span></p>
            <p>Items:</p>
            <ul>
                <?php foreach ($not_input_gr_items as $item): ?>
                    <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- PH -->
        <div class="not-input-card">
            <h3>PH</h3>
            <p>Total Not Inputted: <span><?php echo count($not_input_ph_items); ?></span></p>
            <p>Items:</p>
            <ul>
                <?php foreach ($not_input_ph_items as $item): ?>
                    <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
  </div>
</body>
</html>

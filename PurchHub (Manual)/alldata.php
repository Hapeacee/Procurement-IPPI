<?php
// Sertakan koneksi database
include('db_connection.php');

// Handle Delete Request
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM transactions WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data deleted successfully');</script>";
    } else {
        echo "<script>alert('Failed to delete data');</script>";
    }
    $stmt->close();
}

// Mendapatkan data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $transaction_type = $_POST['transaction_type'];
    $quantity = $_POST['quantity'];

    // Validasi data
    if (in_array($transaction_type, ['PR', 'RFQ', 'GR', 'PH'])) {
        // Query untuk update data
        $sql = "UPDATE transactions SET transaction_type = ?, quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $transaction_type, $quantity, $id); // 's' untuk string, 'i' untuk integer
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Data berhasil diperbarui!";
        } else {
            echo "Terjadi kesalahan saat memperbarui data!";
        }
    } else {
        echo "Jenis transaksi tidak valid!";
    }
}


// Fetch Data for Display
$result = $conn->query("SELECT transactions.id, transactions.item_id, items.name as item_name, transaction_type, quantity, created_at 
                        FROM transactions 
                        JOIN items ON transactions.item_id = items.id 
                        ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data | Procurement</title>
    <link rel="stylesheet" href="css/dashboard.css" />
    <link rel="icon" href="img/IPPI LOGO.png" type="image/icon type">
    <link rel="stylesheet" href="css/alldata.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/dashboard.js" defer></script>
    <script src="js/alldata.js" defer></script>
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
    </nav>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="container table-container">
        <div class="table-header">
            <h2 class="header-title">All Transactions</h2>
        </div>
        <div class="table-scroll">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Transaction Type</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['item_name']; ?></td>
                                <td><?php echo $row['transaction_type']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>
                                    <?php
                                    // Mengubah tanggal menjadi format yang lebih mudah dibaca
                                    $created_at = strtotime($row['created_at']);
                                    echo date("d F Y, H:i:s", $created_at); // Format: 12 Desember 2024 23:59
                                    ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-update" onclick="openUpdateModal(
                                    <?php echo $row['id']; ?>, 
                                    <?php echo $row['item_id']; ?>, 
                                    '<?php echo addslashes($row['transaction_type']); ?>', 
                                    <?php echo $row['quantity']; ?>
                                )">
                                        Update
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-delete"
                                            onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No transactions found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Pop-up Update -->
    <div class="modal" id="updateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Transaction</h5>
                    <button type="button" class="btn-close" onclick="closeUpdateModal()">&times;</button>
                </div>
                <form id="updateForm">
                    <div class="modal-body">
                        <input type="hidden" id="update-id" name="id">
                        <div class="mb-3">
                            <label for="update-transaction-type" class="form-label">Transaction Type</label>
                            <select id="update-transaction-type" name="transaction_type" class="form-control" required>
                                <option value="PR">PR</option>
                                <option value="RFQ">RFQ</option>
                                <option value="GR">GR</option>
                                <option value="PH">PH</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="update-quantity" class="form-label">Quantity</label>
                            <input type="number" id="update-quantity" name="quantity" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeUpdateModal()">Close</button>
                        <button type="button" class="btn btn-primary" id="saveUpdateBtn">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Modal Backdrop -->
    <div class="modal-backdrop"></div>
</body>
</html>
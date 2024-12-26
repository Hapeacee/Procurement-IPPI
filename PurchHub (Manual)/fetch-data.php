<?php
// Include koneksi database
include 'db_connection.php';

// Menangani pengambilan bulan dan tahun dari dropdown
$selected_month = isset($_GET['month']) ? (int)$_GET['month'] : date('n'); // Default bulan saat ini
$selected_year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // Default tahun saat ini

// Query untuk mendapatkan total quantity per tipe transaksi (PR, RFQ, GR, PH) untuk bulan dan tahun yang dipilih
$query_total = "
    SELECT transaction_type, SUM(quantity) as total_quantity
    FROM transactions
    WHERE MONTH(created_at) = ? AND YEAR(created_at) = ? AND transaction_type IN ('PR', 'RFQ', 'GR', 'PH')
    GROUP BY transaction_type
";
$stmt_total = $conn->prepare($query_total);
$stmt_total->bind_param("ii", $selected_month, $selected_year); // Bind bulan dan tahun yang dipilih
$stmt_total->execute();
$result_total = $stmt_total->get_result();

// Menyimpan total quantity per tipe transaksi
$totals = [
    'PR' => 0,
    'RFQ' => 0,
    'GR' => 0,
    'PH' => 0
];

// Mengambil hasil query dan memasukkan ke dalam array $totals
while ($row = $result_total->fetch_assoc()) {
    $totals[$row['transaction_type']] = $row['total_quantity'];
}

// Query untuk mendapatkan item yang belum terinput untuk bulan yang dipilih
// $query_not_input = "
//     SELECT i.name
//     FROM items i
//     LEFT JOIN transactions t ON i.id = t.item_id AND MONTH(t.created_at) = ?
//     WHERE t.item_id IS NULL
// ";
// $stmt_not_input = $conn->prepare($query_not_input);
// $stmt_not_input->bind_param("i", $selected_month); // Bind bulan yang dipilih
// $stmt_not_input->execute();
// $result_not_input = $stmt_not_input->get_result();

// // Menyimpan item yang belum terinput
// $not_input_items = [];
// while ($row = $result_not_input->fetch_assoc()) {
//     $not_input_items[] = $row['name'];
// }
// Query not inputted items untuk transaksi tertentu (PR, RFQ, GR, PH)
// Ambil bulan dan tahun dari parameter GET, gunakan bulan dan tahun sekarang jika tidak ada.
$selected_month = $_GET['month'] ?? date('m');
$selected_year = $_GET['year'] ?? date('Y');

// Fungsi untuk mendapatkan not inputted items berdasarkan jenis transaksi
function getNotInputtedItems($conn, $transaction_type, $month, $year) {
    $query = "
        SELECT i.name 
        FROM items i
        LEFT JOIN transactions t 
            ON i.id = t.item_id 
            AND t.transaction_type = ? 
            AND MONTH(t.created_at) = ? 
            AND YEAR(t.created_at) = ?
        WHERE t.item_id IS NULL
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $transaction_type, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ambil semua item sebagai array
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Dapatkan not inputted items untuk setiap jenis transaksi
$not_input_pr_items = array_column(getNotInputtedItems($conn, 'PR', $selected_month, $selected_year), 'name');
$not_input_rfq_items = array_column(getNotInputtedItems($conn, 'RFQ', $selected_month, $selected_year), 'name');
$not_input_gr_items = array_column(getNotInputtedItems($conn, 'GR', $selected_month, $selected_year), 'name');
$not_input_ph_items = array_column(getNotInputtedItems($conn, 'PH', $selected_month, $selected_year), 'name');
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Fetch Total Transactions
    if ($action === 'fetch_totals') {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

        $query = "
            SELECT transaction_type, SUM(quantity) as total_quantity
            FROM transactions
            WHERE MONTH(created_at) = ?
            GROUP BY transaction_type
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute([$month]);
        $totals = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($totals);
        exit;
    }

    // Fetch Items
    if ($action === 'fetch_items') {
        $query = "SELECT id, name FROM ITEMS";
        $stmt = $conn->prepare($query);
    
        if ($stmt->execute()) {
            $stmt->bind_result($id, $name);
    
            $items = [];
            while ($stmt->fetch()) {
                $items[] = [
                    'id' => $id,
                    'name' => $name,
                ];
            }
    
            header('Content-Type: application/json');
            echo json_encode($items);
        } else {
            // Jika eksekusi query gagal
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to fetch items']);
        }
        exit;
    }
    
}



?>

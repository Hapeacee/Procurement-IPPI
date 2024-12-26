<?php
// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "ippi_item_management";

// Connect to Database
$conn = mysqli_connect($host, $user, $password, $database);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

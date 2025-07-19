<?php
$host = "localhost";
$user = "root";
$pass = ""; // kosongkan jika tidak ada password
$db = "u965477476_rynee_db";

$conn = new mysqli($host, $user, $pass, $db, 3307);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

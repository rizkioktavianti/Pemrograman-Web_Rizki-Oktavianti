<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "u965477476_rynee_db";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: (" . $conn->connect_errno . ") " . $conn->connect_error);
}

// Optional debug
// echo "Koneksi ke database berhasil!";
?>

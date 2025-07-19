<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['checkout'])) {
    header("Location: keranjang.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];
$alamat = $_SESSION['checkout']['alamat'] ?? '';
$produk = $_SESSION['checkout']['produk'] ?? [];

$metode = $_POST['metode'] ?? '';
$kode = $_POST['kode'] ?? '';

if (!$metode || !$kode) {
    echo "<script>alert('Metode dan kode pembayaran harus diisi!'); window.location='pembayaran_selesai.php';</script>";
    exit;
}

// Hitung total belanja
$total_belanja = 0;
foreach ($produk as $item) {
    $harga = (int)$item['harga']; // sudah angka
    $jumlah = (int)$item['jumlah'];
    $total_belanja += ($harga * $jumlah);
}


// 1. Insert ke tabel transaksi
$stmt = $conn->prepare("INSERT INTO transaksi (user_id, user_nama, alamat, total, metode, tanggal) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issis", $user_id, $user_nama, $alamat, $total_belanja, $metode);
$stmt->execute();
$transaksi_id = $stmt->insert_id;
$stmt->close();


// 3. Bersihkan keranjang & checkout
unset($_SESSION['checkout']);
unset($_SESSION['keranjang']);

echo "<script>alert('Pembayaran berhasil dan transaksi disimpan!'); window.location='profile.php';</script>";
exit;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
      text-align: center;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      color: <?= $status_berhasil ? '#27ae60' : '#e74c3c' ?>;
      margin-bottom: 10px;
    }
    .badge {
      display: inline-block;
      padding: 8px 20px;
      background: <?= $status_berhasil ? '#27ae60' : '#e74c3c' ?>;
      color: white;
      border-radius: 25px;
      font-size: 16px;
      margin-bottom: 20px;
    }
    p {
      font-size: 16px;
      margin: 15px 0;
    }
    .btn {
      display: inline-block;
      margin-top: 30px;
      padding: 12px 24px;
      background: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-size: 16px;
    }
    .btn:hover {
      background: #2980b9;
    }
  </style>
</head>
<body>

<div class="container">
<?php if ($status_berhasil): ?>
  <div class="badge">Pembayaran Berhasil</div>
  <h2>🎉 Terima Kasih!</h2>
  <p>Transaksi kamu telah tercatat dengan baik.</p>
  <a href="profile.php" class="btn">Lihat Riwayat Pembelian</a>
<?php else: ?>
  <div class="badge">Pembayaran Gagal</div>
  <h2>❌ Kode Salah</h2>
  <p>Kode pembayaran yang kamu masukkan tidak valid. Silakan coba lagi.</p>
  <a href="keranjang_checkout.php" class="btn">🔁 Coba Lagi</a>
<?php endif; ?>
</div>

</body>
</html>

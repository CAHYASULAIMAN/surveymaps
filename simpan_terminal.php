<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
  http_response_code(403); // 403 Forbidden
  echo "Akses ditolak. Anda tidak memiliki izin.";
  exit;
}

$nama = $_POST['nama'] ?? '';
$lat = $_POST['latitude'] ?? 0;
$lng = $_POST['longitude'] ?? 0;

// Validasi sederhana
if (empty($nama) || !is_numeric($lat) || !is_numeric($lng)) {
  echo "Data tidak valid.";
  exit;
}

$stmt = $koneksi->prepare("INSERT INTO terminals (nama, latitude, longitude) VALUES (?, ?, ?)");

if ($stmt) {
  $stmt->bind_param("sdd", $nama, $lat, $lng);
  if ($stmt->execute()) {
    echo "Terminal berhasil ditambahkan!";
  } else {
    echo "Gagal menambahkan: " . $stmt->error;
  }
} else {
  echo "Query gagal: " . $koneksi->error;
}
?>

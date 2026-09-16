<?php
session_start();

if (!isset($_SESSION['login']) || !in_array($_SESSION['role'], ['teknis', 'admin'])) {
  header("Location: login.php?error=Akses ditolak");
  exit;
}

include 'koneksi.php';

$id               = $_POST['id'];
$nama             = $_POST['nama'];
$telepon          = $_POST['telepon'];
$alamat           = $_POST['alamat'];
$latitude         = $_POST['latitude'];
$longitude        = $_POST['longitude'];
$terminal         = $_POST['terminal_terdekat'];
$jarak            = $_POST['jarak'];
$status_survey    = $_POST['status_survey'];
$tanggal_update   = date("Y-m-d H:i:s");

$stmt = $koneksi->prepare("UPDATE pelanggan SET nama=?, telepon=?, alamat=?, latitude=?, longitude=?, terminal_terdekat=?, jarak=?, status_survey=?, tanggal_update=? WHERE id=?");
$stmt->bind_param("ssssssdssi", $nama, $telepon, $alamat, $latitude, $longitude, $terminal, $jarak, $status_survey, $tanggal_update, $id);

if ($stmt->execute()) {
  header("Location: survey_teknis.php?pesan=berhasil_update");
} else {
  echo "Gagal update: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>

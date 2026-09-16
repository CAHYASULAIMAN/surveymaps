<?php
session_start();

if (!isset($_SESSION['login']) || (time() - $_SESSION['timeout']) > 900) {
  session_destroy();
  http_response_code(401);
  echo "Session expired. Please login again.";
  exit;
}

$_SESSION['timeout'] = time();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo "Metode tidak diizinkan.";
  exit;
}

//koneksi ke database
include'koneksi.php';

$username = $_SESSION['username'];
$nama     = $koneksi->real_escape_string($_POST['nama']);
$email    = $koneksi->real_escape_string($_POST['email']);
$phone    = $koneksi->real_escape_string($_POST['phone']);
$whatsapp   = $koneksi->real_escape_string($_POST['whatsapp']);


$sql = "UPDATE users SET nama='$nama', email='$email', phone='$phone', whatsapp='$whatsapp'  WHERE username='$username'";

if ($koneksi->query($sql) === TRUE) {
  echo "Profil berhasil diperbarui.";
} else {
  http_response_code(500);
  echo "Gagal memperbarui profil: " . $koneksi->error;
}



$koneksi->close();

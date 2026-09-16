<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php?error=Akses ditolak");
  exit;
}

include 'koneksi.php';

$nama = $_POST['nama']; //string
$username = $_POST['username']; //string
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); //string
$email = $_POST['email']; //string
$role     = $_POST['role']; //string
$created_at = date("Y-m-d H:i:s"); //string

// Cek apakah username sudah ada
$cek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
$cek->bind_param("s", $username);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
  header("Location: register_user.php?error=username");
  exit;
}

// Simpan user baru
$stmt = $koneksi->prepare("INSERT INTO users (username, nama, password, email, role, created_at) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $username, $nama, $password, $role,$email, $created_at);

if ($stmt->execute()) {
        header("Location: register_user.php?success=1");
        exit;
} else {
  header("Location: register_user.php?error=insert");
  exit;
}
?>
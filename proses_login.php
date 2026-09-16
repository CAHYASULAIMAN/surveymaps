<?php
session_start();
include 'koneksi.php';

if (empty($_POST['username']) || empty($_POST['password'])) {
    header("Location: login.php?error=kosong");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$query = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    header("Location: login.php?error=akun");
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=pass");
    exit;
}

session_regenerate_id(true);
$_SESSION['login'] = true;
$_SESSION['username'] = $user['username'];
$_SESSION['id'] = $user['id'];
$_SESSION['role'] = $user['role'];
$_SESSION['nama'] = $user['nama'];
$_SESSION['timeout'] = time();

$now = date('Y-m-d H:i:s');
$update_login = $koneksi->prepare("UPDATE users SET last_login = ? WHERE id = ?");
$update_login->bind_param("si", $now, $user['id']);
$update_login->execute();

switch ($user['role']) {
    case 'admin':
        header("Location: dashboard_admin.php");
        break;
    case 'teknis':
        header("Location: dashboard_teknis.php");
        break;
    case 'sales':
        header("Location: dashboard_sales.php");
        break;
    default:
        header("Location: login.php?error=role");
        break;
}
exit;
?>

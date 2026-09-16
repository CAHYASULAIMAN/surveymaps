<?php
session_start();
if (!isset($_SESSION['login']) || !in_array($_SESSION['role'], ['admin', 'teknis'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    include 'koneksi.php'; // koneksi terpisah

    $id = intval($_POST['id']);
    $koneksi->query("DELETE FROM pelanggan WHERE id = $id");
}

header("Location: survey_teknis.php");
exit;
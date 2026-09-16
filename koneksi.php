<?php
date_default_timezone_set("Asia/Jakarta");

$koneksi = new mysqli("localhost", "root", "", "db_surveymap");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>

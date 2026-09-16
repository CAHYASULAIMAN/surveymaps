<?php
session_start();
include 'koneksi.php';

$sales = $_POST['sales'] ?? '';
$nama = $_POST['nama'];
$telepon = $_POST['telepon'];
$alamat = $_POST['alamat'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$terminal = $_POST['terminal'];
$jarak = $_POST['jarak'];
$tanggal_po = $_POST['tanggal_po'] ?? null;
if ($tanggal_po === 'Belum PO' || $tanggal_po === '') {
    $tanggal_po = null;
}
$keterangan = $_POST['keterangan'];
$status_survey = $_POST['status_survey'];



$stmt = $koneksi->prepare("INSERT INTO pelanggan (nama,sales, telepon, alamat, latitude, longitude, terminal_terdekat, jarak, tanggal_po, keterangan, status_survey)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssddsdsss", $nama,$sales, $telepon,$alamat , $lat, $lng, $terminal, $jarak, $tanggal_po, $keterangan, $status_survey);



if ($stmt->execute()) {
  echo "Berhasil disimpan";
} else {
  echo "Gagal menyimpan";
}
?>

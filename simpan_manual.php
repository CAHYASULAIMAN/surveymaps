<?php
session_start();
include 'koneksi.php';

$nama = $_POST['nama'];
$telepon = $_POST['telepon'];
$alamat = $_POST['alamat'];
$latitude   = $_POST['latitude'] ?? '';
$longitude  = $_POST['longitude'] ?? '';
$tanggal_po = $_POST['tanggal_po'];
$keterangan = $_POST['keterangan'];
$sales = $_POST['sales'] ?? '';

// Validasi dasar
if (!empty($nama) && !empty($telepon) && !empty($alamat) && is_numeric($latitude) && is_numeric($longitude)) {
    // Gunakan prepared statement
    $stmt = $koneksi->prepare("INSERT INTO pelanggan (nama, telepon, alamat, latitude, longitude, tanggal_po, keterangan, sales) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nama, $telepon, $alamat, $latitude, $longitude, $tanggal_po, $keterangan, $sales);

    if ($stmt->execute()) {
        echo "Data berhasil disimpan.";
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Data tidak lengkap atau format salah.";
}

$koneksi->close();
?>

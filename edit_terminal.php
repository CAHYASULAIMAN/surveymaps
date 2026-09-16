<?php
session_start();
// Koneksi ke database
include 'koneksi.php';
// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data terminal berdasarkan ID
$sql = "SELECT * FROM terminals WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$terminal = $result->fetch_assoc();

if (!$terminal) {
    die("Data terminal tidak ditemukan.");
}

// Update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $cluster = $_POST['cluster'];

    $sql = "UPDATE terminals SET nama = ?, latitude = ?, longitude = ?, cluster = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $nama, $latitude, $longitude, $cluster, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='update_terminal.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Terminal</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Edit Data Terminal</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label>Nama Terminal</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($terminal['nama']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($terminal['latitude']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($terminal['longitude']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Cluster</label>
                        <input type="text" name="cluster" class="form-control" value="<?= htmlspecialchars($terminal['cluster']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success rounded-pill"> <i class="far fa-save"></i> Simpan </button>
                    <a href="update_terminal.php" class="btn btn-warning rounded-pill">Kembali</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
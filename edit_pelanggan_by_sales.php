<?php
session_start();
// Koneksi ke database
include 'koneksi.php';
// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pelanggan berdasarkan ID yang di klik tadi
$sql_select = "SELECT * FROM pelanggan WHERE id = ?";
$stmt_select = $koneksi->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$pelanggan = $result->fetch_assoc();

if (!$pelanggan) {
    die("Data Calon tidak ditemukan.");
}

// Update data jika form disubmit
$alertMessage = ''; // Flag alert awal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama             = $_POST['nama'];
    $sales            = $_POST['sales'];
    $alamat           = $_POST['alamat'];
    $terminal_terdekat= $_POST['terminal_terdekat'];
    $latitude         = $_POST['latitude'];
    $longitude        = $_POST['longitude'];
    $jarak            = $_POST['jarak'];
    $status_survey    = $_POST['status_survey'];
    $keterangan       = $_POST['keterangan'];
    $tanggal_update   = date('Y-m-d H:i:s');

    $sql = "UPDATE pelanggan 
            SET nama = ?, sales = ?, alamat = ?, terminal_terdekat = ?, latitude = ?, longitude = ?, jarak = ?, status_survey = ?, keterangan = ?, tanggal_update = ?
            WHERE id = ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssssddssssi", $nama, $sales, $alamat, $terminal_terdekat, $latitude, $longitude, $jarak, $status_survey, $keterangan, $tanggal_update, $id);

    if ($stmt->execute()) {
        $alertMessage = 'success';
    } else {
        $alertMessage = 'error';
    }
}
// Ambil semua terminal dari tabel terminals
$ambil_terminal = $koneksi->query("SELECT nama FROM terminals");

// Buat array untuk terminal
$terminals = [];
while ($row = $ambil_terminal->fetch_assoc()) {
    $terminals[] = $row['nama'];
}

$ambil_status = $koneksi->query("SELECT status FROM status_survey");

// Buat array untuk terminal
$status = [];
while ($row = $ambil_status->fetch_assoc()) {
    $status[] = $row['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Survey</title>
        <!-- //icon logo di bar -->
    <link rel="icon" type="image/png" href="img/logo.avif">

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- SweetAlert2 untuk pop up info-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0 font-weight-bold text-primary">Edit Data Survey Pelanggan</h5>
            </div>
            <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Calon</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($pelanggan['nama']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Tenaga Penjual</label>
                            <input type="text" name="sales" class="form-control" value="<?= htmlspecialchars($pelanggan['sales']) ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($pelanggan['alamat']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Terminal Terdekat</label>
                                <input type="text" name="terminal_terdekat" class="form-control" value="<?= htmlspecialchars($pelanggan['terminal_terdekat']) ?>" required readonly>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($pelanggan['latitude']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($pelanggan['longitude']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jarak</label>
                            <input type="text" name="jarak" class="form-control" value="<?= htmlspecialchars($pelanggan['jarak']) ?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label>Status Survey</label>
                            <input type="text" name="status_survey" class="form-control" value="<?= htmlspecialchars($pelanggan['status_survey']) ?>" required readonly> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($pelanggan['keterangan']) ?>" required>
                        </div>
                        <hr>
                        <br>


                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success rounded-pill me-2">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button> 
                            <button type="button" id="btnDelete" class="btn btn-danger rounded-pill">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                            <a href="daftar_pelanggan.php" class="btn btn-warning rounded-pill">Kembali</a>
                        </div>
                    </form>

            </div>
        </div>
    </div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<?php if ($alertMessage === 'success'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil diperbarui!',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'daftar_pelanggan2.php';
    });
</script>
<?php elseif ($alertMessage === 'error'): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Gagal memperbarui data. Silakan coba lagi.',
        confirmButtonText: 'OK'
    });
</script>
<?php endif; ?>

<script>
document.getElementById('btnDelete').addEventListener('click', function() {
    Swal.fire({
        title: 'Yakin hapus?',
        text: "Data ini akan hilang permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim form hapus
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'proses_delete_cl_pelanggan.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = '<?= $pelanggan['id'] ?>';

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
</body>
</html>

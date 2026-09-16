<?php
session_start();

// ===== koenk ke database dulu =====
include 'koneksi.php';
// ===== cek LOGIN kalau belum out=====
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$_SESSION['timeout'] = time();
$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Ambil data user
$user = $koneksi->query("SELECT * FROM users WHERE username = '$username'")->fetch_assoc();
$id = $user['id'];
$nama = $user['nama'] ?? $username;
$email = $user['email'] ?? '';
$phone = $user['phone'] ?? '';
$whatsapp = $user['whatsapp'] ?? '';

$update_status = null; // status update

// ===== UPDATE DATA =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = $_POST['nama'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];
    $whatsapp  = $_POST['whatsapp'];
    $updated_at = date('Y-m-d H:i:s');

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    } else {
        $password = $user['password'];
    }

    $sql = "UPDATE users 
            SET phone = ?, nama = ?, email = ?, password = ?, whatsapp = ?, updated_at = ?
            WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssssssi", $phone, $nama, $email, $password, $whatsapp, $updated_at, $id);

    if ($stmt->execute()) {
        $update_status = "success";
    } else {
        $update_status = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Account</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-light">

<div class="container">
    <!-- Profil -->
    <div class="row justify-content-center">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col d-none d-lg-flex align-items-center justify-content-center bg-light">
                            <img src="img/logo.avif" class="img-fluid" alt="Logo" />
                        </div>

                        <div class="col-lg-6">
                            <div class="p-4">
                                <h4 class="mb-0">
                                    <span class="text-primary"><i class="fa fa-user"></i> Informasi User</span>
                                </h4>
                                <hr>
                                <div class="mb-3">
                                    <h5>Nama</h5>
                                    <span><?= htmlspecialchars($user['nama']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <h5>Email</h5>
                                    <span><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <h5>Role</h5>
                                    <span><?= htmlspecialchars($user['role']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Edit -->
    <div class="row justify-content-center">
        <div class="col-xl-8 order-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user-cog"></i> Edit Account </h6>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Telepon :</label>
                            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Full Name :</label>
                            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($user['nama']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Password :</label>
                            <input type="password" placeholder="Kosongkan jika tidak ingin mengubah" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                            <label>Whatsapp :</label>
                            <input type="text" placeholder="whatsapp" class="form-control" name="whatsapp" value="<?= htmlspecialchars($user['whatsapp']) ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary btn-sm"><i class="fas fa-undo"></i> Reset</button>
                            <button class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
                            <?php if ($role === 'admin') : ?>
                                <a href="dashboard_admin.php" class="btn btn-warning btn-sm">Kembali</a>
                            <?php elseif ($role === 'teknis') : ?>
                                <a href="dashboard_teknis.php" class="btn btn-warning btn-sm">Kembali</a>
                            <?php elseif ($role === 'sales') : ?>
                                <a href="dashboard_sales.php" class="btn btn-warning btn-sm">Kembali</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="col-xl-4 order-xl-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-exclamation-circle"></i> Information </h6>
                </div>
                <div class="card-body">
                    <small><b>Untuk mengupdate tanpa mengubah password, biarkan kolom password kosong.</b></small><br>
                    <small><b>Periksa format email sebelum menyimpan.</b></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($update_status === "success"): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil diperbarui.',
        confirmButtonColor: '#3085d6',
    }).then(() => {
        window.location = 'user_page.php';
    });
</script>
<?php elseif ($update_status === "error"): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Data gagal diperbarui.',
        confirmButtonColor: '#d33',
    });
</script>
<?php endif; ?>
</body>
</html>

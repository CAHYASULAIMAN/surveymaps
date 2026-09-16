<?php
session_start();
// konek ke database
include 'koneksi.php';
// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data users berdasarkan ID yang di klik tadi
$sql_select = "SELECT * FROM users WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Data user tidak ditemukan.");
}

// Update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Tangkap data dari form
    $nama             = $_POST['nama'];
    $email            = $_POST['email'];
    $username            = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role= $_POST['role'];
    $updated_at = date('Y-m-d H:i:s');


        $sql = "UPDATE users 
                SET username = ?, nama = ?, email = ?, password = ?, role = ?, updated_at = ?
                WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $nama, $email, $password, $role, $updated_at, $id);


    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='daftar_user.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
    
}


// ambil data role
$ambil_role = $conn->query("SELECT * FROM role");

// Buat array untuk role
$role = [];
while ($row = $ambil_role->fetch_assoc()) {
    $role[] = $row['role'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SUVEYS - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">


</head>

<body class="bg-gradient-light">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col d-none d-lg-flex align-items-center justify-content-center bg-light">
                                <img src="img/logo.avif" class="img-fluid" alt="Gambar Utama" />
                            </div>

                            <div class="col-lg-6">
                                <div class="p-4">
                                    <div class="text-left">
                                        <h4 class="mb-0" style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                            <span class="text-primary "><div class="fa fa-user"></div> informasi user</span> 
                                        </h4>
                                        <hr>
                                        
                                        <div class="text-left mb-3">
                                            <div class="text-gray-550  bg-gray-200"><h4>Nama</h4></div>
                                            <div>
                                                <span><?= htmlspecialchars($user['nama']) ?></span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="text-left mb-3">
                                            <div class="text-gray-550  bg-gray-200"><h4>Email</h4></div>
                                            <div>
                                                <span><?= htmlspecialchars($user['email']) ?></span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="text-left mb-3">
                                            <div class="text-gray-550  bg-gray-200"><h4>Role</h4></div>
                                            <div>
                                                <span><?= htmlspecialchars($user['role']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8 order-xl-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-fw fa-user-cog" style="font-size:13px;"></i> Edit Account </h6>
                    </div>
                    <div class="collapse show" id="account">
                        <div class="card-body">
                            <form  method="post" >
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label for="username">
                                            <i class="fas fa-user fa-fw" style="font-size:13px;"></i> Username :
                                        </label>
                                        <input type="text" id="username"  class="form-control " name="username" value="<?= htmlspecialchars($user['username']) ?>" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label for="name">
                                            <i class="fas fa-user fa-fw" style="font-size:13px;"></i> Full Name :
                                        </label>
                                        <input type="text" id="nama"  class="form-control " name="nama" value="<?= htmlspecialchars($user['nama']) ?>" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope fa-fw" style="font-size:13px;"></i> Email :</label>
                                    <input type="text"  class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="password"><i class="fas fa-key fa-fw" style="font-size:13px;"></i> Password :</label>
                                    <input type="text" id="password" placeholder="password" class="form-control" name="password" value="<?= htmlspecialchars($user['password']) ?>" />
                                </div>
                                <div class="form-group">
                                    <label>Role User</label>

                                    <select name="role" class="form-control" id="role" required>
                                      <option value="">-- Pilih role--</option>
                                      <?php foreach ($role as $rl): ?>
                                        <option value="<?= htmlspecialchars($rl) ?>"><?= htmlspecialchars($rl) ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">
                                        <i class="fas fa-undo" style="font-size:13px"></i> Reset
                                    </button>
                                    <button class="btn btn-sm btn-primary  rounded-pill" id="action"><i class="fa fa-save" style="font-size:13px"></i> Save</button>
                                    <a href="daftar_user.php" class="btn btn-warning btn-sm rounded-pill">
                                        kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 order-xl-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-exclamation-circle" style="font-size:13px;"></i> Information </h6>
                    </div>
                    <div class="collapse show" id="informasivpn">
                        <div class="card-body">
                            <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                <b>Untuk mengupdate tanpa mengubah password, biarkan kolom seperti itu tanpa di edit . </b>
                            </small><br>
                            <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                <b>Untuk mengubah email tolong ikuti format @ diperhatikan </b></small><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
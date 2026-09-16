<!-- <?php
session_start();

// Hanya admin yang boleh akses
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php?error=Akses ditolak");
  exit;
}
?> -->

<?php
// Koneksi ke database
include 'koneksi.php';
// ambil data role
$ambil_role = $koneksi->query("SELECT * FROM role");

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
                <!-- //icon logo di bar -->
   <link rel="icon" type="image/png" href="img/logo.avif">

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
                            <div class="col">
                                <div class="p-4">
                                    <div class="text-center">
                                        <h4 class="mb-0" style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                            <span class="text-primary "><div class="fa fa-user-plus"></div>  Registrasi User Baru</span> 
                                        </h4>
                                        <hr>
                                    </div>
                                    
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
                           <!-- ini akan mengirim ke proses menambahkan user_error -->
                            <form  method="post" action="proses_register_user.php" > 
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label for="username">
                                            <i class="fas fa-user fa-fw" style="font-size:13px;"></i> Username :
                                        </label>
                                        <input type="text" id="username"  class="form-control " name="username" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <label for="name">
                                            <i class="fas fa-user fa-fw" style="font-size:13px;"></i> Full Name :
                                        </label>
                                        <input type="text" id="nama"  class="form-control " name="nama" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password"><i class="fas fa-key fa-fw" style="font-size:13px;"></i> Password :</label>
                                    <input type="text" id="password" class="form-control" name="password" required/>
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-key fa-fw" style="font-size:13px;"></i> email :</label>
                                    <input type="text" id="email"  class="form-control" name="email" required/>
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
                                    <button type="reset" class="btn btn-sm btn-danger rounded-pill">
                                        <i class="fas fa-undo" style="font-size:13px"></i> Reset
                                    </button>
                                    <button class="btn btn-sm btn-success  rounded-pill" id="action"><i class="fa fa-save" style="font-size:13px"></i> Save</button>
                                    <a href="dashboard_admin.php" class="btn btn-warning btn-sm rounded-pill">
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
                                <b>Username</b> <br>
                                <b>Untuk mengupdate username tolong gunakan kombinasi nama dan angka, atau gunakan email sebagai username. </b>
                            </small><br>
                            <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                <b>Password</b> <br>
                                <b>Untuk membuat password tolong kombinasikan dnegan angka dan huruf ( contoh "Admin1945")</b>
                            </small><br>
                                
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

    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_GET['success']) && $_GET['success'] == 1) {
  echo "
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'User berhasil dibuat.',
        showConfirmButton: false,
        timer: 2000
      });
    </script>
  ";
}

if (isset($_GET['error'])) {
  if ($_GET['error'] === 'username') {
    echo "
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Username sudah digunakan.',
          showConfirmButton: true
        });
      </script>
    ";
  } elseif ($_GET['error'] === 'insert') {
    echo "
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: 'Gagal menambahkan user.',
          showConfirmButton: true
        });
      </script>
    ";
  }
}
?>


</body>

</html>
<?php
session_start();

// Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    // Redirect ke halaman login atau tampilkan pesan error
    header("Location: login.php?error=Akses ditolak. Hanya admin yang dapat mengakses halaman ini.");
    exit;
}

// Koneksi ke database
include 'koneksi.php';

// Query data user
$sql = "SELECT id, nama, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $koneksi->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Survey - Delete User</title>
            <!-- //icon logo di bar -->
   <link rel="icon" type="image/png" href="img/logo.avif">



    <style>
        table.dataTable tfoot {
        display: none !important;
    }
    </style>


    

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <!-- DataTables CSS -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <i class="fas fa-microscope"></i>
                <div class="sidebar-brand-text mx-2">Survey <sup>RTL</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard_admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-th-large"></i>
                    <span>Terminal</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">TERMINAL :</h6>
                        <a class="collapse-item" href="daftar_terminal.php">Daftar Terminal</a>
                        <a class="collapse-item" href="update_terminal.php">Update Terminal</a>
                        <a class="collapse-item" href="tambah_terminal.php">Tambah Terminal</a>
                        <a class="collapse-item" href="delete_terminal.php">Delete Terminal</a>

                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Survey</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">SURVEY :</h6>
                        <a class="collapse-item" href="daftar_pelanggan2.php">daftar Pelanggan</a>
                        <a class="collapse-item" href="survey_teknis2.php">Request Survey nama</a>
                        <a class="collapse-item" href="cek_ai.php">Survey AI</a>
                        <a class="collapse-item" href="cek_manual2.php">survey Manual </a>
                    </div>
                </div>
            </li>
                        <!-- Nav Item - User -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
                    aria-expanded="true" aria-controls="collapseUser">
                    <i class="fas fa-user-cog"></i>
                    <span>User</span>
                </a>
                <div id="collapseUser" class="collapse" aria-labelledby="headingUser"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">user :</h6>
                        <a class="collapse-item" href="register_user.php">Registrasi User</a>
                        <!-- <a class="collapse-item" href="tambah_user.php">Tambah User</a> -->
                        <a class="collapse-item" href="daftar_user.php">List & Update User</a>
                        <a class="collapse-item" href="delete_user.php">Delete User</a>
                    </div>
                </div>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manajemen
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="user_page.php">
                    <i class="fas fa-user"></i>
                    <span>penguna</span></a>
            </li>

                        <!-- Divider -->
            <hr class="sidebar-divider ">

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-angle-double-left"></i>
                    <span>Keluar</span></a>
            </li>
            <br>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                                        <!-- ini jam -->
                    <div
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <?= date('l, d M Y ') ?>  |   <?= date('H:i') ?>
                    </div>



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- untuk fullscreen view -->
                        <li class="nav-item dropdown no-arrow mx-1" data-toggle="tooltip" data-original-title="Full Screen">
                             <a class="nav-link" href="#" onclick="toggleFullScreen()"> 
                                <i class="fas fa-expand fa-fw"></i> 
                            </a> 
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

     
                        <!-- untuk menampilkan nama user -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Guest'; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/user.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="user_page.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Delete User / Pengguna</h1>
                    <p class="mb-4">Daftar User yang terdaftar :</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel user :</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>nama</th>
                                            <th>username</th>
                                            <th>email</th>
                                            <th>role</th>
                                            <th>pembuatan</th>
                                            <th>aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                                    <td><?= htmlspecialchars($row['role']) ?></td>
                                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                                    <td>
                                                      <form action="proses_delete_user.php" method="POST" class="delete-form" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill">
                                                          <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                      </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center">Tidak ada data user.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright © Routelink MediaTech </span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" untuk mengakhiri sesi ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="login.php">Logout</a>
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

    <script>
        function toggleFullScreen() {
  if (!document.fullscreenElement &&
      !document.mozFullScreenElement && 
      !document.webkitFullscreenElement && 
      !document.msFullscreenElement) {

    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }

  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

    </script>


        <!-- DataTables JS untuk plugin sortir data terbaru ke terlama -->
     <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Init DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>

    <!-- SweetAlert2 notif sukses nya tau delete-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'deleted'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'user berhasil dihapus!',
                    showConfirmButton: false,
                    timer: 2000
                });
            <?php elseif ($_GET['status'] === 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menghapus user.',
                    showConfirmButton: false,
                    timer: 2500
                });
            <?php endif; ?>
        <?php endif; ?>
    </script>

    <!-- notif delet user konfirmasi -->
     
    <script>
      document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault(); // cegah form langsung submit
        
          Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit(); // submit manual kalau user setuju
            }
          });
        });
      });
    </script>

</body>

</html>
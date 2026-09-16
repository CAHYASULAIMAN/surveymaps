<?php
session_start();
//koneksi ke database
include 'koneksi.php';

// Redirect jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];


// Query data terminal
$sql = "SELECT id, nama, latitude, longitude, cluster, created_at FROM terminals ORDER BY created_at DESC";
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

    <title>survey - Dashboard</title>
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
                <a class="nav-link" href="<?php
                    if ($role == 'admin') echo 'dashboard_admin.php';
                    elseif ($role == 'teknis') echo 'dashboard_teknis.php';
                    elseif ($role == 'sales') echo 'dashboard_sales.php';
                ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>
            <!-- Terminal (admin & teknis) -->
            <?php if ($role === 'admin' || $role === 'teknis') : ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTerminal">
                    <i class="fas fa-th-large"></i>
                    <span>Terminal</span>
                </a>
                <div id="collapseTerminal" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">TERMINAL :</h6>
                        <a class="collapse-item" href="daftar_terminal.php">Daftar Terminal</a>
                        <a class="collapse-item" href="update_terminal.php">Update Terminal</a>
                        <a class="collapse-item" href="tambah_terminal.php">Tambah Terminal</a>
                        <a class="collapse-item" href="delete_terminal.php">Delete Terminal</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <!-- Survey teknis dan admin -->
            <?php if ($role === 'admin' || $role === 'teknis') : ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurveyTeknis">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Survey</span>
                </a>
                <div id="collapseSurveyTeknis" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">SURVEY :</h6>
                        <a class="collapse-item" href="daftar_pelanggan2.php">Daftar Pelanggan</a>
                        <a class="collapse-item" href="survey_teknis2.php">Request Survey Sales</a>
                        <a class="collapse-item" href="cek_ai.php">Survey AI</a>
                        <a class="collapse-item" href="cek_manual2.php">Survey Manual</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>

                <!-- User management untuk admin -->
            <?php if ($role === 'admin') : ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser">
                    <i class="fas fa-user-cog"></i>
                    <span>User</span>
                </a>
                <div id="collapseUser" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">User :</h6>
                        <a class="collapse-item" href="register_user.php">Registrasi User</a>
                        <a class="collapse-item" href="daftar_user.php">List & Update User</a>
                        <a class="collapse-item" href="delete_user.php">Delete User</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manajemen
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-user"></i>
                    <span>penguna</span></a>
            </li>

                        <!-- Divider -->
            <hr class="sidebar-divider ">

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
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

                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>


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
                    <h1 class="h3 mb-2 text-gray-800">Update Terminal</h1>
                    <p class="mb-4">Daftar Terminal yang tersedia.</p>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Terminal</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Terminal</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Cluster</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                                    <td><?= htmlspecialchars($row['latitude']) ?></td>
                                                    <td><?= htmlspecialchars($row['longitude']) ?></td>
                                                    <td><?= htmlspecialchars($row['cluster']) ?></td>
                                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                                    <td>
                                                        <a href="edit_terminal.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm rounded-pill">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="7" class="text-center">Tidak ada data terminal.</td></tr>
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
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
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

        <!-- DataTables JS -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Init DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>


</body>

</html>
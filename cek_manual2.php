<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];


// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_surveymap";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$ambil_cluster = $conn->query("SELECT nama_cluster FROM cluster");

// Buat array untuk terminal
$cluster = [];
while ($row = $ambil_cluster->fetch_assoc()) {
    $cluster[] = $row['nama_cluster'];
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

    <title>survey - Dashboard</title>
            <!-- //icon logo di bar atas-->
   <link rel="icon" type="image/png" href="img/logo.avif">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom fonts jenisnya-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- custom untuk dat update dengan pop up -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     

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


            <!-- Survey khusus sales -->
            <?php if ($role === 'sales') : ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurveySales">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Survey</span>
                </a>
                <div id="collapseSurveySales" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">SURVEY :</h6>
                        <a class="collapse-item" href="cek_ai.php">Survey AI</a>
                        <a class="collapse-item" href="cek_manual.php">Request Survey Ahli</a>
                        <a class="collapse-item" href="daftar_pelanggan2.php">Hasil Survey</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <!-- Pelanggan untuk sales -->
            <?php if ($role === 'sales') : ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePelanggan">
                    <i class="fas fa-user-cog"></i>
                    <span>Pelanggan</span>
                </a>
                <div id="collapsePelanggan" class="collapse" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pelanggan :</h6>
                        <a class="collapse-item" href="daftar_pelanggan2.php">Daftar Pelanggan</a>
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
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <?= date('l, d M Y ') ?>  |   <?= date('H:i') ?>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
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

                    <h1 class="h2 mb-2 text-gray-800">Check Survey</h1>
                    <p class="mb-4">Check Terminal by AHLI <i class="fas fa-user-md"></i></p>
                    
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Survey  :</h6>
                                </div>
                                
                                <div class="card-body">
                                    <form id="survey-form">
                                        <input type="hidden" id="salesId" value="<?= htmlspecialchars($_SESSION['username']) ?>">
                                        


                                        <div class="mb-3">
                                          <label>Nama pelanggan:</label>
                                          <input type="text" id="nama" class="form-control "  required>
                                        </div>
                                        <div class="mb-3">
                                          <label>Nomor telepon:</label>
                                          <input type="text" id="telepon" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                          <label>Alamat Pelanggan:</label>
                                          <input type="text" id="alamat" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                          <label>Koordinat (lat,lng):</label>
                                          <input type="text" id="koordinat" class="form-control" placeholder="contoh: -7.5040, 110.8135" required>
                                        </div>
                                        <div class="mb-3">
                                          <label>Tanggal PO (Pre Order):</label>
                                          <input type="date" id="tanggal_po" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                          <label>Keterangan:</label>
                                          <input type="text" id="keterangan" class="form-control">
                                        </div>
    

                                        <button type="submit" class="btn btn-primary rounded-pill"><i class="far fa-save"></i> SUBMIT</button>
                                        <a href="cek_manual2.php" class="btn btn-danger rounded-pill"><i class="fas fa-undo"></i> Mulai Ulang</a>
                                    </form>
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


    <!-- Bootstrap 5 Bundle with Popper untuk dropdown  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    


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
    <script>
      const form = document.getElementById('survey-form');
                                                        
      form.addEventListener('submit', function (e) {
        e.preventDefault();
    
        const nama = document.getElementById('nama').value.trim();
        const telepon = document.getElementById('telepon').value.trim();
        const alamat = document.getElementById('alamat').value.trim();
        const koordinat = document.getElementById('koordinat').value.trim();
        const [lat, lng] = koordinat.split(',').map(val => parseFloat(val.trim()));
        const tanggal_po = document.getElementById('tanggal_po').value;
        const keterangan = document.getElementById('keterangan').value.trim();
        const salesId = document.getElementById('salesId').value;
    
        
    
        // Validasi koordinat
        if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
          return Swal.fire({
            icon: 'error',
            title: 'Koordinat tidak valid',
            text: 'Format harus: latitude,longitude (contoh: -7.5040, 110.8135)',
          });
        }
    
        // Konfirmasi data
        Swal.fire({
          title: 'Konfirmasi Data',
          html: `
            <strong>Nama:</strong> ${nama}<br>
            <strong>Telepon:</strong> ${telepon}<br>
            <strong>Alamat:</strong> ${alamat}<br>
            <strong>Latitude:</strong> ${lat}<br>
            <strong>Longitude:</strong> ${lng}<br>
            <strong>Tanggal PO:</strong> ${tanggal_po || '-'}<br>
            <strong>Keterangan:</strong> ${keterangan || '-'}
          `,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Ya, Simpan',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            const status_survey = "Survey Teknis";
            const minDistance = null;
            const nearestTerminal = { nama: "-" };
        
            // Kirim ke server
            fetch('simpan_manual.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: new URLSearchParams({
                nama,
                telepon,
                alamat,
                latitude: lat,
                longitude: lng,
                tanggal_po,
                keterangan,
                sales: salesId
              })
            })
            .then(res => res.text())
            .then(async (response) => {
              // Kirim ke Telegram
              const token = "7539666974:AAGjMW0QyVPQiLGPNJm-UGrdq1JJlepInCo";
              const chat_id = "-4831042786";
              const text =
                              `📡 *HASIL SURVEY BARU!*\n\n` +
                              `==============================\n` +
                              `🔧 *Status Survey:* Survey Teknis\n` +
                              `📏 *Jarak        :* - \n` +
                              `📶 *Terminal Terdekat:* -\n\n` +
                              `==============================\n` +
                              `👤 *Nama    :* ${nama}\n` +
                              `📞 *Telepon :* ${telepon}\n` +
                              `📍 *Koordinat:* ${lat}, ${lng}\n` +
                              `🏠 *Alamat  :* ${alamat}\n\n` +
                              `==============================\n` +
                              `🗓️ *Tanggal PO :* ${tanggal_po}\n` +
                              `✏️ *Keterangan :* ${keterangan}\n\n` +
                              `==============================\n` +
                              `👤 *Sales:* ${salesId}`;
            
              await fetch(`https://api.telegram.org/bot${token}/sendMessage`, {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                  chat_id,
                  text,
                  parse_mode: "Markdown"
                })
              })
              .then(res => res.json())
              .then(data => {
                if (!data.ok) {
                    console.error("Telegram error:");
                    console.log(data);
                }
                else console.log("Telegram sent:", data);
              })
              .catch(err => console.error("Fetch error to Telegram:", err));          
          
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response
              });
          
              form.reset();
            })
            .catch(error => {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan: ' + error
              });
            });
          }
        });
      });
    </script>

</body>

</html>
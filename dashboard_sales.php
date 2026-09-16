<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}


// Koneksi ke database
include 'koneksi.php';

////////////////data untuk mengambil informasi
$role = $_SESSION['role']; // simpan role
$nama_sales = $_SESSION['username'];  //simpan nama sales yang login
$bulan_ini = date("m"); // simpan bulan ini contoh: 08
$tahun_ini = date("Y"); // simpan tahun ini contoh: 2025
$today = date("Y-m-d"); // simpan tanggal hari ini
$sekarang = date('m'); // simpan bulan khsus  mengambil data survey 
$sebulan_teknis = date('Y'); // simpan year khsus  mengambil data survey
$hari_ini = date('Y-m-d'); 
/////////////////////////////////////////////////////////
//simpan jumlah survey hari ni
$stmt = $koneksi->prepare("SELECT COUNT(*) as total FROM pelanggan WHERE DATE(tanggal_survey) = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$total_survey_hari_ini = $data['total'];

// Total inputan oleh sales (yang login) hari ini 
$stmt_sales_today = $koneksi->prepare("SELECT COUNT(*) as total 
    FROM pelanggan 
    WHERE sales = ? AND DATE(tanggal_input) = ?
");
$stmt_sales_today->bind_param("ss", $nama_sales, $today);
$stmt_sales_today->execute();
$result_sales_today = $stmt_sales_today->get_result();
$data_sales_today = $result_sales_today->fetch_assoc();
// Hasil akhir
$total_input_sales_hari_ini = $data_sales_today['total'] ?? 0;



//ambil data untuk total survey bulan ini
$stmt_bulan = $koneksi->prepare("SELECT COUNT(*) as total FROM pelanggan WHERE MONTH(tanggal_survey) = ? AND YEAR(tanggal_survey) = ?");
$stmt_bulan->bind_param("ii", $bulan_ini, $tahun_ini);
$stmt_bulan->execute();
$result_bulan = $stmt_bulan->get_result();
$data_bulan = $result_bulan->fetch_assoc();
$total_survey_bulan_ini = $data_bulan['total'];



// Ambil nama sales yang paling banyak input survey bulan ini
$stmt_top_surveyor = $koneksi->prepare("SELECT sales, COUNT(*) AS jumlah FROM pelanggan
                     WHERE MONTH(tanggal_input) = ? 
                     AND YEAR(tanggal_input) = ? 
                     GROUP BY sales 
                     ORDER BY jumlah 
                     DESC LIMIT 1"
                     );
$stmt_top_surveyor->bind_param("ii", $bulan_ini, $tahun_ini);
$stmt_top_surveyor->execute();
$result_top_surveyor = $stmt_top_surveyor->get_result();
$data_top_surveyor = $result_top_surveyor->fetch_assoc();

$top_sales = $data_top_surveyor['sales'] ?? 'Tidak ada data';
$top_sales_count = $data_top_surveyor['jumlah'] ?? 0;


////////////////bagian content dashboard sales//////////////////////////


// Ambil 5 inputan survey terbaru dari tabel pelanggan
$sql_survey_terakhir = "SELECT nama, sales, tanggal_input 
                        FROM pelanggan 
                        ORDER BY tanggal_input DESC 
                        LIMIT 5";
$result_survey_terakhir = $koneksi->query($sql_survey_terakhir);

// menampilkan data untuk calon pelanggan yang sudah di cek tim teknis survey 
$sql_sudah_diperbarui_teknis = "SELECT nama, status_survey, tanggal_update 
                                FROM pelanggan 
                                WHERE tanggal_update IS NOT NULL 
                                  AND MONTH(tanggal_update) = '$sekarang'
                                  AND YEAR(tanggal_update) = '$sebulan_teknis'
                                ORDER BY tanggal_update DESC
                                LIMIT 5";
$result_update_teknis = $koneksi->query($sql_sudah_diperbarui_teknis);


//mengambil jumlah terbanyak sales yang input survey dalam sebulan terakhir
$sql_sales_terbanyak = "SELECT sales, COUNT(*) AS total_input 
                        FROM pelanggan 
                        WHERE MONTH(tanggal_input) = '$sekarang' 
                          AND YEAR(tanggal_input) = '$sebulan_teknis'
                        GROUP BY sales 
                        ORDER BY total_input DESC 
                        LIMIT 5";
$result_sales_terbanyak = $koneksi->query($sql_sales_terbanyak);


//inputan yang dilakukan sales / users hari ini
$sql_survey_user_hari_ini = "SELECT nama, sales, status_survey, tanggal_input
                             FROM pelanggan 
                             WHERE sales = '$nama_sales' 
                               AND DATE(tanggal_input) = '$hari_ini'
                             ORDER BY tanggal_input DESC";

$result_survey_user_hari_ini = $koneksi->query($sql_survey_user_hari_ini);
///////////////////////////////////////////////////////////////////
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

    

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center " href="#">
                <div class="sidebar-brand-icon ">
                    <i> <img src="img/logo.avif" class="img-fluid w-75"  alt="Gambar Utama" /></i>
                </div>
                <!-- <div class="sidebar-brand-text mx-2">Survey <sup>RTL</sup></div> -->
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard_sales.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

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
                        <a class="collapse-item" href="cek_ai.php">Survey AI</a>
                        <a class="collapse-item" href="cek_manual2.php">Request Survey Ahli</a>
                        <a class="collapse-item" href="daftar_survey.php">Status Survey</a>
                        <a class="collapse-item" href="daftar_hasil_survey"> Hasil Survey</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-user-cog"></i>
                    <span>Pelanggan</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pelanggan :</h6>
                        
                        <a class="collapse-item" href="daftar_pelanggan2.php">Daftar Pelanggan</a>
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h2 mb-0 text-gray-800">Dashboard Teknis</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Survey Hari Ini Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Survey Hari Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $total_survey_hari_ini ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Survey Bulan Ini Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Survey Bulan Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $total_survey_bulan_ini ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Survey individu hari ini -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Survey individu</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?= $total_input_sales_hari_ini ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Pending Requests survey teknis -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Top Survey Bulan ini</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?= $top_sales ?>  (<?= $top_sales_count?>)
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- bagian isi terminal diperbarui -->
                        <div class="col-md-6">

                            <!-- bagian isi 10 inputan survey terbaru -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Survey Terbaru</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Sales</th>
                                                    <th>Tanggal Input</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result_survey_terakhir->num_rows > 0): ?>
                                                    <?php while($row = $result_survey_terakhir->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                                            <td><?= htmlspecialchars($row['sales']) ?></td>
                                                            <td><?= date('d M Y H:i', strtotime($row['tanggal_input'])) ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">Belum ada data survey yang masuk</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DataTables survey yang sudah diupdate teknis -->
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">Survey Diperbarui Oleh Teknis Bulan Ini</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Status Survey</th>
                                                    <th>Tanggal Update</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result_update_teknis->num_rows > 0): ?>
                                                    <?php while($row = $result_update_teknis->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                                            <td><?= htmlspecialchars($row['status_survey']) ?></td>
                                                            <td><?= date('d M Y H:i', strtotime($row['tanggal_update'])) ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">Belum ada survey yang diperbarui teknis bulan ini</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                                                
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- DataTables sales terbanyak input -->
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-warning">5 Sales dengan Input Terbanyak Bulan Ini</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>Peringkat</th>
                                                    <th>Nama Sales</th>
                                                    <th>Total Input</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result_sales_terbanyak->num_rows > 0): ?>
                                                    <?php $no = 1; ?>
                                                    <?php while($row = $result_sales_terbanyak->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= htmlspecialchars($row['sales']) ?></td>
                                                            <td><?= htmlspecialchars($row['total_input']) ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">Belum ada data input sales bulan ini</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                
                        <!-- DataTables survey oleh user hari ini -->
                        <div class="col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Inputan Survey Anda Hari Ini 
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Status Survey</th>
                                                    <th>Tanggal Input</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result_survey_user_hari_ini->num_rows > 0): ?>
                                                    <?php while($row = $result_survey_user_hari_ini->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                                            <td><?= htmlspecialchars($row['status_survey']) ?></td>
                                                            <td><?= date('d M Y H:i', strtotime($row['tanggal_input'])) ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            Anda belum menginputkan survey hari ini
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                                                
                    </div>


                    <!-- Content Row -->
                    <div class="row">

                        <div class="col-lg-6 mb-4">

                            <!-- informasi -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;"
                                            src="img/information.png" alt="...">
                                    </div>
                                    <strong class="text-primary">Pengunaan Terminal</strong>
                                    <p class="text-danger">Saat menambahkan Terminal Pastikan syarat-syarat yang ada:</p>
                                        <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                            <b>Koordinat</b> <br>
                                            <p>pastikan koordinat diambil dari format yang sesuai contoh <span class="text-bold">(-7.500866589296892, 110.82571901497367) </span></p>
                                        </small><br>
                                        <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                            <b>Nama Terminal</b> <br>
                                            <p>Ikuti Aturan Format Terminal contoh (B8/ODP-RTMSK-55) dengan huruf kapital semua</p>
                                        </small><br>

                                    <strong class="text-primary">Pengunaan Survey</strong>
                                    <p class="text-danger">Saat menambahkan Survey Pastikan syarat-syarat yang ada:</p>
                                        <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                            <b>Koordinat</b> <br>
                                            <p>pastikan koordinat diambil dari format yang sesuai contoh (-7.500866589296892, 110.82571901497367) </p> <br>
                                            <p>Jangan gunakan koordinat yang mengunakan garis waktu atau bujur lintang, itu bukan koordinat!</p>
                                        </small><br>
                                        <small><i class="fas fa-dot-circle fa-fw" style="font-size:10px;"></i>
                                            <b>Nama Terminal</b> <br>
                                            <p>Ikuti Aturan Format Terminal contoh (B8/ODP-RTMSK-55) dengan huruf kapital semua</p>
                                        </small><br>
    
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- pengembangan Approach -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Pengembangan Web</h6>
                                </div>
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;"
                                            src="img/development.svg" alt="...">
                                    </div>
                                <div class="card-body">
                                    <p>Saat ini, Web Survey Routelink sedang dalam tahap pengembangan oleh tim Development dan mungkin mengalami beberapa bug atau kesalahan teknis.
                                         Kami sedang berupaya untuk memastikan bahwa semua fitur berjalan dengan lancar dan optimal.
                                          Jika Anda menemui masalah atau kesalahan saat menggunakan dashboard, <br>
                                           mohon untuk segera menghubungi tim teknis kami melalui nomor berikut:
                                                <br>
                                                <strong class="text-primary">Tim Development:  +62 8151 9873 652.</strong>
                                        
                                    </p>
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
                <div class="modal-body">Pilih "Logout" jika kamu sudah selesai dengan sesi kali ini. </div>
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

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script>
        function toggleFullScreen() 
        {
            if (!document.fullscreenElement &&
                !document.mozFullScreenElement && 
                !document.webkitFullscreenElement && 
                !document.msFullscreenElement) 
                {   if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } 
                    else if (document.documentElement.msRequestFullscreen) {
                        document.documentElement.msRequestFullscreen();
                    } 
                    else if (document.documentElement.mozRequestFullScreen) {
                        document.documentElement.mozRequestFullScreen();
                              } 
                    else if (document.documentElement.webkitRequestFullscreen) {
                        document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } 

            else 
            {
                if (document.exitFullscreen) {
                  document.exitFullscreen();
                } 
                else if (document.msExitFullscreen) {
                  document.msExitFullscreen();
                } 
                else if (document.mozCancelFullScreen) {
                  document.mozCancelFullScreen();
                } 
                else if (document.webkitExitFullscreen) {
                  document.webkitExitFullscreen();
                }
            }
        }
    </script>
</body>

</html>
<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Hanya admin yang bisa mengakses
if ($_SESSION['role'] !== 'admin') {
    echo "\u274c Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit;
}

require('fpdf186/fpdf.php');

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_surveymap";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Fungsi untuk bulan Indonesia
function getBulanIndonesia($bulanAngka) {
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return $bulan[$bulanAngka];
}
$bulan = getBulanIndonesia(date('n'));
$tahun = date('Y');

// Zona waktu
date_default_timezone_set('Asia/Jakarta');
$hariIndonesia = [
    'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
];
$namaHari = $hariIndonesia[date('l')];
$tanggalCetak = $namaHari . ', ' . date('d-m-Y');

// Query data inputan selama 1 bulan terakhir
$sql = "SELECT nama, sales,telepon, alamat, tanggal_input, tanggal_po,jarak, status_survey
        FROM pelanggan 
        WHERE MONTH(tanggal_po) = MONTH(CURDATE()) AND YEAR(tanggal_po) = YEAR(CURDATE())
        ORDER BY tanggal_po DESC";
$result = $koneksi->query($sql);

// PDF setup
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->Image('img/rtlhd.png',245,0,40);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(270,10,'Laporan Data Survey Pelanggan',0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(270,10,$bulan . ' ' . $tahun,0,1,'C');
$pdf->Ln(3);

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,'Dicetak pada: ' . $tanggalCetak,0,1,'L');
$pdf->Ln(3);

// Header tabel
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(10,8,'No',1,0,'C',true);
$pdf->Cell(40,8,'Nama Pelanggan',1,0,'C',true);
$pdf->Cell(25,8,'Nama Sales',1,0,'C',true);
$pdf->Cell(30,8,'Telepon',1,0,'C',true);
$pdf->Cell(65,8,'Alamat',1,0,'C',true);
$pdf->Cell(25,8,'Tanggal Input',1,0,'C',true);
$pdf->Cell(25,8,'Tanggal Po',1,0,'C',true);
$pdf->Cell(25,8,'Jarak',1,0,'C',true);
$pdf->Cell(30,8,'Status',1,1,'C',true);

$pdf->SetFont('Arial','',9);
$no = 1;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pdf->Cell(10,7,$no++,1,0,'C');
        $pdf->Cell(40,7,$row['nama'],1,0);
        $pdf->Cell(25,7,$row['sales'],1,0,'C');
        $pdf->Cell(30,7,$row['telepon'],1,0,'C');
        $pdf->Cell(65,7,$row['alamat'],1,0);
        $pdf->Cell(25,7,date('d-m-Y', strtotime($row['tanggal_input'])),1,0,'C');
        $pdf->Cell(25,7,date('d-m-Y', strtotime($row['tanggal_po'])),1,0,'C');
        $pdf->Cell(25,7,$row['jarak'],1,0);
        $pdf->Cell(30,7,$row['status_survey'],1,1);
    }
} else {
    $pdf->Cell(190, 10, 'Tidak ada data untuk bulan ini.', 1, 1, 'C');
}

// Footer
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(1);
$pdf->Line(11, $pdf->GetPageHeight() - 38, $pdf->GetPageWidth() - 11, $pdf->GetPageHeight() - 38);
$pdf->SetY(-36);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(200, 0, 0);
$pdf->Cell(0, 5, 'PT. UNION ROUTELINK COMMUNICATION', 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0);
$pdf->MultiCell(0, 5,
    "Yogyakarta : Jl. Pramuka No. 28 Pandean, Umbulharjo, Yogyakarta 55161 No. Tlp: 0274-412650 / 0815 671 0235\n" .
    "Solo | GunungKidul | Kulon Progo | Klaten | Ngawi | Wonogiri | Salatiga | Pacitan | Lampung | Samarinda",
    0, 'L');

$pdf->Output();
?>

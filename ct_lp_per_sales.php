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
include 'koneksi.php';

// bulanuntuk jduul laporan 
function getBulanIndonesia($bulanAngka) {
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    return $bulan[$bulanAngka];
}
$bulan = getBulanIndonesia(date('n')); // date('n') memberi angka bulan tanpa 0 di depan
$tahun = date('Y');

// zona waktu
date_default_timezone_set('Asia/Jakarta');
$hariInggris = date('l'); // Misalnya: Tuesday
$hariIndonesia = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$namaHari = $hariIndonesia[$hariInggris]; // Contoh: Selasa
$tanggalCetak = $namaHari . ', ' . date('d-m-Y');

// Query
$sql = "SELECT 
          sales,
          COUNT(*) AS total_input,
          SUM(status_survey = 'Bisa') AS survey_lolos,
          SUM(status_survey != 'Bisa') AS survey_gagal,
          SUM(status_survey = 'Failed') AS total_failed,
          SUM(status_survey = 'Tidak Tercover') AS total_coverage
        FROM pelanggan
        WHERE MONTH(tanggal_input) = MONTH(CURRENT_DATE())
          AND YEAR(tanggal_input) = YEAR(CURRENT_DATE())
        GROUP BY sales
        ORDER BY total_input DESC";

$result = $koneksi->query($sql);

// PDF Set bikin dokumen 
$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

// Logo
$pdf->Image('img/rtlhd.png',160,0,40); // logo rtl

// Judul
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190, 10, 'Laporan Performa Sales', 0, 1, 'C');
$pdf->Ln(0);

//  bulan dan tahun di bawah judul
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, $bulan . ' ' . $tahun, 0, 1, 'C');
$pdf->Ln(5);
// Tanggal Cetak
$pdf->SetFont('Arial','',10);
$pdf->Cell(0, 5, 'Dicetak pada: ' . $tanggalCetak, 0, 1, 'L');
$pdf->Ln(2);

// Header Tabel
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(200,220,255);
$pdf->Cell(10, 10, 'No', 1, 0, 'C',true);
$pdf->Cell(32, 10, 'Nama Sales', 1, 0, 'C',true);
$pdf->Cell(30, 10, 'Input Survey', 1, 0, 'C',true);
$pdf->Cell(30, 10, 'Survey Lolos', 1, 0, 'C',true);
$pdf->Cell(30, 10, 'Survey Gagal', 1, 0, 'C',true);
$pdf->Cell(30, 10, 'Failed', 1, 0, 'C',true);
$pdf->Cell(30, 10, 'Coverage', 1, 1, 'C',true);

// Inisialisasi total keseluruhan
$total_input = 0;
$total_lolos = 0;
$total_gagal = 0;
$total_failed = 0;
$total_coverage = 0;

$no = 1;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(10,10,$no++,1,0,'C');
        $pdf->Cell(32,10,$row['sales'],1,0);
        $pdf->Cell(30,10,$row['total_input'],1,0,'C');
        $pdf->Cell(30,10,$row['survey_lolos'],1,0,'C');
        $pdf->Cell(30,10,$row['survey_gagal'],1,0,'C');
        $pdf->Cell(30,10,$row['total_failed'],1,0,'C');
        $pdf->Cell(30,10,$row['total_coverage'],1,1,'C');

        // Akumulasi total
        $total_input     += $row['total_input'];
        $total_lolos     += $row['survey_lolos'];
        $total_gagal     += $row['survey_gagal'];
        $total_failed    += $row['total_failed'];
        $total_coverage  += $row['total_coverage'];
    }

    // Baris total setelah loop
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(42, 10, 'Total', 1, 0, 'C', True);
    $pdf->Cell(30, 10, $total_input, 1, 0, 'C');
    $pdf->Cell(30, 10, $total_lolos, 1, 0, 'C');
    $pdf->Cell(30, 10, $total_gagal, 1, 0, 'C');
    $pdf->Cell(30, 10, $total_failed, 1, 0, 'C');
    $pdf->Cell(30, 10, $total_coverage, 1, 1, 'C');
} else {
    $pdf->Cell(192, 10, 'Tidak ada data untuk bulan ini.', 1, 1, 'C');
}


//footer 
$pdf->SetDrawColor(0, 0, 0); // warna hitam
$pdf->SetLineWidth(1); // ketebalan garis
$pdf->Line(11, $pdf->GetPageHeight() - 38, $pdf->GetPageWidth() - 11, $pdf->GetPageHeight() - 38);
$pdf->SetY(-36); // Geser ke bawah halaman
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(200, 0, 0); // Merah
$pdf->Cell(0, 5, 'PT. UNION ROUTELINK COMMUNICATION', 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(0, 0, 0); // Hitam
$pdf->MultiCell(0, 5,
    "Yogyakarta : Jl. Pramuka No. 28 Pandean, Umbulharjo, Yogyakarta 55161 No. Tlp: 0274-412650 / 0815 671 0235\n" .
    "Solo | GunungKidul | Kulon Progo | Klaten | Ngawi | Wonogiri | Salatiga | Pacitan | Lampung | Samarinda",
    0, 'L');

$pdf->Output();
?>

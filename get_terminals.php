<?php
include 'koneksi.php';
header('Content-Type: application/json');

$cluster = $_GET['cluster'] ?? '';

if ($cluster) {
    $stmt = $koneksi->prepare("SELECT nama, latitude AS lat, longitude AS lng FROM terminals WHERE cluster = ?");
    $stmt->bind_param("s", $cluster);
    $stmt->execute();
    $result = $stmt->get_result();

    $terminals = [];
    while ($row = $result->fetch_assoc()) {
        $terminals[] = $row;
    }

    echo json_encode($terminals);
} else {
    echo json_encode([]);
}

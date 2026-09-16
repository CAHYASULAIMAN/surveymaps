<?php
// proses_delete_terminal.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        include 'koneksi.php';
        
        $stmt = $koneksi->prepare("DELETE FROM terminals WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Redirect jika berhasil hapus
            header("Location: delete_terminal.php?status=deleted");
        } else {
            // Redirect jika gagal hapus
            header("Location: delete_terminal.php?status=error");
        }

        $stmt->close();
        $koneksi->close();
        exit;
    } else {
        echo "ID tidak valid.";
    }
} else {
    echo "Permintaan tidak sah.";
}
?>

<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $idLaporan = intval($_GET['id']);
    $query = "DELETE FROM pengumpulanlaporan WHERE id_laporan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idLaporan);

    if ($stmt->execute()) {
        echo "Laporan berhasil dihapus.";
    } else {
        echo "Gagal menghapus laporan.";
    }

    $stmt->close();
}

$conn->close();
?>

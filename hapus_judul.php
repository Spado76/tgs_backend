<?php
// Ambil ID dari URL (gunakan id, bukan id_judul)
$id = $_GET['id'];

include 'koneksi.php';
// Validasi input ID untuk mencegah SQL Injection
$id = $conn->real_escape_string($id);

// SQL untuk menghapus data berdasarkan id_judul
$sql = "DELETE FROM pengajuanjudul WHERE id_judul = ?";

// Persiapkan query
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

// Eksekusi query
if ($stmt->execute()) {
    // Jika berhasil, redirect ke jadwal.php
    header("Location: statusjudul.php");
    exit;
} else {
    // Jika gagal, tampilkan error
    echo "Error: " . $conn->error;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>

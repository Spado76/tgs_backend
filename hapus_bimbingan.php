<?php
// Ambil ID dari URL (gunakan id, bukan id_pengajuan)
$id = $_GET['id'];

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "tugasakhir";
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Validasi input ID untuk mencegah SQL Injection
$id = $conn->real_escape_string($id);

// SQL untuk menghapus data berdasarkan id_pengajuan
$sql = "DELETE FROM pengajuanbimbingan WHERE id_pengajuan = ?";

// Persiapkan query
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

// Eksekusi query
if ($stmt->execute()) {
    // Jika berhasil, redirect ke jadwal.php
    header("Location: jadwal.php");
    exit;
} else {
    // Jika gagal, tampilkan error
    echo "Error: " . $conn->error;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>

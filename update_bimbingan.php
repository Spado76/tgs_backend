<?php
$id = $_POST['id_pengajuan'];
$tgl_bimbingan = $_POST['tgl_bimbingan'];
$catatan = $_POST['catatan'];

// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "tugasakhir";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "UPDATE pengajuanbimbingan SET tgl_bimbingan = ?, catatan = ? WHERE id_pengajuan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $tgl_bimbingan, $catatan, $id);

if ($stmt->execute()) {
    echo "Data berhasil diperbarui.";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
header("Location: jadwal.php");
exit;
?>

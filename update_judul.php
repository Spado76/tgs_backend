<?php
$id = $_POST['id_judul'];
$judul_proposal = $_POST['judul_proposal'];
$deskripsi = $_POST['deskripsi'];

// Koneksi database
include 'koneksi.php';

$sql = "UPDATE pengajuanjudul SET judul_proposal = ?, deskripsi = ? WHERE id_judul = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $judul_proposal, $deskripsi, $id);

if ($stmt->execute()) {
    echo "Data berhasil diperbarui.";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
header("Location: statusjudul.php");
exit;
?>

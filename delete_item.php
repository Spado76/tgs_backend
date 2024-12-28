<?php
session_start();
include 'koneksi.php'; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id_item = $input['id_item'];
    $nim = $_SESSION['username']; // Mendapatkan NIM dari sesi

    // Validasi id_item
    if (!isset($id_item)) {
        echo json_encode(['status' => 'error', 'message' => 'ID item tidak valid.']);
        exit();
    }

    // Query DELETE
    $query = "DELETE FROM kanbanprogres WHERE id_item = ? AND NIM = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $id_item, $nim);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus item.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid.']);
}
?>

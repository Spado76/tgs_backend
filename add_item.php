<?php
session_start();
include 'koneksi.php'; // File koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $input['name'];
    $start_date = $input['startDate'];
    $end_date = $input['endDate'];
    $nim = $_SESSION['username']; // Mendapatkan NIM dari sesi

    // Validasi input
    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Nama tugas tidak boleh kosong.']);
        exit();
    }

    // Query untuk menambahkan tugas ke database
    $query = "INSERT INTO kanbanprogres (NIM, nama_item, tanggal_mulai, tanggal_selesai, status) VALUES (?, ?, ?, ?, 'To Do')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $nim, $name, $start_date, $end_date);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan tugas.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid.']);
}
?>

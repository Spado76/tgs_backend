<?php
$host = 'localhost'; // Ganti sesuai host MySQL Anda
$username = 'root';  // Ganti sesuai username MySQL Anda
$password = '';      // Ganti sesuai password MySQL Anda
$dbname = 'tugasakhir'; // Nama database

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
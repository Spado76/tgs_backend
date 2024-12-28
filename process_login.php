<?php
session_start(); // Tambahkan ini di awal file

// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'tugasakhir';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Ambil data dari form
$nim = $_POST['username'];
$pwd = $_POST['password'];

// Validasi username dan password
$sql = "SELECT NIM, password, nama, matkultugasakhir, totalsks, ipk, dosenpembimbing FROM login WHERE NIM = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $nim, $pwd);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Validasi tambahan
    if (
        $row['matkultugasakhir'] === 'YA' &&
        $row['totalsks'] >= 110 &&
        $row['ipk'] >= 2.00 &&
        $row['dosenpembimbing'] != ""
    ) {
        // Simpan data ke sesi
        $_SESSION['username'] = $nim;
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['dosenpembimbing'] = $row['dosenpembimbing'];

        header('Location: index.php'); // Arahkan ke halaman index
        exit(); // Pastikan script berhenti di sini
    } else {
        header('Location: login.php?error=not_eligible');
        exit();
    }
} else {
    header('Location: login.php?error=invalid_credentials');
    exit();
}

$stmt->close();
$conn->close();
?>

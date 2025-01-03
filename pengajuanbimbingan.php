<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Ambil data login dari sesi
$nim_login = $_SESSION['username'];
$nama_login = $_SESSION['nama']; // Pastikan nama juga disimpan di sesi saat login
$dosen_pembimbing = $_SESSION['dosenpembimbing']; // Asumsikan diambil saat login

// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'tugasakhir';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Proses pengajuan
$message_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl_bimbingan = $_POST['tanggal'];
    $catatan = $_POST['catatan'];

    // Validasi tanggal bimbingan
    $selectedDate = new DateTime($tgl_bimbingan);
    $today = new DateTime();
    $today->setTime(23, 59, 59); // Set waktu ke 00:00 untuk validasi

    if ($selectedDate < $today) {
        $message_error = 'Tanggal bimbingan harus minimal besok.';
    } else {
        // Insert data ke database
        $stmt = $conn->prepare("INSERT INTO pengajuanbimbingan (NIM, nama, tgl_bimbingan, catatan, status, dosenpembimbing) VALUES (?, ?, ?, ?, 'Belum Disetujui', ?)");
        $stmt->bind_param('sssss', $nim_login, $nama_login, $tgl_bimbingan, $catatan, $dosen_pembimbing);

        if ($stmt->execute()) {
            // Redirect setelah berhasil menyimpan data
            header('Location: pengajuanbimbingan.php?success=1');
            exit();
        } else {
            $message_error = 'Gagal mengajukan bimbingan: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengajuan Bimbingan</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>SISTEM INFORMASI<br>TUGAS AKHIR</h2>
      <nav>
        <ul>
          <li><a href="index.php">Dashboard</a></li>
          <li><a href="pendaftaranjudul.php">Proposal Pendaftaran Judul</a></li>
          <li><a href="pengajuanbimbingan.php">Pengajuan Bimbingan</a></li>
          <li><a href="jadwal.php">Jadwal Bimbingan</a></li>
          <li><a href="proposal.php">Project Manajer</a></li>
          <li><a href="statusjudul.php">Status Proposal</a></li>
          <li><a href="laporanjudul.php">Pengumpulan Laporan</a></li>
          <li><a href="hasilupload.php">Hasil Upload</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">

      <section>
        <h1>Pengajuan Bimbingan</h1>
        <div class="overview-box">
          <form action="pengajuanbimbingan.php" method="POST">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama_login); ?>" readonly>

            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($nim_login); ?>" readonly>

            <label for="tanggal">Tanggal Bimbingan:</label>
            <input type="date" id="tanggal" name="tanggal" required>

            <label for="catatan">Catatan:</label>
            <textarea id="catatan" name="catatan" rows="4" required></textarea>

            <button type="submit">Ajukan</button>
          </form>
          <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div id="success-message" style="display: block; margin-top: 20px; color: green;">
              Berhasil melakukan pengajuan!
            </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</body>
</html>

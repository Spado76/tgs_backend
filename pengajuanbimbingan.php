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
$message = '';
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
            $message = 'Berhasil mengajukan bimbingan.';
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
          <li><a href="pendaftaranjudul.html">Proposal Pendaftaran Judul</a></li>
          <li><a href="pengajuanbimbingan.php">Pengajuan Bimbingan</a></li>
          <li><a href="jadwal.html">Jadwal Bimbingan</a></li>
          <li><a href="proposal.html">Project Manajer</a></li>
          <li><a href="statusjudul.html">Status Proposal</a></li>
          <li><a href="laporanjudul.html">Pengumpulan Laporan</a></li>
          <li><a href="hasilupload.html">Hasil Upload</a></li>
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
          <?php if ($message): ?>
          <div style="margin-top: 20px; color: green;">
            <?php echo htmlspecialchars($message); ?>
          </div>
          <?php elseif ($message_error): ?>
          <div style="margin-top: 20px; color: red;">
            <?php echo htmlspecialchars($message_error); ?>
          </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</body>
</html>

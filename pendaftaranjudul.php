<?php
session_start();
include 'koneksi.php'; // File koneksi database

// Validasi sesi login
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

// Ambil data dari sesi
$nim = $_SESSION['username'];
$nama = $_SESSION['nama'];
$dosenPembimbing = $_SESSION['dosenpembimbing'];

// Handle pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judulProposal = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validasi input
    if (empty($judulProposal) || empty($deskripsi)) {
        $error = "Judul proposal dan deskripsi harus diisi!";
    } else {
        // Simpan data ke database
        $query = $conn->prepare("INSERT INTO pengajuanjudul (NIM, judul_proposal, deskripsi, status, dosenpembimbing) VALUES (?, ?, ?, 'Belum Disetujui', ?)");
        $query->bind_param('ssss', $nim, $judulProposal, $deskripsi, $dosenPembimbing);

        if ($query->execute()) {
            // Redirect setelah berhasil menyimpan data
            header('Location: pendaftaranjudul.php?success=1');
            exit();
        } else {
            $error = "Gagal menyimpan data. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Judul Proposal</title>
  <link rel="stylesheet" href="style.css">
  <script>
    function showSuccessMessage(event) {
      event.preventDefault(); // Mencegah pengiriman formulir secara default
      const messageBox = document.getElementById('success-message');
      messageBox.style.display = 'block';
    }
  </script>
</head>
<body>
  <div class="container">
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
          <li><a href="laporanjudul.html">Pengumpulan Laporan</a></li>
          <li><a href="hasilupload.html">Hasil Upload</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <div class="main-content">
      <section>
        <h1>Pendaftaran Judul Proposal</h1>
        <div class="overview-box">
          <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
          <?php endif; ?>

          <form action="" method="POST">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($nim); ?>" readonly>

            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" readonly>

            <label for="judul">Judul Proposal:</label>
            <input type="text" id="judul" name="judul" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" required></textarea>

            <button type="submit">Daftarkan Judul</button>
          </form>
          <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div id="success-message" style="display: block; margin-top: 20px; color: green;">
              Berhasil melakukan pendaftaran!
            </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</body>
</html>

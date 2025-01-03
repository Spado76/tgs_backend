<?php
session_start();

// Cek apakah sesi username ada
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Kembali ke login jika sesi tidak ditemukan
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Informasi Tugas Akhir</title>
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
    <main class="main-content">
      <header>
        <div class="user-info">
          <p>Selamat Datang, <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span></p>
        </div>
      </header>
      <section>
        <h1>Dashboard</h1>
        <p>Silakan pilih fitur yang tersedia di menu navigasi.</p>
      </section>
    </main>
  </div>
</body>
</html>

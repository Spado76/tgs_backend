<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

$nim = $_SESSION['username'];

include 'koneksi.php'; // file koneksi database

// Ambil data dari tabel pengajuanjudul
$sql = "SELECT p.id_judul, p.NIM, l.nama AS nama_login, p.judul_proposal, p.deskripsi, p.status, p.dosenpembimbing 
        FROM pengajuanjudul p
        INNER JOIN login l ON p.NIM = l.NIM
        WHERE p.NIM = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nim);

// Eksekusi query
$stmt->execute();
$result = $stmt->get_result(); // Ambil hasil query

// Tutup statement dan koneksi
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Judul Proposal</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="popup.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      height: 100vh;
    }
    .container {
      display: flex;
      width: 100%;
    }
    .main-content {
      flex: 1;
      padding: 20px;
      box-sizing: border-box;
      background: linear-gradient(to bottom, #f8e9c0, #f1c27c); /* Warna gradasi */
    }
    h1 {
      font-size: 24px;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      background-color: white; /* Latar belakang tabel */
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    table th, table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    table th {
      background-color: #f4f4f4;
    }
    .message {
      text-align: center;
      font-size: 18px;
      color: #7f8c8d;
    }
    button {
      color: white;
      background-color: #16a085;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #1abc9c;
    }
    .overview-box {
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
  </style>
  <script>
    function confirmDelete(id) {
      if (confirm('Yakin ingin menghapus data ini?')) {
        window.location.href = 'hapus_judul.php?id=' + id; // Redirect ke halaman hapus
      }
    }

    function openEditPopup(id_judul, nama, judul_proposal, deskripsi) {
      document.getElementById('id').value = id_judul;
      document.getElementById('nama').value = nama;
      document.getElementById('judul_proposal').value = judul_proposal;
      document.getElementById('deskripsi').value = deskripsi;
      document.getElementById('editPopup').style.display = 'block';
    }

    function closeEditPopup() {
      document.getElementById('editPopup').style.display = 'none';
    }
  </script>
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
          <li><a href="laporanjudul.html">Pengumpulan Laporan</a></li>
          <li><a href="hasilupload.html">Hasil Upload</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
      <section>
        <h1>Status Judul Proposal</h1>
          <table>
            <thead>
              <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Judul Proposal</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Dosen Pembimbing</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['NIM'] ?></td>
                  <td><?= $row['nama_login'] ?></td>
                  <td><?= $row['judul_proposal'] ?></td>
                  <td><?= $row['deskripsi'] ?: '-' ?></td>
                  <td><?= $row['status'] ?></td>
                  <td><?= $row['dosenpembimbing'] ?: 'Belum Ditentukan' ?></td>
                  <td>
                    <?php if ($row['status'] == 'Belum Disetujui'): ?>
                      <button onclick="openEditPopup('<?= $row['id_judul'] ?>', '<?= $row['nama_login'] ?>', '<?= $row['judul_proposal'] ?>', '<?= $row['deskripsi'] ?>')">Edit</button>
                      <button class="delete-btn" onclick="confirmDelete('<?= $row['id_judul'] ?>')">Hapus</button>
                    <?php else: ?>
                      <!-- Jika status bukan "Belum Disetujui", tidak menampilkan tombol -->
                      <span>-</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="message">Belum ada status.</td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
  <!-- Popup untuk Edit -->
  <div id="editPopup" style="display: none;">
    <div class="popup-content">
      <h2>Edit Jadwal Bimbingan</h2>
      <form action="update_judul.php" method="POST">
        <input type="hidden" name="id_judul" id="id">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" readonly>
        <label for="judul_proposal">Judul Proposal:</label>
        <input type="text" name="judul_proposal" id="judul_proposal">
        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi"></textarea>
        <button type="submit">Simpan</button>
        <button type="button" onclick="closeEditPopup()">Batal</button>
      </form>
    </div>
  </div>
</body>
</html>

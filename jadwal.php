<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$nim = $_SESSION['username'];

// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "tugasakhir";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel pengajuanbimbingan
$sql = "SELECT p.id_pengajuan, p.NIM, p.nama, p.tgl_bimbingan, p.catatan, p.status, p.dosenpembimbing FROM pengajuanbimbingan p WHERE nim = ?";
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
  <title>Status Jadwal Bimbingan</title>
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
    function validateDate() {
      const tglBimbingan = document.getElementById('tglBimbingan').value;
      const selectedDate = new Date(tglBimbingan);
      const today = new Date();
      
      // Set batas minimal ke besok
      const minDate = new Date();
      minDate.setDate(today.getDate());

      if (selectedDate < minDate) {
        alert("Tanggal bimbingan harus minimal besok.");
        return false; // Menghentikan submit form
      }

      return true; // Melanjutkan submit jika valid
    }

    function confirmDelete(id) {
      if (confirm('Yakin ingin menghapus data ini?')) {
        window.location.href = 'hapus_bimbingan.php?id=' + id; // Redirect ke halaman hapus
      }
    }

    function openEditPopup(idPengajuan, nama, tglBimbingan, catatan) {
      document.getElementById('id').value = idPengajuan;
      document.getElementById('nama').value = nama;
      document.getElementById('tglBimbingan').value = tglBimbingan;
      document.getElementById('catatan').value = catatan;
      document.getElementById('editPopup').style.display = 'block';
    }

    function closeEditPopup() {
      document.getElementById('editPopup').style.display = 'none';
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
          <li><a href="pendaftaranjudul.html">Proposal Pendaftaran Judul</a></li>
          <li><a href="pengajuanbimbingan.php">Pengajuan Bimbingan</a></li>
          <li><a href="jadwal.php">Jadwal Bimbingan</a></li>
          <li><a href="proposal.html">Project Manajer</a></li>
          <li><a href="statusjudul.html">Status Proposal</a></li>
          <li><a href="laporanjudul.html">Pengumpulan Laporan</a></li>
          <li><a href="hasilupload.html">Hasil Upload</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <div class="main-content">
      <section>
        <h1>Status Jadwal Bimbingan</h1>
        <table>
          <thead>
            <tr>
              <th>NIM</th>
              <th>Nama</th>
              <th>Tanggal Bimbingan</th>
              <th>Catatan</th>
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
                  <td><?= $row['nama'] ?></td>
                  <td><?= $row['tgl_bimbingan'] ?></td>
                  <td><?= $row['catatan'] ?: '-' ?></td>
                  <td><?= $row['status'] ?></td>
                  <td><?= $row['dosenpembimbing'] ?: 'Belum Ditentukan' ?></td>
                  <td>
                    <?php if ($row['status'] == 'Belum Disetujui'): ?>
                      <button onclick="openEditPopup('<?= $row['id_pengajuan'] ?>', '<?= $row['nama'] ?>', '<?= $row['tgl_bimbingan'] ?>', '<?= $row['catatan'] ?>')">Edit</button>
                      <button class="delete-btn" onclick="confirmDelete('<?= $row['id_pengajuan'] ?>')">Hapus</button>
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
      </section>
    </div>
  </div>

  <!-- Popup untuk Edit -->
  <div id="editPopup" style="display: none;">
    <div class="popup-content">
      <h2>Edit Jadwal Bimbingan</h2>
      <form action="update_bimbingan.php" method="POST" onsubmit="return validateDate()">
        <input type="hidden" name="id_pengajuan" id="id">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" readonly>
        <label for="tglBimbingan">Tanggal Bimbingan:</label>
        <input type="date" name="tgl_bimbingan" id="tglBimbingan">
        <label for="catatan">Catatan:</label>
        <textarea name="catatan" id="catatan"></textarea>
        <button type="submit">Simpan</button>
        <button type="button" onclick="closeEditPopup()">Batal</button>
      </form>
    </div>
  </div>
</body>
</html>
<?php $conn->close(); ?>

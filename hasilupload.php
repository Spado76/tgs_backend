<?php
session_start();
include 'koneksi.php'; // file koneksi database

// Validasi sesi login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$nim = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Upload</title>
  <style>
    body {
      background-color: #f5f5f5;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    h1 {
      font-size: 1.8em;
      color: #333;
      margin-bottom: 20px;
      text-align: center;
    }
    #resultList {
      margin: 20px auto;
      text-align: left;
    }
    .upload-item {
      margin-bottom: 20px;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #fafafa;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    .upload-item p {
      margin: 5px 0;
      font-size: 1em;
      color: #555;
    }
    .upload-item strong {
      color: #333;
    }
    .upload-item button {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 12px;
      background-color: #dc3545;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 0.9em;
      cursor: pointer;
    }
    .upload-item button:hover {
      background-color: #c82333;
    }
    .dashboard-button {
      display: block;
      margin: 30px auto 0;
      padding: 10px 20px;
      font-size: 1em;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      text-transform: uppercase;
    }
    .dashboard-button:hover {
      background-color: #0056b3;
    }
    .empty-state {
      text-align: center;
      color: #666;
      font-size: 1.1em;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Hasil Upload</h1>
    <div id="resultList">
      <?php
        // Query untuk mengambil data berdasarkan NIM
        $stmt = $conn->prepare("SELECT pl.id_laporan, pj.judul_proposal, pl.jenis_laporan 
                                FROM pengumpulanlaporan AS pl
                                JOIN pengajuanjudul AS pj ON pl.id_judul = pj.id_judul
                                WHERE pl.NIM = ?");
        $stmt->bind_param('s', $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<div class="upload-item">';
            echo '<p><strong>Judul Proposal:</strong> ' . htmlspecialchars($row['judul_proposal']) . '</p>';
            echo '<p><strong>Jenis Laporan:</strong> ' . htmlspecialchars($row['jenis_laporan']) . '</p>';
            echo '<p><strong>Status:</strong> Terkirim</p>';
            echo '<button onclick="deleteItem(' . intval($row['id_laporan']) . ')">Hapus</button>';
            echo '</div>';
          }
        } else {
          echo '<p class="empty-state">Tidak ada laporan yang diunggah.</p>';
        }

        $stmt->close();
        $conn->close();
      ?>
    </div>
    <a href="index.php" class="dashboard-button">Kembali ke Dashboard</a>
  </div>

  <script>
    function deleteItem(idLaporan) {
      if (confirm("Apakah Anda yakin ingin menghapus laporan ini?")) {
        fetch(`hapus_laporan.php?id=${idLaporan}`, {
          method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
          location.reload(); // Refresh halaman
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Gagal menghapus laporan.');
        });
      }
    }
  </script>
</body>
</html>

<?php
session_start();
include 'koneksi.php'; // File koneksi ke database

// Validasi sesi login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Ambil data dari sesi
$nim = $_SESSION['username'];
$nama = $_SESSION['nama'];
$dosenPembimbing = $_SESSION['dosenpembimbing'];

// Query untuk mengambil judul proposal yang disetujui berdasarkan NIM
$query = "SELECT id_judul, judul_proposal FROM pengajuanjudul WHERE NIM = ? AND status = 'Disetujui'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

$judulOptions = "";
while ($row = $result->fetch_assoc()) {
    $judulOptions .= "<option value='{$row['id_judul']}'>{$row['judul_proposal']}</option>";
}

$stmt->close();

// Validasi pengumpulan laporan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_judul = $_POST['id_judul'];
    $jenisLaporan = $_POST['reportType'] ?? '';
    $link = $_POST['journalLink'] ?? null;
    $fileName = $_FILES['fileUpload']['name'] ?? null;
    $fileTmp = $_FILES['fileUpload']['tmp_name'] ?? null;
    $fileError = $_FILES['fileUpload']['error'] ?? null;

    $link = !empty($link) ? $link : null;

    // Validasi input
    $errors = [];
    if (empty($id_judul)) {
        $errors[] = "Judul proposal harus dipilih.";
    }
    if (empty($jenisLaporan)) {
        $errors[] = "Jenis laporan harus dipilih.";
    }
    if (empty($fileName)) {
        $errors[] = "File harus diunggah.";
    }
    if ($fileName && pathinfo($fileName, PATHINFO_EXTENSION) !== 'pdf') {
        $errors[] = "Format file harus PDF.";
    }
    if ($fileError) {
        $errors[] = "Terjadi kesalahan saat mengunggah file.";
    }

    if (empty($errors)) {
      // Simpan file
      $uploadDir = __DIR__ . '/uploads/';
      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true); // Buat folder jika belum ada
      }
      $uploadPath = $uploadDir . basename($fileName);

      if (move_uploaded_file($fileTmp, $uploadPath)) {
          // Simpan data ke database
          $query = $conn->prepare("INSERT INTO pengumpulanlaporan (NIM, id_judul, jenis_laporan, file_unggah, link, dosenpembimbing) VALUES (?, ?, ?, ?, ?, ?)");
          $query->bind_param('sissss', $nim, $id_judul, $jenisLaporan, $uploadPath, $link, $dosenPembimbing);

          if ($query->execute()) {
              echo "<script>alert('Berhasil mengumpulkan laporan!'); window.location.href = 'index.php';</script>";
          } else {
              $errors[] = "Gagal menyimpan data. Silakan coba lagi.";
          }
      } else {
          $errors[] = "Gagal mengunggah file. Pastikan folder uploads memiliki izin.";
      }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengumpulan Laporan</title>
    <style>
        body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }
    h1 {
      position: absolute;
      top: 0;
      left: 50%;
      transform: translate(-50%, 0);
      text-align: center;
      font-size: 2em;
      font-weight: bold;
      margin: 0;
      padding: 10px 0;
    }
    .container {
      max-width: 600px;
      margin: 100px auto 0;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    label {
      font-weight: bold;
    }
    input, select, button {
      padding: 10px;
      font-size: 1em;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: #28a745;
      color: white;
      cursor: pointer;
    }
    button:hover {
      background-color: #218838;
    }
    .hidden {
      display: none;
    }
    #backToDashboard {
      background-color: #007bff;
      color: white;
      padding: 10px;
      font-size: 1em;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
      margin-top: 20px;
    }
    #backToDashboard:hover {
      background-color: #0056b3;
    }
    </style>
</head>
<body>
    <h1>Pengumpulan Laporan</h1>
    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <?php if (!empty($errors)): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color: red;"> <?= htmlspecialchars($error) ?> </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <label for="nim">NIM</label>
            <input type="text" id="nim" name="nim" value="<?= htmlspecialchars($nim) ?>" readonly>

            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($nama) ?>" readonly>

            <label for="judulProposal">Judul Proposal</label>
            <select id="judulProposal" name="id_judul" required>
              <option value="">-- Pilih Judul Proposal --</option>
              <?php echo $judulOptions; ?>
            </select>


            <label for="reportType">Jenis Laporan</label>
            <select id="reportType" name="reportType" required>
                <option value="">-- Pilih Jenis Laporan --</option>
                <option value="skripsi">Skripsi</option>
                <option value="jurnal">Jurnal</option>
                <option value="projek">Proyek Rekayasa Perangkat Lunak/Keras</option>
                <option value="magang">Magang Industri</option>
                <option value="sertifikasi">Uji Sertifikasi Kompetensi</option>
                <option value="kkn">Kuliah Kerja Nyata Tematik</option>
                <option value="prestasi">Prestasi Lomba</option>
                <option value="eksibisi">Eksibisi Karya Mahasiswa</option>
                <option value="penelitian">Menjadi Anggota Penelitian Dosen</option>
                <option value="buku">Menulis Buku</option>
            </select>

            <label for="fileUpload">Unggah File</label>
            <input type="file" id="fileUpload" name="fileUpload" accept=".pdf">

            <label for="journalLink">Link Yang Bersangkutan Dengan Laporan (Opsional)</label>
            <input type="url" id="journalLink" name="journalLink" placeholder="Masukkan Link">

            <button type="submit">Kirim</button>
            <button id="backToDashboard">Kembali ke Dashboard</button>
        </form>
        <script>
          const backToDashboard = document.getElementById('backToDashboard');
          backToDashboard.addEventListener('click', () => {
          window.location.href = 'index.php'; // Redirect ke halaman dashboard
          });
        </script>
    </div>
</body>
</html>

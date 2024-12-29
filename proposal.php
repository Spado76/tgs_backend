<?php
session_start();
include 'koneksi.php'; // file koneksi database

// Ambil NIM dari sesi
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$nim = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];
  $id_item = $_POST['id_item'] ?? null;
  $nama_item = $_POST['nama_item'];
  $tgl_mulai = empty($_POST['tgl_mulai']) ? null : $_POST['tgl_mulai'];
  $tgl_akhir = empty($_POST['tgl_akhir']) ? null : $_POST['tgl_akhir'];

  if ($action === 'add') {
      // Validasi nama item
      if (empty($nama_item)) {
          echo json_encode(['status' => 'error', 'message' => 'Nama item harus diisi!']);
          exit();
      }
      if (empty($tgl_mulai) && $tgl_akhir) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak bisa mengisi waktu selesai tanpa adanya waktu mulai!']);
        exit();
      }

      $stmt = $conn->prepare("INSERT INTO kanbanprogres (NIM, nama_item, tgl_mulai, tgl_akhir) VALUES (?, ?, ?, ?)");
      $stmt->bind_param('ssss', $nim, $nama_item, $tgl_mulai, $tgl_akhir);
      $stmt->execute();
      $id = $stmt->insert_id;
      $stmt->close();

      echo json_encode(['status' => 'success', 'id_item' => $id]);
      exit();
  } elseif ($action === 'edit') {
      if (empty($nama_item)) {
          echo json_encode(['status' => 'error', 'message' => 'Nama item tidak boleh kosong!']);
          exit();
      }
      if (empty($tgl_mulai) && $tgl_akhir) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak bisa mengisi waktu selesai tanpa adanya waktu mulai!']);
        exit();
      }

      $stmt = $conn->prepare("UPDATE kanbanprogres SET nama_item = ?, tgl_mulai = ?, tgl_akhir = ? WHERE id_item = ? AND NIM = ?");
      $stmt->bind_param('sssis', $nama_item, $tgl_mulai, $tgl_akhir, $id_item, $nim);
      $stmt->execute();
      $stmt->close();

      echo json_encode(['status' => 'success']);
      exit();
  }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Informasi Tugas Akhir</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="kanban.css">
  <link rel="stylesheet" href="popup.css">
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
        <section>
          <h1>Daftar Progres Tugas Akhir</h1>
          <p>Silakan tambahkan daftar tugas yang ingin dikerjakan</p>
  
          <div class="kanban-board">
            <button class="Click-here">Tambah</button>
            <div class="kanban-columns">
              <div class="kanban-column">
                <h3>To Do</h3>
                <div class="kanban-tasks" id="todo-tasks">
                </div>
              </div>
              <div class="kanban-column">
                <h3>In Progress</h3>
                <div class="kanban-tasks" id="inprogress-tasks">
                </div>
              </div>
              <div class="kanban-column">
                <h3>Done</h3>
                <div class="kanban-tasks" id="done-tasks">
                </div>
              </div>
            </div>
          </div>
  
          <!-- Popup Modal -->
          <div id="taskModal" class="custom-model-main">
            <div class="custom-model-inner">
                <div class="custom-model-wrap">
                    <div class="pop-up-content-wrap">
                    <h2 id="modalTitle">Tambah Tugas</h2>
                    <label for="taskName">Nama Item:</label>
                    <input type="text" id="taskName" placeholder="Masukkan nama item"></br>
                    <label for="taskStartDate">Tanggal Mulai:</label>
                    <input type="date" id="taskStartDate"></br>
                    <label for="taskEndDate">Tanggal Berakhir:</label>
                    <input type="date" id="taskEndDate"></br></br>
                    <button id="saveTaskButton">Selesai</button>
                    <button id="cancelTaskButton" class="close-btn">Batal</button>
                    <button id="deleteTaskButton" class="delete-btn">Hapus</button>
                    </div>
                </div>
            </div>
          </div>
        </section>
      </main>
    </div>

  <script>
    let currentTask = null; // Track the task being edited

    // Modal functionality
    document.querySelector('.Click-here').addEventListener('click', function() {
      currentTask = null; // Reset current task
      openModal('Tambah Tugas');
    });

    function openModal(title) {
      document.getElementById('modalTitle').textContent = title;
      document.querySelector('.custom-model-main').classList.add('model-open');
    }

    document.querySelectorAll('.close-btn').forEach(element => {
      element.addEventListener('click', function() {
        closeModal();
      });
    });

    function closeModal() {
      document.querySelector('.custom-model-main').classList.remove('model-open');
      document.getElementById('taskName').value = '';
      document.getElementById('taskStartDate').value = '';
      document.getElementById('taskEndDate').value = '';
    }

    // Save Task Button Functionality
    document.getElementById('saveTaskButton').addEventListener('click', () => {
      const taskName = document.getElementById('taskName').value;
      const taskStartDate = document.getElementById('taskStartDate').value;
      const taskEndDate = document.getElementById('taskEndDate').value;

      if (!taskName) {
        alert('Nama item harus diisi!');
        return;
      }

      const action = currentTask ? 'edit' : 'add';
      const id_item = currentTask ? currentTask.dataset.id : null;

      const formData = new FormData();
      formData.append('action', action);
      formData.append('id_item', id_item);
      formData.append('nama_item', taskName);
      formData.append('tgl_mulai', taskStartDate);
      formData.append('tgl_akhir', taskEndDate);

      fetch('proposal.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            if (action === 'add') {
              const newTask = document.createElement('div');
              newTask.classList.add('kanban-task');
              newTask.dataset.id = data.id_item;
              newTask.textContent = formatTaskText(taskName, taskStartDate, taskEndDate);
              newTask.addEventListener('click', () => editTask(newTask));
              moveTask(newTask, taskName, taskStartDate, taskEndDate);
            } else {
              currentTask.textContent = formatTaskText(taskName, taskStartDate, taskEndDate);
              moveTask(currentTask, taskName, taskStartDate, taskEndDate);
            }
          } else {
            alert(data.message);
          }
          closeModal();
        })
        .catch(error => console.error('Error:', error));
    });

    function formatTaskText(name, startDate, endDate) {
      let text = name;
      if (startDate) text += ` || ${startDate}`;
      if (endDate) text += ` - ${endDate}`;
      return text;
    }

    function moveTask(taskElement, name, startDate, endDate) {
      const todoTasks = document.getElementById('todo-tasks');
      const inProgressTasks = document.getElementById('inprogress-tasks');
      const doneTasks = document.getElementById('done-tasks');

      if (name && startDate && endDate) {
        doneTasks.appendChild(taskElement);
      } else if (name && startDate) {
        inProgressTasks.appendChild(taskElement);
      } else if (name) {
        todoTasks.appendChild(taskElement);
      } else if (name && endDate){
        alert('Tidak bisa mengisi waktu selesai tanpa adanya waktu mulai!');
        return;
      }
    }

    function editTask(taskElement) {
      currentTask = taskElement;

      const taskText = taskElement.textContent;
      const [name, dates] = taskText.split(' || ');
      let startDate = '', endDate = '';
      if (dates) {
        [startDate, endDate] = dates.split(' - ');
      }

      document.getElementById('taskName').value = name.trim();
      document.getElementById('taskStartDate').value = startDate ? startDate.trim() : '';
      document.getElementById('taskEndDate').value = endDate ? endDate.trim() : '';

      openModal('Edit Tugas');
    }

    // Delete Task Button Functionality
    document.getElementById('deleteTaskButton').addEventListener('click', () => {
    if (currentTask) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            const taskId = currentTask.getAttribute('data-id'); // Asumsikan setiap task memiliki atribut data-id
            fetch('delete_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_item: taskId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    currentTask.remove(); // Hapus elemen dari tampilan
                    currentTask = null;
                    alert('Item berhasil dihapus.');
                } else {
                    alert(data.message || 'Gagal menghapus item.');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan saat menghapus item.');
            });
        }
    }
    closeModal();
  });

  document.addEventListener('DOMContentLoaded', () => {
    const tasks = document.querySelectorAll('.kanban-task');
    tasks.forEach(task => {
        const status = task.getAttribute('data-status');
        document.getElementById(status).appendChild(task);

        // Tambahkan event listener untuk edit
        task.addEventListener('click', () => editTask(task));
    });
  });

  </script>
  <?php
  $query = $conn->prepare("SELECT * FROM kanbanprogres WHERE NIM = ?");
  $query->bind_param('s', $nim);
  $query->execute();
  $result = $query->get_result();

  while ($row = $result->fetch_assoc()) {
      if (!$row['tgl_mulai'] && !$row['tgl_akhir']) {
          $status = 'todo-tasks';
          echo "<div class='kanban-task' data-id='{$row['id_item']}' data-status='$status'>
                {$row['nama_item']}
              </div>";
      } elseif ($row['tgl_mulai'] && !$row['tgl_akhir']) {
          $status = 'inprogress-tasks';
          echo "<div class='kanban-task' data-id='{$row['id_item']}' data-status='$status'>
            {$row['nama_item']} || {$row['tgl_mulai']}
          </div>";
      } elseif ($row['tgl_mulai'] && $row['tgl_akhir']) {
          $status = 'done-tasks';
          echo "<div class='kanban-task' data-id='{$row['id_item']}' data-status='$status'>
            {$row['nama_item']} || {$row['tgl_mulai']} - {$row['tgl_akhir']}
          </div>";
      }
  }
  ?>
</body>
</html>

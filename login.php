<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* CSS untuk memusatkan form login */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .login-container label {
      font-size: 14px;
      margin-bottom: 5px;
      display: block;
    }

    .login-container input {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .login-container button {
      width: 100%;
      padding: 10px;
      background-color: #16a085;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .login-container button:hover {
      background-color: #1abc9c;
    }

    .login-container p {
      text-align: center;
    }

    .login-container a {
      color: #16a085;
      text-decoration: none;
    }

    .login-container a:hover {
      text-decoration: underline;
    }

    #error-message {
      color: red;
      display: none;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <?php if (isset($_GET['error'])): ?>
      <div class="error-message">
        <?php
        if ($_GET['error'] == 'invalid_credentials') {
          echo 'Username dan Password tidak valid.<br> Klik <a href="login.php" class="back-link">kembali</a> untuk melakukan login ulang.';
        } elseif ($_GET['error'] == 'not_eligible') {
          echo 'Anda Tidak Memenuhi Syarat Untuk Melakukan Tugas Akhir.<br> Klik <a href="login.php" class="back-link">kembali</a> untuk melakukan login ulang.';
        }
        ?>
      </div>
    <?php else: ?>
    <h2>Login</h2>
    <form action="process_login.php" method="POST">
      <label for="username">NIM:</label>
      <input type="text" id="username" name="username" required pattern="^\d+$" title="Username harus berupa angka">

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>

      <p>Gunakanlah username dan password siakad</p>
      <p><a href="#">Demo Documentation</a></p>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>

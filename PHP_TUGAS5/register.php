<?php
require_once 'db_connect.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashedPassword])) {
                $success = "Registrasi berhasil! Anda akan diarahkan ke halaman login dalam 5 detik...";
                header("refresh:5;url=login.php");
            } else {
                $error = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - To‑Do List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      /* Import font */
      @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
      :root {
        --bg: #121212;
        --card: #1e1e1e;
        --accent: #BB86FC;
        --text: #E0E0E0;
        --text-secondary: #757575;
        --radius: 0.5rem;
        --transition: 0.25s ease;
      }
      * { box-sizing: border-box; margin: 0; padding: 0; }
      body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg);
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
      }
      .container {
        background: var(--card);
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        width: 100%;
        max-width: 360px;
        animation: fadeIn 0.5s var(--transition);
        text-align: center;
      }
      .container h2 {
        font-weight: 600;
        margin-bottom: 1.5rem;
      }
      .form-group {
        position: relative;
        margin-bottom: 1.25rem;
      }
      .form-group label {
        display: block;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        color: var(--text-secondary);
      }
      .form-group input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: var(--radius);
        background: #2a2a2a;
        color: var(--text);
        transition: background var(--transition), box-shadow var(--transition);
        padding-left: 2.5rem;
      }
      .form-group input:focus {
        outline: none;
        background: #333;
        box-shadow: 0 0 0 2px var(--accent);
      }
      .form-group .fa-user,
      .form-group .fa-lock {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: var(--text-secondary);
      }
      .toggle-password {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--text-secondary);
      }
      .btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background var(--transition), transform var(--transition);
      }
      .btn:hover {
        background: #a66ae0;
        transform: translateY(-2px);
      }
      .register-link {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.875rem;
* color: var(--text-secondary);
      }
      .register-link a {
        color: var(--accent);
        text-decoration: none;
        transition: color var(--transition);
      }
      .register-link a:hover {
        color: #d08efc;
      }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(1rem); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
</head>
<body>
  <div class="container">
    <h2>Registrasi</h2>
    <?php if ($error): ?>
      <p style="color: #ff6b6b; margin-bottom:1rem;"> <?= htmlspecialchars($error) ?> </p>
    <?php endif; ?>
    <?php if ($success): ?>
      <p style="color: #34c759; margin-bottom:1rem;"> <?= htmlspecialchars($success) ?> </p>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="form-group">
        <label for="username">Username</label>
        <i class="fas fa-user"></i>
        <input id="username" name="username" type="text" placeholder="username Anda" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <i class="fas fa-lock"></i>
        <input id="password" name="password" type="password" placeholder="password Anda" required>
        <i class="fas fa-eye toggle-password" data-target="password"></i>
      </div>
      <div class="form-group">
        <label for="confirm_password">Konfirmasi Password</label>
        <i class="fas fa-lock"></i>
        <input id="confirm_password" name="confirm_password" type="password" placeholder="konfirmasi password" required>
        <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
      </div>
      <button type="submit" class="btn">Daftar</button>
    </form>
    <p class="register-link">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>
  </div>
  <script>
    document.querySelectorAll('.toggle-password').forEach(icon => {
      icon.addEventListener('click', () => {
        const fieldId = icon.getAttribute('data-target');
        const input = document.getElementById(fieldId);
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
      });
    });
  </script>
</body>
</html>

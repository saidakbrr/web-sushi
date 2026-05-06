<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $apiUrl = "http://localhost:3000/api/users/login";

    $data = ['username' => $username, 'password' => $password];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $_SESSION['username'] = $username;
        header("Location: manajemen");
        exit;
    } else {
        $error = "Login gagal. Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('assets/thumb-1920-1341443.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .login-card {
      background-color: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
      width: 100%;
      max-width: 400px;
      animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .btn-primary {
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      transform: scale(1.03);
    }

    .form-label {
      font-weight: 500;
    }

    a.text-primary:hover {
      text-decoration: underline;
    }

    .spinner-border {
      width: 1rem;
      height: 1rem;
    }
  </style>
</head>
<body>

<div class="login-card text-dark">
  <h4 class="text-center mb-4">Login</h4>
  <?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'register'): ?>
  <div class="alert alert-success">Akun berhasil dibuat. Silakan login.</div>
<?php endif; ?>
  <form method="POST" onsubmit="showLoading(this)">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button id="submitBtn" class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-2" type="submit">
      <span>Login</span>
      <div id="spinner" class="spinner-border text-light d-none" role="status"></div>
    </button>
    <div class="text-center mt-3">
      <a href="register.php" class="text-primary text-decoration-none">Belum punya akun? Daftar di sini</a>
    </div>
  </form>
</div>

<script>
  function showLoading(form) {
    const btn = form.querySelector('#submitBtn');
    const spinner = form.querySelector('#spinner');
    btn.disabled = true;
    spinner.classList.remove('d-none');
  }
</script>

</body>
</html>

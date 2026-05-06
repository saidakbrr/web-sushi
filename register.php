<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        $apiUrl = "http://localhost:3000/api/users/register";

        $data = [
            'username' => $username,
            'password' => $password
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201) {
            header("Location: login.php?success=register");
            exit;
        } else {
            $resp = json_decode($response, true);
            $error = $resp['message'] ?? "Registrasi gagal. Username mungkin sudah digunakan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('assets/thumb-1920-1341443.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
        }
        .register-card {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            animation: fadeInUp 0.8s ease-out;
        }
        .form-label {
            font-weight: 500;
        }
        .text-link {
            color: #007bff;
            text-decoration: none;
        }
        .text-link:hover {
            text-decoration: underline;
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
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body>

<div class="register-card text-dark">
    <h4 class="text-center mb-4">Daftar Akun</h4>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" novalidate onsubmit="showLoading(this)">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm" class="form-control" required>
        </div>
        <button id="submitBtn" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
            <span>Daftar</span>
            <div id="spinner" class="spinner-border text-light d-none" role="status"></div>
        </button>
        <div class="text-center mt-2">
            <a href="login.php" class="text-link">Sudah punya akun? Login di sini</a>
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

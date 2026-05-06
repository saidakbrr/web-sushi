<?php
require 'api_call.php';
$base_pelanggan = "http://localhost:3000/api/pelanggan";

// === HANDLE AKSI PELANGGAN ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "nama" => $_POST['nama'],
        "no_meja" => $_POST['no_meja'],
        "kontak" => $_POST['kontak']
    ];
    if (!empty($_POST['id'])) {
        callAPI("PUT", "$base_pelanggan/{$_POST['id']}", $data);
    } else {
        callAPI("POST", $base_pelanggan, $data);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    callAPI("DELETE", "$base_pelanggan/{$_GET['delete']}");
    header("Location: index.php");
    exit;
}

$pelanggan = callAPI("GET", $base_pelanggan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../assets/314191-sushi-and-slurm-1920-x-1080-wallpaper.png'); /* <- pastikan nama file gambar sesuai */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            backdrop-filter: blur(4px);
            min-height: 100vh;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 1rem;
        }
        .form-control, .btn {
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-house-fill"></i> Tom Sushi
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../manajemen"><i class="bi bi-speedometer2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="index.php"><i class="bi bi-people-fill"></i> Pelanggan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pesanan/index.php"><i class="bi bi-journal-text"></i> Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../menu/index.php"><i class="bi bi-book-half"></i> Menu</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center text-white mb-4">
        <h1 class="fw-bold display-5 animate__animated animate__fadeInDown"><i class="bi bi-people-fill"></i> Manajemen Pelanggan</h1>
    </div>

    <!-- FORM -->
    <form method="POST" id="formPelanggan" class="card p-4 mb-4 shadow-lg animate__animated animate__fadeInUp">
        <input type="hidden" name="id" id="id_pelanggan">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="no_meja" id="no_meja" class="form-control" placeholder="Meja" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="kontak" id="kontak" class="form-control" placeholder="No HP / WA">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-success" id="btnPelanggan">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
        </div>
    </form>

    <!-- TABEL -->
    <div class="card shadow-lg animate__animated animate__fadeInUp">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Meja</th>
                            <th>Kontak</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pelanggan as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nama']) ?></td>
                            <td><?= $p['no_meja'] ?></td>
                            <td><?= htmlspecialchars($p['kontak']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editPelanggan(<?= json_encode($p) ?>)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelanggan ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap + Animate.css -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>

<script>
function editPelanggan(data) {
    document.getElementById("id_pelanggan").value = data.id;
    document.getElementById("nama").value = data.nama;
    document.getElementById("no_meja").value = data.no_meja;
    document.getElementById("kontak").value = data.kontak;
    document.getElementById("btnPelanggan").innerHTML = '<i class="bi bi-save"></i> Update';
}
</script>
</body>
</html>

<?php
require 'api_call.php';
$base_pelanggan = "http://localhost:3000/api/pelanggan";
$base_pesanan = "http://localhost:3000/api/pesanan";

// Handle Submit Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "id_pelanggan" => $_POST['id_pelanggan'],
        "tanggal"      => $_POST['tanggal'],
        "total"        => $_POST['total'],
        "status"       => $_POST['status']
    ];

    if (!empty($_POST['id'])) {
        callAPI("PUT", "$base_pesanan/{$_POST['id']}", $data);
    } else {
        callAPI("POST", $base_pesanan, $data);
    }

    header("Location: index.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    callAPI("DELETE", "$base_pesanan/{$_GET['delete']}");
    header("Location: index.php");
    exit;
}

// Ambil data dari API
$pelanggan = callAPI("GET", $base_pelanggan);
$pesanan   = callAPI("GET", $base_pesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../assets/314191-sushi-and-slurm-1920-x-1080-wallpaper.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            backdrop-filter: blur(2px);
        }
        .glass {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            padding: 2rem;
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .loading-spinner {
            display: none;
        }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-house-fill"></i> Tom Sushi</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../manajemen"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../pelanggan/index.php"><i class="bi bi-people-fill"></i> Pelanggan</a></li>
                <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-receipt-cutoff"></i> Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="../menu/index.php"><i class="bi bi-book-half"></i> Menu</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container py-5 fade-in">
    <div class="glass shadow-lg">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="bi bi-receipt-cutoff"></i> Manajemen Pesanan</h2>
        </div>

        <!-- FORM -->
        <form method="POST" class="card p-4 mb-4 shadow-sm" onsubmit="showSpinner()">
            <input type="hidden" name="id" id="id_pesanan">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="id_pelanggan_select" class="form-label">Pelanggan</label>
                    <select name="id_pelanggan" id="id_pelanggan_select" class="form-select" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelanggan as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['nama'] ?> (Meja <?= $p['no_meja'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="datetime-local" name="tanggal" id="tanggal" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="total" class="form-label">Total</label>
                    <input type="number" name="total" id="total" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="menunggu">Menunggu</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-success" id="btnPesanan">
                        <span class="spinner-border spinner-border-sm loading-spinner" id="spinner" role="status" aria-hidden="true"></span>
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>
            </div>
        </form>

        <!-- TABEL -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pesanan as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= $p['nama'] ?> (Meja <?= $p['no_meja'] ?>)</td>
                            <td><?= date("Y-m-d H:i", strtotime($p['tanggal'])) ?></td>
                            <td><?= number_format($p['total'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= match($p['status']) {
                                    'menunggu' => 'secondary',
                                    'diproses' => 'warning',
                                    'selesai'  => 'success',
                                    'batal'    => 'danger',
                                    default    => 'light'
                                } ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='editPesanan(<?= json_encode($p) ?>)'><i class="bi bi-pencil-square"></i></button>
                                <a href="?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pesanan ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT -->
<script>
function editPesanan(data) {
    document.getElementById("id_pesanan").value = data.id;
    document.getElementById("id_pelanggan_select").value = data.id_pelanggan;
    document.getElementById("tanggal").value = data.tanggal.replace(" ", "T").slice(0, 16);
    document.getElementById("total").value = data.total;
    document.getElementById("status").value = data.status;
    document.getElementById("btnPesanan").innerHTML = '<i class="bi bi-save"></i> Update';
}

function showSpinner() {
    document.getElementById("spinner").style.display = "inline-block";
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

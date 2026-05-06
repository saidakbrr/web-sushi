<?php
include_once "api_call.php";

$menu_list = callAPI("GET", "http://localhost:3000/api/menu");
$pesanan_list = callAPI("GET", "http://localhost:3000/api/pesanan");
$detail_pesanan = callAPI("GET", "http://localhost:3000/api/detailpesanan");

if (!is_array($menu_list)) $menu_list = [];
if (!is_array($pesanan_list)) $pesanan_list = [];
if (!is_array($detail_pesanan)) $detail_pesanan = [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan - Tom Sushi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/314191-sushi-and-slurm-1920-x-1080-wallpaper.png') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        h2 {
            font-weight: bold;
            animation: fadeInDown 1s ease-in-out;
        }

        .table {
            animation: fadeIn 1.5s ease-in-out;
        }

        .btn {
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        #loader {
            position: fixed;
            z-index: 9999;
            height: 100%;
            width: 100%;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
<!-- Loader -->
<div id="loader">
    <div class="spinner-border text-danger" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container mt-5">
    <h2 class="mb-4 text-center">🍣 Daftar Detail Pesanan</h2>
    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Detail Pesanan</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>No Meja</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_pesanan as $dp): ?>
                <tr>
                    <td><?= $dp['id'] ?></td>
                    <td><?= $dp['nama_pelanggan'] ?></td>
                    <td><?= $dp['no_meja'] ?></td>
                    <td><?= $dp['nama_menu'] ?></td>
                    <td><?= $dp['jumlah'] ?></td>
                    <td>Rp <?= number_format($dp['subtotal'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $dp['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $dp['id'] ?>" onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <a href="../manajemen" class="btn btn-secondary mt-3">← Kembali ke Dashboard</a>
</div>

<!-- Bootstrap & Script untuk loader -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("loader");
        loader.style.display = "none";
    });
</script>
</body>
</html>

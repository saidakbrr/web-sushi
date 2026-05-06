<?php
include_once "api_call.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "id_pesanan" => $_POST['id_pesanan'],
        "id_menu" => $_POST['id_menu'],
        "jumlah" => $_POST['jumlah'],
        "subtotal" => $_POST['jumlah'] * $_POST['harga']
    ];
    callAPI("POST", "http://localhost:3000/api/detailpesanan", $data);
    header("Location: index.php");
    exit;
}

$pesanan = callAPI("GET", "http://localhost:3000/api/pesanan");
$menu = callAPI("GET", "http://localhost:3000/api/menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
    <style>
        body {
            background-image: url('../assets/314191-sushi-and-slurm-1920-x-1080-wallpaper.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-wrapper {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: auto;
            margin-top: 80px;
            animation: fadeInDown 1s;
        }

        h3 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
    <script>
        function updateHarga() {
            const select = document.getElementById("id_menu");
            const harga = select.options[select.selectedIndex].getAttribute("data-harga");
            document.getElementById("harga").value = harga;
        }
    </script>
</head>
<body>
<div class="container">
    <div class="form-wrapper animate__animated animate__fadeInDown">
        <h3>Tambah Detail Pesanan</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">ID Pesanan</label>
                <select name="id_pesanan" class="form-select" required>
                    <?php foreach ($pesanan as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= $p['nama'] ?? 'Pelanggan' ?> - Meja <?= $p['no_meja'] ?? '' ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Menu</label>
                <select name="id_menu" id="id_menu" class="form-select" onchange="updateHarga()" required>
                    <?php foreach ($menu as $m): ?>
                        <option value="<?= $m['id'] ?>" data-harga="<?= $m['harga'] ?>">
                            <?= $m['nama'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" required>
            </div>
            <input type="hidden" name="harga" id="harga" value="<?= $menu[0]['harga'] ?>">
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success px-4 animate__animated animate__pulse animate__delay-1s">Simpan</button>
                <a href="index.php" class="btn btn-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

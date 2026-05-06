<?php
include_once "api_call.php";

// Ambil ID dari parameter
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data detail_pesanan yang dipilih
$detail = callAPI("GET", "http://localhost:3000/api/detailpesanan");

foreach ($detail as $d) {
    if ($d['id'] == $id) {
        $detail_data = $d;
        break;
    }
}

if (!$detail_data) {
    echo "Data detail tidak ditemukan.";
    exit;
}

// Ambil semua menu
$menu = callAPI("GET", "http://localhost:3000/api/menu");

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = $_POST['jumlah'];
    $id_menu = $_POST['id_menu'];
    $harga = $_POST['harga'];
    $subtotal = $jumlah * $harga;

    $data = [
        "id_pesanan" => $detail_data['id_pesanan'], // tidak bisa diubah
        "id_menu" => $id_menu,
        "jumlah" => $jumlah,
        "subtotal" => $subtotal
    ];

    callAPI("PUT", "http://localhost:3000/api/detailpesanan/$id", $data);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function updateHarga() {
        const select = document.getElementById("id_menu");
        const harga = select.options[select.selectedIndex].getAttribute("data-harga");
        document.getElementById("harga").value = harga;
    }
</script>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Edit Detail Pesanan</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Menu</label>
            <select name="id_menu" id="id_menu" class="form-control" onchange="updateHarga()" required>
                <?php foreach ($menu as $m): ?>
                    <option value="<?= $m['id'] ?>" data-harga="<?= $m['harga'] ?>" <?= ($m['id'] == $detail_data['id_menu']) ? 'selected' : '' ?>>
                        <?= $m['nama'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="<?= $detail_data['jumlah'] ?>" required>
        </div>
        <input type="hidden" name="harga" id="harga" value="<?= $detail_data['subtotal'] / $detail_data['jumlah'] ?>">
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>

<?php
require 'api_call.php';

$id = $_GET['id'] ?? null;
$error = '';
$menu = null;

// Ambil data menu berdasarkan ID
if ($id) {
    $response = @file_get_contents("http://localhost:3000/api/menu/$id");
    if ($response !== false) {
        $menu = json_decode($response, true);
    } else {
        $error = "❌ Data menu dengan ID $id tidak ditemukan.";
    }
} else {
    $error = "❌ ID menu tidak diberikan.";
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama' => $_POST['nama'],
        'deskripsi' => $_POST['deskripsi'],
        'harga' => $_POST['harga'],
        'kategori' => $_POST['kategori'],
        'foto' => $_POST['foto']
    ];

    callAPI("PUT", "http://localhost:3000/api/menu/$id", $data);
  header("Location: index.php?success=edit");
exit;

}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3 class="text-center mb-4">Edit Menu</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <a href="index.php" class="btn btn-secondary">← Kembali</a>
  <?php elseif ($menu): ?>
    <form method="POST" class="card p-4 shadow-sm mx-auto" style="max-width: 600px;">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Menu</label>
        <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($menu['nama']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($menu['deskripsi']) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" name="harga" id="harga" value="<?= htmlspecialchars($menu['harga']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select name="kategori" id="kategori" class="form-select" required>
          <option value="makanan" <?= $menu['kategori'] == 'makanan' ? 'selected' : '' ?>>Makanan</option>
          <option value="minuman" <?= $menu['kategori'] == 'minuman' ? 'selected' : '' ?>>Minuman</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="foto" class="form-label">Foto (Nama file)</label>
        <input type="text" name="foto" id="foto" value="<?= htmlspecialchars($menu['foto']) ?>" class="form-control">
      </div>

      <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary">← Batal</a>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  <?php endif; ?>
</div>
</body>
</html>

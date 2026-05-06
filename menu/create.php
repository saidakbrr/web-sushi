<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil path file sementara
    $fotoTmp = $_FILES['foto']['tmp_name'];

    if (!file_exists($fotoTmp)) {
        $error = "❌ File tidak ditemukan.";
    } else {
        // Siapkan data untuk dikirim via multipart/form-data ke Node.js API
        $dataCurl = [
            'nama' => $_POST['nama'],
            'deskripsi' => $_POST['deskripsi'],
            'harga' => $_POST['harga'],
            'kategori' => $_POST['kategori'],
            'foto' => new CURLFile($fotoTmp, $_FILES['foto']['type'], $_FILES['foto']['name'])
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://localhost:3000/api/menu",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $dataCurl
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode === 200) {
            header('Location: index.php?success=tambah');
            exit;
        } else {
            $error = "❌ Gagal mengirim data ke API.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('../assets/314191-sushi-and-slurm-1920-x-1080-wallpaper.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-control:focus, .form-select:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
      transition: 0.3s ease-in-out;
    }

    .btn-success:hover,
    .btn-secondary:hover {
      transform: scale(1.03);
      transition: 0.2s ease-in-out;
    }

    h2 {
      text-shadow: 1px 1px 2px #00000050;
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="text-center mb-4">Tambah Menu</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-container p-4 mx-auto" style="max-width: 600px;">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Menu</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
      </div>

      <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label for="harga" class="form-label">Harga (Rp)</label>
        <input type="number" class="form-control" id="harga" name="harga" required>
      </div>

      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" id="kategori" name="kategori" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="makanan">Makanan</option>
          <option value="minuman">Minuman</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="foto" class="form-label">Foto Menu</label>
        <input type="file" class="form-control" name="foto" id="foto" accept="image/*" required>
      </div>

      <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary">← Kembali</a>
        <button type="submit" class="btn btn-success">Simpan Menu</button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

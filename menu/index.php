<?php
require 'api_call.php';
$menus = json_decode(file_get_contents("http://localhost:3000/api/menu"), true);
?> 
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Menu</title>
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
      background: rgba(255,255,255,0.95);
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .fade-in {
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .btn-sm {
      padding: 0.25rem 0.5rem;
    }
  </style>
</head>
<body>
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
        <li class="nav-item"><a class="nav-link" href="../pesanan/index.php"><i class="bi bi-receipt-cutoff"></i> Pesanan</a></li>
        <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-book-half"></i> Menu</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
  <!-- ALERT -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      ✅
      <?php if ($_GET['success'] === 'hapus') echo 'Menu berhasil dihapus!';
            elseif ($_GET['success'] === 'tambah') echo 'Menu berhasil ditambahkan!';
            elseif ($_GET['success'] === 'edit') echo 'Menu berhasil diperbarui!'; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      ❌ Terjadi kesalahan: <?= htmlspecialchars($_GET['error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="glass">
    <h2 class="text-center mb-4">Data Menu</h2>
    <div class="text-center mb-3">
      <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Menu</a>
    </div>

    <div class="row">
      <?php foreach ($menus as $m): ?>
        <div class="col-md-4 mb-4 fade-in">
          <div class="card shadow-sm h-100">
            <?php if (!empty($m['foto'])): ?>
              <img src="../uploads/<?= $m['foto'] ?>" class="card-img-top" alt="<?= $m['nama'] ?>" onerror="this.src='https://via.placeholder.com/200x150?text=No+Image'">
            <?php else: ?>
              <img src="https://via.placeholder.com/200x150?text=No+Image" class="card-img-top" alt="No Image">
            <?php endif; ?>
            
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($m['nama']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($m['deskripsi']) ?></p>
              <p class="card-text fw-bold text-success">Rp <?= number_format($m['harga'], 0, ',', '.') ?></p>
              <span class="badge bg-secondary"><?= htmlspecialchars($m['kategori']) ?></span>
            </div>
            <div class="card-footer bg-white border-0 d-flex justify-content-between">
              <a href="edit.php?id=<?= $m['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $m['id'] ?>">Hapus</button>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin menghapus menu ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="#" id="btn-confirm-delete" class="btn btn-danger">Ya, Hapus</a>
      </div>
    </div>
  </div>
</div>

<script>
  const deleteModal = document.getElementById('confirmDeleteModal');
  const deleteBtn = document.getElementById('btn-confirm-delete');

  deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const menuId = button.getAttribute('data-id');
    deleteBtn.href = `delete.php?id=${menuId}`;
  });

  setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) alert.classList.remove('show');
  }, 3000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

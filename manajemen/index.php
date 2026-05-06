<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Tom Sushi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-image: url('../assets/314191-sushi-and-slurm-1920-x-1080-wallpaper.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
    }

    #loader {
      position: fixed;
      z-index: 9999;
      background-color: rgba(255, 255, 255, 0.9);
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .spinner-border {
      width: 3rem;
      height: 3rem;
    }

    #main-content {
      display: none;
      animation: fadeIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card {
      background-color: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(4px);
      border: none;
      border-radius: 20px;
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
    }

    .card-icon {
      font-size: 3rem;
      color: #dc3545;
    }

    footer {
      margin-top: 50px;
      text-align: center;
      color: white;
      padding: 10px 0;
    }

    .navbar {
      background-color: rgba(0, 0, 0, 0.85) !important;
    }

    h1 {
      color: white;
      text-shadow: 1px 1px 3px black;
    }

    .loader-text {
      margin-top: 1rem;
      font-size: 1.3rem;
      font-weight: bold;
      color: #333;
    }
  </style>
</head>
<body>

<!-- Loading -->
<div id="loader">
    <div class="spinner-border text-danger" role="status"></div>
    <div class="loader-text">🍣 Memuat Dashboard Tom Sushi...</div>
</div>

<!-- Konten -->
<div id="main-content">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">🍣 Tom Sushi</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5">

    <div class="row g-4 justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card text-center p-4">
          <div class="card-icon mb-3"><i class="bi bi-people-fill"></i></div>
          <h4 class="card-title">Manajemen Pelanggan</h4>
          <a href="../pelanggan/index.php" class="btn btn-primary mt-3">Buka</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card text-center p-4">
          <div class="card-icon mb-3"><i class="bi bi-receipt-cutoff"></i></div>
          <h4 class="card-title">Manajemen Pesanan</h4>
          <a href="../pesanan/index.php" class="btn btn-danger mt-3">Buka</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card text-center p-4">
          <div class="card-icon mb-3"><i class="bi bi-box-seam"></i></div>
          <h4 class="card-title">Manajemen Menu</h4>
          <a href="../menu/index.php" class="btn btn-success mt-3">Buka</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card text-center p-4">
          <div class="card-icon mb-3"><i class="bi bi-list-check"></i></div>
          <h4 class="card-title">Detail Pesanan</h4>
          <a href="../detail_pesanan/index.php" class="btn btn-warning mt-3">Buka</a>
        </div>
      </div>
    </div>
  </div>

  <footer>
    &copy; <?= date("Y") ?> Tom Sushi - REST Client by gilang
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  window.addEventListener('load', function () {
    setTimeout(() => {
      document.getElementById('loader').style.display = 'none';
      document.getElementById('main-content').style.display = 'block';
    }, 700);
  });
</script>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<?php include 'users.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <!-- Header info login -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar User</h2>
        <div class="text-end">
            <span class="fw-bold"><?= htmlspecialchars($_SESSION['username']) ?></span>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <span class="badge bg-danger">Admin</span>
            <?php else: ?>
                <span class="badge bg-secondary">User</span>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-outline-danger btn-sm ms-2">Logout</a>
        </div>
    </div>

    <!-- TABEL USER -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="badge bg-danger">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">User</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?= $user['id'] ?>&username=<?= $user['username'] ?>&role=<?= $user['role'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php if ($user['username'] !== $_SESSION['username']): ?>
                                <a href="?delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            <?php else: ?>
                                <span class="text-muted">Tidak bisa hapus diri sendiri</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>

    <!-- FORM -->
    <?php if (isset($_GET['edit'])): ?>
        <h4>Edit User</h4>
        <form method="POST" class="row g-3">
            <input type="hidden" name="id" value="<?= $_GET['edit'] ?>">
            <div class="col-md-3">
                <input type="text" name="username" class="form-control" value="<?= $_GET['username'] ?>" required>
            </div>
            <div class="col-md-3">
                <input type="password" name="password" class="form-control" placeholder="Password baru" required>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" required>
                    <option value="user" <?= $_GET['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $_GET['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    <?php else: ?>
        <h4>Tambah User</h4>
        <form method="POST" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="col-md-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" name="add" class="btn btn-success">Tambah</button>
            </div>
        </form>
    <?php endif; ?>

</div>
</body>
</html>

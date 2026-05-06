<?php
require 'api_call.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $response = callAPI("DELETE", "http://localhost:3000/api/menu/$id");
    // Redirect ke index dengan notifikasi sukses
    header("Location: index.php?success=hapus");
    exit;
} else {
    // Redirect ke index dengan notifikasi gagal
    header("Location: index.php?error=id_tidak_ditemukan");
    exit;
}
?>

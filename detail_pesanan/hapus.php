<?php
include_once "api_call.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Panggil API untuk hapus detail pesanan
    $response = callAPI("DELETE", "http://localhost:3000/api/detailpesanan/$id");

    // Redirect ke halaman index
    header("Location: index.php");
    exit;
} else {
    echo "ID tidak ditemukan.";
}

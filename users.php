<?php
session_start();
$token = $_SESSION['token'] ?? '';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$apiUrl = "http://localhost:3000/api/users"; // Ganti sesuai alamat backend kamu

// Fungsi ambil semua user
function fetchUsers($url, $token) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}



// Tambah user
if (isset($_POST['add'])) {
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'role'     => $_POST['role'] ?? 'user'  // tambahkan jika form mendukung role
    ];
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ]);
    curl_exec($ch);
    curl_close($ch);
    header("Location: index.php");
    exit;
}


// Edit user
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'role'     => $_POST['role'] ?? 'user'
    ];
    $ch = curl_init("$apiUrl/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ]);
    curl_exec($ch);
    curl_close($ch);
    header("Location: index.php");
    exit;
}


// Hapus user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $ch = curl_init("$apiUrl/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $token"
    ]);
    curl_exec($ch);
    curl_close($ch);
    header("Location: index.php");
    exit;
}


// Ambil semua user untuk ditampilkan di index.php
$users = fetchUsers($apiUrl, $token);


<?php
// Entry point (front controller) + routing sederhana
session_start();

require '../config/database.php';
require '../app/models/buku.php';
require '../app/controllers/BukuController.php';

try {
    $db = (new Database())->connect();
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan MySQL menyala dan perpustakaan.sql sudah diimpor.');
}

$model      = new Buku($db);
$controller = new BukuController($model);

$page   = $_GET['page']   ?? 'buku';
$action = $_GET['action'] ?? 'index';

if ($page !== 'buku') {
    http_response_code(404);
    exit('Halaman tidak ditemukan.');
}

match ($action) {
    'index'  => $controller->index(),
    'create' => $controller->create(),
    'store'  => $controller->store(),
    'edit'   => $controller->edit(),
    'update' => $controller->update(),
    'delete' => $controller->delete(),
    default  => (function () {
        http_response_code(404);
        exit('Action tidak ditemukan.');
    })(),
};
?>
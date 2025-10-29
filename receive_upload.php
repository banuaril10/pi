<?php
// Folder penyimpanan
$uploadBaseDir = __DIR__ . '../backupdb/';
if (!is_dir($uploadBaseDir)) {
    mkdir($uploadBaseDir, 0777, true);
}

// Cek apakah ada file
if (!isset($_FILES['file'])) {
    die(json_encode(['status' => 'error', 'message' => 'Tidak ada file diterima']));
}

// Dapatkan tanggal folder dari form POST
$dateFolder = $_POST['folder'] ?? date('Ymd');
$uploadDir = $uploadBaseDir . $dateFolder . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = basename($_FILES['file']['name']);
$targetPath = $uploadDir . $filename;

if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    echo json_encode([
        'status' => 'success',
        'file' => $filename,
        'path' => $targetPath
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal upload file']);
}

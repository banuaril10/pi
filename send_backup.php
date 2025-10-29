<?php
date_default_timezone_set('Asia/Jakarta');

$baseDir = '../backupdb/';
$dateFolder = date('Ymd'); // otomatis tanggal hari ini
$folderPath = $baseDir . $dateFolder . '/';

// IP atau domain server tujuan
$targetUrl = 'http://0.3/receive_upload.php'; // ganti sesuai IP server 0.3

if (!is_dir($folderPath)) {
    die("Folder tidak ditemukan: $folderPath");
}

$files = glob($folderPath . '*.json');
if (empty($files)) {
    die("Tidak ada file JSON di folder $folderPath");
}

foreach ($files as $file) {
    echo "Kirim file: $file ... ";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $targetUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($file),
        'folder' => $dateFolder, // kirim info tanggal
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo "❌ Error: $err\n";
    } else {
        echo "✅ OK: $response\n";
    }
}

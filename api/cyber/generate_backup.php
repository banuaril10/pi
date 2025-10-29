<?php
// === KONFIGURASI DATABASE ===
include "../../config/koneksi.php";
date_default_timezone_set("Asia/Jakarta");

// === BUAT FOLDER BACKUP ===
$date = date("Ymd");
$backupDir = __DIR__ . "/../../../backupdb/{$date}/";

// Buat folder jika belum ada
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// === DAFTAR TABEL YANG AKAN DIEKSPOR ===
$tables = [
    "pos_dcashierbalance" => "pos_dcashierbalance_key",
    "pos_dsales" => "pos_dsales_key",
    "pos_dsalesline" => "pos_dsalesline_key",
    "pos_dshopsales" => "pos_dshopsales_key"
];

// === FUNGSI STREAMING EKSPOR BESAR (langsung JSON) ===
function exportTableToJsonStream(PDO $pdo, string $table, string $primaryKey, string $backupDir)
{
    $filename = "{$backupDir}{$table}.json";
    $tempfile = "{$filename}.tmp";

    echo "🔄 Mengekspor tabel {$table}...\n";

    // Buat file sementara untuk tulis JSON secara bertahap
    $fh = fopen($tempfile, 'w');
    if (!$fh) {
        echo "❌ Tidak bisa menulis ke {$tempfile}\n";
        return;
    }

    fwrite($fh, "[\n");

    $stmt = $pdo->prepare("SELECT * FROM {$table}");
    $stmt->execute();

    $count = 0;
    $first = true;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!$first)
            fwrite($fh, ",\n");
        fwrite($fh, json_encode($row, JSON_UNESCAPED_UNICODE));
        $first = false;
        $count++;
    }

    fwrite($fh, "\n]");
    fclose($fh);

    // Ganti file lama jika ada
    if (file_exists($filename)) {
        unlink($filename);
    }
    rename($tempfile, $filename);

    echo "✅ {$table}: {$count} baris diekspor ke {$filename}\n";
}

// === EKSEKUSI UNTUK SETIAP TABEL ===
foreach ($tables as $table => $pk) {
    exportTableToJsonStream($connec, $table, $pk, $backupDir);
}

echo "\n🎉 Backup selesai!\n";
echo "📁 Lokasi: {$backupDir}\n";
?>
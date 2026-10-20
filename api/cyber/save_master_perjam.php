<?php
include "../../config/koneksi.php";
header('Content-type: application/json');

$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ---- helper UUID v4 ----
function uuidv4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$input = file_get_contents('php://input');
$payload = json_decode($input, true);

$salesDate = trim((string)($payload['sales_date'] ?? ''));
$rows      = $payload['rows'] ?? [];

if ($salesDate === '') {
    echo json_encode(['status'=>'FAILED','message'=>'sales_date wajib dikirim']);
    exit;
}

if (!is_array($rows) || empty($rows)) {
    echo json_encode(['status'=>'FAILED','message'=>'Data kosong']);
    exit;
}

try {
    $connec->beginTransaction();

    $stmt = $connec->prepare("
        INSERT INTO in_target_pola_perjam (id, jam, sales_date, realisasi, is_sync, isactived, created_at, updated_at)
        VALUES (:id, :jam, :tgl, :real, 0, 1, NOW(), NOW())
        ON CONFLICT (jam, sales_date) DO UPDATE
        SET realisasi  = EXCLUDED.realisasi,
            is_sync    = 0,
            updated_at = NOW()
        RETURNING id
    ");

    $count = 0;
    foreach ($rows as $r) {
        $jam  = trim((string)($r['jam'] ?? ''));
        $real = (float)($r['realisasi'] ?? 0);
        if ($jam === '') continue;

        // id: pakai dari client kalau valid UUID, kalau tidak generate baru
        $id = trim((string)($r['id'] ?? ''));
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $id)) {
            $id = uuidv4();
        }

        $stmt->execute([
            ':id'    => $id,
            ':jam'   => $jam,
            ':tgl'   => $salesDate,
            ':real'  => $real,
        ]);
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception("Baris gagal disimpan untuk jam: " . $jam);
        }
        $count++;
    }

    if ($count < 1) {
        throw new Exception("Tidak ada baris yang tersimpan");
    }

    $connec->commit();

    echo json_encode([
        'status'  => 'OK',
        'message' => 'Realisasi berhasil disimpan',
        'total'   => $count,
    ]);
} catch (Throwable $e) {
    if ($connec->inTransaction()) $connec->rollBack();
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'Gagal simpan: ' . $e->getMessage(),
    ]);
}
?>
<?php
include "../../config/koneksi.php";
header('Content-type: application/json');

$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ============================================================
// 1. Ambil id_location dari ad_morg
// ============================================================
$idstore = null;
$q = $connec->query("SELECT ad_morg_key FROM ad_morg WHERE isactived = 'Y' LIMIT 1");
$idstore = $q->fetchColumn();

if (!$idstore) {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'id_location tidak ditemukan di ad_morg (isactived=Y)',
    ]);
    exit;
}

// ============================================================
// 2. Ambil data lokal yang belum di-sync
// ============================================================
try {
    $stmt = $connec->prepare("
        SELECT id, jam, sales_date, realisasi
        FROM in_target_pola_perjam
        WHERE is_sync = 0
          AND isactived = 1
        ORDER BY id
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'Gagal baca data lokal: ' . $e->getMessage(),
    ]);
    exit;
}

if (empty($rows)) {
    echo json_encode([
        'status'  => 'OK',
        'message' => 'Tidak ada data yang perlu di-sync',
        'total'   => 0,
    ]);
    exit;
}

// ============================================================
// 3. Susun payload (tambahkan id_location dari ad_morg)
// ============================================================
$payload_rows = [];
foreach ($rows as $r) {
    $payload_rows[] = [
        'id'          => $r['id'],
        'jam'         => $r['jam'],
        'sales_date'  => $r['sales_date'],
        'id_location' => $idstore,
        'realisasi'   => (float)$r['realisasi'],
        'is_sync'     => 1,
    ];
}

$body = json_encode(['data' => $payload_rows]);

$url = $base_url . '/store/target_perjam/save_target_perjam.php';
$api_key = 'ganti-dengan-kunci-rahasia-anda';
// ============================================================
// 4. Kirim ke server
// ============================================================
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_POSTFIELDS     => $body,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'X-API-KEY: ' . $api_key,
    ],
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($curl);
curl_close($curl);

if ($response === false || $curlErr !== '') {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'Gagal konek ke server: ' . $curlErr,
    ]);
    exit;
}

$jresp = json_decode($response, true);

if ($httpCode !== 200 || !is_array($jresp) || ($jresp['status'] ?? '') !== 'success') {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'Server menolak: ' . ($jresp['message'] ?? ('HTTP ' . $httpCode)),
        'server'  => $jresp,
    ]);
    exit;
}

// ============================================================
// 5. Tandai lokal sudah sync
// ============================================================
// ============================================================
// 5. Tandai lokal sudah sync
// ============================================================
try {
    $ids = array_column($rows, 'id');

    if (empty($ids)) {
        throw new Exception('Tidak ada id untuk di-update');
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmtUpd = $connec->prepare("
        UPDATE in_target_pola_perjam
        SET is_sync = 1, sync_at = NOW()
        WHERE id IN ($placeholders)
    ");
    $stmtUpd->execute($ids);

    echo json_encode([
        'status'      => 'OK',
        'message'     => count($ids) . ' baris berhasil di-sync ke server',
        'total'       => count($ids),
        'id_location' => $idstore,
        'inserted'    => $jresp['inserted'] ?? 0,
        'updated'     => $jresp['updated']  ?? 0,
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => 'Data sudah terkirim ke server, tapi gagal update flag lokal: ' . $e->getMessage(),
    ]);
}
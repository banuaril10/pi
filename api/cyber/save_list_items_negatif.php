<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../../config/koneksi.php";

// Terima array items via $_POST['items'] (JSON string) atau $_POST['items'][i][...]
$items = null;


function gen_uuid_ran() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

if (isset($_POST['items'])) {
    // format 1: items dikirim sebagai JSON string
    if (is_string($_POST['items'])) {
        $items = json_decode($_POST['items'], true);
    }
    // format 2: items dikirim sebagai array POST
    else if (is_array($_POST['items'])) {
        $items = $_POST['items'];
    }
}

// fallback: baca body JSON (kalau dikirim raw application/json)
if ($items === null) {
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true);
    if (is_array($body) && isset($body['items'])) {
        $items = $body['items'];
    }
}

if (!is_array($items) || count($items) === 0) {
    echo json_encode(['status' => 'FAILED', 'message' => 'items kosong']);
    exit;
}

try {
    $connec->beginTransaction();

    $stmt = $connec->prepare(
        "INSERT INTO list_items_negatif
            (id, sku, item_name, stock, created_at, updated_at, status_intransit)
         VALUES
            (:id, :sku, :item_name, :stock, NOW(), NOW(), :status_intransit)"
    );

    // Prefix batch supaya mudah dilacak per pencetakan
    $batchId = 'LNG' . date('YmdHis');
    $inserted = 0;

    foreach ($items as $idx => $item) {
        $sku       = isset($item['itemsid'])  ? trim((string)$item['itemsid'])  : '';
        $item_name = isset($item['itemname']) ? trim((string)$item['itemname']) : '';
        $stock     = isset($item['stock'])    ? (int)$item['stock']             : 0;
        $status    = isset($item['status_intransit']) && $item['status_intransit'] !== ''
                        ? (string)$item['status_intransit']
                        : null;

        if ($sku === '' || $item_name === '') {
            continue;
        }

        $stmt->execute([
            ':id'               => gen_uuid_ran(),
            ':sku'              => $sku,
            ':item_name'        => $item_name,
            ':stock'            => $stock,
            ':status_intransit' => $status,
        ]);
        $inserted++;
    }

    $connec->commit();

    echo json_encode([
        'status'   => 'OK',
        'batch_id' => $batchId,
        'inserted' => $inserted,
    ]);

} catch (Exception $e) {
    if ($connec->inTransaction()) {
        $connec->rollBack();
    }
    echo json_encode(['status' => 'FAILED', 'message' => $e->getMessage()]);
}
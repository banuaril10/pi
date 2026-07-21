<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json');

include "../../config/koneksi.php";

try {

    $pos_medc_key = $_POST['pos_medc_key'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (
        empty($pos_medc_key) ||
        $amount === ''
    ) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap'
        ]);
        exit;
    }

    // ambil shop sales terakhir
    $q = $connec->query("
        SELECT pos_dshopsales_key
        FROM pos_dshopsales
        ORDER BY insertdate DESC
        LIMIT 1
    ");

    $row = $q->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode([
            'success' => false,
            'message' => 'Data pos_dshopsales tidak ditemukan'
        ]);
        exit;
    }

    $pos_dshopsales_key = $row['pos_dshopsales_key'];

    $pos_settlement_key = uniqid() . '_' . date('YmdHis');

    $sql = "
        INSERT INTO pos_settlement
        (
            pos_settlement_key,
            pos_dshopsales_key,
            pos_medc_key,
            amount,
            tanggal
        )
        VALUES
        (
            :pos_settlement_key,
            :pos_dshopsales_key,
            :pos_medc_key,
            :amount,
            :tanggal
        )
    ";

    $stmt = $connec->prepare($sql);

    $stmt->execute([
        ':pos_settlement_key' => $pos_settlement_key,
        ':pos_dshopsales_key' => $pos_dshopsales_key,
        ':pos_medc_key' => $pos_medc_key,
        ':amount' => $amount,
        ':tanggal' => date('Y-m-d H:i:s')
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Settlement berhasil disimpan',
        'pos_dshopsales_key' => $pos_dshopsales_key
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

}

exit;
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

    // =========================================================
    // AMBIL SHOP SALES TERAKHIR
    // salesdate menjadi acuan tanggal settlement
    // =========================================================
    $q = $connec->query("
        SELECT 
            pos_dshopsales_key,
            salesdate
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
    $salesdate = $row['salesdate'];

    // =========================================================
    // CEK APAKAH SUDAH ADA SETTLEMENT
    // berdasarkan:
    // pos_medc_key
    // +
    // tanggal sales (bukan tanggal server)
    // =========================================================
    $checkSql = "
        SELECT ps.pos_settlement_key
        FROM pos_settlement ps
        INNER JOIN pos_dshopsales ds
            ON ds.pos_dshopsales_key = ps.pos_dshopsales_key
        WHERE ps.pos_medc_key = :pos_medc_key
          AND DATE(ds.salesdate) = DATE(:salesdate)
        LIMIT 1
    ";

    $checkStmt = $connec->prepare($checkSql);

    $checkStmt->execute([
        ':pos_medc_key' => $pos_medc_key,
        ':salesdate' => $salesdate
    ]);

    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

    // =========================================================
    // SUDAH ADA → SKIP SAJA
    // TIDAK DIANGGAP ERROR
    // =========================================================
    if ($existing) {
        echo json_encode([
            'success' => true,
            'skipped' => true,
            'message' => 'Settlement sudah ada untuk sales date tersebut',
            'pos_dshopsales_key' => $pos_dshopsales_key
        ]);
        exit;
    }

    // =========================================================
    // GENERATE SETTLEMENT KEY
    // =========================================================
    $pos_settlement_key = uniqid() . '_' . date('YmdHis');

    // =========================================================
    // INSERT
    // =========================================================
    $sql = "
        INSERT INTO pos_settlement
        (
            pos_settlement_key,
            pos_dshopsales_key,
            pos_medc_key,
            amount,
            tanggal,
            salesdate
        )
        VALUES
        (
            :pos_settlement_key,
            :pos_dshopsales_key,
            :pos_medc_key,
            :amount,
            :tanggal,
            :salesdate
        )
    ";

    $stmt = $connec->prepare($sql);
    $salesdateonly = date('Y-m-d', strtotime($salesdate));
    $stmt->execute([
        ':pos_settlement_key' => $pos_settlement_key,
        ':pos_dshopsales_key' => $pos_dshopsales_key,
        ':pos_medc_key' => $pos_medc_key,
        ':amount' => $amount,
        ':tanggal' => date('Y-m-d H:i:s'),
        ':salesdate' => $salesdateonly
    ]);

    echo json_encode([
        'success' => true,
        'skipped' => false,
        'message' => 'Settlement berhasil disimpan',
        'pos_dshopsales_key' => $pos_dshopsales_key,
        'salesdate' => $salesdateonly
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

}

exit;
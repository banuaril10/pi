<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

while (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json');

include "../../config/koneksi.php";

try {

    $connec->beginTransaction();

    $connec->exec("DROP TABLE IF EXISTS tmp_keep");

    $connec->exec("
        CREATE TEMPORARY TABLE tmp_keep (
            pos_settlement_key BIGINT PRIMARY KEY
        )
    ");

    // =====================================================
    // 1. SIMPAN DATA VALID
    // =====================================================
    $connec->exec("
        INSERT INTO tmp_keep (pos_settlement_key)
        SELECT MAX(ps.pos_settlement_key)
        FROM pos_settlement ps
        INNER JOIN pos_dshopsales ds
            ON ds.pos_dshopsales_key = ps.pos_dshopsales_key
        GROUP BY
            ps.pos_dshopsales_key,
            ps.pos_medc_key
    ");

    // =====================================================
    // 2. SIMPAN 1 ANOMALI TERBARU
    // HANYA UNTUK pos_medc_key YANG TIDAK PUNYA DATA VALID
    // =====================================================
    $connec->exec("
        INSERT INTO tmp_keep (pos_settlement_key)
        SELECT MAX(ps.pos_settlement_key)
        FROM pos_settlement ps
        LEFT JOIN pos_dshopsales ds
            ON ds.pos_dshopsales_key = ps.pos_dshopsales_key
        WHERE ds.pos_dshopsales_key IS NULL
          AND NOT EXISTS (
                SELECT 1
                FROM pos_settlement ps2
                INNER JOIN pos_dshopsales ds2
                    ON ds2.pos_dshopsales_key = ps2.pos_dshopsales_key
                WHERE ps2.pos_medc_key = ps.pos_medc_key
          )
        GROUP BY ps.pos_medc_key
    ");

    // =====================================================
    // HAPUS YANG TIDAK DIPAKAI
    // =====================================================
    $affected = $connec->exec("
        DELETE FROM pos_settlement
        WHERE pos_settlement_key NOT IN (
            SELECT pos_settlement_key
            FROM tmp_keep
        )
    ");

    $connec->exec("DROP TABLE IF EXISTS tmp_keep");

    $connec->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Cleanup settlement berhasil',
        'total_dihapus' => $affected
    ]);

} catch (Exception $e) {

    if ($connec->inTransaction()) {
        $connec->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
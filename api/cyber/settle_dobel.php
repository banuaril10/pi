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

    // =====================================================
    // TEMP TABLE DATA YANG DIPERTAHANKAN
    // =====================================================
    $connec->exec("DROP TABLE IF EXISTS tmp_keep");

    $connec->exec("
        CREATE TEMPORARY TABLE tmp_keep (
            pos_settlement_key BIGINT PRIMARY KEY
        )
    ");

    // =====================================================
    // 1. DATA VALID
    //
    // pos_dshopsales_key ADA di pos_dshopsales
    //
    // Kalau ada duplikat:
    // simpan yang pos_settlement_key paling besar
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
    // 2. DATA ANOMALI
    //
    // pos_dshopsales_key TIDAK ADA di pos_dshopsales
    //
    // HANYA disimpan kalau pos_medc_key tersebut
    // TIDAK PUNYA DATA VALID SAMA SEKALI
    //
    // Kalau semuanya anomali:
    // simpan 1 yang paling baru
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
    // HITUNG DULU JUMLAH YANG AKAN DIHAPUS
    // =====================================================
    $stmt = $connec->query("
        SELECT COUNT(*) AS total
        FROM pos_settlement ps
        WHERE NOT EXISTS (
            SELECT 1
            FROM tmp_keep tk
            WHERE tk.pos_settlement_key = ps.pos_settlement_key
        )
    ");

    $totalDihapus = (int) $stmt->fetchColumn();

    // =====================================================
    // DELETE
    // =====================================================
    $connec->exec("
        DELETE FROM pos_settlement
        WHERE NOT EXISTS (
            SELECT 1
            FROM tmp_keep tk
            WHERE tk.pos_settlement_key = pos_settlement.pos_settlement_key
        )
    ");

    // =====================================================
    // CLEANUP
    // =====================================================
    $connec->exec("DROP TABLE IF EXISTS tmp_keep");

    $connec->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Cleanup settlement berhasil',
        'total_dihapus' => $totalDihapus
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
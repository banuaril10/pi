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

    // =========================================================
    // SIMPAN 1 BARIS per kombinasi
    // pos_dshopsales_key + pos_medc_key
    // yang disimpan: tanggal PALING AKHIR
    // =========================================================
    $connec->exec("
        CREATE TEMPORARY TABLE tmp_keep AS
        SELECT pos_settlement_key
        FROM (
            SELECT
                pos_settlement_key,
                ROW_NUMBER() OVER (
                    PARTITION BY pos_dshopsales_key, pos_medc_key
                    ORDER BY tanggal DESC, pos_settlement_key DESC
                ) AS rn
            FROM pos_settlement
        ) x
        WHERE rn = 1
    ");

    // =========================================================
    // DELETE YANG TIDAK MASUK tmp_keep
    // =========================================================
    $affected = $connec->exec("
        DELETE FROM pos_settlement
        WHERE pos_settlement_key NOT IN (
            SELECT pos_settlement_key FROM tmp_keep
        )
    ");

    $connec->exec("DROP TABLE IF EXISTS tmp_keep");

    $connec->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Delete data double berhasil',
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
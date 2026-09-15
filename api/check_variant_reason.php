<?php
include "../config/koneksi.php";

header('Content-Type: application/json');


/*
|--------------------------------------------------------------------------
| CARI OTOMATIS:
| - HARI INI
| - KEMARIN
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
    a.pos_dshopsales_key,
    DATE(a.salesdate) AS salesdate,
    a.status,

    COALESCE(
        (
            SELECT
                COALESCE(SUM(COALESCE(s.total_settlement, 0) - COALESCE(t.total_transaksi, 0)),0)
            FROM (
                SELECT
                    m.pos_medc_key,

                    COALESCE(SUM(n.valueamount), 0)
                    -
                    COALESCE(SUM(n.pointamount), 0)
                    -
                    COALESCE(SUM(n.voucheramount), 0)
                    AS total_transaksi

                FROM pos_dshopsalesnoncash n

                INNER JOIN (
                    SELECT *
                    FROM pos_medc
                    WHERE jenis = 'Debit'
                ) m
                    ON n.pos_medc_key = m.pos_medc_key

                WHERE n.pos_dshopsales_key = a.pos_dshopsales_key

                GROUP BY m.pos_medc_key

            ) t

            FULL OUTER JOIN (

                SELECT
                    s.pos_medc_key,

                    COALESCE(SUM(s.amount), 0)
                    AS total_settlement

                FROM pos_settlement s

                WHERE s.pos_dshopsales_key = a.pos_dshopsales_key

                GROUP BY s.pos_medc_key

            ) s

                ON t.pos_medc_key = s.pos_medc_key
        ),
        0
    ) AS total_variant

FROM pos_dshopsales a

WHERE
    a.status = 'DONE'
    AND DATE(a.salesdate) IN (
        CURRENT_DATE,
        CURRENT_DATE - INTERVAL '1 day'
    )

ORDER BY
    a.salesdate DESC
";


$stmt = $connec->query($sql);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$result = [];


/*
|--------------------------------------------------------------------------
| CEK MASING-MASING TANGGAL
|--------------------------------------------------------------------------
*/

foreach ($rows as $row) {

    $totalVariant = (float)$row['total_variant'];
    $salesDate = $row['salesdate'];


    /*
    |--------------------------------------------------------------------------
    | VALIDASI KHUSUS KEMARIN
    |--------------------------------------------------------------------------
    |
    | Kalau transaksi adalah KEMARIN dan belum ada SATU PUN data
    | di pos_settlement, jangan dianggap sebagai variant.
    |
    | Ini untuk kondisi saat fitur pertama kali dijalankan,
    | dimana data settlement kemarin memang belum masuk sama sekali.
    |
    */

    if ($salesDate == date('Y-m-d', strtotime('-1 day'))) {

        $stmtSettlement = $connec->prepare("
            SELECT 1
            FROM pos_settlement
            WHERE pos_dshopsales_key = :pos_dshopsales_key
            LIMIT 1
        ");

        $stmtSettlement->execute([
            ':pos_dshopsales_key' =>
                $row['pos_dshopsales_key']
        ]);

        $hasSettlement = $stmtSettlement->fetchColumn();


        /*
        |--------------------------------------------------------------------------
        | KEMARIN + BELUM ADA DATA SETTLEMENT SAMA SEKALI → SKIP
        |--------------------------------------------------------------------------
        */

        if (!$hasSettlement) {
            continue;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HANYA PROSES KALAU ADA VARIANT
    |--------------------------------------------------------------------------
    */

    if ($totalVariant == 0) {
        continue;
    }


    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH REASON SUDAH ADA
    |--------------------------------------------------------------------------
    */

    $stmtReason = $connec->prepare("
        SELECT
            pos_dshopsales_variant_reason_key,
            reason_id,
            reason_name,
            total_variant,
            createby,
            createdate

        FROM pos_dshopsales_variant_reason

        WHERE pos_dshopsales_key = :pos_dshopsales_key

        LIMIT 1
    ");

    $stmtReason->execute([
        ':pos_dshopsales_key' =>
            $row['pos_dshopsales_key']
    ]);

    $reason = $stmtReason->fetch(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | KALAU SUDAH ADA REASON → SKIP
    |--------------------------------------------------------------------------
    */

    if ($reason) {
        continue;
    }


    /*
    |--------------------------------------------------------------------------
    | SUDAH DONE + ADA VARIANT + BELUM ADA REASON
    |--------------------------------------------------------------------------
    | MASUKKAN KE LIST LOCK
    |--------------------------------------------------------------------------
    */

    $result[] = [
        "pos_dshopsales_key" =>
            $row['pos_dshopsales_key'],

        "sales_date" =>
            $row['salesdate'],

        "total_variant" =>
            $totalVariant,

        "status" =>
            $row['status'],

        "locked" => true
    ];
}


/*
|--------------------------------------------------------------------------
| RESPONSE
|--------------------------------------------------------------------------
*/

echo json_encode([
    "success" => true,

    "locked" => count($result) > 0,

    "total" => count($result),

    "data" => $result
]);
<?php

include "../../config/koneksi.php";

$tanggal = $_GET['date'] ?? 'now';


/*
|--------------------------------------------------------------------------
| GET STORE
|--------------------------------------------------------------------------
*/

$idstore = '';

$query = $connec->query("
    SELECT ad_morg_key
    FROM ad_morg
    WHERE isactived = 'Y'
    LIMIT 1
");

if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}


/*
|--------------------------------------------------------------------------
| PUSH FUNCTION
|--------------------------------------------------------------------------
*/

function push_to_line($url, $line, $idstore)
{
    $postData = [
        "line" => $line,
        "idstore" => $idstore
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($postData)
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}


/*
|--------------------------------------------------------------------------
| GET DATA YANG BELUM SYNC
|--------------------------------------------------------------------------
*/

    $sql = "
        SELECT *
        FROM pos_dshopsales_variant_reason
        WHERE status_intransit IS NULL
        ORDER BY createdate
    ";

    $stmt = $connec->query($sql);



$jj_variant = [];


/*
|--------------------------------------------------------------------------
| BUILD DATA
|--------------------------------------------------------------------------
*/

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $jj_variant[] = [
        "pos_dshopsales_variant_reason_key" =>
            $row['pos_dshopsales_variant_reason_key'],

        "pos_dshopsales_key" =>
            $row['pos_dshopsales_key'],

        "sales_date" =>
            $row['sales_date'],

        "total_variant" =>
            $row['total_variant'],

        "reason_id" =>
            $row['reason_id'],

        "reason_name" =>
            $row['reason_name'],

        "createby" =>
            $row['createby'],

        "createdate" =>
            $row['createdate']
    ];
}


/*
|--------------------------------------------------------------------------
| PUSH KE SERVER PUSAT
|--------------------------------------------------------------------------
*/

if (!empty($jj_variant)) {

    $url =
        $base_url .
        "/store/lpk/insert_variant_reason.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";


    $array_variant = [
        "line" => $jj_variant
    ];

    $hasil_variant = push_to_line(
        $url,
        json_encode($array_variant),
        $idstore
    );


    $j_hasil_variant =
        json_decode($hasil_variant, true);


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS SETELAH BERHASIL SYNC
    |--------------------------------------------------------------------------
    */

    if (!empty($j_hasil_variant)) {

        foreach ($j_hasil_variant as $key) {

            $stmtUpdate = $connec->prepare("
                UPDATE pos_dshopsales_variant_reason
                SET status_intransit = '1'
                WHERE pos_dshopsales_variant_reason_key =
                    :key
            ");

            $stmtUpdate->execute([
                ':key' => $key
            ]);
        }
    }


    echo json_encode([
        "success" => true,
        "total" => count($jj_variant),
        "synced" => count($j_hasil_variant ?? []),
        "response" => $j_hasil_variant
    ]);

} else {

    echo json_encode([
        "success" => true,
        "total" => 0,
        "synced" => 0,
        "message" => "Tidak ada data variant reason yang belum sync."
    ]);
}
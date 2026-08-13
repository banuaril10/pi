<?php
include "../config/koneksi.php";

header('Content-Type: application/json');



function get_uuid_php() {
    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

try {

    $pos_dshopsales_key = trim($_POST['pos_dshopsales_key'] ?? '');
    $sales_date         = trim($_POST['sales_date'] ?? '');
    $total_variant      = $_POST['total_variant'] ?? 0;
    $reason_id          = (int)($_POST['reason_id'] ?? 0);
    $reason_name        = trim($_POST['reason_name'] ?? '');
    $createby           = trim($_POST['createby'] ?? '');


    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    if ($pos_dshopsales_key == '') {
        throw new Exception("POS DShop Sales tidak ditemukan.");
    }

    if ($sales_date == '') {
        throw new Exception("Sales Date tidak ditemukan.");
    }

    if ($reason_id <= 0) {
        throw new Exception("Silakan pilih alasan variant.");
    }

    if ($reason_name == '') {
        throw new Exception("Alasan variant tidak ditemukan.");
    }


    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH SUDAH PERNAH INPUT
    |--------------------------------------------------------------------------
    */

    $stmtCheck = $connec->prepare("
        SELECT
            pos_dshopsales_variant_reason_key
        FROM pos_dshopsales_variant_reason
        WHERE pos_dshopsales_key = :pos_dshopsales_key
        LIMIT 1
    ");

    $stmtCheck->execute([
        ':pos_dshopsales_key' => $pos_dshopsales_key
    ]);

    if ($stmtCheck->fetch()) {

        echo json_encode([
            "success" => false,
            "message" => "Alasan variant untuk sales date ini sudah diinput."
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */
    $pos_dshopsales_variant_reason_key = get_uuid_php();
    $sql = "
        INSERT INTO pos_dshopsales_variant_reason (
            pos_dshopsales_variant_reason_key,
            pos_dshopsales_key,
            sales_date,
            total_variant,
            reason_id,
            reason_name,
            createby,
            createdate
        )
        VALUES (
            :pos_dshopsales_variant_reason_key,
            :pos_dshopsales_key,
            :sales_date,
            :total_variant,
            :reason_id,
            :reason_name,
            :createby,
            NOW()
        )
    ";

    $stmt = $connec->prepare($sql);
   
    $stmt->execute([
        ':pos_dshopsales_variant_reason_key' => $pos_dshopsales_variant_reason_key,
        ':pos_dshopsales_key' => $pos_dshopsales_key,
        ':sales_date'         => $sales_date,
        ':total_variant'      => $total_variant,
        ':reason_id'          => $reason_id,
        ':reason_name'        => $reason_name,
        ':createby'           => $createby
    ]);


    echo json_encode([
        "success" => true,
        "message" => "Alasan variant berhasil disimpan."
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
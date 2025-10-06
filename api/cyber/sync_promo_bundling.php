<?php
include "../../config/koneksi.php";

//show error
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// Ambil org aktif
$ll = "SELECT * FROM ad_morg WHERE isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

function get_data($url)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// =====================
// SYNC PROMO BUNDLING
// =====================
$url = $base_url . '/store/promo/get_promo_bundling.php?idstore=' . $idstore;
$hasil = get_data($url);
$j_hasil = json_decode($hasil, true);

try {
    if ($j_hasil == null) {
        echo json_encode([
            "status" => "FAILED",
            "message" => "Data Not Found"
        ]);
        die();
    }

    // Truncate detail
    $truncate = "TRUNCATE table pos_mproductdiscount_bundling";
    $statement = $connec->prepare($truncate);
    $statement->execute();

    // Truncate header
    $truncateHeader = "TRUNCATE table pos_mproductdiscount_bundling_header";
    $statement = $connec->prepare($truncateHeader);
    $statement->execute();

    // Pisahkan array jika format JSON seperti {"bundling":[],"files":[]}
    $bundlingData = isset($j_hasil['bundling']) ? $j_hasil['bundling'] : $j_hasil;
    $filesData = isset($j_hasil['files']) ? $j_hasil['files'] : [];

    // Simpan header bundling
    foreach ($filesData as $file) {
        $insertHeader = "INSERT INTO pos_mproductdiscount_bundling_header 
            (ad_mclient_key, ad_morg_key, bundling_code, minbuy, isactived, insertdate, insertby)
            VALUES 
            (:ad_mclient_key, :ad_morg_key, :bundling_code, :minbuy, :isactived, :insertdate, :insertby)";
        $stmtHeader = $connec->prepare($insertHeader);
        $stmtHeader->execute([
            ':ad_mclient_key' => $ad_mclient_key ?? null,
            ':ad_morg_key' => $idstore,
            ':bundling_code' => $file['bundling_code'],
            ':minbuy' => $file['minbuy'],
            ':isactived' => 'Y',
            ':insertdate' => date("Y-m-d H:i:s"),
            ':insertby' => 'system'
        ]);
    }

    // Simpan detail bundling
    foreach ($bundlingData as $value) {
        $insert = "INSERT INTO pos_mproductdiscount_bundling 
            (ad_mclient_key, ad_morg_key, isactived, postdate, insertdate, insertby, 
             discountname, discounttype, sku, discount, fromdate, todate, typepromo, maxqty, jenis_promo) 
            VALUES 
            (:ad_mclient_key, :ad_morg_key, :isactived, :postdate, :insertdate, :insertby, 
             :discountname, :discounttype, :sku, :discount, :fromdate, :todate, :typepromo, :maxqty, :jenis_promo)";

        $stmt = $connec->prepare($insert);
        $stmt->execute([
            ':ad_mclient_key' => $ad_mclient_key ?? null,
            ':ad_morg_key' => $value['ad_morg_key'],
            ':isactived' => $value['isactived'],
            ':postdate' => date("Y-m-d H:i:s"),
            ':insertdate' => date("Y-m-d H:i:s"),
            ':insertby' => $value['insertby'],
            ':discountname' => $value['discountname'],
            ':discounttype' => $value['discounttype'],
            ':sku' => str_replace(' ', '', $value['sku']),
            ':discount' => $value['discount'],
            ':fromdate' => $value['fromdate'],
            ':todate' => $value['todate'],
            ':typepromo' => $value['typepromo'],
            ':maxqty' => $value['maxqty'],
            ':jenis_promo' => $value['jenis_promo']
        ]);
    }

    $url_combo = $base_url . '/store/promo/get_promo_combo.php?idstore=' . $idstore;
    $hasil_combo = get_data($url_combo);
    $j_combo = json_decode($hasil_combo, true);

    if (!empty($j_combo) && !empty($j_combo['promo_combo_header'])) {

        // Kosongkan tabel lama
        $connec->exec("TRUNCATE TABLE pos_mcombo_promo_detail RESTART IDENTITY CASCADE");
        $connec->exec("TRUNCATE TABLE pos_mcombo_promo_header RESTART IDENTITY CASCADE");

        // --- Insert Header ---
        $sqlHeader = "
        INSERT INTO pos_mcombo_promo_header 
            (pos_mcombo_promo_header_key, promo_name, promo_type, min_items, max_items, fromdate, todate, isactived, insertdate, insertby, id_location)
        VALUES 
            (:pos_mcombo_promo_header_key, :promo_name, :promo_type, :min_items, :max_items, :fromdate, :todate, :isactived, :insertdate, :insertby, :id_location)
        RETURNING pos_mcombo_promo_header_key
    ";
        $stmtHeader = $connec->prepare($sqlHeader);

        // --- Insert Detail ---
        $sqlDetail = "
        INSERT INTO pos_mcombo_promo_detail 
            (pos_mcombo_promo_detail_key, pos_mcombo_promo_header_key, sku, is_required, min_qty, insertdate, insertby, id_location)
        VALUES 
            (:pos_mcombo_promo_detail_key, :pos_mcombo_promo_header_key, :sku, :is_required, :min_qty, :insertdate, :insertby, :id_location)
    ";
        $stmtDetail = $connec->prepare($sqlDetail);

        // Loop setiap header promo combo
        foreach ($j_combo['promo_combo_header'] as $hdr) {
            $stmtHeader->execute([
                ':pos_mcombo_promo_header_key' => $hdr['pos_mcombo_promo_header_key'],
                ':promo_name' => $hdr['promo_name'] ?? '',
                ':promo_type' => $hdr['promo_type'] ?? 'BUY_2_PAY_HIGHEST',
                ':min_items' => $hdr['min_items'] ?? 2,
                ':max_items' => $hdr['max_items'] ?? 2,
                ':fromdate' => $hdr['fromdate'] ?? null,
                ':todate' => $hdr['todate'] ?? null,
                ':isactived' => $hdr['isactived'] ?? '1',
                ':insertdate' => $hdr['insertdate'] ?? date("Y-m-d H:i:s"),
                ':insertby' => $hdr['insertby'] ?? 'SYSTEM',
                ':id_location' => $hdr['id_location'] ?? $idstore
            ]);

            $headerKey = $stmtHeader->fetchColumn();

            // Masukkan detail berdasarkan header key
            foreach ($j_combo['promo_combo_detail'] as $dtl) {
                if ($dtl['pos_mcombo_promo_header_key'] === $hdr['pos_mcombo_promo_header_key']) {
                    $stmtDetail->execute([
                        ':pos_mcombo_promo_detail_key' => $dtl['pos_mcombo_promo_detail_key'],
                        ':pos_mcombo_promo_header_key' => $headerKey,
                        ':sku' => $dtl['sku'],
                        ':is_required' => $dtl['is_required'] ?? '1',
                        ':min_qty' => $dtl['min_qty'] ?? 1,
                        ':insertdate' => $dtl['insertdate'] ?? date("Y-m-d H:i:s"),
                        ':insertby' => $dtl['insertby'] ?? 'SYSTEM',
                        ':id_location' => $dtl['id_location'] ?? $idstore
                    ]);
                }
            }
        }

        echo json_encode([
            "status" => "OK",
            "message" => "Promo Combo synced successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "OK",
            "message" => "No Promo Combo found"
        ]);
    }


} catch (Exception $e) {
    echo json_encode([
        "status" => "FAILED",
        "message" => $e->getMessage()
    ]);
    die();
}
?>
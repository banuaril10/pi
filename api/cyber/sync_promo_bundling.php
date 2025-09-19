<?php
include "../../config/koneksi.php";

// Ambil org aktif
$ll = "SELECT * FROM ad_morg WHERE isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

function get_category($url)
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

// Panggil API bundling
$url = $base_url . '/store/promo/get_promo_bundling.php?idstore=' . $idstore;
$hasil = get_category($url);
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

    // Simpan header (file bundling)
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

    echo json_encode([
        "status" => "OK",
        "message" => "Data Inserted"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "FAILED",
        "message" => $e->getMessage()
    ]);
    die();
}
?>
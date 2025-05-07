<?php
include "../../config/koneksi.php";
ini_set('max_execution_time', '300');
$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ambil idstore
$ll = "SELECT * FROM ad_morg WHERE isactived = 'Y'";
$query = $connec->query($ll);
$idstore = '';
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

// fungsi ambil data dari API
function get($url)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        )
    );
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

$url = $base_url . '/store/items/get_items.php?idstore=' . $idstore;
$hasil = get($url);
$j_hasil = json_decode($hasil, true);

if (!$j_hasil || count($j_hasil) === 0) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Data kosong atau gagal diambil dari API"
    ]);
    exit;
}

try {
    $connec->beginTransaction();

    // TRUNCATE lebih baik dilakukan setelah beginTransaction agar rollback masih memungkinkan jika insert gagal
    $connec->exec("TRUNCATE TABLE pos_mproduct");

    $stmt = $connec->prepare("
        INSERT INTO pos_mproduct (
            ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate,
            m_product_id, m_product_category_id, sku, name, description, price, stockqty,
            shortcut, barcode, tag, idcat, idsubcat, idsubitem
        ) VALUES (
            :client_key, :org_key, :isactived, :insertdate, :insertby, :postby, :postdate,
            :product_id, :category_id, :sku, :name, :description, :price, :stockqty,
            :shortcut, :barcode, :tag, :idcat, :idsubcat, :idsubitem
        )
    ");

    $now = date("Y-m-d H:i:s");

    foreach ($j_hasil as $value) {
        $stmt->execute([
            ':client_key' => $ad_mclient_key,
            ':org_key' => $idstore,
            ':isactived' => $value['isactived'],
            ':insertdate' => $now,
            ':insertby' => 'SYSTEM',
            ':postby' => 'SYSTEM',
            ':postdate' => $now,
            ':product_id' => $value['id'],
            ':category_id' => $value['idcat'] ?: 0,
            ':sku' => $value['sku'],
            ':name' => str_replace("'", "''", $value['name']),
            ':description' => '',
            ':price' => 0,
            ':stockqty' => 0,
            ':shortcut' => $value['shortcut'],
            ':barcode' => $value['barcode'],
            ':tag' => $value['tag'],
            ':idcat' => $value['idcat'] ?: 0,
            ':idsubcat' => $value['idsubcat'] ?: 0,
            ':idsubitem' => $value['idsubitem'] ?: 0,
        ]);
    }

    $connec->commit();

    echo json_encode([
        "status" => "OK",
        "message" => "Data berhasil diambil, di-truncate, dan di-insert",
    ]);
} catch (PDOException $e) {
    $connec->rollBack();
    echo json_encode([
        "status" => "FAILED",
        "message" => "Error: " . $e->getMessage(),
    ]);
}
?>
<?php 
include "../../config/koneksi.php";
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function get_voucher_rules($url)
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
    
    // Cek error curl
    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        return json_encode([
            "status" => "ERROR",
            "message" => "CURL Error: " . $error_msg
        ]);
    }
    
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($http_code != 200) {
        return json_encode([
            "status" => "ERROR",
            "message" => "HTTP Error: " . $http_code
        ]);
    }
    
    return $response;
}

// URL API untuk mendapatkan voucher rules
$url = $base_url.'/store/voucher/get_voucher_rules.php?idstore='. $idstore;
// echo "Syncing from: " . $url . "\n";

$hasil = get_voucher_rules($url);
$j_hasil = json_decode($hasil, true);

// Cek jika response adalah error
if (isset($j_hasil['status']) && $j_hasil['status'] == 'ERROR') {
    $json = array(
        "status" => "ERROR",
        "message" => "Failed to fetch data: " . $j_hasil['message'],
    );
    echo json_encode($json);
    die();
}

// Cek jika data kosong
if (empty($j_hasil)) {
    $json = array(
        "status" => "FAILED",
        "message" => "No data received from API",
    );
    echo json_encode($json);
    die();
}

$s = array();

// Jika response memiliki struktur data
$data_array = isset($j_hasil['data']) ? $j_hasil['data'] : $j_hasil;

foreach ($data_array as $key => $value) {
    // Mapping data dari API ke struktur tabel
    $pos_mvoucher_rule_key = isset($value['pos_mvoucher_rule_key']) ? $value['pos_mvoucher_rule_key'] : (isset($value['id']) ? $value['id'] : '');
    $isactived = isset($value['isactived']) ? $value['isactived'] : (isset($value['isactive']) ? $value['isactive'] : '1');
    $id_location = isset($value['id_location']) ? $value['id_location'] : (isset($value['id_store']) ? $value['id_store'] : '');
    $min_transaction = isset($value['min_transaction']) ? $value['min_transaction'] : (isset($value['nominal']) ? $value['nominal'] : 0);
    $voucher_amount = isset($value['voucher_amount']) ? $value['voucher_amount'] : 0;
    $valid_days = isset($value['valid_days']) ? $value['valid_days'] : 30; // Default 30 hari
    $description = isset($value['description']) ? $value['description'] : '';
    $insertdate = isset($value['insertdate']) ? $value['insertdate'] : date('Y-m-d H:i:s');
    $insertby = isset($value['insertby']) ? $value['insertby'] : 'SYSTEM';
    $percent = isset($value['percent']) ? $value['percent'] : 0; // Default 20%
    $limit_voucher = isset($value['limit_voucher']) ? $value['limit_voucher'] : 0; // Default 0
    
    // Escape string untuk mencegah SQL injection
    $pos_mvoucher_rule_key = $connec->quote($pos_mvoucher_rule_key);
    $isactived = $connec->quote($isactived);
    $id_location = $connec->quote($id_location);
    $description = $connec->quote($description);
    $insertdate = $connec->quote($insertdate);
    $insertby = $connec->quote($insertby);
    
    $s[] = "($pos_mvoucher_rule_key, $isactived, $id_location, $min_transaction, $voucher_amount, $valid_days, $description, $insertdate, $insertby, $percent, $limit_voucher)";
}

if(empty($s)){
    $json = array(
        "status" => "FAILED",
        "message" => "No valid data to sync",
    );
    echo json_encode($json);
    die();
}

// Mulai transaction
$connec->beginTransaction();

try {
    // Truncate table
    $truncate = "TRUNCATE TABLE pos_mvoucher_rule";
    $statement = $connec->prepare($truncate);
    $statement->execute();
    
    // Insert data baru
    $values = implode(", ", $s);
    $insert = "INSERT INTO pos_mvoucher_rule (pos_mvoucher_rule_key, isactived, id_location, min_transaction, voucher_amount, valid_days, description, insertdate, insertby, percent, limit_voucher) VALUES $values";
    
    $statement = $connec->prepare($insert);
    $statement->execute();
    
    // Commit transaction
    $connec->commit();
    
    $affected_rows = $statement->rowCount();
    
    $json = array(
        "status" => "OK",
        "message" => "Successfully synced $affected_rows voucher rules",
        "total_synced" => $affected_rows,
    );
    
} catch (Exception $e) {
    // Rollback jika ada error
    $connec->rollBack();
    
    $json = array(
        "status" => "ERROR",
        "message" => "Failed to sync voucher rules: " . $e->getMessage(),
        "query_error" => isset($insert) ? $insert : 'N/A',
    );
}

echo json_encode($json);
?>
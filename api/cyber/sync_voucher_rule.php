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

// URL API
$url = $base_url.'/store/voucher/get_voucher_rules.php?idstore='. $idstore;

$hasil = get_voucher_rules($url);
$j_hasil = json_decode($hasil, true);

// Cek error response
if (isset($j_hasil['status']) && $j_hasil['status'] == 'ERROR') {
    $json = array(
        "status" => "ERROR",
        "message" => "Failed to fetch data: " . $j_hasil['message'],
    );
    echo json_encode($json);
    die();
}

// ===== PERUBAHAN UTAMA DI SINI =====
// Jika data kosong, tetap lanjutkan dengan truncate
// karena "klo data kosong ya udah ttp jalanin berarti ikutin truncate data local"

$data_array = isset($j_hasil['data']) ? $j_hasil['data'] : $j_hasil;

// Filter data yang valid
$valid_data = array();
foreach ($data_array as $key => $value) {
    // Cek apakah data memiliki minimal field yang diperlukan
    $pos_mvoucher_rule_key = isset($value['pos_mvoucher_rule_key']) ? $value['pos_mvoucher_rule_key'] : (isset($value['id']) ? $value['id'] : '');
    
    // Hanya proses jika memiliki key yang valid
    if (!empty($pos_mvoucher_rule_key)) {
        $valid_data[] = $value;
    }
}

// Siapkan data untuk insert
$s = array();
foreach ($valid_data as $key => $value) {
    $pos_mvoucher_rule_key = isset($value['pos_mvoucher_rule_key']) ? $value['pos_mvoucher_rule_key'] : (isset($value['id']) ? $value['id'] : '');
    $isactived = isset($value['isactived']) ? $value['isactived'] : (isset($value['isactive']) ? $value['isactive'] : '1');
    $id_location = isset($value['id_location']) ? $value['id_location'] : (isset($value['id_store']) ? $value['id_store'] : '');
    $min_transaction = isset($value['min_transaction']) ? $value['min_transaction'] : (isset($value['nominal']) ? $value['nominal'] : 0);
    $voucher_amount = isset($value['voucher_amount']) ? $value['voucher_amount'] : 0;
    $valid_days = isset($value['valid_days']) ? $value['valid_days'] : 30;
    $description = isset($value['description']) ? $value['description'] : '';
    $insertdate = isset($value['insertdate']) ? $value['insertdate'] : date('Y-m-d H:i:s');
    $insertby = isset($value['insertby']) ? $value['insertby'] : 'SYSTEM';
    $percent = isset($value['percent']) ? $value['percent'] : 0;
    $limit_voucher = isset($value['limit_voucher']) ? $value['limit_voucher'] : 0;
    
    $pos_mvoucher_rule_key = $connec->quote($pos_mvoucher_rule_key);
    $isactived = $connec->quote($isactived);
    $id_location = $connec->quote($id_location);
    $description = $connec->quote($description);
    $insertdate = $connec->quote($insertdate);
    $insertby = $connec->quote($insertby);
    
    $s[] = "($pos_mvoucher_rule_key, $isactived, $id_location, $min_transaction, $voucher_amount, $valid_days, $description, $insertdate, $insertby, $percent, $limit_voucher)";
}

// Mulai transaction
$connec->beginTransaction();

try {
    // TRUNCATE tetap dijalankan APAPUN kondisinya
    $truncate = "TRUNCATE TABLE pos_mvoucher_rule";
    $statement = $connec->prepare($truncate);
    $statement->execute();
    
    $affected_rows = 0;
    
    // Jika ada data valid, insert
    if (!empty($s)) {
        $values = implode(", ", $s);
        $insert = "INSERT INTO pos_mvoucher_rule (pos_mvoucher_rule_key, isactived, id_location, min_transaction, voucher_amount, valid_days, description, insertdate, insertby, percent, limit_voucher) VALUES $values";
        
        $statement = $connec->prepare($insert);
        $statement->execute();
        $affected_rows = $statement->rowCount();
    }
    
    // Commit transaction
    $connec->commit();
    
    $json = array(
        "status" => "OK",
        "message" => "Table truncated and synced " . $affected_rows . " voucher rules",
        "total_synced" => $affected_rows,
        "truncated" => true,
    );
    
} catch (Exception $e) {
    $connec->rollBack();
    
    $json = array(
        "status" => "ERROR",
        "message" => "Failed to sync voucher rules: " . $e->getMessage(),
    );
}

echo json_encode($json);
?>
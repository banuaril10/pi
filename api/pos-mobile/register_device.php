<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$has = $input["has"] ?? null;
$domain = $input["domain"] ?? null;
$locationid = $input["locationid"] ?? null;
$name = $input["name"] ?? null;
$kode_otp = $input["kode_otp"] ?? null;

// Validasi input
if (empty($has) || empty($name) || empty($kode_otp)) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Parameter has, name, dan kode_otp wajib diisi"
    ]);
    exit;
}

// Fungsi untuk membersihkan response dari karakter non-JSON
function cleanJsonResponse($response) {
    // Hapus BOM (Byte Order Mark) jika ada
    $bom = pack('H*', 'EFBBBF');
    $response = preg_replace("/^$bom/", '', $response);
    
    // Hapus whitespace di awal dan akhir
    $response = trim($response);
    
    // Hapus karakter kontrol yang tidak valid (kecuali \n, \r, \t)
    $response = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $response);
    
    return $response;
}

// Fungsi untuk push register ke server utama
function push_register($url, $has, $domain, $locationid, $name, $kode_otp)
{
    $postData = array(
        'has' => $has,
        'domain' => $domain,
        'locationid' => $locationid,
        'name' => $name,
        'kode_otp' => $kode_otp
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        )
    );

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);

    curl_close($curl);

    if ($curlError) {
        return [
            "success" => false,
            "message" => "Curl error: " . $curlError,
            "httpCode" => $httpCode
        ];
    }

    if ($httpCode != 200) {
        return [
            "success" => false,
            "message" => "HTTP error: " . $httpCode,
            "httpCode" => $httpCode,
            "response" => $response
        ];
    }

    // Bersihkan response dari karakter aneh
    $cleanedResponse = cleanJsonResponse($response);
    
    // Coba decode JSON
    $decoded = json_decode($cleanedResponse, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Simpan response asli dan yang sudah dibersihkan untuk debug
        return [
            "success" => false,
            "message" => "Invalid JSON response: " . json_last_error_msg(),
            "original_response" => $response,
            "cleaned_response" => $cleanedResponse,
            "error" => json_last_error_msg()
        ];
    }

    return [
        "success" => true,
        "data" => $decoded
    ];
}

// URL server utama untuk register
$url = $base_url . '/store/register/sync_register.php';

// Push ke server utama
$result = push_register($url, $has, $domain, $locationid, $name, $kode_otp);

if (!$result['success']) {
    // Jika gagal push ke server utama
    $response = [
        "status" => "FAILED",
        "message" => $result['message'],
        "debug" => [
            "url" => $url,
            "httpCode" => $result['httpCode'] ?? null,
        ]
    ];
    
    // Tambahkan info response jika ada
    if (isset($result['original_response'])) {
        $response['debug']['original_response'] = substr($result['original_response'], 0, 500);
        $response['debug']['cleaned_response'] = substr($result['cleaned_response'], 0, 500);
    }
    if (isset($result['response'])) {
        $response['debug']['response'] = substr($result['response'], 0, 500);
    }
    
    echo json_encode($response);
    exit;
}

$j_hasil = $result['data'];

if ($j_hasil['status'] == 'FAILED') {
    echo json_encode([
        "status" => "FAILED",
        "message" => $j_hasil['message']
    ]);
    exit;
}

// Jika sukses push ke server utama, simpan juga di lokal
try {
    // Ambil data dari hasil register
    $has = $j_hasil['data']['has'];
    $domain = $j_hasil['data']['domain'];
    $locationid = $j_hasil['data']['locationid'];
    $name = $j_hasil['data']['name'];

    // Ambil ad_mclient_key dari konfigurasi atau tabel
    $ad_mclient_key = "D089DFFA729F4A22816BD8838AB0813C";

    // Cek apakah device sudah terdaftar
    $check = $connec->prepare("SELECT COUNT(pos_mcashier_key) as jum FROM pos_mcashier WHERE code = :has");
    $check->bindParam(":has", $has);
    $check->execute();
    $row = $check->fetch(PDO::FETCH_ASSOC);
    $jum = $row['jum'];

    if ($jum > 0) {
        // Update device yang sudah ada
        $sql = "UPDATE pos_mcashier SET 
                isactived = '1', 
                insertdate = NOW(), 
                insertby = 'SYSTEM', 
                postby = 'SYSTEM', 
                postdate = NOW(), 
                name = :name, 
                description = '', 
                ad_morg_key = :locationid 
                WHERE code = :has";
    } else {
        // Insert device baru
        $sql = "INSERT INTO pos_mcashier 
                (ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, name, description, code) 
                VALUES 
                (:ad_mclient_key, :locationid, '1', NOW(), 'SYSTEM', 'SYSTEM', NOW(), :name, '', :has)";
    }

    $stmt = $connec->prepare($sql);

    if ($jum > 0) {
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":locationid", $locationid);
        $stmt->bindParam(":has", $has);
    } else {
        $stmt->bindParam(":ad_mclient_key", $ad_mclient_key);
        $stmt->bindParam(":locationid", $locationid);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":has", $has);
    }

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "OK",
            "message" => "Registrasi Berhasil",
            "data" => [
                "has" => $has,
                "domain" => $domain,
                "locationid" => $locationid,
                "name" => $name
            ]
        ]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode([
            "status" => "FAILED",
            "message" => "Gagal menyimpan data lokal: " . $errorInfo[2]
        ]);
    }

} catch(PDOException $e) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
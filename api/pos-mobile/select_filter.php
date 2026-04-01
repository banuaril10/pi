<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();
include "../../config/koneksi.php";

// Ambil parameter GET
$f3 = $_GET['f3'] ?? ''; // sp name 
$f4 = $_GET['f4'] ?? ''; // user id / search term

// Ambil ad_morg_key dari session atau parameter
// Untuk sementara, kita perlu ad_morg_key
$ad_morg_key = $_GET['org_id'] ?? '134'; // Ganti dengan cara mendapatkan org_id yang benar

error_log("select_filter.php called with f3=" . $f3 . ", f4=" . $f4 . ", org_id=" . $ad_morg_key);

if (empty($f3)) {
    echo json_encode(["data" => []]);
    exit;
}

try {
    if ($f3 == 'pos_supervisor_user_get') {
        $sql = "SELECT * FROM proc_pos_supervisor_user_get(
            null, null, null, null, :p_search
        )";
        
        $stmt = $connec->prepare($sql);
        $stmt->bindParam(":p_search", $f4);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $o_data = $result['o_data'] ?? null;
        
        if ($o_data) {
            $data = json_decode($o_data, true);
            echo json_encode(["data" => $data]);
        } else {
            echo json_encode(["data" => []]);
        }
    } 
    else if ($f3 == 'pos_dcashierbalance_reprint_get') {
        // Function membutuhkan 5 parameter:
        // p_ad_mclient_key, p_ad_morg_key, p_filterby, p_parentid, p_search
        $sql = "SELECT * FROM proc_pos_dcashierbalance_reprint_get(
            null, :p_ad_morg_key, null, null, :p_search
        )";
        
        $stmt = $connec->prepare($sql);
        $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
        $stmt->bindParam(":p_search", $f4);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $o_data = $result['o_data'] ?? null;
        $o_message = $result['o_message'] ?? '';
        
        error_log("Result o_data: " . ($o_data ?? 'null'));
        error_log("Result o_message: " . $o_message);
        
        if ($o_data) {
            $data = json_decode($o_data, true);
            echo json_encode(["data" => $data]);
        } else {
            echo json_encode(["data" => []]);
        }
    }
    else if ($f3 == 'pos_dshopsales_reprint_get') {
        // Function membutuhkan 5 parameter, dengan status 'DONE' sebagai parameter ke-3
        $sql = "SELECT * FROM proc_pos_dshopsales_reprint_get(
            null, :p_ad_morg_key, null, null, :p_search
        )";
        
        $stmt = $connec->prepare($sql);
        $stmt->bindParam(":p_ad_morg_key", $ad_morg_key); // Perbaiki: pakai $ad_morg_key, bukan $org_id
        $stmt->bindParam(":p_search", $f4);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $o_data = $result['o_data'] ?? null;
        
        error_log("pos_dshopsales_reprint_get result: " . ($o_data ?? 'null'));
        
        if ($o_data) {
            $data = json_decode($o_data, true);
            echo json_encode(["data" => $data]);
        } else {
            echo json_encode(["data" => []]);
        }
    }
    else {
        error_log("Unknown sp: " . $f3);
        echo json_encode(["data" => []]);
    }
    
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        "data" => [],
        "error" => $e->getMessage()
    ]);
}
?>
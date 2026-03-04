<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$type = $input["type"] ?? null; // SALDO_AWAL, TUTUP_KASIR, TUTUP_HARIAN
$date = $input["date"] ?? null;
$cashierId = $input["cashier_id"] ?? null;

// Validasi input
if (empty($type) || empty($cashierId)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    if ($type == "TUTUP_KASIR") {
        // Untuk tutup kasir, panggil function proc_pos_dcashierbalance_close_print
        $sql = "SELECT * FROM proc_pos_dcashierbalance_close_print(
            :p_ad_muser_key,
            :p_strsalesdate
        )";
        
        $stmt = $connec->prepare($sql);
        $stmt->bindParam(":p_ad_muser_key", $cashierId);
        $stmt->bindParam(":p_strsalesdate", $date);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Ambil hasil dari function
        $o_data = $result["o_data"] ?? null;
        $o_data2 = $result["o_data2"] ?? null;
        $o_category = $result["o_category"] ?? null;
        $o_message = $result["o_message"] ?? "";
        
        // Decode JSON data
        $header = $o_data ? json_decode($o_data, true) : [];
        $edcData = $o_data2 ? json_decode($o_data2, true) : [];
        $categoryData = $o_category ? json_decode($o_category, true) : [];
        
        if ($o_message == "success") {
            // Gabungkan semua data dalam satu response
            echo json_encode([
                "o_data_header" => $header,
                "o_data_edc" => $edcData,
                "o_data_category" => $categoryData,
                "o_message" => $o_message
            ]);
        } else {
            echo json_encode([
                "o_data_header" => [],
                "o_data_edc" => [],
                "o_data_category" => [],
                "o_message" => $o_message ?: "Data tutup kasir tidak ditemukan"
            ]);
        }
    } 
    else if ($type == "SALDO_AWAL") {
        $sql = "SELECT * FROM proc_pos_dcashierbalance_print(
            :p_cashier_id,
            :p_date
        )";
        // ... handling untuk saldo awal
    } 
    else if ($type == "TUTUP_HARIAN") {
        $sql = "SELECT * FROM proc_pos_dailyclose_print(
            :p_cashier_id,
            :p_date
        )";
        // ... handling untuk tutup harian
    } 
    else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Tipe dokumen tidak dikenal"
        ]);
        exit;
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
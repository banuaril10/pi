<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();
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
    } else if ($type == "SALDO_AWAL") {
    // Untuk saldo awal, kita perlu mencari pos_dcashierbalance_key berdasarkan user dan tanggal
    // Format tanggal dari Android: "25-Feb-26 14:06" atau mungkin "25-Feb-26"
    
    // Log untuk debugging
    error_log("SALDO_AWAL - cashierId: " . $cashierId);
    error_log("SALDO_AWAL - date: " . $date);
    
    // Cari pos_dcashierbalance_key berdasarkan user dan tanggal
    // Asumsi: $date bisa berisi "25-Feb-26" atau "25-Feb-26 14:06"
    $searchDate = $date;
    
    // Jika date mengandung spasi (format lengkap), ambil bagian tanggalnya saja
    if (strpos($date, ' ') !== false) {
        $searchDate = substr($date, 0, strpos($date, ' '));
    }
    
    error_log("SALDO_AWAL - searchDate: " . $searchDate);
    
    // Query untuk mencari balance_key
    $searchSql = "SELECT pos_dcashierbalance_key 
                  FROM pos_dcashierbalance 
                  WHERE ad_muser_key = :cashier_id 
                    AND to_char(startdate, 'DD-Mon-YY') = :search_date
                  ORDER BY startdate DESC 
                  LIMIT 1";
    
    $searchStmt = $connec->prepare($searchSql);
    $searchStmt->bindParam(":cashier_id", $cashierId);
    $searchStmt->bindParam(":search_date", $searchDate);
    $searchStmt->execute();
    
    $balanceResult = $searchStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$balanceResult) {
        error_log("SALDO_AWAL - No balance key found for date: " . $searchDate);
        echo json_encode([
            "o_data_header" => [],
            "o_data_edc" => [],
            "o_data_category" => [],
            "o_message" => "Data saldo awal tidak ditemukan untuk tanggal " . $searchDate
        ]);
        exit;
    }
    
    $balanceKey = $balanceResult['pos_dcashierbalance_key'];
    error_log("SALDO_AWAL - balanceKey found: " . $balanceKey);
    
    // Panggil function proc_pos_dcashierbalance_reprint dengan balance_key yang benar
    $sql = "SELECT * FROM proc_pos_dcashierbalance_reprint(
        :p_ad_muser_key,
        :p_pos_dcashierbalance_key
    )";

    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_muser_key", $cashierId);
    $stmt->bindParam(":p_pos_dcashierbalance_key", $balanceKey);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";

    $header = $o_data ? json_decode($o_data, true) : [];

    echo json_encode([
        "o_data_header" => $header,
        "o_data_edc" => [],
        "o_data_category" => [],
        "o_message" => $o_message
    ]);
} else if ($type == "TUTUP_HARIAN") {
        // Ambil ad_mclient_key dan ad_morg_key dari database berdasarkan cashierId
        $sqlUser = "SELECT 
                    a.ad_mclient_key,
                    a.ad_morg_key
                FROM ad_muser a
                WHERE a.ad_muser_key = :cashier_id";

        $stmtUser = $connec->prepare($sqlUser);
        $stmtUser->bindParam(":cashier_id", $cashierId);
        $stmtUser->execute();
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            echo json_encode([
                "o_data_header" => [],
                "o_data_edc" => [],
                "o_data_category" => [],
                "o_message" => "Data user tidak ditemukan"
            ]);
            exit;
        }

        $ad_mclient_key = $userData['ad_mclient_key'];
        $ad_morg_key = $userData['ad_morg_key'];

        error_log("TUTUP_HARIAN - ad_mclient_key: " . $ad_mclient_key);
        error_log("TUTUP_HARIAN - ad_morg_key: " . $ad_morg_key);
        error_log("TUTUP_HARIAN - date: " . $date);

        $sql = "SELECT * FROM proc_pos_dshopsales_close_print(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_strsalesdate
    )";

        $stmt = $connec->prepare($sql);
        $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
        $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
        $stmt->bindParam(":p_strsalesdate", $date);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $o_data = $result["o_data"] ?? null;
        $o_data2 = $result["o_data2"] ?? null;
        $o_category = $result["o_category"] ?? null;
        $o_message = $result["o_message"] ?? "";

        $header = $o_data ? json_decode($o_data, true) : [];
        $edcData = $o_data2 ? json_decode($o_data2, true) : [];
        $categoryData = $o_category ? json_decode($o_category, true) : [];

        echo json_encode([
            "o_data_header" => $header,
            "o_data_edc" => $edcData,
            "o_data_category" => $categoryData,
            "o_message" => $o_message
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Tipe dokumen tidak dikenal"
        ]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
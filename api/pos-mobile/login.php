<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$userid = $input["userid"] ?? null;
$password = $input["password"] ?? null;
$cashierid = $input["cashierid"] ?? null; // Tambahkan parameter cashierid

// Validasi input
if (empty($userid) || empty($password) || empty($cashierid)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "User ID, Password, dan Cashier ID harus diisi"
    ]);
    exit;
}

// Enkripsi password dengan HMAC SHA256
$secret_key = 'marinuak';
$userpwd = hash_hmac("sha256", $password, $secret_key);

try {
    // Panggil function proc_pos_login_get
    $sql = "SELECT * FROM proc_pos_login_get(
        :p_userid,
        :p_userpwd,
        :p_pos_mcashier_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_userid", $userid);
    $stmt->bindParam(":p_userpwd", $userpwd);
    $stmt->bindParam(":p_pos_mcashier_key", $cashierid);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_user = $result["o_user"] ?? null;
    $o_info = $result["o_info"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $userData = $o_user ? json_decode($o_user, true) : null;
    $infoData = $o_info ? json_decode($o_info, true) : null;
    
    if ($o_message == "success") {
        echo json_encode([
            "o_user" => $userData,
            "o_info" => $infoData,
            "o_message" => $o_message
        ]);
    } else {
        echo json_encode([
            "o_user" => null,
            "o_info" => null,
            "o_message" => $o_message ?: "Login gagal"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "o_user" => null,
        "o_info" => null,
        "o_message" => "Database error: " . $e->getMessage()
    ]);
}
?>
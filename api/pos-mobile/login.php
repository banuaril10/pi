<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include "../../config/koneksi.php";
require_once 'jwt_helper.php'; // Versi sederhana

$input = json_decode(file_get_contents("php://input"), true);

$userid = $input["userid"] ?? null;
$password = $input["password"] ?? null;
$cashierid = $input["cashierid"] ?? null;

if (empty($userid) || empty($password) || empty($cashierid)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "User ID, Password, dan Cashier ID harus diisi"
    ]);
    exit;
}

$secret_key = 'marinuak';
$userpwd = hash_hmac("sha256", $password, $secret_key);

try {
    $sql = "SELECT * FROM proc_pos_login_get(
        :p_userid, :p_userpwd, :p_pos_mcashier_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_userid", $userid);
    $stmt->bindParam(":p_userpwd", $userpwd);
    $stmt->bindParam(":p_pos_mcashier_key", $cashierid);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $o_user = $result["o_user"] ?? null;
    $o_info = $result["o_info"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    $userData = $o_user ? json_decode($o_user, true) : null;
    $infoData = $o_info ? json_decode($o_info, true) : null;
    
    if ($o_message == "success" && $userData && $infoData) {
        $tokenData = [
            'user_id' => $userData[0]['id'],
            'username' => $userData[0]['username'],
            'userid' => $userData[0]['userid'],
            'cashier_id' => $infoData[0]['cashierid'],
            'role' => 'user'
        ];
        
        $token = JWTHandler::generateToken($tokenData);
        
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Login berhasil",
            "o_user" => $userData,
            "o_info" => $infoData,
            "token" => $token
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Login gagal"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
} catch(Exception $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "General error: " . $e->getMessage()
    ]);
}
?>
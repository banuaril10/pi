<?php
// auth_middleware.php
require_once 'jwt_helper.php';

function authenticate() {
    $headers = getallheaders();
    
    // Cek header Authorization
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    
    if (empty($authHeader)) {
        http_response_code(401);
        echo json_encode([
            "status" => "ERROR",
            "message" => "Unauthorized: No token provided"
        ]);
        exit;
    }

    // Ambil token (format: "Bearer {token}")
    $token = str_replace('Bearer ', '', $authHeader);

    // Validasi token menggunakan JWT helper sederhana
    $userData = JWTHandler::validateToken($token);
    
    if (!$userData) {
        http_response_code(401);
        echo json_encode([
            "status" => "ERROR",
            "message" => "Unauthorized: Invalid token"
        ]);
        exit;
    }

    // Simpan data user untuk digunakan di API
    $GLOBALS['user_data'] = $userData;
    return $userData;
}

// Optional: Cek role tertentu
function authorize($allowedRoles = []) {
    $userData = $GLOBALS['user_data'] ?? null;
    
    if (empty($allowedRoles)) {
        return true;
    }
    
    if (!$userData || !isset($userData['role']) || !in_array($userData['role'], $allowedRoles)) {
        http_response_code(403);
        echo json_encode([
            "status" => "ERROR",
            "message" => "Forbidden: Insufficient privileges"
        ]);
        exit;
    }
    
    return true;
}

// Optional: Get current user data
function getCurrentUser() {
    return $GLOBALS['user_data'] ?? null;
}
?>
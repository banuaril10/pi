<?php
// jwt_helper.php - Versi sederhana tanpa library eksternal

class JWTHandler {
    private static $secret_key = 'PosIdolmartSecretKey2026!@#$';
    
    // Encode data ke base64 URL
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    // Decode dari base64 URL
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
    
    // Generate JWT Token
    public static function generateToken($data) {
        // Header
        $header = json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]);
        
        // Payload
        $payload = json_encode([
            'iat' => time(),
            'exp' => time() + (60*60*24),
            'data' => $data
        ]);
        
        // Encode header dan payload
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);
        
        // Buat signature
        $signature = hash_hmac('sha256', 
            $base64UrlHeader . "." . $base64UrlPayload, 
            self::$secret_key, 
            true
        );
        $base64UrlSignature = self::base64UrlEncode($signature);
        
        // Gabungkan
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
    
    // Validasi Token
    public static function validateToken($token) {
        // Pisahkan token
        $parts = explode('.', $token);
        if (count($parts) != 3) {
            return false;
        }
        
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
        
        // Verifikasi signature
        $signature = self::base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', 
            $base64UrlHeader . "." . $base64UrlPayload, 
            self::$secret_key, 
            true
        );
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        // Decode payload
        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);
        
        // Cek expired
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        return $payload['data'] ?? false;
    }
}
?>
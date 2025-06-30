<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private static $secret_key = 'your-super-secret-key'; // change this to env var

    public static function authenticate() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            self::unauthorizedResponse("Authorization header missing");
        }
        $authHeader = $headers['Authorization'];
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            self::unauthorizedResponse("Invalid Authorization header format");
        }
        $jwt = $matches[1];
        try {
            $decoded = JWT::decode($jwt, new Key(self::$secret_key, 'HS256'));
            // Token is valid
            return $decoded; // contains teacher info (id, email, etc.)
        } catch (Exception $e) {
            self::unauthorizedResponse("Invalid token: " . $e->getMessage());
        }
    }

    private static function unauthorizedResponse($message) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => $message]);
        exit;
    }
}

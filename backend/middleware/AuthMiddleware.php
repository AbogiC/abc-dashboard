<?php
require_once __DIR__ . '/../config/constants.php';

class AuthMiddleware
{
    public static function authenticate()
    {
        $headers = apache_request_headers();
        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::error('No token provided', HTTP_UNAUTHORIZED);
        }

        $token = $matches[1];
        $payload = self::verifyToken($token);

        if (!$payload) {
            Response::error('Invalid or expired token', HTTP_UNAUTHORIZED);
        }

        return $payload;
    }

    private static function verifyToken($token)
    {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return false;
            }

            $header = json_decode(base64_decode($parts[0]), true);
            $payload = json_decode(base64_decode($parts[1]), true);
            $signature = base64_decode($parts[2]);

            if ($header['alg'] !== JWT_ALGORITHM) {
                return false;
            }

            $expectedSignature = hash_hmac('sha256', "$parts[0].$parts[1]", JWT_SECRET, true);

            if (!hash_equals($signature, $expectedSignature)) {
                return false;
            }

            // Check expiration
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return false;
            }

            return $payload;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function generateToken($userId, $username, $role)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => JWT_ALGORITHM]);
        $payload = json_encode([
            'user_id' => $userId,
            'username' => $username,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // 24 hours
        ]);

        $base64Header = base64_encode($header);
        $base64Payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", JWT_SECRET, true);
        $base64Signature = base64_encode($signature);

        return "$base64Header.$base64Payload.$base64Signature";
    }
}
?>
<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

date_default_timezone_set("Asia/Kolkata");

define(
    'JWT_SECRET',
    $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET')
);
define('JWT_EXPIRY', 3600); // 1 hour in seconds

function generateJWT(array $payload, int $expiry = JWT_EXPIRY): string
{
    // overwrite time claims to prevent manipulation
    $payload['iat'] = time();           // issued at
    $payload['exp'] = time() + $expiry; // expiry time

    return JWT::encode($payload, JWT_SECRET, JWT_ALGO);
}

function verifyJWT(string $token): ?array
{
    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
        return (array) $decoded;

    } catch (Exception $e) {
        // Token is expired, tampered, or malformed
        return null;
    }
}

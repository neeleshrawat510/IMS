<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

date_default_timezone_set("Asia/Kolkata");

define("JWT_ALGO", "HS256");

define(
    "JWT_SECRET",
    getenv("JWT_SECRET") ?: ($_ENV["JWT_SECRET"] ?? "")
);

define("JWT_EXPIRY", 3600);

function generateJWT(array $payload, int $expiry = JWT_EXPIRY): string
{
    $payload["iat"] = time();
    $payload["exp"] = time() + $expiry;

    return JWT::encode(
        $payload,
        JWT_SECRET,
        JWT_ALGO
    );
}

function verifyJWT(string $token): ?array
{
    try {

        $decoded = JWT::decode(
            $token,
            new Key(JWT_SECRET, JWT_ALGO)
        );

        return (array)$decoded;

    } catch (Throwable $e) {

        return null;

    }
}
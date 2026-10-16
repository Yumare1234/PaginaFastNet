<?php
declare(strict_types=1);

function jsonResponse(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function sanitizeText(string $value): string
{
    return trim(htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8'));
}

$host = 'localhost';
$user = 'root';
$password = '1';
$database = 'fastnet';

$conn = @new mysqli($host, $user, $password, $database);

if ($conn->connect_errno) {
    jsonResponse(500, [
        'success' => false,
        'message' => 'No se pudo conectar a la base de datos. Revisa que MySQL esté activo y que la base fastnet exista.'
    ]);
}

$conn->set_charset('utf8mb4');

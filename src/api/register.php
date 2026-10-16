<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once __DIR__ . '/../db/conexion.php';

$nombre = sanitizeText($_POST['nombre'] ?? '');
$apellido = sanitizeText($_POST['apellido'] ?? '');
$email = strtolower(sanitizeText($_POST['email'] ?? ''));
$telefono = sanitizeText($_POST['telefono'] ?? '');
$direccion = sanitizeText($_POST['direccion'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirmar = (string) ($_POST['confirmar'] ?? '');

if ($nombre === '' || $apellido === '' || $email === '' || $telefono === '' || $password === '' || $confirmar === '') {
    jsonResponse(400, [
        'success' => false,
        'message' => 'Por favor completa todos los campos obligatorios.'
    ]);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(400, [
        'success' => false,
        'message' => 'El correo electrónico no es válido.'
    ]);
}

if (strlen($password) < 6 || strlen($password) > 128) {
    jsonResponse(400, [
        'success' => false,
        'message' => 'La contraseña debe tener entre 6 y 128 caracteres.'
    ]);
}

if ($password !== $confirmar) {
    jsonResponse(400, [
        'success' => false,
        'message' => 'Las contraseñas no coinciden.'
    ]);
}

if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) {
    jsonResponse(400, [
        'success' => false,
        'message' => 'Nombre y apellido solo pueden contener letras y espacios.'
    ]);
}

if (!preg_match('/^[0-9+()\-\s]+$/', $telefono)) {
    jsonResponse(400, [
        'success' => false,
        'message' => 'El teléfono contiene caracteres no válidos.'
    ]);
}

$stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    jsonResponse(409, [
        'success' => false,
        'message' => 'Este correo ya está registrado.'
    ]);
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, password_hash) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssssss', $nombre, $apellido, $email, $telefono, $direccion, $passwordHash);

if (!$stmt->execute()) {
    jsonResponse(500, [
        'success' => false,
        'message' => 'No se pudo guardar el usuario.'
    ]);
}

echo json_encode([
    'success' => true,
    'message' => 'Cuenta creada exitosamente.'
], JSON_UNESCAPED_UNICODE);

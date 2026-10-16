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

session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

$email = strtolower(sanitizeText($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    jsonResponse(400, [
        'success' => false,
        'message' => 'Debes ingresar tu correo y contraseña.'
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

$stmt = $conn->prepare('SELECT id, nombre, apellido, email, password_hash FROM usuarios WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    jsonResponse(401, [
        'success' => false,
        'message' => 'Credenciales incorrectas.'
    ]);
}

$usuario = $result->fetch_assoc();

if (!is_array($usuario) || !password_verify($password, (string) ($usuario['password_hash'] ?? ''))) {
    jsonResponse(401, [
        'success' => false,
        'message' => 'Credenciales incorrectas.'
    ]);
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = (int) $usuario['id'];
$_SESSION['usuario_nombre'] = sanitizeText((string) $usuario['nombre']);
$_SESSION['usuario_apellido'] = sanitizeText((string) $usuario['apellido']);
$_SESSION['usuario_email'] = strtolower((string) $usuario['email']);
$_SESSION['usuario_logged_in'] = true;

jsonResponse(200, [
    'success' => true,
    'message' => 'Inicio de sesión exitoso.'
]);

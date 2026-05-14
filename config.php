<?php
// Configuración de conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'salon_comunal_app');
define('DB_PORT', 3306);

// Crear conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Configurar charset a UTF-8
$conn->set_charset("utf8mb4");

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]));
}

// Permitir CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Función para responder con JSON
function responder($datos, $codigo = 200) {
    http_response_code($codigo);
    echo json_encode($datos);
    exit();
}

// Función para log de errores
function logError($mensaje) {
    error_log('[' . date('Y-m-d H:i:s') . '] ' . $mensaje . PHP_EOL, 3, 'errores.log');
}
?>

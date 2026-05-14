<?php
require_once __DIR__ . '/../config.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$path = preg_replace('#^' . preg_quote($scriptDir, '#') . '#', '', str_replace('\\', '/', $path));
$path = trim($path, '/');

// ENDPOINTS DE AUTENTICACIÓN
if ($path === 'auth/login' && $metodo === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $conn->real_escape_string($data['email'] ?? '');
    $contrasena = $data['password'] ?? '';

    $result = $conn->query("SELECT * FROM usuarios WHERE email = '$email' AND activo = TRUE");
    
    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verificar contraseña con bcrypt
        if (password_verify($contrasena, $usuario['contrasena'])) {
            session_start();
            $_SESSION['userId'] = $usuario['id'];
            $_SESSION['perfil'] = $usuario['perfil'];
            
            responder([
                'exito' => true,
                'usuario' => [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'email' => $usuario['email'],
                    'perfil' => $usuario['perfil'],
                    'apto' => $usuario['apto'],
                    'telefono' => $usuario['telefono']
                ]
            ]);
        }
    }
    responder(['exito' => false, 'error' => 'Email o contraseña incorrectos'], 401);
}

// Registro de nuevo usuario (residente)
if ($path === 'auth/register' && $metodo === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $apto = $conn->real_escape_string($data['apto'] ?? '');
    $telefono = $conn->real_escape_string($data['telefono'] ?? '');
    $contrasena = $data['password'] ?? '';

    // Validar email único
    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
    if ($check && $check->num_rows > 0) {
        responder(['exito' => false, 'error' => 'El email ya está registrado'], 400);
    }

    // Hashear contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);
    $id = 'u' . time() . rand(1000, 9999);

    $sql = "INSERT INTO usuarios (id, nombre, email, contrasena, perfil, apto, telefono, activo) 
            VALUES ('$id', '$nombre', '$email', '$hashedPassword', 'residente', '$apto', '$telefono', TRUE)";

    if ($conn->query($sql)) {
        session_start();
        $_SESSION['userId'] = $id;
        $_SESSION['perfil'] = 'residente';

        responder([
            'exito' => true,
            'usuario' => [
                'id' => $id,
                'nombre' => $nombre,
                'email' => $email,
                'perfil' => 'residente',
                'apto' => $apto,
                'telefono' => $telefono
            ]
        ]);
    } else {
        logError("Error al registrar usuario: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al crear la cuenta'], 500);
    }
}

// ===== ENDPOINTS DE RESERVAS =====

// Obtener todas las reservas (admin/supervisor) o mis reservas
if ($path === 'reservas' && $metodo === 'GET') {
    $sql = "SELECT * FROM reservas ORDER BY fecha DESC";
    $result = $conn->query($sql);
    $reservas = [];
    
    while ($row = $result->fetch_assoc()) {
        $row['insumos'] = json_decode($row['insumos'], true) ?? [];
        $reservas[] = $row;
    }

    responder(['exito' => true, 'reservas' => $reservas]);
}

// Crear nueva reserva
if ($path === 'reservas' && $metodo === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = 'r' . time() . rand(1000, 9999);
    $userId = $conn->real_escape_string($data['userId'] ?? '');
    $creadoPor = $conn->real_escape_string($data['creadoPor'] ?? '');
    $fecha = $conn->real_escape_string($data['fecha'] ?? '');
    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $asistentes = (int)($data['asistentes'] ?? 0);
    $descripcion = $conn->real_escape_string($data['descripcion'] ?? '');
    $insumos = json_encode($data['insumos'] ?? []);
    $obs = $conn->real_escape_string($data['obs'] ?? '');

    // Verificar que la fecha no esté reservada
    $check = $conn->query("SELECT id FROM reservas WHERE fecha = '$fecha' AND estado != 'rechazada'");
    if ($check && $check->num_rows > 0) {
        responder(['exito' => false, 'error' => 'Esta fecha ya está reservada'], 400);
    }

    $sql = "INSERT INTO reservas (id, userId, creadoPor, fecha, nombre, asistentes, descripcion, insumos, obs, estado) 
            VALUES ('$id', '$userId', '$creadoPor', '$fecha', '$nombre', $asistentes, '$descripcion', '$insumos', '$obs', 'pendiente')";

    if ($conn->query($sql)) {
        responder(['exito' => true, 'id' => $id, 'mensaje' => 'Reserva creada exitosamente']);
    } else {
        logError("Error al crear reserva: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al crear la reserva'], 500);
    }
}

// Actualizar estado de reserva (aprobar/rechazar)
if (preg_match('/^reservas\/(\w+)$/', $path, $matches) && $metodo === 'PUT') {
    $id = $matches[1];
    $data = json_decode(file_get_contents('php://input'), true);
    
    $estado = $conn->real_escape_string($data['estado'] ?? '');
    $comentario = $conn->real_escape_string($data['comentario'] ?? '');

    $sql = "UPDATE reservas SET estado = '$estado', comentario = '$comentario' WHERE id = '$id'";

    if ($conn->query($sql)) {
        responder(['exito' => true, 'mensaje' => 'Reserva actualizada']);
    } else {
        logError("Error al actualizar reserva: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al actualizar'], 500);
    }
}

// ===== ENDPOINTS DE USUARIOS =====

// Obtener todos los usuarios (admin)
if ($path === 'usuarios' && $metodo === 'GET') {
    $sql = "SELECT id, nombre, email, perfil, apto, telefono, activo FROM usuarios ORDER BY nombre ASC";
    $result = $conn->query($sql);
    $usuarios = [];
    
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    responder(['exito' => true, 'usuarios' => $usuarios]);
}

// Crear nuevo usuario (admin)
if ($path === 'usuarios' && $metodo === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = 'u' . time() . rand(1000, 9999);
    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $perfil = $conn->real_escape_string($data['perfil'] ?? 'residente');
    $apto = $conn->real_escape_string($data['apto'] ?? '');
    $telefono = $conn->real_escape_string($data['telefono'] ?? '');
    $contrasena = $data['password'] ?? 'Password123';

    // Verificar email único
    $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
    if ($check && $check->num_rows > 0) {
        responder(['exito' => false, 'error' => 'El email ya existe'], 400);
    }

    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (id, nombre, email, contrasena, perfil, apto, telefono, activo) 
            VALUES ('$id', '$nombre', '$email', '$hashedPassword', '$perfil', '$apto', '$telefono', TRUE)";

    if ($conn->query($sql)) {
        responder(['exito' => true, 'id' => $id, 'mensaje' => 'Usuario creado exitosamente']);
    } else {
        logError("Error al crear usuario: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al crear usuario'], 500);
    }
}

// Actualizar usuario (admin)
if (preg_match('/^usuarios\/(\w+)$/', $path, $matches) && $metodo === 'PUT') {
    $id = $matches[1];
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $perfil = $conn->real_escape_string($data['perfil'] ?? '');
    $apto = $conn->real_escape_string($data['apto'] ?? '');
    $telefono = $conn->real_escape_string($data['telefono'] ?? '');
    $activo = isset($data['activo']) ? ($data['activo'] ? 1 : 0) : 1;

    $sql = "UPDATE usuarios SET nombre = '$nombre', email = '$email', perfil = '$perfil', apto = '$apto', telefono = '$telefono', activo = $activo WHERE id = '$id'";

    if ($conn->query($sql)) {
        responder(['exito' => true, 'mensaje' => 'Usuario actualizado']);
    } else {
        logError("Error al actualizar usuario: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al actualizar'], 500);
    }
}

// Cambiar contraseña
if ($path === 'cambiar-password' && $metodo === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $conn->real_escape_string($data['userId'] ?? '');
    $passwordActual = $data['passwordActual'] ?? '';
    $passwordNueva = $data['passwordNueva'] ?? '';

    $result = $conn->query("SELECT contrasena FROM usuarios WHERE id = '$userId'");
    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        if (password_verify($passwordActual, $usuario['contrasena'])) {
            $hashedPassword = password_hash($passwordNueva, PASSWORD_BCRYPT);
            $conn->query("UPDATE usuarios SET contrasena = '$hashedPassword' WHERE id = '$userId'");
            responder(['exito' => true, 'mensaje' => 'Contraseña actualizada']);
        } else {
            responder(['exito' => false, 'error' => 'Contraseña actual incorrecta'], 401);
        }
    }
}

// ===== ENDPOINTS DE INVENTARIO =====

// Obtener inventario
if ($path === 'inventario' && $metodo === 'GET') {
    $sql = "SELECT * FROM inventario WHERE activo = TRUE ORDER BY nombre ASC";
    $result = $conn->query($sql);
    $inventario = [];
    
    while ($row = $result->fetch_assoc()) {
        $inventario[] = $row;
    }

    responder(['exito' => true, 'inventario' => $inventario]);
}

// Crear insumo
if ($path === 'inventario' && $metodo === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = 'i' . time() . rand(1000, 9999);
    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $cantidad = (int)($data['cantidad'] ?? 0);
    $unidad = $conn->real_escape_string($data['unidad'] ?? '');
    $minimo = (int)($data['minimo'] ?? 5);
    $categoria = $conn->real_escape_string($data['categoria'] ?? '');
    $descripcion = $conn->real_escape_string($data['descripcion'] ?? '');

    $sql = "INSERT INTO inventario (id, nombre, cantidad, unidad, minimo, categoria, descripcion, activo) 
            VALUES ('$id', '$nombre', $cantidad, '$unidad', $minimo, '$categoria', '$descripcion', TRUE)";

    if ($conn->query($sql)) {
        responder(['exito' => true, 'id' => $id, 'mensaje' => 'Insumo creado']);
    } else {
        logError("Error al crear insumo: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al crear insumo'], 500);
    }
}

// Actualizar insumo
if (preg_match('/^inventario\/(\w+)$/', $path, $matches) && $metodo === 'PUT') {
    $id = $matches[1];
    $data = json_decode(file_get_contents('php://input'), true);
    
    $nombre = $conn->real_escape_string($data['nombre'] ?? '');
    $cantidad = (int)($data['cantidad'] ?? 0);
    $unidad = $conn->real_escape_string($data['unidad'] ?? '');
    $minimo = (int)($data['minimo'] ?? 5);
    $categoria = $conn->real_escape_string($data['categoria'] ?? '');
    $descripcion = $conn->real_escape_string($data['descripcion'] ?? '');

    $sql = "UPDATE inventario SET nombre = '$nombre', cantidad = $cantidad, unidad = '$unidad', minimo = $minimo, categoria = '$categoria', descripcion = '$descripcion' WHERE id = '$id'";

    if ($conn->query($sql)) {
        responder(['exito' => true, 'mensaje' => 'Insumo actualizado']);
    } else {
        logError("Error al actualizar insumo: " . $conn->error);
        responder(['exito' => false, 'error' => 'Error al actualizar'], 500);
    }
}

// 404
responder(['exito' => false, 'error' => 'Endpoint no encontrado'], 404);
$conn->close();
?>

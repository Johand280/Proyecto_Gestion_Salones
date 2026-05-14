-- Crear base de datos
CREATE DATABASE IF NOT EXISTS salon_comunal_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salon_comunal_app;

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    perfil ENUM('residente', 'supervisor', 'administrador') DEFAULT 'residente',
    apto VARCHAR(20),
    telefono VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_perfil (perfil),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Reservas
CREATE TABLE IF NOT EXISTS reservas (
    id VARCHAR(50) PRIMARY KEY,
    userId VARCHAR(50) NOT NULL,
    creadoPor VARCHAR(50),
    fecha DATE NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    asistentes INT DEFAULT 0,
    descripcion LONGTEXT,
    insumos JSON,
    obs LONGTEXT,
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    comentario LONGTEXT,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (creadoPor) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_fecha (fecha),
    INDEX idx_estado (estado),
    INDEX idx_userId (userId),
    UNIQUE KEY unique_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Inventario
CREATE TABLE IF NOT EXISTS inventario (
    id VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    cantidad INT DEFAULT 0,
    unidad VARCHAR(20),
    minimo INT DEFAULT 5,
    categoria VARCHAR(50),
    descripcion LONGTEXT,
    activo BOOLEAN DEFAULT TRUE,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre),
    INDEX idx_activo (activo),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Movimientos de Inventario
CREATE TABLE IF NOT EXISTS movimientos_inventario (
    id VARCHAR(50) PRIMARY KEY,
    insumoId VARCHAR(50) NOT NULL,
    tipo ENUM('entrada', 'salida', 'ajuste') DEFAULT 'salida',
    cantidad INT NOT NULL,
    razon VARCHAR(255),
    usuarioId VARCHAR(50),
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (insumoId) REFERENCES inventario(id) ON DELETE CASCADE,
    FOREIGN KEY (usuarioId) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_insumoId (insumoId),
    INDEX idx_tipo (tipo),
    INDEX idx_creado (creado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Auditoría
CREATE TABLE IF NOT EXISTS auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuarioId VARCHAR(50),
    accion VARCHAR(255),
    tabla VARCHAR(100),
    registroId VARCHAR(50),
    detalles JSON,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuarioId) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_accion (accion),
    INDEX idx_tabla (tabla),
    INDEX idx_creado (creado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario admin por defecto
INSERT IGNORE INTO usuarios (id, nombre, email, contrasena, perfil, apto, telefono, activo) 
VALUES ('admin001', 'Administrador', 'admin@salon.com', '$2y$10$AfKvo7uIMeYsBs8JQ7Xide4rtdB3BdeRThupMxHt3gstKnjEEAO3S', 'administrador', '001', '3000000000', TRUE);

-- Nota: La contraseña de admin es: admin123 (hasheada con bcrypt)

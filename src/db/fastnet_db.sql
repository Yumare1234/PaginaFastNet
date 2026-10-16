CREATE DATABASE IF NOT EXISTS fastnet CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE fastnet;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    telefono VARCHAR(30) NOT NULL,
    direccion VARCHAR(255) NULL,
    password_hash VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_email CHECK (email LIKE '%@%')
) ENGINE=InnoDB;

-- Ejemplo de inserción de un usuario registrado desde el formulario:
-- INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, password_hash)
-- VALUES ('Juan', 'Pérez', 'juan@correo.com', '+58 412-0000000', 'Urbanización, municipio, estado', '$2y$10$...');

-- Consulta para iniciar sesión por correo y contraseña:
-- SELECT id, nombre, apellido, email, telefono, direccion, password_hash
-- FROM usuarios
-- WHERE email = 'juan@correo.com';

-- En PHP o backend, luego se usa password_verify($passwordIngresada, $usuario['password_hash'])
-- para validar la contraseña.

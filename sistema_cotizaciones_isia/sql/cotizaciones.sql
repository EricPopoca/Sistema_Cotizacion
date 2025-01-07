CREATE DATABASE cotizaciones;

USE cotizaciones;

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    descripcion TEXT,
    fecha DATE,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Insertar administrador con contraseña cifrada
INSERT INTO administradores (nombre, correo, contrasena) VALUES
('Admin', 'admin@ejemplo.com', '$2y$10$6F2l7J9lHeIc5WUN0bSm4.TMCqkMdfhlAswBDjl0ItxKq1SJeKDKm'); -- Contraseña: admin123

INSERT INTO clientes (nombre, apellido, telefono, correo) VALUES
('Cliente1', 'Apellido1', '5551234567', 'cliente1@ejemplo.com'),
('Cliente2', 'Apellido2', '5559876543', 'cliente2@ejemplo.com');

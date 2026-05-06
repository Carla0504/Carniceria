CREATE DATABASE IF NOT EXISTS la_dehesa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE la_dehesa;

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE secciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    orden TINYINT UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_seccion INT UNSIGNED NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    foto VARCHAR(255),
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_seccion) REFERENCES secciones(id) ON DELETE CASCADE
);

CREATE TABLE promociones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNSIGNED NOT NULL,
    descripcion TEXT,
    precio_promocional DECIMAL(10,2) NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1,
    fecha_inicio DATE,
    fecha_fin DATE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE carrito_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    cantidad TINYINT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_producto (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)  ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

INSERT INTO secciones (nombre, slug, descripcion, orden) VALUES 
('Carnicería', 'carniceria', 'Carne fresca de calidad seleccionada', 1), 
('Charcutería', 'charcuteria', 'Embutidos y productos curados', 2),
('Pollería', 'polleria', 'Pollo, pavo y otras aves', 3), 
('Conservas', 'conservas', 'Conservas y productos en lata', 4);

INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador', 'admin@ladehesa.es', '$2b$10$kDYN/LFcHExN925KRxcbHO2drA8Xi7cZotmJnFVLytGV7OP2elcNC', 'admin');

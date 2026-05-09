CREATE DATABASE IF NOT EXISTS la_dehesa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE la_dehesa;

DROP TABLE IF EXISTS carrito_items;
DROP TABLE IF EXISTS promociones;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS secciones;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@ladehesa.es', '$2b$10$kDYN/LFcHExN925KRxcbHO2drA8Xi7cZotmJnFVLytGV7OP2elcNC', 'admin'),
('prueba', 'prueba@gmail.com', '$2b$10$Yakq5YNFN6r9IDRtFhNUlumfiqjf/dOS0BClfCTkkCv0hCWt25AyS', 'cliente');

CREATE TABLE secciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    orden TINYINT UNSIGNED NOT NULL DEFAULT 0
);

INSERT INTO secciones (nombre, slug, descripcion, orden) VALUES
('Carnicería', 'carniceria', 'Carne fresca de calidad seleccionada', 1),
('Charcutería', 'charcuteria', 'Embutidos y productos curados', 2),
('Pollería', 'polleria', 'Pollo, pavo y otras aves', 3),
('Conservas', 'conservas', 'Conservas y productos en lata', 4);

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

INSERT INTO productos (id_seccion, nombre, descripcion, precio, foto) VALUES
-- Carnicería
(1, 'Entrecot de ternera', 'Corte jugoso de ternera nacional, ideal para plancha o barbacoa.', 18.90, NULL),
(1, 'Chuletas de cerdo', 'Chuletas de cerdo ibérico con hueso, perfectas para asar.', 7.50, NULL),
(1, 'Solomillo de cerdo', 'Pieza tierna y magra de cerdo, ideal para medallones.', 12.00, NULL),
(1, 'Costillas de ternera', 'Costillas de ternera para horno o barbacoa, muy jugosas.', 9.80, NULL),
(1, 'Hamburguesas artesanas', 'Hamburguesas elaboradas a mano con carne de ternera 100%.', 8.40, NULL),
-- Charcutería
(2, 'Jamón ibérico de bellota', 'Jamón ibérico de bellota curado 36 meses, sabor intenso.', 65.00, 'jamon-iberico-bellota.png'),
(2, 'Lomo embuchado', 'Lomo de cerdo ibérico adobado y embutido artesanalmente.', 22.50, 'lomo_embuchado.png'),
(2, 'Chorizo extra', 'Chorizo curado con pimentón de la Vera, sabor ahumado.', 11.00, 'chorizo extra.png'),
(2, 'Salchichón ibérico', 'Salchichón elaborado con carnes selectas y especias naturales.', 13.50, NULL),
(2, 'Morcilla de Burgos', 'Morcilla tradicional con arroz y cebolla, receta artesana.', 6.90, NULL),
-- Pollería
(3, 'Pollo entero', 'Pollo fresco de granja, criado en libertad. Peso aproximado 1,8 kg.', 5.90, NULL),
(3, 'Pechugas de pollo', 'Pechugas fileteadas listas para cocinar, sin piel ni hueso.', 7.20, NULL),
(3, 'Muslos de pollo', 'Muslos con contramuslo, ideales para horno o guiso.', 4.50, NULL),
(3, 'Pavo en filetes', 'Filetes de pavo finos, bajos en grasa y muy versátiles.', 8.00, NULL),
(3, 'Alitas de pollo', 'Alitas frescas perfectas para asar o preparar en salsa.', 3.90, NULL),
-- Conservas
(4, 'Paté de campaña', 'Paté artesano elaborado con hígado de cerdo y especias.', 3.50, NULL),
(4, 'Morcilla en conserva', 'Morcilla de calidad envasada al vacío para mayor durabilidad.', 4.20, NULL),
(4, 'Lomo en manteca', 'Lomo de cerdo ibérico conservado en manteca colorá tradicional.', 6.80, NULL),
(4, 'Chicharrones', 'Chicharrones de cerdo crujientes, elaborados de forma artesanal.', 5.10, NULL),
(4, 'Chistorra en aceite', 'Chistorra navarra en conserva de aceite de oliva virgen extra.', 4.75, NULL);

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

INSERT INTO promociones (id_producto, descripcion, precio_promocional, activa, fecha_inicio, fecha_fin) VALUES
(1, 'Oferta de temporada en entrecot de ternera nacional.', 14.90, 1, '2026-05-01', '2026-05-31'),
(5, 'Hamburguesas artesanas con descuento especial esta semana.', 6.50, 1, '2026-05-06', '2026-05-12'),
(6, 'Jamón ibérico de bellota a precio reducido por tiempo limitado.', 55.00, 1, '2026-05-01', '2026-05-31'),
(11, 'Pollo entero de granja con descuento especial.', 4.50, 1, '2026-05-06', '2026-05-20'),
(16, 'Paté de campaña artesano en promoción esta semana.', 2.50, 1, '2026-05-06', '2026-05-12');

CREATE TABLE carrito_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    cantidad TINYINT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_producto (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

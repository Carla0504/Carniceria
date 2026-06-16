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
('Carnicería', 'carniceria', 'Sin conservantes ni potenciadores de sabor. Picamos la carne en el momento que nos lo pidan.', 1),
('Charcutería y Jamones', 'charcuteria', 'Las mejores marcas españolas e italianas en embutidos artesanales y jamones de calidad.', 2),
('Quesos y Especialidades', 'polleria', 'Quesos premiados internacionalmente, cecina de León y especialidades únicas.', 3),
('Conservas y Bacalao', 'conservas', 'Gadus Morhua de las Islas Faroe, conservas, legumbres, vinos, picos y regañas.', 4);

CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_seccion INT UNSIGNED NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    unidad_medida ENUM('kg','g','100g','unidad','bandeja','pack','blister') NOT NULL DEFAULT 'unidad',
    stock DECIMAL(10,3) NOT NULL DEFAULT 0,
    foto VARCHAR(255),
    disponible TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_seccion) REFERENCES secciones(id) ON DELETE CASCADE
);

INSERT INTO productos (id_seccion, nombre, descripcion, precio, unidad_medida, stock, foto) VALUES
-- Carnicería
(1, 'Filete de 1ª', 'Ternera de primera calidad. Sin conservantes ni potenciadores de sabor, picamos la carne en el momento que nos lo pidan.', 17.99, 'kg', 20.000, 'filete_primera.jpg'),
(1, 'Entrecot', 'Corte premium de ternera, jugoso y con mucho sabor. Sin conservantes.', 34.99, 'kg', 8.000, 'entrecot.jpg'),
(1, 'Solomillo', 'El corte más tierno y valorado. Carne de primera sin conservantes.', 44.99, 'kg', 5.000, 'solomillo.jpg'),
(1, 'Cantero de cadera', 'Pieza noble de ternera, perfecta para filetear o hacer a la plancha.', 27.99, 'kg', 10.000, 'cantero_cadera.jpg'),
(1, 'Chuletas de cerdo', 'Chuletas de cerdo frescas, perfectas para plancha o barbacoa.', 8.49, 'kg', 18.000, 'chuletas_cerdo.jpg'),
(1, 'Costillas frescas', 'Costillas de cerdo frescas para horno, barbacoa o guiso.', 11.99, 'kg', 15.000, 'costillas_frescas.jpg'),
(1, 'Costillas adobadas oreadas', 'Costillas adobadas y oreadas en obrador propio, listas para asar directamente.', 17.49, 'kg', 12.000, 'costillas_adobadas.jpg'),
(1, 'Migas', 'Migas de matanza elaboradas artesanalmente en obrador propio. Sin conservantes.', 17.99, 'kg', 10.000, 'migas.jpg'),
-- Charcutería y Jamones
(2, 'Lomos', 'Lomo embuchado artesanal seleccionado de las mejores marcas españolas.', 24.99, 'kg', 12.000, 'lomos.jpg'),
(2, 'Chicharrones', 'Chicharrones elaborados artesanalmente, sin conservantes ni aditivos.', 15.99, 'kg', 15.000, 'chicharrones.jpg'),
(2, 'Mortadela trufada italiana', 'Mortadela italiana artesanal con trufa, de las mejores marcas del país.', 19.99, 'kg', 10.000, 'mortadela_trufada.jpg'),
(2, 'Cecina de León con D.O.', 'Cecina de vacuno con Denominación de Origen de León, curación mínima 7 meses.', 54.99, 'kg', 5.000, 'cecina_leon.jpg'),
(2, 'Jamón Reserva', 'El perfecto para el bocadillo. Centro de jamón reserva con más de 15 meses de curación, partido con máquina.', 34.99, 'kg', 8.000, 'jamon_reserva.jpg'),
(2, 'Jamón Serrano Segoviano', 'El rey de los serranos con pata blanca. Segoviano, más de 24 meses de curación, 50% raza DUROC, tratado desde origen a la antigua usanza (chamuscado).', 69.99, 'kg', 5.000, 'jamon_serrano.jpg'),
(2, 'Jamón Ibérico 50%', 'De Salamanca. Cerdo ibérico 50% de raza ibérica, con al menos 30 meses de curación.', 83.99, 'kg', 3.000, 'jamon_iberico.jpg'),
(2, 'Jabugo D.O. 100% Ibérico Bellota', 'El rey de reyes. Con D.O. Jabugo, 100% raza ibérica y de bellota, más de 36 meses de curación.', 199.99, 'kg', 2.000, 'jabugo.jpg'),
-- Quesos y Especialidades
(3, 'Queso con D.O.', 'Queso con Denominación de Origen, seleccionado de los mejores queseros artesanales.', 24.99, 'kg', 8.000, 'queso_do.jpg'),
(3, 'Quesos artesanales', 'Selección de quesos artesanales de producción limitada, cambia según temporada.', 24.99, 'kg', 10.000, 'quesos_artesanales.jpg'),
(3, 'Queso especial de romero', 'Ganador de premios internacionales en su categoría. Corteza natural de romero, elaboración artesanal.', 30.00, 'kg', 5.000, 'queso_romero.jpg'),
(3, 'Queso especial con trufa', 'Galardonado a nivel internacional. Queso con trufa de producción artesanal y limitada.', 30.00, 'kg', 4.000, 'queso_trufa.jpg'),
(3, 'Queso especial al pesto', 'Premiado internacionalmente. Queso al pesto de elaboración artesanal y curación controlada.', 30.00, 'kg', 4.000, 'queso_pesto.jpg'),
(3, 'Gildas con cecina y queso', 'Gildas artesanas con cecina de León D.O. y queso curado. Listas para comer.', 6.99, 'unidad', 25.000, 'gildas.jpg'),
-- Conservas y Bacalao
(4, 'Gadus Morhua — Bacalao Islas Faroe', 'El mayor bacalao del mundo. Procedente de las Islas Faroe, pescado con anzuelo de forma sostenible y respetuosa.', 35.99, 'kg', 10.000, 'gadus_morhua.jpg'),
(4, 'Mejillones cocidos', 'Mejillones cocidos al natural, listos para comer.', 11.99, 'unidad', 30.000, 'mejillones_cocidos.jpg');

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
(2, 'Entrecot de ternera en oferta de temporada.', 29.99, 1, '2026-06-01', '2026-06-30'),
(13, 'Jamón Reserva con descuento especial este mes.', 28.99, 1, '2026-06-01', '2026-06-30');

CREATE TABLE mensajes_contacto (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    leido TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE carrito_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_producto INT UNSIGNED NOT NULL,
    cantidad DECIMAL(8,3) NOT NULL DEFAULT 1.000,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_producto (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);
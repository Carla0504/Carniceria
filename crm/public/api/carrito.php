<?php
session_start();

// todas las respuestas de esta api son json
header('Content-Type: application/json; charset=utf-8');

// si no hay sesión no dejo pasar
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Tienes que iniciar sesión']);
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/models/CarritoItem.php';

$idUsuario = (int) $_SESSION['user']['id'];
$metodo = $_SERVER['REQUEST_METHOD'];

// GET: devuelvo los productos del carrito con el total
if ($metodo === 'GET') {
    $productos = CarritoItem::porUsuario($pdo, $idUsuario);

    $total = 0;
    foreach ($productos as $p) {
        $total += $p['precio_efectivo'] * $p['cantidad'];
    }

    echo json_encode([
        'items' => $productos,
        'total' => round($total, 2),
        'count' => CarritoItem::contar($pdo, $idUsuario)
    ]);
    exit();
}

// POST: gestiono las acciones del carrito (agregar, actualizar, eliminar, vaciar)
if ($metodo === 'POST') {
    $accion = $_POST['_action'] ?? '';

    if ($accion === 'agregar') {
        $idProducto = (int) ($_POST['id_producto'] ?? 0);

        if ($idProducto === 0) {
            echo json_encode(['error' => 'Falta el id del producto']);
            exit();
        }

        CarritoItem::agregar($pdo, $idUsuario, $idProducto);
        $total = CarritoItem::contar($pdo, $idUsuario);
        echo json_encode(['ok' => true, 'count' => $total]);
        exit();
    }

    if ($accion === 'actualizar') {
        $id = (int) ($_POST['id'] ?? 0);
        $cantidad = (int) ($_POST['cantidad'] ?? 1);

        // me aseguro de que la cantidad no baje de 1
        if ($cantidad < 1) $cantidad = 1;

        CarritoItem::actualizar($pdo, $id, $idUsuario, $cantidad);
        $total = CarritoItem::contar($pdo, $idUsuario);
        echo json_encode(['ok' => true, 'count' => $total]);
        exit();
    }

    if ($accion === 'eliminar') {
        $id = (int) ($_POST['id'] ?? 0);
        CarritoItem::eliminar($pdo, $id, $idUsuario);
        $total = CarritoItem::contar($pdo, $idUsuario);
        echo json_encode(['ok' => true, 'count' => $total]);
        exit();
    }

    if ($accion === 'vaciar') {
        CarritoItem::vaciar($pdo, $idUsuario);
        echo json_encode(['ok' => true, 'count' => 0]);
        exit();
    }

    echo json_encode(['error' => 'Acción no reconocida']);
}

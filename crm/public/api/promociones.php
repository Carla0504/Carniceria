<?php
session_start();

// solo el admin puede gestionar promociones
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/models/Promocion.php';

header('Content-Type: application/json');

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    $idProducto = isset($_GET['id_producto']) ? (int) $_GET['id_producto'] : 0;

    if ($idProducto === 0) {
        echo json_encode(null);
        exit();
    }

    $promo = Promocion::porProducto($pdo, $idProducto);
    echo json_encode($promo ?: null);
    exit();
}

if ($metodo === 'POST') {
    $accion = $_POST['_action'] ?? '';

    if ($accion === 'crear') {
        $datos = [
            'id_producto' => (int) $_POST['id_producto'],
            'precio_promocional' => (float) $_POST['precio_promocional'],
            'fecha_inicio' => $_POST['fecha_inicio'] !== '' ? $_POST['fecha_inicio'] : null,
            'fecha_fin' => $_POST['fecha_fin'] !== '' ? $_POST['fecha_fin'] : null,
        ];

        Promocion::crear($pdo, $datos);
        echo json_encode(['ok' => true]);
        exit();
    }

    if ($accion === 'actualizar') {
        $id = (int) $_POST['id'];
        $datos = [
            'precio_promocional' => (float) $_POST['precio_promocional'],
            'fecha_inicio' => $_POST['fecha_inicio'] !== '' ? $_POST['fecha_inicio'] : null,
            'fecha_fin' => $_POST['fecha_fin'] !== '' ? $_POST['fecha_fin'] : null,
        ];

        Promocion::actualizar($pdo, $id, $datos);
        echo json_encode(['ok' => true]);
        exit();
    }

    if ($accion === 'eliminar') {
        $id = (int) $_POST['id'];
        Promocion::eliminar($pdo, $id);
        echo json_encode(['ok' => true]);
        exit();
    }

    echo json_encode(['ok' => false, 'error' => 'Acción desconocida']);
    exit();
}

echo json_encode(['ok' => false, 'error' => 'Método no permitido']);

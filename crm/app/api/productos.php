<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Producto.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        echo json_encode(Producto::porId($pdo, (int)$_GET['id']));
    } else {
        echo json_encode(Producto::todos($pdo));
    }
    exit();
}

if ($method === 'POST') {
    $action = $_POST['_action'] ?? '';

    if ($action === 'eliminar') {
        Producto::eliminar($pdo, (int)$_POST['id']);
        echo json_encode(['ok' => true]);
        exit();
    }

    $idSeccion = (int)$_POST['id_seccion'];
    $stmt = $pdo->prepare("SELECT slug FROM secciones WHERE id = ? LIMIT 1");
    $stmt->execute([$idSeccion]);
    $slug = $stmt->fetchColumn();

    $foto = $_POST['foto_actual'] ?? null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $nombre = uniqid() . '.' . $ext;
        $destino = __DIR__ . '/../../public/img/productos/' . $slug . '/' . $nombre;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $foto = $nombre;
        }
    }

    $datos = [
        'id_seccion' => $idSeccion,
        'nombre' => trim($_POST['nombre']),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'precio' => (float)$_POST['precio'],
        'foto' => $foto ?: null,
        'disponible' => ($_POST['disponible'] ?? '0') === '1' ? 1 : 0,
    ];

    if ($action === 'crear') {
        Producto::crear($pdo, $datos);
        echo json_encode(['ok' => true]);
    } elseif ($action === 'actualizar') {
        Producto::actualizar($pdo, (int)$_POST['id'], $datos);
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Acción desconocida']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);

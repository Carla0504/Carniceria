<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// solo los administradores pueden usar esta api
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo json_encode(['error' => 'No tienes permiso para hacer esto']);
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/models/Producto.php';
$metodo = $_SERVER['REQUEST_METHOD'];

// GET: devuelvo un producto por id o todos
if ($metodo === 'GET') {
    if (isset($_GET['id'])) {
        echo json_encode(Producto::porId($pdo, (int)$_GET['id']));
    } else {
        echo json_encode(Producto::todos($pdo));
    }
    exit();
}

// POST: crear, editar o eliminar producto
if ($metodo === 'POST') {
    $accion = $_POST['_action'] ?? '';

    if ($accion === 'eliminar') {
        Producto::eliminar($pdo, (int)$_POST['id']);
        echo json_encode(['ok' => true]);
        exit();
    }

    // necesito el slug de la sección para guardar la foto en la carpeta correcta
    $idSeccion = (int)$_POST['id_seccion'];
    $stmt = $pdo->prepare("SELECT slug FROM secciones WHERE id = ? LIMIT 1");
    $stmt->execute([$idSeccion]);
    $slug = $stmt->fetchColumn();

    // si han subido una foto la muevo a la carpeta de imágenes
    $foto = $_POST['foto_actual'] ?? null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $nombreFoto = uniqid() . '.' . $extension;
        $destino = __DIR__ . '/../../public/img/productos/' . $slug . '/' . $nombreFoto;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $foto = $nombreFoto;
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

    if ($accion === 'crear') {
        Producto::crear($pdo, $datos);
        echo json_encode(['ok' => true]);
    } else if ($accion === 'actualizar') {
        Producto::actualizar($pdo, (int)$_POST['id'], $datos);
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['error' => 'Acción no reconocida']);
    }

    exit();
}

echo json_encode(['error' => 'Método no permitido']);

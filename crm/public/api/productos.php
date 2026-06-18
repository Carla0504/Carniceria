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

    if ($accion === 'actualizar_stock') {
        $stock = max(0, (float)($_POST['stock'] ?? 0));
        Producto::actualizarStock($pdo, (int)$_POST['id'], $stock);
        echo json_encode(['ok' => true]);
        exit();
    }

    if ($accion === 'recargar_lote') {
        $items = json_decode($_POST['items'] ?? '[]', true);
        if (!is_array($items)) {
            echo json_encode(['error' => 'Datos inválidos']);
            exit();
        }
        $nuevos_stocks = [];
        $actualizados = 0;
        $stmtUp = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $stmtSel = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
        foreach ($items as $item) {
            $id = (int)($item['id'] ?? 0);
            $cantidad = (float)($item['cantidad'] ?? 0);
            if ($id > 0 && $cantidad > 0) {
                $stmtUp->execute([$cantidad, $id]);
                $stmtSel->execute([$id]);
                $nuevoStock = (float)$stmtSel->fetchColumn();
                $nuevos_stocks[] = ['id' => $id, 'stock' => number_format($nuevoStock, 3, ',', '.')];
                $actualizados++;
            }
        }
        echo json_encode(['ok' => true, 'actualizados' => $actualizados, 'nuevos_stocks' => $nuevos_stocks]);
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

    $unidadesValidas = ['kg', 'g', '100g', 'unidad', 'bandeja', 'pack', 'blister'];
    $unidad = $_POST['unidad_medida'] ?? 'unidad';
    if (!in_array($unidad, $unidadesValidas)) $unidad = 'unidad';

    $datos = [
        'id_seccion' => $idSeccion,
        'nombre' => trim($_POST['nombre']),
        'nombre_en' => trim($_POST['nombre_en'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'descripcion_en' => trim($_POST['descripcion_en'] ?? ''),
        'precio' => (float)$_POST['precio'],
        'unidad_medida' => $unidad,
        'stock' => max(0, (float)($_POST['stock'] ?? 0)),
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
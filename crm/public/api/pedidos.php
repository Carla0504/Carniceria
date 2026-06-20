<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo json_encode(['error' => 'Sin permiso']);
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/models/Pedido.php';

$id = (int)($_POST['id'] ?? 0);
$estado = $_POST['estado'] ?? '';
$motivo = trim($_POST['motivo'] ?? '');

$estadosValidos = ['en_preparacion', 'listo_recogida', 'entregado', 'denegado'];

if ($id <= 0 || !in_array($estado, $estadosValidos)) {
    echo json_encode(['error' => 'Datos invalidos']);
    exit();
}

if ($estado === 'denegado' && $motivo === '') {
    echo json_encode(['error' => 'Indica el motivo del rechazo']);
    exit();
}

$pedido = Pedido::porId($pdo, $id);
if (!$pedido) {
    echo json_encode(['error' => 'Pedido no encontrado']);
    exit();
}

if ($estado === 'denegado') {
    Pedido::restaurarStock($pdo, $id);
}

Pedido::cambiarEstado($pdo, $id, $estado, $estado === 'denegado' ? $motivo : null);

echo json_encode(['ok' => true, 'estado' => $estado]);

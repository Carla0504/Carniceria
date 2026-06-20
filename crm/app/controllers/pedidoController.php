<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /Carniceria/crm/app/views/auth/login.php');
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Carniceria/crm/config/db.php';
require_once __DIR__ . '/../models/CarritoItem.php';
require_once __DIR__ . '/../models/Pedido.php';

$idUsuario = (int)$_SESSION['user']['id'];
$items = CarritoItem::porUsuario($pdo, $idUsuario);

if (empty($items)) {
    header('Location: /Carniceria/crm/app/views/carrito/index.php?error=vacio');
    exit();
}

// comprobar stock antes de crear nada
foreach ($items as $item) {
    $stmt = $pdo->prepare("SELECT stock, nombre FROM productos WHERE id = ?");
    $stmt->execute([$item['id_producto']]);
    $producto = $stmt->fetch();
    if ((float)$producto['stock'] < (float)$item['cantidad']) {
        $nombre = urlencode($producto['nombre']);
        header("Location: /Carniceria/crm/app/views/carrito/index.php?error=stock&producto=$nombre");
        exit();
    }
}

$total = 0;
foreach ($items as $item) {
    $total += $item['precio_efectivo'] * $item['cantidad'];
}

try {
    $idPedido = Pedido::crear($pdo, $idUsuario, $items, round($total, 2));
    CarritoItem::vaciar($pdo, $idUsuario);
    header('Location: /Carniceria/crm/app/views/pedidos/mis_pedidos.php?ok=' . $idPedido);
} catch (Exception $e) {
    error_log('Error al crear pedido: ' . $e->getMessage());
    header('Location: /Carniceria/crm/app/views/carrito/index.php?error=pedido');
}
exit();
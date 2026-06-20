<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /Carniceria/crm/app/views/auth/login.php');
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Carniceria/crm/config/db.php';
require_once __DIR__ . '/../models/CarritoItem.php';
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../helpers/Mailer.php';

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

    $cfg = require __DIR__ . '/../../config/mail.php';
    $nombreCliente = htmlspecialchars($_SESSION['user']['nombre']);
    $emailCliente = $_SESSION['user']['email'];

    // construyo la tabla de productos para los emails
    $filasHtml = '';
    foreach ($items as $item) {
        $nombre = htmlspecialchars($item['nombre']);
        if ($item['unidad_medida'] === 'kg') {
            $cant = number_format($item['cantidad'], 2, ',', '.') . ' kg';
        } else {
            $cant = (int)$item['cantidad'] . ' ' . $item['unidad_medida'];
        }
        $sub = number_format($item['precio_efectivo'] * $item['cantidad'], 2, ',', '.') . ' €';
        $filasHtml .= "<tr>
            <td style='padding:6px 10px'>$nombre</td>
            <td style='padding:6px 10px'>$cant</td>
            <td style='padding:6px 10px'>$sub</td>
        </tr>";
    }
    $totalFmt = number_format($total, 2, ',', '.') . ' €';

    $tabla = "<table style='border-collapse:collapse;width:100%'>
        <tr style='background:#1e1a14;color:#e8c07a'>
            <th style='padding:8px 10px;text-align:left'>Producto</th>
            <th style='padding:8px 10px;text-align:left'>Cantidad</th>
            <th style='padding:8px 10px;text-align:left'>Subtotal</th>
        </tr>
        $filasHtml
        <tr>
            <td colspan='2' style='padding:8px 10px;font-weight:bold'>Total</td>
            <td style='padding:8px 10px;font-weight:bold'>$totalFmt</td>
        </tr>
    </table>";

    // email al admin avisando del nuevo pedido
    Mailer::enviar(
        $cfg['admin_email'],
        "Nuevo pedido #$idPedido de $nombreCliente",
        "<h2 style='color:#9a2b0c'>Nuevo pedido #$idPedido</h2>
         <p><strong>Cliente:</strong> $nombreCliente ($emailCliente)</p>
         $tabla"
    );

    // email de confirmacion al cliente
    Mailer::enviar(
        $emailCliente,
        "Pedido #$idPedido recibido - La Dehesa",
        "<h2 style='color:#9a2b0c'>Pedido recibido - La Dehesa</h2>
         <p>Hola, <strong>$nombreCliente</strong>. Hemos recibido tu pedido correctamente.</p>
         <p><strong>Numero de pedido:</strong> #$idPedido</p>
         $tabla
         <p style='margin-top:16px'>Te avisaremos por correo cuando tu pedido este en preparacion.</p>
         <p>Un saludo,<br><strong>La Dehesa</strong></p>"
    );

    header('Location: /Carniceria/crm/app/views/pedidos/mis_pedidos.php?ok=' . $idPedido);
} catch (Exception $e) {
    error_log('Error al crear pedido: ' . $e->getMessage());
    header('Location: /Carniceria/crm/app/views/carrito/index.php?error=pedido');
}
exit();
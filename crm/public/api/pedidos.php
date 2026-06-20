<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    echo json_encode(['error' => 'Sin permiso']);
    exit();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../app/models/Pedido.php';
require_once __DIR__ . '/../../app/helpers/Mailer.php';

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

// email al cliente notificando el cambio de estado
$nombreCliente = htmlspecialchars($pedido['cliente_nombre']);
$emailCliente = $pedido['cliente_email'];

if ($estado === 'denegado') {
    Mailer::enviar(
        $emailCliente,
        "Pedido #$id - No procesado",
        "<h2 style='color:#9a2b0c'>Pedido #$id - La Dehesa</h2>
         <p>Hola, <strong>$nombreCliente</strong>.</p>
         <p>Lo sentimos, tu pedido <strong>#$id</strong> no ha podido procesarse.</p>
         <p><strong>Motivo:</strong> " . htmlspecialchars($motivo) . "</p>
         <p>Si tienes dudas puedes contactarnos.<br>Un saludo,<br><strong>La Dehesa</strong></p>"
    );
} elseif ($estado === 'en_preparacion') {
    Mailer::enviar(
        $emailCliente,
        "Pedido #$id - En preparacion",
        "<h2 style='color:#9a2b0c'>Pedido #$id - La Dehesa</h2>
         <p>Hola, <strong>$nombreCliente</strong>.</p>
         <p>Tu pedido <strong>#$id</strong> ya esta <strong>en preparacion</strong>.</p>
         <p>Un saludo,<br><strong>La Dehesa</strong></p>"
    );
} elseif ($estado === 'listo_recogida') {
    Mailer::enviar(
        $emailCliente,
        "Pedido #$id - Listo para recoger",
        "<h2 style='color:#9a2b0c'>Pedido #$id - La Dehesa</h2>
         <p>Hola, <strong>$nombreCliente</strong>.</p>
         <p>Tu pedido <strong>#$id</strong> esta <strong>listo para recoger en tienda</strong>.</p>
         <p>Un saludo,<br><strong>La Dehesa</strong></p>"
    );
} elseif ($estado === 'entregado') {
    Mailer::enviar(
        $emailCliente,
        "Pedido #$id - Entregado",
        "<h2 style='color:#9a2b0c'>Pedido #$id - La Dehesa</h2>
         <p>Hola, <strong>$nombreCliente</strong>.</p>
         <p>Tu pedido <strong>#$id</strong> ha sido <strong>entregado</strong>. Gracias por tu compra.</p>
         <p>Un saludo,<br><strong>La Dehesa</strong></p>"
    );
}

echo json_encode(['ok' => true, 'estado' => $estado]);

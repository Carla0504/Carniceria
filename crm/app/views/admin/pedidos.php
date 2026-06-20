<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    header('Location: /Carniceria/crm/app/views/auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Pedido.php';

$pedidos = Pedido::todos($pdo);

foreach ($pedidos as &$p) {
    $p['items'] = Pedido::items($pdo, $p['id']);
}
unset($p);

$titulo = 'La Dehesa — Pedidos';
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/admin.css">
<link rel="stylesheet" href="/Carniceria/crm/public/css/pedidos.css">

<div class="admin-pedidos">
    <div class="admin-lote-header">
        <h1>Pedidos</h1>
    </div>

    <?php if (empty($pedidos)): ?>
        <p class="mensajes-vacio">No hay pedidos todavia.</p>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido-card" id="pedido-<?= $pedido['id'] ?>">
            <div class="pedido-header">
                <div>
                    <span class="pedido-num">Pedido #<?= $pedido['id'] ?></span>
                    <span class="pedido-fecha"><?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></span>
                    <span class="pedido-cliente">
                        <?= htmlspecialchars($pedido['cliente_nombre']) ?>
                        - <a href="mailto:<?= htmlspecialchars($pedido['cliente_email']) ?>">
                            <?= htmlspecialchars($pedido['cliente_email']) ?>
                        </a>
                    </span>
                </div>
                <span class="pedido-estado estado-<?= $pedido['estado'] ?>">
                    <?php
                    $etiquetas = [
                        'pendiente'      => 'Pendiente',
                        'en_preparacion' => 'En preparacion',
                        'listo_recogida' => 'Listo para recoger',
                        'entregado'      => 'Entregado',
                        'denegado'       => 'No procesado',
                    ];
                    echo $etiquetas[$pedido['estado']] ?? $pedido['estado'];
                    ?>
                </span>
            </div>

            <table class="pedido-items">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedido['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                        <td>
                            <?= $item['unidad_medida'] === 'kg'
                                ? number_format($item['cantidad'], 2, ',', '.') . ' kg'
                                : (int)$item['cantidad'] . ' ' . $item['unidad_medida'] ?>
                        </td>
                        <td><?= number_format($item['subtotal'], 2, ',', '.') ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong><?= number_format($pedido['total'], 2, ',', '.') ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>

            <?php if ($pedido['estado'] === 'denegado' && $pedido['motivo_denegacion']): ?>
                <p class="pedido-motivo">Motivo: <?= htmlspecialchars($pedido['motivo_denegacion']) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

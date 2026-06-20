<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /Carniceria/crm/app/views/auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Pedido.php';

$idUsuario = (int)$_SESSION['user']['id'];
$pedidos = Pedido::porUsuario($pdo, $idUsuario);

foreach ($pedidos as &$p) {
    $p['items'] = Pedido::items($pdo, $p['id']);
}
unset($p);

$titulo = 'La Dehesa — Mis pedidos';
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/pedidos.css">

<div class="pedidos-page">
    <h1>Mis pedidos</h1>

    <?php if (isset($_GET['ok'])): ?>
        <div class="pedido-confirmado">
            Pedido #<?= (int)$_GET['ok'] ?> realizado correctamente. En cuanto lo gestionemos te avisamos.
        </div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <div class="pedidos-vacio">
            <p>Todavia no has realizado ningun pedido.</p>
            <a href="/Carniceria/crm/app/views/catalogo/carniceria.php" class="btn-ver-catalogo">Ver catalogo</a>
        </div>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido-card">
            <div class="pedido-header">
                <div>
                    <span class="pedido-num">Pedido #<?= $pedido['id'] ?></span>
                    <span class="pedido-fecha"><?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></span>
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

            <?php if ($pedido['estado'] === 'denegado' && $pedido['motivo_denegacion']): ?>
                <p class="pedido-motivo">Motivo: <?= htmlspecialchars($pedido['motivo_denegacion']) ?></p>
            <?php endif; ?>

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
                            <?= $item['unidad_medida'] === 'kg' ? number_format($item['cantidad'], 2, ',', '.') . ' kg' 
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
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

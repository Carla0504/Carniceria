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
    <h1><?= $t['pedidos_titulo'] ?></h1>

    <?php if (isset($_GET['ok'])): ?>
        <div class="pedido-confirmado">
            <?= sprintf($t['pedidos_confirmado'], (int)$_GET['ok']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <div class="pedidos-vacio">
            <p><?= $t['pedidos_vacio'] ?></p>
            <a href="/Carniceria/crm/app/views/catalogo/carniceria.php" class="btn-ver-catalogo"><?= $t['pedidos_ver_catalogo'] ?></a>
        </div>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido-card">
            <div class="pedido-header">
                <div>
                    <span class="pedido-num"><?= $t['pedidos_num'] ?><?= $pedido['id'] ?></span>
                    <span class="pedido-fecha"><?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></span>
                </div>
                <span class="pedido-estado estado-<?= $pedido['estado'] ?>">
                    <?php
                    $etiquetas = [
                        'pendiente'      => $t['estado_pendiente'],
                        'en_preparacion' => $t['estado_en_preparacion'],
                        'listo_recogida' => $t['estado_listo_recogida'],
                        'entregado'      => $t['estado_entregado'],
                        'denegado'       => $t['estado_denegado'],
                    ];
                    echo $etiquetas[$pedido['estado']] ?? $pedido['estado'];
                    ?>
                </span>
            </div>

            <?php if ($pedido['estado'] === 'denegado' && $pedido['motivo_denegacion']): ?>
                <p class="pedido-motivo"><?= $t['pedidos_motivo'] ?> <?= htmlspecialchars($pedido['motivo_denegacion']) ?></p>
            <?php endif; ?>

            <table class="pedido-items">
                <thead>
                    <tr>
                        <th><?= $t['pedidos_col_producto'] ?></th>
                        <th><?= $t['pedidos_col_cantidad'] ?></th>
                        <th><?= $t['pedidos_col_subtotal'] ?></th>
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
                        <td colspan="2"><strong><?= $t['pedidos_col_total'] ?></strong></td>
                        <td><strong><?= number_format($pedido['total'], 2, ',', '.') ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

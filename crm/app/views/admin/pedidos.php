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
                <span class="pedido-estado estado-<?= $pedido['estado'] ?>" id="badge-<?= $pedido['id'] ?>">
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

            <?php if (!in_array($pedido['estado'], ['entregado', 'denegado'])): ?>
            <div class="pedido-acciones" id="acciones-<?= $pedido['id'] ?>">
                <?php if ($pedido['estado'] === 'pendiente'): ?>
                    <button class="btn-estado" onclick="cambiarEstado(<?= $pedido['id'] ?>, 'en_preparacion', this)">
                        Aceptar - En preparacion
                    </button>
                    <button class="btn-estado btn-denegar" onclick="abrirDenegar(<?= $pedido['id'] ?>)">
                        Denegar pedido
                    </button>
                <?php elseif ($pedido['estado'] === 'en_preparacion'): ?>
                    <button class="btn-estado" onclick="cambiarEstado(<?= $pedido['id'] ?>, 'listo_recogida', this)">
                        Marcar listo para recoger
                    </button>
                <?php elseif ($pedido['estado'] === 'listo_recogida'): ?>
                    <button class="btn-estado" onclick="cambiarEstado(<?= $pedido['id'] ?>, 'entregado', this)">
                        Marcar entregado
                    </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal para denegar pedido -->
<div id="modal-denegar" class="modal-overlay" hidden>
    <div class="modal">
        <h2>Denegar pedido</h2>
        <p>Indica el motivo para que el cliente lo reciba por correo:</p>
        <textarea id="motivo-texto" rows="4" placeholder="Ej: Stock insuficiente para completar el pedido."></textarea>
        <div class="modal-acciones">
            <button class="btn-admin-add" onclick="confirmarDenegar()">Confirmar denegacion</button>
            <button class="btn btn-secondary" onclick="cerrarDenegar()">Cancelar</button>
        </div>
        <p id="denegar-error" class="lote-feedback lote-error" hidden></p>
    </div>
</div>

<script>
// guardo el id del pedido que se esta denegando para usarlo al confirmar
let pedidoDenegarId = null;

function cambiarEstado(id, estado, btn) {
    btn.disabled = true;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('estado', estado);

    fetch('/Carniceria/crm/public/api/pedidos.php', {
        method: 'POST',
        body: formData
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        if (datos.ok) {
            actualizarBadge(id, estado);
            document.getElementById('acciones-' + id).remove();
        } else {
            alert(datos.error || 'Error al cambiar el estado');
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert('Error de red, intentalo de nuevo');
        btn.disabled = false;
    });
}

function abrirDenegar(id) {
    pedidoDenegarId = id;
    document.getElementById('motivo-texto').value = '';
    document.getElementById('denegar-error').hidden = true;
    document.getElementById('modal-denegar').hidden = false;
}

function cerrarDenegar() {
    document.getElementById('modal-denegar').hidden = true;
    pedidoDenegarId = null;
}

function confirmarDenegar() {
    const motivo = document.getElementById('motivo-texto').value.trim();
    const errorEl = document.getElementById('denegar-error');

    if (motivo === '') {
        errorEl.textContent = 'Tienes que escribir el motivo antes de confirmar.';
        errorEl.hidden = false;
        return;
    }

    const formData = new FormData();
    formData.append('id', pedidoDenegarId);
    formData.append('estado', 'denegado');
    formData.append('motivo', motivo);

    fetch('/Carniceria/crm/public/api/pedidos.php', {
        method: 'POST',
        body: formData
    })
    .then(respuesta => respuesta.json())
    .then(datos => {
        if (datos.ok) {
            actualizarBadge(pedidoDenegarId, 'denegado');
            document.getElementById('acciones-' + pedidoDenegarId).remove();
            cerrarDenegar();
        } else {
            errorEl.textContent = datos.error || 'Ha habido un error';
            errorEl.hidden = false;
        }
    });
}

// texto que se muestra en el badge segun el estado
const textoEstado = {
    en_preparacion: 'En preparacion',
    listo_recogida: 'Listo para recoger',
    entregado: 'Entregado',
    denegado: 'No procesado'
};

function actualizarBadge(id, estado) {
    const badge = document.getElementById('badge-' + id);
    badge.textContent = textoEstado[estado];
    badge.className = 'pedido-estado estado-' + estado;
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

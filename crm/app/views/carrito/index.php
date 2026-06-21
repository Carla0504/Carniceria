<?php
session_start();

// si no hay sesión activa mando al login
if (!isset($_SESSION['user'])) {
    header("Location: /Carniceria/crm/app/views/auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/CarritoItem.php';

$idUsuario = (int) $_SESSION['user']['id'];

// recojo los productos del carrito del usuario
$productos = CarritoItem::porUsuario($pdo, $idUsuario);

// calculo el total sumando precio * cantidad de cada producto
$total = 0;
foreach ($productos as $p) {
    $total += $p['precio_efectivo'] * $p['cantidad'];
}

$titulo = 'La Dehesa — Carrito';
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/carrito.css">

<div class="carrito-page">
    <h1><?= $t['carrito_titulo'] ?></h1>
    <p class="carrito-nota"><?= $t['carrito_nota'] ?></p>

    <?php if (isset($_GET['pedido_ok'])): ?>
        <div class="carrito-exito">
            <?= sprintf($t['carrito_pedido_ok'], (int)$_GET['pedido_ok']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($productos)): ?>

        <div class="carrito-vacio">
            <p><?= $t['carrito_vacio'] ?></p>
            <a href="/Carniceria/crm/app/views/catalogo/carniceria.php" class="btn-ver-catalogo"><?= $t['carrito_ver_catalogo'] ?></a>
        </div>

    <?php else: ?>

        <ul class="carrito-lista" id="carrito-lista">
            <?php
            // calcula el incremento según la unidad: kg=0.25, g=100, 100g=1, unidad/bandeja=1
            function incrementoPorUnidad($u) {
                return match($u) { 'kg' => 0.25, 'g' => 100, default => 1 };
            }
            function labelUnidad($u) {
                return match($u) { 'kg' => 'kg', 'g' => 'g', '100g' => '×100g', 'bandeja' => 'bandeja', default => 'ud' };
            }
            foreach ($productos as $p):
                $unidad = $p['unidad_medida'] ?? 'unidad';
                $paso = incrementoPorUnidad($unidad);
                $label = labelUnidad($unidad);
            ?>
                <li class="carrito-item" id="item-<?= $p['id'] ?>"
                    data-precio="<?= $p['precio_efectivo'] ?>"
                    data-id-producto="<?= $p['id_producto'] ?>"
                    data-paso="<?= $paso ?>">

                    <div class="carrito-item-info">
                        <span class="carrito-item-nombre"><?= htmlspecialchars($p['nombre']) ?></span>
                        <span class="carrito-item-seccion"><?= htmlspecialchars($p['seccion_nombre']) ?></span>
                    </div>

                    <div class="carrito-item-precio">
                        <?= number_format($p['precio_efectivo'], 2, ',', '.') ?> €/<?= $label ?>
                    </div>

                    <div class="carrito-item-cantidad">
                        <span id="cant-<?= $p['id'] ?>"><?= $p['unidad_medida'] === 'kg' ? number_format($p['cantidad'], 2, ',', '.') : (int)$p['cantidad'] ?></span> <?= $label ?>
                    </div>

                    <div class="carrito-item-subtotal" id="sub-<?= $p['id'] ?>">
                        <?= number_format($p['precio_efectivo'] * $p['cantidad'], 2, ',', '.') ?> €
                    </div>

                    <div class="carrito-item-acciones">
                        <button class="btn-menos" id="menos-<?= $p['id'] ?>"
                                onclick="quitarUno(<?= $p['id'] ?>)"
                                <?php if ($p['cantidad'] <= $paso) echo 'style="display:none"'; ?>>
                            -<?= $paso ?> <?= $label ?>
                        </button>
                        <button class="btn-mas" onclick="agregarMas(<?= $p['id'] ?>, <?= $p['id_producto'] ?>, <?= $paso ?>)">
                            +<?= $paso ?> <?= $label ?>
                        </button>
                        <button class="btn-eliminar" onclick="eliminarItem(<?= $p['id'] ?>)">
                            <?= $t['carrito_eliminar'] ?>
                        </button>
                    </div>

                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (isset($_GET['error'])): ?>
            <p class="carrito-error">
                <?php if ($_GET['error'] === 'stock'): ?>
                    <?= sprintf($t['carrito_error_stock'], htmlspecialchars($_GET['producto'] ?? '')) ?>
                <?php elseif ($_GET['error'] === 'vacio'): ?>
                    <?= $t['carrito_error_vacio'] ?>
                <?php else: ?>
                    <?= $t['carrito_error_generico'] ?>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <div class="carrito-total-bar">
            <button class="btn-vaciar" onclick="vaciarCarrito()"><?= $t['carrito_vaciar'] ?></button>
            <div class="carrito-total">
                <?= $t['carrito_total'] ?>: <strong id="total-valor"><?= number_format($total, 2, ',', '.') ?> €</strong>
            </div>
            <form action="/Carniceria/crm/app/controllers/pedidoController.php" method="POST">
                <button type="submit" class="btn-confirmar-pedido"><?= $t['carrito_confirmar_pedido'] ?></button>
            </form>
        </div>

    <?php endif; ?>
</div>

<script src="/Carniceria/crm/public/js/carrito-page.js"></script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
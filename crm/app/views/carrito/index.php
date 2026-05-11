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
    <h1>Tu carrito</h1>
    <p class="carrito-nota">Los precios son orientativos. Pasa por nuestra tienda para formalizar tu compra.</p>

    <?php if (empty($productos)): ?>

        <div class="carrito-vacio">
            <p>No tienes ningún producto en el carrito.</p>
            <a href="/Carniceria/crm/app/views/catalogo/carniceria.php" class="btn-ver-catalogo">Ver catálogo</a>
        </div>

    <?php else: ?>

        <ul class="carrito-lista" id="carrito-lista">
            <?php foreach ($productos as $p): ?>
                <li class="carrito-item" id="item-<?= $p['id'] ?>"
                    data-precio="<?= $p['precio_efectivo'] ?>"
                    data-id-producto="<?= $p['id_producto'] ?>">

                    <div class="carrito-item-info">
                        <span class="carrito-item-nombre"><?= htmlspecialchars($p['nombre']) ?></span>
                        <span class="carrito-item-seccion"><?= htmlspecialchars($p['seccion_nombre']) ?></span>
                    </div>

                    <div class="carrito-item-precio">
                        <?= number_format($p['precio_efectivo'], 2, ',', '.') ?> €/kg
                    </div>

                    <div class="carrito-item-cantidad">
                        <span id="cant-<?= $p['id'] ?>"><?= $p['cantidad'] ?></span> kg
                    </div>

                    <div class="carrito-item-subtotal" id="sub-<?= $p['id'] ?>">
                        <?= number_format($p['precio_efectivo'] * $p['cantidad'], 2, ',', '.') ?> €
                    </div>

                    <div class="carrito-item-acciones">
                        <?php // el botón de quitar solo aparece si hay más de 1 kg ?>
                        <button class="btn-menos" id="menos-<?= $p['id'] ?>"
                                onclick="quitarUno(<?= $p['id'] ?>)"
                                <?php if ($p['cantidad'] <= 1) echo 'style="display:none"'; ?>>
                            -1 kg
                        </button>
                        <button class="btn-mas" onclick="agregarMas(<?= $p['id'] ?>, <?= $p['id_producto'] ?>)">
                            +1 kg
                        </button>
                        <button class="btn-eliminar" onclick="eliminarItem(<?= $p['id'] ?>)">
                            Eliminar
                        </button>
                    </div>

                </li>
            <?php endforeach; ?>
        </ul>

        <div class="carrito-total-bar">
            <button class="btn-vaciar" onclick="vaciarCarrito()">Vaciar carrito</button>
            <div class="carrito-total">
                Total estimado: <strong id="total-valor"><?= number_format($total, 2, ',', '.') ?> €</strong>
            </div>
        </div>

    <?php endif; ?>
</div>

<script src="/Carniceria/crm/public/js/carrito-page.js"></script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

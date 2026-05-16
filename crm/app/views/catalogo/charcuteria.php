<?php
session_start();


require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Producto.php';

$slug = 'charcuteria';
$seccion = Producto::seccion($pdo, $slug);
$productos = Producto::porSeccion($pdo, $seccion['id']);

$titulo = 'La Dehesa — Charcutería';
require __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/Carniceria/crm/public/css/catalogo.css">

<div class="catalogo-header">
    <p class="secciones-tag"><?= htmlspecialchars($seccion['descripcion']) ?></p>
    <h1>Charcutería</h1>
</div>

<?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
<div class="admin-toolbar">
    <button class="btn-admin-add" onclick="abrirModalCrear(<?= $seccion['id'] ?>)">+ Añadir producto</button>
</div>
<?php endif; ?>

<div class="catalogo-grid">
    <?php foreach ($productos as $p): ?>
        <?php $enPromo = $p['precio_promocional'] != null; ?>
        <div class="producto-card" id="card-<?= $p['id'] ?>">
            <div class="producto-foto">
                <?php if ($p['foto']): ?>
                    <img src="/Carniceria/crm/public/img/productos/charcuteria/<?= htmlspecialchars($p['foto']) ?>"
                         alt="<?= htmlspecialchars($p['nombre']) ?>">
                <?php endif; ?>
                <?php if ($enPromo): ?>
                    <span class="badge-oferta"><?= $t['badge_oferta'] ?></span>
                <?php endif; ?>
            </div>
            <div class="producto-info">
                <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                <p><?= htmlspecialchars($p['descripcion']) ?></p>
                <div class="producto-precio">
                    <?php if ($enPromo): ?>
                        <span class="precio-original"><?= number_format($p['precio'], 2, ',', '.') ?> €</span>
                        <span class="precio-actual"><?= number_format($p['precio_promocional'], 2, ',', '.') ?> €</span>
                    <?php else: ?>
                        <span class="precio-actual"><?= number_format($p['precio'], 2, ',', '.') ?> €</span>
                    <?php endif; ?>
                    <span class="precio-kg">/kg</span>
                </div>
                <?php if (isset($_SESSION['user'])): ?>
                <button class="btn-carrito"
                        data-id="<?= $p['id'] ?>"
                        data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>">
                    <?= $t['anadir_carrito'] ?>
                </button>
                <?php else: ?>
                <a href="/Carniceria/crm/app/views/auth/login.php" class="btn-carrito"><?= $t['anadir_carrito'] ?></a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <div class="admin-card-actions">
                    <button class="btn-admin-edit" onclick="abrirModalEditar(<?= $p['id'] ?>)">Editar</button>
                    <button class="btn-admin-delete"
                            onclick="eliminarProducto(<?= $p['id'] ?>, document.getElementById('card-<?= $p['id'] ?>'))">
                        Eliminar
                    </button>
                    <?php if ($p['id_promo']): ?>
                        <button class="btn-admin-promo"
                                onclick="abrirModalEditarPromo(<?= $p['id'] ?>, <?= $p['id_promo'] ?>)">Editar oferta</button>
                        <button class="btn-admin-promo-delete"
                                onclick="quitarPromo(<?= $p['id_promo'] ?>)">Quitar oferta</button>
                    <?php else: ?>
                        <button class="btn-admin-promo"
                                onclick="abrirModalCrearPromo(<?= $p['id'] ?>)">Añadir oferta</button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../admin/formulario.php'; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /Carniceria/crm/app/views/auth/login.php");
    exit();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Producto.php';

$slug = 'polleria';
$seccion = Producto::seccion($pdo, $slug);
$productos = Producto::porSeccion($pdo, $seccion['id']);

$titulo = 'La Dehesa — Pollería';
require __DIR__ . '/../layout/header.php';
echo '<link rel="stylesheet" href="/Carniceria/crm/public/css/catalogo.css">';
?>

<div class="catalogo-header">
    <p class="secciones-tag"><?= htmlspecialchars($seccion['descripcion']) ?></p>
    <h1>Pollería</h1>
</div>

<div class="catalogo-grid">
    <?php foreach ($productos as $p): ?>
        <?php $enPromo = !is_null($p['precio_promocional']); ?>
        <div class="producto-card">
            <div class="producto-foto">
                <?php if ($p['foto']): ?>
                    <img src="/Carniceria/crm/public/img/productos/polleria/<?= htmlspecialchars($p['foto']) ?>"
                         alt="<?= htmlspecialchars($p['nombre']) ?>">
                <?php endif; ?>
                <?php if ($enPromo): ?>
                    <span class="badge-oferta">Oferta</span>
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
                <button class="btn-carrito"
                        data-id="<?= $p['id'] ?>"
                        data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>">
                    Añadir al carrito
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

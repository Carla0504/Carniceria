<?php
session_start();


require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../models/Producto.php';
require_once __DIR__ . '/../../helpers/Traductor.php';

$slug = 'carniceria';
$seccion = Producto::seccion($pdo, $slug);
$productos = Producto::porSeccion($pdo, $seccion['id']);

$titulo = 'La Dehesa — Carnicería';
require __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/Carniceria/crm/public/css/catalogo.css">

<div class="catalogo-header">
    <p class="secciones-tag"><?= htmlspecialchars(($idioma === 'en' && !empty($seccion['descripcion_en'])) ? $seccion['descripcion_en'] : $seccion['descripcion']) ?></p>
    <h1><?= $idioma === 'en' ? 'Butchery' : 'Carnicería' ?></h1>
</div>

<?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
<div class="admin-toolbar">
    <button class="btn-admin-add" onclick="abrirModalCrear(<?= $seccion['id'] ?>)">+ Añadir producto</button>
</div>
<?php endif; ?>

<div class="catalogo-grid">
    <?php foreach ($productos as $p): ?>
        <?php
        $enPromo = $p['precio_promocional'] != null;

        if ($idioma === 'en' && empty($p['nombre_en'])) {
            $p['nombre_en'] = Traductor::traducir($p['nombre']);
            $p['descripcion_en'] = Traductor::traducir($p['descripcion']);
            Producto::actualizarTraduccion($pdo, $p['id'], $p['nombre_en'], $p['descripcion_en']);
        }

        $nombre_mostrar = ($idioma === 'en' && !empty($p['nombre_en'])) ? $p['nombre_en'] : $p['nombre'];
        $desc_mostrar = ($idioma === 'en' && !empty($p['descripcion_en'])) ? $p['descripcion_en'] : $p['descripcion'];
        ?>
        <div class="producto-card" id="card-<?= $p['id'] ?>">
            <div class="producto-foto">
                <?php if ($p['foto']): ?>
                    <img src="/Carniceria/crm/public/img/productos/carniceria/<?= htmlspecialchars($p['foto']) ?>"
                         alt="<?= htmlspecialchars($nombre_mostrar) ?>">
                <?php endif; ?>
                <?php if ($enPromo): ?>
                    <span class="badge-oferta"><?= $t['badge_oferta'] ?></span>
                <?php endif; ?>
            </div>
            <div class="producto-info">
                <h3><?= htmlspecialchars($nombre_mostrar) ?></h3>
                <p><?= htmlspecialchars($desc_mostrar) ?></p>
                <div class="producto-precio">
                    <?php if ($enPromo): ?>
                        <span class="precio-original"><?= number_format($p['precio'], 2, ',', '.') ?> €</span>
                        <span class="precio-actual"><?= number_format($p['precio_promocional'], 2, ',', '.') ?> €</span>
                    <?php else: ?>
                        <span class="precio-actual"><?= number_format($p['precio'], 2, ',', '.') ?> €</span>
                    <?php endif; ?>
                    <span class="precio-kg">/<?= htmlspecialchars($p['unidad_medida'] ?? 'unidad') ?></span>
                </div>
                <?php if ((float)$p['stock'] <= 0): ?>
                    <span class="badge-agotado"><?= $t['badge_agotado'] ?></span>
                <?php elseif (isset($_SESSION['user'])): ?>
                <div class="carrito-accion">
                    <?php
                    $opciones = match($p['unidad_medida'] ?? 'unidad') {
                        'kg' => ['0.25'=>'250 g', '0.5'=>'500 g', '0.75'=>'750 g', '1'=>'1 kg', '1.5'=>'1,5 kg', '2'=>'2 kg'],
                        'g' => ['100'=>'100 g', '200'=>'200 g', '300'=>'300 g', '500'=>'500 g'],
                        '100g' => ['1'=>'×100 g', '2'=>'×200 g', '3'=>'×300 g', '5'=>'×500 g'],
                        default => ['1'=>'1 ud', '2'=>'2 ud', '3'=>'3 ud', '4'=>'4 ud', '5'=>'5 ud'],
                    };
                    ?>
                    <select class="select-cantidad">
                        <?php foreach ($opciones as $val => $label): ?>
                        <option value="<?= $val ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn-carrito"
                            data-id="<?= $p['id'] ?>"
                            data-unidad="<?= htmlspecialchars($p['unidad_medida'] ?? 'unidad') ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>">
                        <?= $t['anadir_carrito'] ?>
                    </button>
                </div>
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
                    <div class="admin-stock-inline">
                        <input type="number" class="input-stock-inline" value="<?= (float)$p['stock'] ?>" step="1" min="0">
                        <button class="btn-admin-stock" onclick="actualizarStock(<?= $p['id'] ?>, this)">Stock</button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/../admin/formulario.php'; ?>
<?php require __DIR__ . '/../layout/footer.php'; ?>

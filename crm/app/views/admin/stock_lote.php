<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    header('Location: /Carniceria/crm/app/views/auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../config/db.php';

$stmt = $pdo->query(
    "SELECT p.id, p.nombre, p.stock, p.unidad_medida, s.nombre AS seccion
     FROM productos p
     JOIN secciones s ON p.id_seccion = s.id
     ORDER BY s.id, p.nombre"
);
$productos = $stmt->fetchAll();

// Cantidades de recarga estándar por nombre de producto
$predefinidas = [
    'Filete de primera' => 50,
    'Entrecot' => 30,
    'Solomillo de ternera' => 20,
    'Cantero de cadera' => 40,
    'Chuletas de cerdo' => 35,
    'Costillas frescas' => 30,
    'Costillas adobadas oreadas' => 25,
    'Migas' => 20,
    'Lomos' => 15,
    'Chicharrones' => 10,
    'Mortadela trufada italiana' => 8,
    'Cecina de León con D.O.' => 5,
    'Jamón Reserva' => 5,
    'Jamón Serrano Segoviano' => 5,
    'Jamón Ibérico 50%' => 3,
    'Jabugo D.O. 100% Ibérico Bellota' => 2,
    'Queso con D.O.' => 8,
    'Quesos artesanales' => 10,
    'Queso especial de romero' => 5,
    'Queso especial con trufa' => 4,
    'Queso especial al pesto' => 4,
    'Gildas con cecina y queso' => 20,
    'Gadus Morhua — Bacalao Islas Faroe' => 10,
    'Mejillones cocidos' => 30,
];

// Agrupar productos por sección
$secciones = [];
foreach ($productos as $p) {
    $secciones[$p['seccion']][] = $p;
}

$titulo = 'La Dehesa — Recarga de stock';
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/admin.css">

<div class="admin-stock-lote">
    <div class="admin-lote-header">
        <h1><?= $t['admin_stock_h1'] ?></h1>
        <a href="/Carniceria/crm/app/views/admin/mensajes.php" class="btn btn-secondary">← <?= $t['nav_admin_mensajes'] ?></a>
    </div>

    <p class="admin-lote-desc"><?= $t['admin_stock_desc'] ?></p>

    <div id="lote-feedback" class="lote-feedback" hidden></div>

    <form id="form-lote">
        <?php foreach ($secciones as $nombreSeccion => $prods): ?>
        <div class="lote-seccion">
            <div class="lote-seccion-header">
                <label class="lote-check-all">
                    <input type="checkbox" class="check-seccion" data-seccion="<?= htmlspecialchars($nombreSeccion) ?>">
                    <span><?= htmlspecialchars($nombreSeccion) ?></span>
                </label>
            </div>
            <table class="lote-tabla">
                <thead>
                    <tr>
                        <th></th>
                        <th><?= $t['admin_stock_col_producto'] ?></th>
                        <th><?= $t['admin_stock_col_stock_actual'] ?></th>
                        <th><?= $t['admin_stock_col_anadir'] ?></th>
                        <th><?= $t['admin_stock_col_unidad'] ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prods as $p):
                        $default = $predefinidas[$p['nombre']]
                            ?? (in_array($p['unidad_medida'], ['kg', 'g', '100g']) ? 10 : 5);
                    ?>
                    <tr data-seccion="<?= htmlspecialchars($nombreSeccion) ?>">
                        <td>
                            <input type="checkbox" class="check-producto" value="<?= $p['id'] ?>">
                        </td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td class="stock-actual" id="stock-<?= $p['id'] ?>">
                            <?= number_format((float)$p['stock'], 3, ',', '.') ?>
                        </td>
                        <td>
                            <input type="number"
                                   class="input-cantidad"
                                   value="<?= $default ?>"
                                   min="0.001"
                                   step="0.001"
                                   data-id="<?= $p['id'] ?>">
                        </td>
                        <td class="lote-unidad"><?= htmlspecialchars($p['unidad_medida']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endforeach; ?>

        <div class="lote-actions">
            <button type="button" id="btn-recargar" class="btn-admin-add" disabled>
                <?= $t['admin_stock_btn_recargar'] ?>
            </button>
            <span class="lote-seleccionados"><?= sprintf($t['admin_stock_sel_plural'], 0) ?></span>
        </div>
    </form>
</div>

<script>
const _tSel1 = <?= json_encode($t['admin_stock_sel_singular']) ?>;
const _tSelN = <?= json_encode($t['admin_stock_sel_plural']) ?>;
const _tOk1 = <?= json_encode($t['admin_stock_ok_singular']) ?>;
const _tOkN = <?= json_encode($t['admin_stock_ok_plural']) ?>;
const _tRecargando = <?= json_encode($t['admin_stock_recargando']) ?>;
const _tError = <?= json_encode($t['admin_stock_error']) ?>;
const _tBtnRecargar = <?= json_encode($t['admin_stock_btn_recargar']) ?>;

const checkboxes = document.querySelectorAll('.check-producto');
const btnRecargar = document.getElementById('btn-recargar');
const labelSel = document.querySelector('.lote-seleccionados');
const feedback = document.getElementById('lote-feedback');

function actualizarBoton() {
    const n = document.querySelectorAll('.check-producto:checked').length;
    btnRecargar.disabled = n === 0;
    labelSel.textContent = (n === 1 ? _tSel1 : _tSelN).replace('%d', n);
}

document.querySelectorAll('.check-seccion').forEach(chk => {
    chk.addEventListener('change', () => {
        document.querySelectorAll(`tr[data-seccion="${CSS.escape(chk.dataset.seccion)}"] .check-producto`).forEach(c => { c.checked = chk.checked; });
        actualizarBoton();
    });
});

checkboxes.forEach(c => c.addEventListener('change', actualizarBoton));

btnRecargar.addEventListener('click', () => {
    const items = [];
    document.querySelectorAll('.check-producto:checked').forEach(chk => {
        const id = chk.value;
        const input = document.querySelector(`.input-cantidad[data-id="${id}"]`);
        const cantidad = parseFloat(input.value);
        if (cantidad > 0) items.push({ id: parseInt(id), cantidad });
    });
    if (items.length === 0) return;

    btnRecargar.disabled = true;
    btnRecargar.textContent = _tRecargando;

    const fd = new FormData();
    fd.append('_action', 'recargar_lote');
    fd.append('items', JSON.stringify(items));

    fetch('/Carniceria/crm/public/api/productos.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                feedback.hidden = false;
                feedback.className = 'lote-feedback lote-ok';
                feedback.textContent = (data.actualizados === 1 ? _tOk1 : _tOkN).replace('%d', data.actualizados);
                // actualizar stocks visibles sin recargar la página
                data.nuevos_stocks.forEach(item => {
                    const td = document.getElementById('stock-' + item.id);
                    if (td) td.textContent = item.stock;
                });
                // desmarcar todos
                document.querySelectorAll('.check-producto, .check-seccion').forEach(c => c.checked = false);
                btnRecargar.textContent = _tBtnRecargar;
                actualizarBoton();
            } else {
                throw new Error(data.error || 'Error desconocido');
            }
        })
        .catch(err => {
            feedback.hidden = false;
            feedback.className = 'lote-feedback lote-error';
            feedback.textContent = _tError + err.message;
            btnRecargar.disabled = false;
            btnRecargar.textContent = _tBtnRecargar;
        });
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>

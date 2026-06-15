<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
    header('Location: /Carniceria/crm/app/views/auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../config/db.php';

// marcar como leído si llega por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_leido'])) {
    $id = (int) $_POST['marcar_leido'];
    $pdo->prepare("UPDATE mensajes_contacto SET leido = 1 WHERE id = ?")->execute([$id]);
    header('Location: /Carniceria/crm/app/views/admin/mensajes.php');
    exit();
}

$mensajes = $pdo->query(
    "SELECT * FROM mensajes_contacto ORDER BY leido ASC, created_at DESC"
)->fetchAll();

$titulo = 'La Dehesa — Panel admin';
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/admin.css">

<div class="admin-mensajes">
    <h1>Mensajes de contacto</h1>

    <?php if (empty($mensajes)): ?>
        <p class="mensajes-vacio">No hay mensajes todavía.</p>
    <?php else: ?>
        <table class="mensajes-tabla">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Mensaje</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $m): ?>
                <tr class="<?= $m['leido'] ? 'leido' : 'no-leido' ?>">
                    <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                    <td><?= htmlspecialchars($m['nombre']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
                    <td><?= nl2br(htmlspecialchars($m['mensaje'])) ?></td>
                    <td>
                        <?php if (!$m['leido']): ?>
                        <form method="POST">
                            <input type="hidden" name="marcar_leido" value="<?= $m['id'] ?>">
                            <button type="submit" class="btn btn-secondary">Marcar leído</button>
                        </form>
                        <?php else: ?>
                        <span class="leido-tag">Leído</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

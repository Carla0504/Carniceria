<?php
session_start();


$titulo = 'La Dehesa — Imágenes';
require __DIR__ . '/../../../app/views/layout/header.php';
?>

<link rel="stylesheet" href="/Carniceria/crm/public/css/galeria.css">

<?php
$imagenes = glob(__DIR__ . '/../../../public/img/galeria/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
?>

<div class="content">
    <h1>Galería</h1>
    <div class="galeria-grid">
        <?php foreach ($imagenes as $ruta): ?>
            <?php $nombre = basename($ruta); ?>
            <div class="galeria-item">
                <img src="/Carniceria/crm/public/img/galeria/<?= htmlspecialchars($nombre) ?>" alt="Imagen de La Dehesa">
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/../../../app/views/layout/footer.php'; ?>
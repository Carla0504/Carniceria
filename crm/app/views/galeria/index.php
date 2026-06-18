<?php
session_start();

$langActual = $_SESSION['lang'] ?? 'es';
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'en')) {
    $langActual = $_GET['lang'];
}
$titulo = 'La Dehesa — ' . ($langActual == 'en' ? 'Gallery' : 'Imágenes');
require __DIR__ . '/../../../app/views/layout/header.php';
?>

<link rel="stylesheet" href="/Carniceria/crm/public/css/galeria.css">

<?php
$imagenes = glob(__DIR__ . '/../../../public/img/galeria/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
$videos = glob(__DIR__ . '/../../../public/img/galeria/*.{mp4,webm}', GLOB_BRACE);
$archivos = array_merge($imagenes ?: [], $videos ?: []);
?>

<div class="content">
    <h1><?= $t['galeria_titulo'] ?></h1>
    <div class="galeria-grid">
        <?php foreach ($archivos as $ruta):
            $nombre = basename($ruta);
            $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));
            $src = '/Carniceria/crm/public/img/galeria/' . htmlspecialchars($nombre);
        ?>
            <div class="galeria-item">
                <?php if (in_array($ext, ['mp4', 'webm'])): ?>
                    <video src="<?= $src ?>" controls muted playsinline
                           style="width:100%;height:100%;object-fit:cover;border-radius:inherit"></video>
                <?php else: ?>
                    <img src="<?= $src ?>" alt="Imagen de La Dehesa">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/../../../app/views/layout/footer.php'; ?>
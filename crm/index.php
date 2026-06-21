<?php
session_start();
$titulo = 'La Dehesa — Inicio';
require __DIR__ . '/app/views/layout/header.php';
?>

<?php
$imagenesCarrusel = glob(__DIR__ . '/public/img/carrusel/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
?>
<section class="hero">
    <?php if (!empty($imagenesCarrusel)): ?>
    <div class="hero-bg" id="hero-bg">
        <?php foreach ($imagenesCarrusel as $i => $img): ?>
            <?php $src = '/Carniceria/crm/public/img/carrusel/' . htmlspecialchars(basename($img)); ?>
            <div class="hero-slide" style="opacity:<?= $i === 0 ? '1' : '0' ?>">
                <img src="<?= $src ?>" alt="" class="hero-slide-blur">
                <img src="<?= $src ?>" alt="" class="hero-slide-img">
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="hero-contenido">
        <p class="hero-tag"><?= $t['hero_tag'] ?></p>
        <h1><?= $t['hero_titulo'] ?></h1>
        <p class="hero-desc"><?= $t['hero_desc'] ?></p>
        <a href="#secciones" class="btn-hero"><?= $t['hero_btn'] ?></a>
    </div>
</section>

<section class="secciones" id="secciones">
  <p class="secciones-tag"><?= $t['secciones_tag'] ?></p>
  <h2><?= $t['secciones_titulo'] ?></h2>
  <div class="secciones-grid">
    <a href="/Carniceria/crm/app/views/catalogo/carniceria.php" class="seccion-card">
      <div class="seccion-icon">🥩</div>
      <h3><?= $t['nav_carniceria'] ?></h3>
      <p><?= $t['card_carniceria_desc'] ?></p>
      <span class="seccion-link"><?= $t['ver_productos'] ?></span>
    </a>
    <a href="/Carniceria/crm/app/views/catalogo/charcuteria.php" class="seccion-card">
      <div class="seccion-icon">🍖</div>
      <h3><?= $t['nav_charcuteria'] ?></h3>
      <p><?= $t['card_charcuteria_desc'] ?></p>
      <span class="seccion-link"><?= $t['ver_productos'] ?></span>
    </a>
    <a href="/Carniceria/crm/app/views/catalogo/polleria.php" class="seccion-card">
      <div class="seccion-icon">🧀</div>
      <h3><?= $t['nav_quesos'] ?></h3>
      <p><?= $t['card_quesos_desc'] ?></p>
      <span class="seccion-link"><?= $t['ver_productos'] ?></span>
    </a>
    <a href="/Carniceria/crm/app/views/catalogo/conservas.php" class="seccion-card">
      <div class="seccion-icon">🥫</div>
      <h3><?= $t['nav_conservas'] ?></h3>
      <p><?= $t['card_conservas_desc'] ?></p>
      <span class="seccion-link"><?= $t['ver_productos'] ?></span>
    </a>
  </div>
</section>

<section class="sobre-nosotros" id="sobre-nosotros">
  <div class="sobre-texto">
    <p class="secciones-tag"><?= $t['sobre_tag'] ?></p>
    <h2><?= $t['sobre_titulo'] ?></h2>
    <p><?= $t['sobre_p1'] ?></p>
    <p><?= $t['sobre_p2'] ?></p>
  </div>
  <div class="sobre-valores">
    <div class="valor-item">
      <span>🌿</span>
      <div>
        <h4><?= $t['valor1_titulo'] ?></h4>
        <p><?= $t['valor1_desc'] ?></p>
      </div>
    </div>
    <div class="valor-item">
      <span>🔪</span>
      <div>
        <h4><?= $t['valor2_titulo'] ?></h4>
        <p><?= $t['valor2_desc'] ?></p>
      </div>
    </div>
    <div class="valor-item">
      <span>🤝</span>
      <div>
        <h4><?= $t['valor3_titulo'] ?></h4>
        <p><?= $t['valor3_desc'] ?></p>
      </div>
    </div>
  </div>
</section>

<section class="localizacion" id="localizacion">
  <p class="secciones-tag"><?= $t['localizacion_tag'] ?></p>
  <h2><?= $t['localizacion_titulo'] ?></h2>
  <div class="mapa-wrap">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d189.75938423199375!2d-3.772912422630669!3d40.44981362993626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4187a7c09d9b95%3A0xca8c28eba51407b5!2sCarnicer%C3%ADa%20La%20Dehesa!5e0!3m2!1ses-419!2ses!4v1778084792982!5m2!1ses-419!2ses" width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
  <p class="localizacion-dir">📍 Calle Seis de Diciembre, Aravaca (Madrid)</p>
</section>

<?php require __DIR__ . '/app/views/layout/footer.php'; ?>

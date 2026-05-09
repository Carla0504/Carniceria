<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: /Carniceria/crm/app/views/auth/login.php");
  exit();
}

$titulo = 'La Dehesa — Inicio';
// __DIR__ devuelve la ruta absoluta del directorio donde está este fichero,
// evitando que PHP resuelva la ruta desde el directorio de trabajo de Apache
require __DIR__ . '/app/views/layout/header.php';
?>

<section class="hero">
  <p class="hero-tag">Aravaca · Madrid · Desde 1985</p>
  <h1>Producto fresco.<br>Trato de siempre.</h1>
  <p class="hero-desc">Carnicería artesanal en el corazón de Aravaca. Seleccionamos cada pieza con cuidado para ofrecerte la mejor calidad del mercado.</p>
  <a href="#secciones" class="btn-hero">Ver nuestros productos</a>
</section>

<section class="secciones" id="secciones">
  <p class="secciones-tag">Nuestras secciones</p>
  <h2>Lo que encontrarás</h2>
  <div class="secciones-grid">
    <a href="#" class="seccion-card">
      <div class="seccion-icon">🥩</div>
      <h3>Carnicería</h3>
      <p>Ternera, cerdo y cordero de primera calidad, cortado al momento.</p>
      <span class="seccion-link">Ver productos →</span>
    </a>
    <a href="#" class="seccion-card">
      <div class="seccion-icon">🍖</div>
      <h3>Charcutería</h3>
      <p>Jamones ibéricos, embutidos y productos curados artesanos.</p>
      <span class="seccion-link">Ver productos →</span>
    </a>
    <a href="#" class="seccion-card">
      <div class="seccion-icon">🍗</div>
      <h3>Pollería</h3>
      <p>Pollo, pavo y otras aves frescas de granja, criadas en libertad.</p>
      <span class="seccion-link">Ver productos →</span>
    </a>
    <a href="#" class="seccion-card">
      <div class="seccion-icon">🥫</div>
      <h3>Conservas</h3>
      <p>Patés, embutidos en conserva y productos tradicionales de calidad.</p>
      <span class="seccion-link">Ver productos →</span>
    </a>
  </div>
</section>

<section class="sobre-nosotros" id="sobre-nosotros">
  <div class="sobre-texto">
    <p class="secciones-tag">Sobre nosotros</p>
    <h2>Una carnicería con historia</h2>
    <p>Desde 1985 llevamos sirviendo a los vecinos de Aravaca con el mismo compromiso: producto fresco, trato cercano y calidad que se nota en cada pieza.</p>
    <p>Trabajamos con proveedores locales de confianza y seleccionamos cada animal con cuidado para garantizar la mejor carne en tu mesa.</p>
  </div>
  <div class="sobre-valores">
    <div class="valor-item">
      <span>🌿</span>
      <div>
        <h4>Producto local</h4>
        <p>Trabajamos con ganaderos de la región para ofrecerte carne de proximidad.</p>
      </div>
    </div>
    <div class="valor-item">
      <span>🔪</span>
      <div>
        <h4>Corte artesanal</h4>
        <p>Cada pieza se prepara al momento según tus necesidades.</p>
      </div>
    </div>
    <div class="valor-item">
      <span>🤝</span>
      <div>
        <h4>Trato personal</h4>
        <p>Te asesoramos en cada compra para que siempre elijas lo mejor.</p>
      </div>
    </div>
  </div>
</section>

<section class="localizacion" id="localizacion">
  <p class="secciones-tag">Localización</p>
  <h2>Encuéntranos</h2>
  <div class="mapa-wrap">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d189.75938423199375!2d-3.772912422630669!3d40.44981362993626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4187a7c09d9b95%3A0xca8c28eba51407b5!2sCarnicer%C3%ADa%20La%20Dehesa!5e0!3m2!1ses-419!2ses!4v1778084792982!5m2!1ses-419!2ses" width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
  <p class="localizacion-dir">📍 Calle Seis de Diciembre, Aravaca (Madrid)</p>
</section>

<?php require __DIR__ . '/app/views/layout/footer.php'; ?>
<?php
header('Content-Type: text/html; charset=utf-8');
// actualizo el idioma en sesión si viene por URL
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
  $_SESSION['lang'] = $_GET['lang'];
}

$idioma = $_SESSION['lang'] ?? 'es';
$t = require __DIR__ . '/../../../lang/' . $idioma . '.php';
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulo ?? 'La Dehesa' ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/Carniceria/crm/public/css/index.css">
</head>
<body>

<nav class="navbar">
  <div class="logo">La Dehesa</div>

  <button class="menu-toggle" aria-label="Menú">
    <span></span><span></span><span></span>
  </button>

  <ul class="menu">
    <li>
      <a href="/Carniceria/crm/index.php"><?= $t['nav_inicio'] ?></a>
      <ul class="submenu">
        <li><a href="/Carniceria/crm/index.php#sobre-nosotros"><?= $t['sobre_tag'] ?></a></li>
        <li><a href="/Carniceria/crm/index.php#localizacion"><?= $t['localizacion_tag'] ?></a></li>
      </ul>
    </li>
    <li><a href="/Carniceria/crm/app/views/catalogo/carniceria.php"><?= $t['nav_carniceria'] ?></a></li>
    <li><a href="/Carniceria/crm/app/views/catalogo/charcuteria.php"><?= $t['nav_charcuteria'] ?></a></li>
    <li><a href="/Carniceria/crm/app/views/catalogo/polleria.php"><?= $t['nav_quesos'] ?></a></li>
    <li><a href="/Carniceria/crm/app/views/catalogo/conservas.php"><?= $t['nav_conservas'] ?></a></li>
    <li><a href="/Carniceria/crm/app/views/galeria/index.php"><?= $t['nav_imagenes'] ?></a></li>
    <li><a href="/Carniceria/crm/app/views/contacto/index.php"><?= $t['nav_contacto'] ?></a></li>

    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="/Carniceria/crm/app/views/carrito/index.php">🛒 <span id="cart-count"></span></a></li>

      <?php if ($_SESSION['user']['rol'] !== 'admin'): ?>
      <li><a href="/Carniceria/crm/app/views/pedidos/mis_pedidos.php"><?= $t['nav_mis_pedidos'] ?></a></li>
      <?php endif; ?>

      <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
      <li>
        <a href="/Carniceria/crm/app/views/admin/pedidos.php"><?= $t['nav_panel_admin'] ?></a>
        <ul class="submenu">
          <li><a href="/Carniceria/crm/app/views/admin/pedidos.php"><?= $t['nav_admin_pedidos'] ?></a></li>
          <li><a href="/Carniceria/crm/app/views/admin/mensajes.php"><?= $t['nav_admin_mensajes'] ?></a></li>
          <li><a href="/Carniceria/crm/app/views/admin/stock_lote.php"><?= $t['nav_admin_stock'] ?></a></li>
        </ul>
      </li>
      <?php endif; ?>

      <li>
        <form action="/Carniceria/crm/app/controllers/logoutController.php" method="POST" style="display:inline;">
          <button class="logout"><?= $t['nav_salir'] ?></button>
        </form>
      </li>
    <?php else: ?>
      <li><a href="/Carniceria/crm/app/views/auth/login.php"><?= $t['nav_iniciar_sesion'] ?></a></li>
    <?php endif; ?>

    <li>
      <a href="?lang=es">ES</a> | <a href="?lang=en">EN</a>
    </li>
  </ul>
</nav>
<?php if (isset($_SESSION['user'])): ?>
<script src="/Carniceria/crm/public/js/carrito.js"></script>
<?php endif; ?>

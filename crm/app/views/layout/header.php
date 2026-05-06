<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulo ?? 'La Dehesa' ?></title>
  <link rel="stylesheet" href="/Carniceria/crm/public/css/index.css">
</head>
<body>

<nav class="navbar">
  <div class="logo">La Dehesa</div>

  <ul class="menu">
    <li>
      <a href="/Carniceria/crm/index.php">Inicio</a>
      <ul class="submenu">
        <li><a href="#">Sobre nosotros</a></li>
        <li><a href="#">Localización</a></li>
      </ul>
    </li>
    <li><a href="#">Carnicería</a></li>
    <li><a href="#">Charcutería</a></li>
    <li><a href="#">Pollería</a></li>
    <li><a href="#">Conservas</a></li>
    <li><a href="#">Promociones</a></li>
    <li><a href="#">Imágenes</a></li>
    <li><a href="#">Contacto</a></li>

    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="#">🛒 Carrito</a></li>

      <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
        <li><a href="#">Panel Admin</a></li>
      <?php endif; ?>

      <li>
        <form action="/Carniceria/crm/app/controllers/logoutController.php" method="POST" style="display:inline;">
          <button class="logout">Salir</button>
        </form>
      </li>
    <?php else: ?>
      <li><a href="/Carniceria/crm/app/views/auth/login.php">Iniciar sesión</a></li>
    <?php endif; ?>

    <li>
      <a href="?lang=es">ES</a> | <a href="?lang=en">EN</a>
    </li>
  </ul>
</nav>

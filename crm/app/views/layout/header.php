<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>">
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
      <a href="/Carniceria/crm/index.php">Inicio</a>
      <ul class="submenu">
        <li><a href="#">Sobre nosotros</a></li>
        <li><a href="#">Localización</a></li>
      </ul>
    </li>
    <li><a href="/Carniceria/crm/app/views/catalogo/carniceria.php">Carnicería</a></li>
    <li><a href="/Carniceria/crm/app/views/catalogo/charcuteria.php">Charcutería</a></li>
    <li><a href="/Carniceria/crm/app/views/catalogo/polleria.php">Pollería</a></li>
    <li><a href="/Carniceria/crm/app/views/catalogo/conservas.php">Conservas</a></li>
    <li><a href="/Carniceria/crm/app/views/galeria/index.php">Imágenes</a></li>
    <li><a href="#">Contacto</a></li>

    <?php if (isset($_SESSION['user'])): ?>
      <li><a href="#">🛒 Carrito</a></li>

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

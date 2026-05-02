<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carnicería - Inicio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/index.css">
</head>

<body>

<nav class="navbar">
  <div class="logo">Nombre Carnicería</div>

  <ul class="menu">
    <li>
      <a href="#">Inicio</a>
      <ul class="submenu">
        <li><a href="#">Sobre nosotros</a></li>
        <li><a href="#">Localización</a></li>
      </ul>
    </li>

    <li><a href="#">Carnicería</a></li>
    <li><a href="#">Charcutería</a></li>
    <li><a href="#">Pollería</a></li>
    <li><a href="#">Conservas</a></li>
    <li><a href="#">Imágenes</a></li>
    <li><a href="#">Contacto</a></li>

    <li>
      <form action="/logout" method="POST" style="display:inline;">
        <button class="logout">Salir</button>
      </form>
    </li>
  </ul>
</nav>

<div class="content">
  <h1>Bienvenido a la carnicería</h1>
  <p>Selecciona una sección del menú</p>
</div>

</body>
</html>
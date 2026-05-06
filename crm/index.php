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

<div class="content">
  <h1>Bienvenido a La Dehesa</h1>
  <p>Selecciona una sección del menú para ver nuestros productos.</p>
</div>

<?php 
require __DIR__ . '/app/views/layout/footer.php'; 
?>
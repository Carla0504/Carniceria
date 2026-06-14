<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — La Dehesa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/auth.css">
  </head>
  <body>
    <div class="card">
      <p class="card-logo">La Dehesa</p>
      <h1>Bienvenido de nuevo</h1>
      <p class="subtitle">Inicia sesión en tu cuenta</p>
      <?php if (!empty($_GET['error'])): ?>
        <p class="error-msg">
          <?= $_GET['error'] === 'campos' ? 'Rellena todos los campos.' : 'Correo o contraseña incorrectos.' ?>
        </p>
      <?php endif; ?>
      <form action="../../controllers/loginController.php" method="POST">
        <div class="field">
          <label for="email">Correo electrónico</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="tu@correo.com" autocomplete="email" required>
          </div>
        </div>
        <div class="field">
          <label for="password">Contraseña</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
          </div>
        </div>
        <div class="row">
          <label class="remember">
            <input type="checkbox" name="remember"> Recordarme
          </label>
          <a href="/forgot-password" class="forgot">¿Olvidaste tu contraseña?</a>
        </div>
        <button type="submit" class="btn-submit">Iniciar sesión</button>
      </form>
      <p class="signup">¿No tienes cuenta? <a href="./register.php">Regístrate gratis</a></p>
    </div>
  </body>
</html>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta — La Dehesa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/auth.css">
  </head>
  <body>
    <div class="card">
      <p class="card-logo">La Dehesa</p>
      <h1>Crear cuenta</h1>
      <p class="subtitle">Regístrate para empezar</p>
      <?php if (!empty($_GET['error'])): ?>
        <p class="error-msg">
          <?php
            $e = $_GET['error'];
            if ($e === 'campos') {
                echo 'Rellena todos los campos.';
            } else if ($e === 'passwords') {
                echo 'Las contraseñas no coinciden.';
            } else if ($e === 'longitud') {
                echo 'La contraseña debe tener al menos 8 caracteres.';
            } else if ($e === 'existe') {
                echo 'Este correo ya está registrado.';
            } else {
                echo 'Ha ocurrido un error. Inténtalo de nuevo.';
            }
          ?>
        </p>
      <?php endif; ?>
      <form action="../../controllers/registerController.php" method="POST">
        <div class="field">
          <label for="name">Nombre completo</label>
          <div class="input-wrap">
            <input type="text" id="name" name="name" placeholder="Juan García" autocomplete="name" required>
          </div>
        </div>
        <div class="field">
          <label for="email">Correo electrónico</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="tu@correo.com" autocomplete="email" required>
          </div>
        </div>
        <div class="field">
          <label for="password">Contraseña</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" required>
          </div>
        </div>
        <div class="field">
          <label for="password_confirm">Confirmar contraseña</label>
          <div class="input-wrap">
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Repite tu contraseña" autocomplete="new-password" required>
          </div>
        </div>
        <div class="row">
          <label class="remember">
            <input type="checkbox" name="terms" required> Acepto los <a href="/terms" class="forgot">términos y condiciones</a>
          </label>
        </div>
        <button type="submit" class="btn-submit">Crear cuenta</button>
      </form>
      <p class="signup">¿Ya tienes cuenta? <a href="./login.php">Inicia sesión</a></p>
    </div>
  </body>
</html>

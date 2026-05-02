<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta</title>
  <link rel="stylesheet" href="../../../public/css/auth.css"></head>
<body>

<div class="card">
  <h1>Crear cuenta</h1>
  <p class="subtitle">Regístrate para empezar</p>

  <form action="../../controllers/registerController.php" method="POST">    <div class="field">
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

<script src="../../../public/js/auth.js"></script></body>
</html>
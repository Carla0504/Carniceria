<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="/public/css/auth.css">
</head>
<body>

<div class="card">
  <div class="logo">
    <svg viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
  </div>

  <h1>Bienvenido de nuevo</h1>
  <p class="subtitle">Inicia sesión en tu cuenta</p>

  <form action="/login" method="POST">

    <div class="field">
      <label for="email">Correo electrónico</label>
      <div class="input-wrap">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="4" width="20" height="16" rx="2"/>
          <path d="M2 7l10 7 10-7"/>
        </svg>
        <input type="email" id="email" name="email" placeholder="tu@correo.com" autocomplete="email" required>
      </div>
    </div>

    <div class="field">
      <label for="password">Contraseña</label>
      <div class="input-wrap">
        <svg viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
        <button type="button" class="toggle-pass" id="toggle-pass" title="Mostrar contraseña">
          <svg id="eye-icon" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
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

  <p class="signup">¿No tienes cuenta? <a href="/register">Regístrate gratis</a></p>
</div>

<script src="/public/js/auth.js"></script>
</body>
</html>
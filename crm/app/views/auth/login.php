<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'en')) {
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
    <title><?= $t['login_titulo'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/auth.css">
  </head>
  <body>
    <div class="card">
      <p class="card-logo">La Dehesa</p>
      <h1><?= $t['login_h1'] ?></h1>
      <p class="subtitle"><?= $t['login_subtitle'] ?></p>
      <?php if (!empty($_GET['error'])): ?>
        <p class="error-msg">
          <?= $_GET['error'] === 'campos' ? $t['login_error_campos'] : $t['login_error_credenciales'] ?>
        </p>
      <?php endif; ?>
      <form action="../../controllers/loginController.php" method="POST">
        <div class="field">
          <label for="email"><?= $t['login_email_label'] ?></label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="tu@correo.com" autocomplete="email" required>
          </div>
        </div>
        <div class="field">
          <label for="password"><?= $t['login_pass_label'] ?></label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
          </div>
        </div>
        <div class="row">
          <label class="remember">
            <input type="checkbox" name="remember"> <?= $t['login_remember'] ?>
          </label>
          <a href="/forgot-password" class="forgot"><?= $t['login_forgot'] ?></a>
        </div>
        <button type="submit" class="btn-submit"><?= $t['login_btn'] ?></button>
      </form>
      <p class="signup"><?= $t['login_no_cuenta'] ?> <a href="./register.php"><?= $t['login_registrate'] ?></a></p>
      <p class="lang-switch"><a href="?lang=es">ES</a> | <a href="?lang=en">EN</a></p>
    </div>
  </body>
</html>

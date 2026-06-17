<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
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
    <title><?= $t['register_titulo'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/auth.css">
  </head>
  <body>
    <div class="card">
      <p class="card-logo">La Dehesa</p>
      <h1><?= $t['register_h1'] ?></h1>
      <p class="subtitle"><?= $t['register_subtitle'] ?></p>
      <?php if (!empty($_GET['error'])): ?>
        <p class="error-msg">
          <?php
            $e = $_GET['error'];
            if ($e === 'campos')    echo $t['register_error_campos'];
            elseif ($e === 'passwords') echo $t['register_error_passwords'];
            elseif ($e === 'longitud')  echo $t['register_error_longitud'];
            elseif ($e === 'existe')    echo $t['register_error_existe'];
            else                        echo $t['register_error_generico'];
          ?>
        </p>
      <?php endif; ?>
      <form action="../../controllers/registerController.php" method="POST">
        <div class="field">
          <label for="name"><?= $t['register_nombre_label'] ?></label>
          <div class="input-wrap">
            <input type="text" id="name" name="name" placeholder="<?= $t['register_nombre_placeholder'] ?>" autocomplete="name" required>
          </div>
        </div>
        <div class="field">
          <label for="email"><?= $t['register_email_label'] ?></label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="tu@correo.com" autocomplete="email" required>
          </div>
        </div>
        <div class="field">
          <label for="password"><?= $t['register_pass_label'] ?></label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="<?= $t['register_pass_placeholder'] ?>" autocomplete="new-password" required>
          </div>
        </div>
        <div class="field">
          <label for="password_confirm"><?= $t['register_pass_confirm_label'] ?></label>
          <div class="input-wrap">
            <input type="password" id="password_confirm" name="password_confirm" placeholder="<?= $t['register_pass_confirm_ph'] ?>" autocomplete="new-password" required>
          </div>
        </div>
        <div class="row">
          <label class="remember">
            <input type="checkbox" name="terms" required> <?= $t['register_terms'] ?> <a href="/terms" class="forgot"><?= $t['register_terms_link'] ?></a>
          </label>
        </div>
        <button type="submit" class="btn-submit"><?= $t['register_btn'] ?></button>
      </form>
      <p class="signup"><?= $t['register_ya_cuenta'] ?> <a href="./login.php"><?= $t['register_login_link'] ?></a></p>
      <p class="lang-switch"><a href="?lang=es">ES</a> | <a href="?lang=en">EN</a></p>
    </div>
  </body>
</html>

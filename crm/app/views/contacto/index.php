<?php
session_start();

require_once __DIR__ . '/../../../config/db.php';

$enviado = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === '' || $email === '' || $mensaje === '') {
        $error = 'campos';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'email';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (?, ?, ?)"
        );
        $stmt->execute([$nombre, $email, $mensaje]);
        $enviado = true;
    }
}

$langActual = $_SESSION['lang'] ?? 'es';
if (isset($_GET['lang']) && ($_GET['lang'] == 'es' || $_GET['lang'] == 'en')) {
    $langActual = $_GET['lang'];
}
$titulo = 'La Dehesa — ' . ($langActual == 'en' ? 'Contact' : 'Contacto');
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/contacto.css">

<div class="contacto-page">
    <div class="contacto-info">
        <h1><?= $t['contacto_h1'] ?></h1>
        <p><?= $t['contacto_intro'] ?></p>

        <ul class="contacto-datos">
            <li>
                <span class="dato-label"><?= $t['contacto_dir_label'] ?></span>
                <span>Calle Seis de Diciembre, Aravaca (Madrid)</span>
            </li>
            <li>
                <span class="dato-label"><?= $t['contacto_tel_label'] ?></span>
                <span>91 234 56 78</span>
            </li>
            <li>
                <span class="dato-label"><?= $t['contacto_email_label'] ?></span>
                <span>info@ladehesa.es</span>
            </li>
            <li>
                <span class="dato-label"><?= $t['contacto_horario_label'] ?></span>
                <span><?= $t['footer_horario_semana'] ?><br><?= $t['footer_horario_sabado'] ?><br><?= $t['footer_horario_domingo'] ?></span>
            </li>
        </ul>
    </div>

    <div class="contacto-formulario">
        <?php if ($enviado): ?>
            <div class="contacto-exito">
                <p><?= $t['contacto_exito'] ?></p>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <p class="contacto-error">
                    <?php
                    if ($error == 'campos') {
                        echo $t['contacto_error_campos'];
                    } else {
                        echo $t['contacto_error_email'];
                    }
                    ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="campo">
                    <label for="nombre"><?= $t['contacto_nombre_label'] ?></label>
                    <input type="text" id="nombre" name="nombre"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                           placeholder="<?= $t['contacto_placeholder_nombre'] ?>" required>
                </div>

                <div class="campo">
                    <label for="email"><?= $t['contacto_email_form_label'] ?></label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="tu@correo.com" required>
                </div>

                <div class="campo">
                    <label for="mensaje"><?= $t['contacto_mensaje_label'] ?></label>
                    <textarea id="mensaje" name="mensaje" rows="5"
                              placeholder="<?= $t['contacto_placeholder_msg'] ?>"><?= htmlspecialchars($_POST['mensaje'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-enviar"><?= $t['contacto_enviar'] ?></button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

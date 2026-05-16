<?php
session_start();


// proceso el formulario si se ha enviado
$enviado = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === '' || $email === '' || $mensaje === '') {
        $error = 'Rellena todos los campos.';
    } else {
        // aquí se podría enviar un correo o guardar en BD
        // de momento solo muestro el mensaje de confirmación
        $enviado = true;
    }
}

$titulo = 'La Dehesa — Contacto';
require __DIR__ . '/../layout/header.php';
?>
<link rel="stylesheet" href="/Carniceria/crm/public/css/contacto.css">

<div class="contacto-page">
    <div class="contacto-info">
        <h1>Contacto</h1>
        <p>¿Tienes alguna pregunta o quieres hacer un encargo especial? Escríbenos y te respondemos lo antes posible.</p>

        <ul class="contacto-datos">
            <li>
                <span class="dato-label">Dirección</span>
                <span>Calle Seis de Diciembre, Aravaca (Madrid)</span>
            </li>
            <li>
                <span class="dato-label">Teléfono</span>
                <span>91 234 56 78</span>
            </li>
            <li>
                <span class="dato-label">Correo</span>
                <span>info@ladehesa.es</span>
            </li>
            <li>
                <span class="dato-label">Horario</span>
                <span>Lun – Vie: 9:00 – 14:00 y 17:00 – 20:30<br>Sábados: 9:00 – 14:00<br>Domingos: cerrado</span>
            </li>
        </ul>
    </div>

    <div class="contacto-formulario">
        <?php if ($enviado): ?>
            <div class="contacto-exito">
                <p>Mensaje enviado. ¡Gracias por contactarnos!</p>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <p class="contacto-error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                           placeholder="Tu nombre" required>
                </div>

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="tu@correo.com" required>
                </div>

                <div class="campo">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="5"
                              placeholder="Escribe tu mensaje aquí..."><?= htmlspecialchars($_POST['mensaje'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-enviar">Enviar mensaje</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

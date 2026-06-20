<?php
// credenciales para enviar emails desde La Dehesa
// la contraseña es una contraseña de aplicacion de Gmail, no la normal
// se genera en: cuenta de Google > Seguridad > Verificacion en dos pasos > Contrasenas de aplicacion
return [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'user' => getenv('MAIL_USER') ?: 'adminladehesa@gmail.com',
    'pass' => getenv('MAIL_PASS') ?: 'nxvn biql xcue pdgz',
    'from_name' => 'La Dehesa',
    'admin_email' => getenv('MAIL_ADMIN') ?: 'adminladehesa@gmail.com',
];

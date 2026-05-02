<?php
session_start();

// Recoger datos
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 👉 Aquí tu lógica (ejemplo simple)
if ($email === "admin@admin.com" && $password === "12345678") {

    $_SESSION['user'] = [
        'name' => 'Admin',
        'email' => $email
    ];

    header("Location: ../../../index.php");
    exit();
}

// Si falla → volver al login
header("Location: ../views/auth/login.php");
exit();
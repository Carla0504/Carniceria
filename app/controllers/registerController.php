<?php
session_start();

// Recoger datos
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 👉 Aquí guardarías en BD (de momento simple)

$_SESSION['user'] = [
    'name' => $name,
    'email' => $email
];

// Redirigir
header("Location: /index.php");
exit();
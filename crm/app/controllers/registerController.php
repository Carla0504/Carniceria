<?php
session_start();
// $_SERVER['DOCUMENT_ROOT'] devuelve la ruta absoluta de htdocs (C:\xampp\htdocs)
require_once $_SERVER['DOCUMENT_ROOT'] . '/Carniceria/crm/config/db.php';

$nombre = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['password_confirm'] ?? '';

// Validaciones básicas
if ($nombre === '' || $email === '' || $password === '') {
    header("Location: /Carniceria/crm/app/views/auth/register.php?error=campos");
    exit();
}

if ($password !== $confirm) {
    header("Location: /Carniceria/crm/app/views/auth/register.php?error=passwords");
    exit();
}

if (strlen($password) < 8) {
    header("Location: /Carniceria/crm/app/views/auth/register.php?error=longitud");
    exit();
}

// Comprobar si el email ya existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header("Location: /Carniceria/crm/app/views/auth/register.php?error=existe");
    exit();
}

// Insertar usuario
$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')");
$stmt->execute([$nombre, $email, $hash]);

$_SESSION['user'] = [
    'id' => $pdo->lastInsertId(),
    'nombre' => $nombre,
    'email' => $email,
    'rol' => 'cliente',
];

header("Location: /Carniceria/crm/index.php");
exit();
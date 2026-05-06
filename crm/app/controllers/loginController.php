<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Carniceria/crm/config/db.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header("Location: /Carniceria/crm/app/views/auth/login.php?error=campos");
    exit();
}

$stmt = $pdo->prepare("SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

// Los hashes de la BD son $2b$ (bcrypt de Node); PHP usa $2y$, son equivalentes
$hash = $usuario ? str_replace('$2b$', '$2y$', $usuario['password']) : '';

if (!$usuario || !password_verify($password, $hash)) {
    header("Location: /Carniceria/crm/app/views/auth/login.php?error=credenciales");
    exit();
}

$_SESSION['user'] = [
    'id' => $usuario['id'],
    'nombre' => $usuario['nombre'],
    'email' => $usuario['email'],
    'rol' => $usuario['rol'],
];

header("Location: /Carniceria/crm/index.php");
exit();
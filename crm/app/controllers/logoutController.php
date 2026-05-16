<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();
    session_destroy();
    header("Location: /Carniceria/crm/index.php");
} else {
    header("Location: /Carniceria/crm/index.php");
}
exit();

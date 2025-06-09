<?php
session_start();

$page = $_GET['page'] ?? 'login'; // Por defecto, carga 'login'
$public_pages = ['login', 'registro'];

if (!isset($_SESSION['IDMiembro']) && !in_array($page, $public_pages)) {
    header("Location: index.php?page=login");
    exit;
}

$allowed_pages = ['home', 'servicios', 'planes', 'contacto', 'miembros', 'registro', 'login', 'reservar', 'historial', 'pagos','logout','admin_pagos'];

// Solo incluye header/footer si NO es login ni registro
if (!in_array($page, $public_pages)) {
    include 'includes/header.php';
}

if (in_array($page, $allowed_pages)) {
    include "pages/$page.php";
} else {
    echo "<h2>Página no encontrada </h2>";
}

if (!in_array($page, $public_pages)) {
    include 'includes/footer.php';
}
?>

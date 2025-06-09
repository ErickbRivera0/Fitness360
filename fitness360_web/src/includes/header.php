<?php
require_once __DIR__ . '/../includes/conexion.php';

$isAdmin = false;
if (isset($_SESSION['IDMiembro'])) {
    $id = $_SESSION['IDMiembro'];
    $result = $mysqli->query("SELECT EsAdmin FROM Miembros WHERE IDMiembro = $id");
    if ($row = $result->fetch_assoc()) {
        $isAdmin = $row['EsAdmin'] == 1;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Fitness360</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="log/png" href="img/logo.png">
</head>
<body>
  <header>
    <div class="container">
      <h1>Fitness360</h1>
      <nav>
        <ul>
          <li><a href="index.php?page=home">Inicio</a></li>
          <li><a href="index.php?page=servicios">Servicios</a></li>
          <li><a href="index.php?page=planes">Planes</a></li>
          <li><a href="index.php?page=contacto">Contacto</a></li>
          <li><a href="index.php?page=miembros">Miembros</a></li>
          <li><a href="index.php?page=reservar">Reservar</a></li>
          <li><a href="index.php?page=historial">Historial</a></li>
          <li><a href="index.php?page=pagos">Pagos</a></li>
          <?php if (!isset($_SESSION['IDMiembro'])): ?>
            <li><a href="index.php?page=registro">Registro</a></li>
            <li><a href="index.php?page=login">Login</a></li>
          <?php endif; ?>
          <?php if (isset($_SESSION['IDMiembro'])): ?>
            <li><a href="logout.php" style="color:#c00;font-weight:bold;">Salir</a></li>
          <?php endif; ?>
          <?php if ($isAdmin): ?>
            <li><a href="index.php?page=historial_registros">Historial de registros</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

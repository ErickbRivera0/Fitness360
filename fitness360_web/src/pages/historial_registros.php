<?php
?>
<ul>
  <li><a href="index.php?page=home">Inicio</a></li>
  <!-- ...otros enlaces... -->
  <?php if (!isset($_SESSION['IDMiembro'])): ?>
    <li><a href="index.php?page=registro">Registro</a></li>
    <li><a href="index.php?page=login">Login</a></li>
  <?php endif; ?>
  <?php if (isset($_SESSION['IDMiembro'])): ?>
    <li><a href="index.php?page=logout" style="color:#c00;font-weight:bold;">Salir</a></li>
  <?php endif; ?>
  <?php if ($isAdmin): ?>
    <li><a href="index.php?page=registro_admin">Registrar usuario (Admin)</a></li>
    <li><a href="index.php?page=historial_registros">Historial de registros</a></li>
  <?php endif; ?>
</ul>
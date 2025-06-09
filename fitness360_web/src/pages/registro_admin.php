<?php
require_once __DIR__ . '/../includes/conexion.php';
session_start();

if (!isset($_SESSION['IDMiembro'])) {
    header("Location: index.php?page=login");
    exit;
}
$id = $_SESSION['IDMiembro'];
$result = $mysqli->query("SELECT EsAdmin FROM Miembros WHERE IDMiembro = $id");
$row = $result->fetch_assoc();
if (!$row || $row['EsAdmin'] != 1) {
    echo "<h2>Acceso denegado. Solo administradores pueden registrar usuarios.</h2>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $_POST['telefono'];
    $fecha_registro = date('Y-m-d');

    $stmt = $mysqli->prepare("INSERT INTO Miembros (NombreCompleto, CorreoElectronico, Password, NumeroTelefono, FechaRegistro) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $password, $telefono, $fecha_registro);
    $stmt->execute();

    echo "<p>Usuario registrado por el administrador. <a href='index.php?page=miembros'>Ver miembros</a></p>";
    exit;
}
?>
<!-- Aquí tu formulario bonito como antes -->
<?php if ($isAdmin): ?>
  <li><a href="index.php?page=registro_admin">Registrar usuario (Admin)</a></li>
  <li><a href="index.php?page=historial_registros">Historial de registros</a></li>
<?php endif; ?>
<?php
require_once __DIR__ . '/../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $_POST['telefono'];
    $fecha_registro = date('Y-m-d');

    $stmt = $mysqli->prepare("INSERT INTO Miembros (NombreCompleto, CorreoElectronico, Password, NumeroTelefono, FechaRegistro) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $password, $telefono, $fecha_registro);
    $stmt->execute();

    echo "<p>Registro exitoso. <a href='index.php?page=login'>Inicia sesión aquí</a></p>";
    exit;
}
?>
<style>
.registro-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
}
.registro-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    max-width: 420px;
    width: 100%;
    padding: 40px 32px 32px 32px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.registro-card h2 {
    font-size: 1.5rem;
    margin-bottom: 22px;
    font-weight: bold;
    color: #222;
}
.registro-form {
    width: 100%;
}
.registro-label {
    font-weight: bold;
    margin-bottom: 6px;
    color: #222;
    display: block;
}
.registro-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    margin-bottom: 16px;
    background: #fafbfc;
}
.registro-input:focus {
    border-color: #007b55;
    outline: none;
}
.registro-btn {
    width: 100%;
    padding: 12px;
    background: #007b55;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.2s;
}
.registro-btn:hover {
    background: #005c3c;
}
</style>

<div class="registro-wrapper">
  <div class="registro-card">
    <h2>Registro de Usuario</h2>
    <form class="registro-form" method="post">
        <label class="registro-label" for="nombre">Nombre Completo</label>
        <input class="registro-input" type="text" name="nombre" id="nombre" placeholder="Nombre completo" required>

        <label class="registro-label" for="email">Correo electrónico</label>
        <input class="registro-input" type="email" name="email" id="email" placeholder="Correo electrónico" required>

        <label class="registro-label" for="password">Contraseña</label>
        <input class="registro-input" type="password" name="password" id="password" placeholder="Contraseña" required>

        <label class="registro-label" for="telefono">Número de Celular</label>
        <input class="registro-input" type="tel" name="telefono" id="telefono" placeholder="Ej: 9123-4567" required>

        <button class="registro-btn" type="submit">Registrarse</button>
    </form>
    <p style="margin-top:18px;">¿Ya tienes cuenta? <a href="index.php?page=login">Inicia sesión aquí</a></p>
  </div>
</div>
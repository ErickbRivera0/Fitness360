<?php
require_once __DIR__ . '/../includes/conexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];
    $telefono = trim($_POST['telefono']);
    $fecha_registro = date('Y-m-d');

    // Validaciones básicas
    if (strlen($nombre) < 3) {
        $error = "El nombre debe tener al menos 3 caracteres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico no válido.";
    } elseif (strlen($password_raw) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } elseif (!preg_match('/^[0-9\- ]{7,15}$/', $telefono)) {
        $error = "El número de teléfono no es válido.";
    } else {
        // Verificar si el correo ya existe
        $stmt = $mysqli->prepare("SELECT IDMiembro FROM Miembros WHERE CorreoElectronico=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // Registrar usuario
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO Miembros (NombreCompleto, CorreoElectronico, Password, NumeroTelefono, FechaRegistro) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombre, $email, $password, $telefono, $fecha_registro);
            if ($stmt->execute()) {
                $success = "Registro exitoso. <a href='index.php?page=login'>Inicia sesión aquí</a>";
            } else {
                $error = "Error al registrar. Intenta nuevamente.";
            }
        }
        $stmt->close();
    }
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
    <?php if (!empty($error)): ?>
      <div class="login-error"><?= $error ?></div>
    <?php elseif (!empty($success)): ?>
      <div class="login-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (empty($success)): ?>
    <form class="registro-form" method="post" autocomplete="off">
        <label class="registro-label" for="nombre">Nombre Completo</label>
        <input class="registro-input" type="text" name="nombre" id="nombre" placeholder="Nombre completo" required value="<?= isset($nombre) ? htmlspecialchars($nombre) : '' ?>">

        <label class="registro-label" for="email">Correo electrónico</label>
        <input class="registro-input" type="email" name="email" id="email" placeholder="Correo electrónico" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

        <label class="registro-label" for="password">Contraseña</label>
        <input class="registro-input" type="password" name="password" id="password" placeholder="Contraseña (mínimo 6 caracteres)" required>

        <label class="registro-label" for="telefono">Número de Celular</label>
        <input class="registro-input" type="tel" name="telefono" id="telefono" placeholder="Ej: 9123-4567" required value="<?= isset($telefono) ? htmlspecialchars($telefono) : '' ?>">

        <button class="registro-btn" type="submit">Registrarse</button>
    </form>
    <p style="margin-top:18px;">¿Ya tienes cuenta? <a href="index.php?page=login">Inicia sesión aquí</a></p>
    <?php endif; ?>
  </div>
</div>
<?php

require_once __DIR__ . '/../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT IDMiembro, Password, Rol FROM Miembros WHERE CorreoElectronico=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hash, $rol);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['IDMiembro'] = $id;
        $_SESSION['Rol'] = $rol; // <-- Guarda el rol en la sesión
        header("Location: index.php?page=home");
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<style>
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
}
.login-card {
    display: flex;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    overflow: hidden;
    max-width: 650px;
    width: 100%;
}
.login-img-side {
    background: linear-gradient(135deg, #007b55 60%, #2ecc71 100%);
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 260px;
}
.login-img-side img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #fff;
    margin-bottom: 18px;
    object-fit: cover;
    border: 3px solid #fff;
}
.login-img-side h2 {
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: 0;
    font-weight: bold;
}
.login-img-side p {
    color: #e0ffe0;
    font-size: 1.1rem;
    margin-top: 8px;
}
.login-form-side {
    flex: 1;
    padding: 40px 32px 32px 32px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.login-form-side h2 {
    font-size: 1.5rem;
    margin-bottom: 22px;
    font-weight: bold;
    color: #222;
}
.login-form {
    width: 100%;
}
.login-label {
    font-weight: bold;
    margin-bottom: 6px;
    color: #222;
    display: block;
}
.login-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    margin-bottom: 16px;
    background: #fafbfc;
}
.login-input:focus {
    border-color: #007b55;
    outline: none;
}
.login-remember {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
}
.login-remember input[type="checkbox"] {
    margin-right: 8px;
}
.login-btn {
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
.login-btn:hover {
    background: #005c3c;
}
.login-links {
    display: flex;
    justify-content: space-between;
    font-size: 0.97rem;
    margin-bottom: 12px;
}
.login-links a {
    color: #007b55;
    text-decoration: none;
}
.login-links a:hover {
    text-decoration: underline;
}
.login-error {
    color: #c00;
    margin-bottom: 12px;
    font-weight: bold;
    text-align: center;
}
@media (max-width: 700px) {
    .login-card { flex-direction: column; }
    .login-img-side { width: 100%; padding: 32px 0; }
}
</style>

<div class="login-wrapper">
  <div class="login-card">
    <div class="login-img-side">
      <!-- Cambia la ruta de la imagen por tu logo o imagen de usuario -->
      <img src="img/logo.png" alt="Logo">
      <h2>Fitness360</h2>
      <p>Bienvenido de nuevo</p>
    </div>
    <div class="login-form-side">
      <h2>Inicio de sesión</h2>
      <?php if (!empty($error)): ?>
        <div class="login-error"><?= $error ?></div>
      <?php endif; ?>
      <form class="login-form" method="post">
        <label class="login-label" for="email">Correo electrónico</label>
        <input class="login-input" type="email" name="email" id="email" placeholder="Ingresa tu correo" required>

        <div class="login-links">
          <label class="login-label" for="password" style="margin-bottom:0;">Contraseña</label>
          <a href="#">¿Olvidó su contraseña?</a>
        </div>
        <input class="login-input" type="password" name="password" id="password" placeholder="Contraseña" required>

        <div class="login-remember">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember" style="margin-bottom:0; font-weight:normal;">Recordar credenciales</label>
        </div>
        <button class="login-btn" type="submit">Acceder</button>
      </form>
      <p style="margin-top:18px;">¿No tienes cuenta? <a href="index.php?page=registro">Regístrate aquí</a></p>
    </div>
  </div>
</div>
<?php
$isAdmin = (isset($_SESSION['Rol']) && $_SESSION['Rol'] === 'admin');
?>
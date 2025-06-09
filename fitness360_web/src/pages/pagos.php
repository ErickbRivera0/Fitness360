<?php
// session_start(); // <-- Elimina o comenta esta línea
if (!isset($_SESSION['IDMiembro'])) {
    header("Location: index.php?page=login");
    exit;
}
require_once __DIR__ . '/../includes/conexion.php';

$idMiembro = intval($_SESSION['IDMiembro']);
$isAdmin = (isset($_SESSION['Rol']) && $_SESSION['Rol'] === 'admin');

// --- ELIMINAR PAGO (solo admin) ---
if ($isAdmin && isset($_GET['eliminar'])) {
    $idPago = intval($_GET['eliminar']);
    $mysqli->query("DELETE FROM Pagos WHERE IDPago = $idPago");
    header("Location: index.php?page=pagos");
    exit;
}

// --- EDITAR PAGO (solo admin) ---
if ($isAdmin && isset($_POST['editar'])) {
    $idPago = intval($_POST['idpago']);
    $desc = $_POST['descripcion'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $metodo = $_POST['metodo'];
    $estado = $_POST['estado'];
    $idTrans = $_POST['idtransaccion'];
    $inicio = $_POST['fechainicio'];
    $fin = $_POST['fechafin'];
    $stmt = $mysqli->prepare("UPDATE Pagos SET DescripcionPago=?, Monto=?, FechaPago=?, MetodoPago=?, EstadoPago=?, IDTransaccion=?, FechaInicioPeriodo=?, FechaFinPeriodo=? WHERE IDPago=?");
    $stmt->bind_param("sdssssssi", $desc, $monto, $fecha, $metodo, $estado, $idTrans, $inicio, $fin, $idPago);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php?page=pagos");
    exit;
}

// --- FORMULARIO DE EDICIÓN (solo admin) ---
$editarPago = null;
if ($isAdmin && isset($_GET['editar'])) {
    $idPago = intval($_GET['editar']);
    $res = $mysqli->query("SELECT * FROM Pagos WHERE IDPago = $idPago");
    $editarPago = $res->fetch_assoc();
}
?>

<style>
.pagos-section {
    max-width: 900px;
    margin: 32px auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    padding: 32px 32px 24px 32px;
}
.pagos-section h2 {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 18px;
    color: #222;
}
.pagos-form {
    display: flex;
    flex-wrap: wrap;
    gap: 24px 32px;
    align-items: flex-end;
    margin-bottom: 32px;
}
.pagos-form label {
    display: flex;
    flex-direction: column;
    font-weight: 600;
    color: #222;
    font-size: 1rem;
    min-width: 210px;
}
.pagos-form input[type="text"],
.pagos-form input[type="number"],
.pagos-form input[type="date"] {
    padding: 12px 12px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1.08rem;
    background: #fafbfc;
    margin-top: 6px;
    transition: border 0.2s;
}
.pagos-form input:focus {
    border-color: #007b55;
    outline: none;
}
.pagos-form button, .pagos-form a {
    padding: 12px 22px;
    border: none;
    border-radius: 8px;
    background: #ff6f4d;
    color: #fff;
    font-weight: bold;
    font-size: 1.08rem;
    cursor: pointer;
    margin-top: 18px;
    margin-right: 10px;
    transition: background 0.2s;
    text-decoration: none;
    display: inline-block;
}
.pagos-form button:hover, .pagos-form a:hover {
    background: #e05533;
    color: #fff;
}
.pagos-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    margin-top: 24px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
}
.pagos-table th, .pagos-table td {
    padding: 16px 12px;
    text-align: left;
}
.pagos-table th {
    background: #f5f5f5;
    color: #222;
    font-weight: bold;
    font-size: 1.05rem;
    border-bottom: 2px solid #e9ecef;
}
.pagos-table tr {
    border-bottom: 1px solid #e9ecef;
}
.pagos-table tr:last-child {
    border-bottom: none;
}
.pagos-table td {
    color: #222;
    background: #fff;
    font-weight: 500;
}
.pagos-table tr:hover td {
    background: #f7f7f7;
}
.pagos-table .acciones a {
    color: #007b55;
    font-weight: bold;
    margin-right: 8px;
    text-decoration: none;
    transition: color 0.2s;
}
.pagos-table .acciones a:hover {
    color: #c0392b;
}
.pagos-totales {
    margin-top: 18px;
    display: flex;
    justify-content: flex-end;
    gap: 32px;
    font-size: 1.15rem;
}
.pagos-totales .total-label {
    color: #888;
}
.pagos-totales .total-value {
    font-weight: bold;
    color: #007b55;
    font-size: 1.25rem;
}
@media (max-width: 900px) {
    .pagos-section { padding: 18px 4vw; }
    .pagos-form label { min-width: 140px; }
    .pagos-table th, .pagos-table td { padding: 10px 6px; }
}

/* Wizard grande y visual */
.wizard-container {
  max-width: 800px;
  margin: 40px auto 32px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 16px rgba(0,0,0,0.09);
  padding: 36px 36px 28px 36px;
}
.wizard-steps {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 36px;
}
.step {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #e0e0e0;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.3rem;
  transition: background 0.2s;
}
.step.active, .step.completed { background: #007b55; }
.step.completed { background: #27ae60; }
.step-line {
  width: 80px;
  height: 5px;
  background: #e0e0e0;
  margin: 0 10px;
  border-radius: 2px;
}
.step.active + .step-line, .step.completed + .step-line { background: #007b55; }
.wizard-step { display: block; }
.wizard-step:not(:first-child) { margin-top: 0; }
.wizard-options {
  display: flex;
  gap: 32px;
  margin: 32px 0 32px 0;
  flex-wrap: wrap;
}
.wizard-options label {
  background: #fff; /* Fondo blanco */
  border-radius: 10px;
  padding: 22px 32px;
  font-size: 1.15rem;
  font-weight: 500;
  color: #222; /* Texto oscuro */
  cursor: pointer;
  box-shadow: 0 1px 8px rgba(0,0,0,0.04);
  transition: background 0.2s, box-shadow 0.2s, color 0.2s;
  display: flex;
  align-items: center;
  gap: 12px;
}
.wizard-options input[type="radio"] { margin-right: 10px; }
.wizard-options label:hover, .wizard-options input[type="radio"]:checked + span {
  background: #e0f7ef;
  color: #007b55; /* Texto verde al seleccionar o hover */
  box-shadow: 0 2px 12px rgba(0,123,85,0.08);
}
.next-btn, .back-btn {
  padding: 14px 36px;
  border: none;
  border-radius: 8px;
  font-weight: bold;
  font-size: 1.15rem;
  margin: 8px 8px 0 0;
  transition: background 0.2s;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  opacity: 1; /* Asegura que no haya transparencia */
}
.next-btn {
  background: #009e60; /* Verde sólido */
  color: #fff;
}
.next-btn:hover {
  background: #007b48;
}
.back-btn {
  background: #bdbdbd; /* Gris más oscuro */
  color: #222;
}
.back-btn:hover {
  background: #888;
}
label { display: block; margin: 18px 0 8px 0; }
input[type="text"], input[type="number"] {
  width: 100%; padding: 10px; border: 1.5px solid #ccc; border-radius: 6px; font-size: 1.08rem;
}
.card-preview {
  width: 320px; height: 180px; background: #888; border-radius: 18px; margin: 0 auto 24px auto; color: #fff; position: relative; box-shadow: 0 2px 12px rgba(0,0,0,0.12);
  display: flex; flex-direction: column; justify-content: flex-end; padding: 24px 24px 18px 24px;
}
.chip { width: 40px; height: 28px; background: #ffe082; border-radius: 6px; margin-bottom: 18px; }
.card-number { font-size: 1.3rem; letter-spacing: 2px; margin-bottom: 12px; }
.card-name { font-size: 1.1rem; letter-spacing: 1px; }
.card-exp { font-size: 1rem; position: absolute; right: 24px; bottom: 18px; }
.bank-logo.visa { width: 40px; height: 24px; background: url('https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png') no-repeat center/contain; display: inline-block; }
.bank-logo.cash { width: 40px; height: 24px; background: url('https://cdn-icons-png.flaticon.com/512/3135/3135715.png') no-repeat center/contain; display: inline-block; }
.bank-logo.transfer { width: 40px; height: 24px; background: url('https://cdn-icons-png.flaticon.com/512/1256/1256650.png') no-repeat center/contain; display: inline-block; }
@media (max-width: 900px) {
  .wizard-container { padding: 18px 4vw; }
  .wizard-options { gap: 12px; }
  .card-preview { width: 98vw; max-width: 320px; }
}
</style>

<!-- Wizard de Pagos -->
<div class="wizard-container">
  <div class="wizard-steps">
    <div class="step" id="step-ind-1">1</div>
    <div class="step-line"></div>
    <div class="step" id="step-ind-2">2</div>
    <div class="step-line"></div>
    <div class="step" id="step-ind-3">3</div>
  </div>
  <form id="pagosWizardForm" method="post" action="">
    <!-- Paso 1 -->
    <div class="wizard-step" id="step1">
      <h3>Seleccionar el tipo de pago</h3>
      <div class="wizard-options">
        <label><input type="radio" name="tipo_pago" value="Matrícula" required> Matrícula</label>
        <label><input type="radio" name="tipo_pago" value="Cuotas"> Cuotas</label>
        <label><input type="radio" name="tipo_pago" value="Reserva"> Reserva</label>
        <label><input type="radio" name="tipo_pago" value="Entrenador"> Entrenador</label>
      </div>
      <button type="button" class="next-btn" onclick="nextStep()">Continuar</button>
    </div>
    <!-- Paso 2 -->
    <div class="wizard-step" id="step2" style="display:none;">
      <h3>Pasarela de Pago</h3>
      <div class="wizard-options">
        <label><input type="radio" name="proveedor" value="Tarjeta" required> <span class="bank-logo visa"></span> Tarjeta (Visa/Mastercard)</label>
        <label><input type="radio" name="proveedor" value="Efectivo"> <span class="bank-logo cash"></span> Efectivo</label>
        <label><input type="radio" name="proveedor" value="Transferencia"> <span class="bank-logo transfer"></span> Transferencia</label>
      </div>
      <div style="margin-top:32px;">
        <button type="button" class="back-btn" onclick="prevStep()">Atrás</button>
        <button type="button" class="next-btn" onclick="nextStep()">Continuar</button>
      </div>
    </div>
    <!-- Paso 3 -->
    <div class="wizard-step" id="step3" style="display:none;">
      <h3>Información de pago</h3>
      <div id="tarjetaFields" style="display:none;">
        <div class="card-preview" id="cardPreview">
          <div class="chip"></div>
          <div class="card-number" id="cardNumberPreview">•••• •••• •••• ••••</div>
          <div class="card-name" id="cardNamePreview">SU NOMBRE AQUÍ</div>
          <div class="card-exp" id="cardExpPreview">MM/AA</div>
        </div>
        <label>Número de tarjeta
          <input type="text" name="tarjeta" id="tarjetaInput" maxlength="19" placeholder="•••• •••• •••• ••••" autocomplete="cc-number" inputmode="numeric" pattern="\d{16}">
        </label>
        <label>Fecha Exp
          <input type="month" name="exp" id="expInput" min="<?= date('Y-m'); ?>" placeholder="mm/yyyy" autocomplete="cc-exp">
        </label>
        <label>CVV
          <input type="text" name="cvv" id="cvvInput" maxlength="4" placeholder="123" autocomplete="cc-csc" inputmode="numeric" pattern="\d{3,4}">
        </label>
        <label>Nombre en la tarjeta
          <input type="text" name="nombre_tarjeta" id="nombreTarjetaInput" maxlength="26" autocomplete="cc-name">
        </label>
      </div>
      <div style="margin-top:32px;">
        <button type="button" class="back-btn" onclick="prevStep()">Atrás</button>
        <button type="submit" class="next-btn">Pagar</button>
      </div>
    </div>
  </form>
</div>

<script>
let currentStep = 1;
function showStep(step) {
  document.querySelectorAll('.wizard-step').forEach((el, idx) => {
    el.style.display = (idx+1) === step ? 'block' : 'none';
  });
  document.querySelectorAll('.wizard-steps .step').forEach((el, idx) => {
    el.classList.remove('active','completed');
    if (idx+1 < step) el.classList.add('completed');
    else if (idx+1 === step) el.classList.add('active');
  });
  // Mostrar campos de tarjeta solo si corresponde
  if (step === 3) {
    const proveedor = document.querySelector('input[name="proveedor"]:checked');
    document.getElementById('tarjetaFields').style.display = (proveedor && proveedor.value === 'Tarjeta') ? 'block' : 'none';
  }
}
function nextStep() {
  if (currentStep < 3) {
    currentStep++;
    showStep(currentStep);
  }
}
function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    showStep(currentStep);
  }
}
document.addEventListener('change', function(e) {
  if (e.target.name === 'proveedor') showStep(3);
});
showStep(currentStep);
</script>

<script>
// Formatear número de tarjeta y reflejar en la tarjeta visual
const tarjetaInput = document.getElementById('tarjetaInput');
const cardNumberPreview = document.getElementById('cardNumberPreview');
tarjetaInput.addEventListener('input', function(e) {
  // Solo números, máximo 16 dígitos
  let value = e.target.value.replace(/\D/g, '').slice(0,16);
  // Formato #### #### #### ####
  let formatted = value.replace(/(.{4})/g, '$1 ').trim();
  e.target.value = formatted;
  cardNumberPreview.textContent = formatted.padEnd(19, '•');
});

// Reflejar nombre en la tarjeta
const nombreTarjetaInput = document.getElementById('nombreTarjetaInput');
const cardNamePreview = document.getElementById('cardNamePreview');
nombreTarjetaInput.addEventListener('input', function(e) {
  let value = e.target.value.toUpperCase();
  cardNamePreview.textContent = value || 'SU NOMBRE AQUÍ';
});

// Reflejar fecha de expiración en la tarjeta
const expInput = document.getElementById('expInput');
const cardExpPreview = document.getElementById('cardExpPreview');
expInput.addEventListener('input', function(e) {
  if (e.target.value) {
    let [year, month] = e.target.value.split('-');
    cardExpPreview.textContent = `${month}/${year.slice(-2)}`;
  } else {
    cardExpPreview.textContent = 'MM/AA';
  }
});

// Limitar CVV a 4 dígitos numéricos
const cvvInput = document.getElementById('cvvInput');
cvvInput.addEventListener('input', function(e) {
  e.target.value = e.target.value.replace(/\D/g, '').slice(0,4);
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo_pago']) && isset($_POST['proveedor'])) {
    $tipo_pago = $_POST['tipo_pago'];
    $proveedor = $_POST['proveedor'];
    $tarjeta = $_POST['tarjeta'] ?? null;
    $exp = $_POST['exp'] ?? null;
    $cvv = $_POST['cvv'] ?? null;
    $nombre_tarjeta = $_POST['nombre_tarjeta'] ?? null;

    // Aquí tu lógica para calcular monto, descuentos, etc.
    $monto = 1000; // Puedes calcular según tipo_pago

    // Estado y transacción automáticos
    if ($proveedor === "Efectivo") {
        $estado = "Aprobado";
    } elseif ($proveedor === "Tarjeta") {
        $estado = (rand(0, 1) === 1) ? "Aprobado" : "Declinado";
    } else {
        $estado = "Pendiente";
    }
    $idTrans = strval(rand(100000000, 999999999));
    $fecha = date('Y-m-d');

    // Inserta en la base de datos
    $stmt = $mysqli->prepare("INSERT INTO Pagos (IDMiembro, DescripcionPago, Monto, FechaPago, MetodoPago, EstadoPago, IDTransaccion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssss", $idMiembro, $tipo_pago, $monto, $fecha, $proveedor, $estado, $idTrans);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php?page=pagos');
    exit;
}

$isAdmin = (isset($_SESSION['Rol']) && $_SESSION['Rol'] === 'admin');
?>

<?php if ($isAdmin): ?>
  <div style="background:#e0f7ef; border:2px solid #007b55; border-radius:10px; padding:24px; margin:32px 0;">
    <h2 style="color:#007b55; margin-top:0;">Panel de Administración</h2>
    <ul style="font-size:1.1rem; color:#222;">
      <li>Ver todos los pagos de todos los usuarios</li>
      <li>Editar o eliminar cualquier pago</li>
    </ul>
  </div>

  <?php if ($editarPago): ?>
    <!-- Formulario de edición de pago -->
    <h2>Editar Pago</h2>
    <form method="post" class="pagos-form">
      <input type="hidden" name="idpago" value="<?= $editarPago['IDPago'] ?>">
      <label>Descripción
        <input type="text" name="descripcion" value="<?= htmlspecialchars($editarPago['DescripcionPago']) ?>" required>
      </label>
      <label>Monto
        <input type="number" name="monto" value="<?= $editarPago['Monto'] ?>" step="0.01" required>
      </label>
      <label>Fecha
        <input type="date" name="fecha" value="<?= substr($editarPago['FechaPago'],0,10) ?>" required>
      </label>
      <label>Método
        <input type="text" name="metodo" value="<?= htmlspecialchars($editarPago['MetodoPago']) ?>" required>
      </label>
      <label>Estado
        <input type="text" name="estado" value="<?= htmlspecialchars($editarPago['EstadoPago']) ?>" required>
      </label>
      <label>ID Transacción
        <input type="text" name="idtransaccion" value="<?= htmlspecialchars($editarPago['IDTransaccion']) ?>">
      </label>
      <label>Inicio de periodo
        <input type="date" name="fechainicio" value="<?= $editarPago['FechaInicioPeriodo'] ?>">
      </label>
      <label>Fin de periodo
        <input type="date" name="fechafin" value="<?= $editarPago['FechaFinPeriodo'] ?>">
      </label>
      <button type="submit" name="editar">Guardar cambios</button>
      <a href="index.php?page=pagos">Cancelar</a>
    </form>
    <hr>
  <?php endif; ?>

  <!-- Historial de pagos -->
  <h2>Historial de Pagos</h2>
  <table class="pagos-table">
    <tr>
      <th>ID</th>
      <th>Miembro</th>
      <th>Descripción</th>
      <th>Monto</th>
      <th>Fecha</th>
      <th>Método</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
    <?php
    $result = $mysqli->query("SELECT p.*, m.NombreCompleto FROM Pagos p JOIN Miembros m ON p.IDMiembro = m.IDMiembro ORDER BY FechaPago DESC");
    while ($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?= $row['IDPago'] ?></td>
      <td><?= htmlspecialchars($row['NombreCompleto']) ?></td>
      <td><?= htmlspecialchars($row['DescripcionPago']) ?></td>
      <td><?= $row['Monto'] ?></td>
      <td><?= $row['FechaPago'] ?></td>
      <td><?= $row['MetodoPago'] ?></td>
      <td><?= $row['EstadoPago'] ?></td>
      <td class="acciones">
        <a href="index.php?page=pagos&editar=<?= $row['IDPago'] ?>">Editar</a>
        <a href="index.php?page=pagos&eliminar=<?= $row['IDPago'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este pago?')">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
<?php endif; ?>
<a href="index.php?page=admin_pagos" target="_blank" style="display:inline-block; margin-top:18px; background:#007b55; color:#fff; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:bold;">Ir a reportes</a>
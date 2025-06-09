<?php
session_start();
require_once __DIR__ . '/../includes/conexion.php';

if (!isset($_SESSION['IDMiembro']) || !isset($_SESSION['Rol']) || $_SESSION['Rol'] !== 'admin') {
    header("Location: index.php?page=login");
    exit;
}

// Eliminar pago
if (isset($_GET['eliminar'])) {
    $idPago = intval($_GET['eliminar']);
    $mysqli->query("DELETE FROM Pagos WHERE IDPago = $idPago");
    header("Location: admin_pagos.php");
    exit;
}

// Editar pago
$editarPago = null;
if (isset($_GET['editar'])) {
    $idPago = intval($_GET['editar']);
    $res = $mysqli->query("SELECT * FROM Pagos WHERE IDPago = $idPago");
    $editarPago = $res->fetch_assoc();
}
if (isset($_POST['editar'])) {
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
    header("Location: admin_pagos.php");
    exit;
}
?>

<h2 style="color:#007b55; margin-top:24px;">Panel de Administración - Pagos</h2>

<?php if ($editarPago): ?>
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
      <a href="admin_pagos.php">Cancelar</a>
    </form>
    <hr>
<?php endif; ?>

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
    $result = $mysqli->query("SELECT p.*, m.NombreCompleto FROM Pagos p JOIN Miembros m ON p.IDMiembro = m.IDMiembro ORDER BY FechaPago DESC, IDPago DESC");
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
        <a href="admin_pagos.php?editar=<?= $row['IDPago'] ?>">Editar</a>
        <a href="admin_pagos.php?eliminar=<?= $row['IDPago'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este pago?')">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="index.php?page=admin_pagos" target="_blank" style="display:inline-block; margin-top:18px; background:#007b55; color:#fff; padding:12px 28px; border-radius:8px; text-decoration:none; font-weight:bold;">Ir a reportes</a>
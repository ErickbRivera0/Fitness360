
<?php

if (!isset($_SESSION['IDMiembro'])) {
    header("Location: index.php?page=login");
    exit;
}
require_once __DIR__ . '/../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMiembro = $_SESSION['IDMiembro'];
    $idClase = $_POST['id_clase'];
    $stmt = $mysqli->prepare("INSERT INTO Reserva (IDMiembro, IDClase, FechaHoraReserva) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $idMiembro, $idClase);
    $stmt->execute();
    echo "<p>¡Clase reservada!</p>";
}

// Mostrar clases disponibles
$result = $mysqli->query("SELECT IDClase, NombreClase, Horario FROM Clases");
echo "<h2>Reservar Clase</h2>";
echo "<form method='post'><select name='id_clase'>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['IDClase']}'>{$row['NombreClase']} - {$row['Horario']}</option>";
}
echo "</select><button type='submit'>Reservar</button></form>";

// Mostrar lista de reservas del usuario actual
$idMiembro = $_SESSION['IDMiembro'];
$reservas = $mysqli->query("SELECT R.FechaHoraReserva, C.NombreClase, C.Horario 
                            FROM Reserva R 
                            JOIN Clases C ON R.IDClase = C.IDClase 
                            WHERE R.IDMiembro = $idMiembro 
                            ORDER BY R.FechaHoraReserva DESC");

echo "<h3>Mis Reservas</h3>";
if ($reservas->num_rows > 0) {
    echo "<ul>";
    while ($res = $reservas->fetch_assoc()) {
        echo "<li>{$res['NombreClase']} ({$res['Horario']}) - Reservado el {$res['FechaHoraReserva']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No tienes reservas.</p>";
}
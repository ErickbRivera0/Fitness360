<?php
if (!isset($_SESSION['IDMiembro'])) {
    header("Location: index.php?page=login");
    exit;
}
require_once __DIR__ . '/../includes/conexion.php';

$idMiembro = $_SESSION['IDMiembro'];
$result = $mysqli->query("SELECT C.NombreClase, R.FechaHoraReserva FROM Reserva R JOIN Clases C ON R.IDClase = C.IDClase WHERE R.IDMiembro = $idMiembro ORDER BY R.FechaHoraReserva DESC");

echo "<h2>Historial de Asistencia</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>{$row['NombreClase']} - {$row['FechaHoraReserva']}</li>";
}
echo "</ul>";

// Progreso
echo "<h2>Progreso</h2>";
$progreso = $mysqli->query("SELECT TipoMetrica, Valor, FechaRegistroProgreso, Notas FROM Progreso WHERE IDMiembro = $idMiembro ORDER BY FechaRegistroProgreso DESC");

if ($progreso->num_rows > 0) {
    echo "<style>
            .progreso-table {
                width: 100%;
                border-collapse: collapse;
                background: #fff;
                margin-top: 20px;
            }
            .progreso-table th, .progreso-table td {
                border: 1px solid #ddd;
                padding: 12px 8px;
                font-weight: bold;
                color: #222;
                background: #fff;
            }
            .progreso-table th {
                background: #f5f5f5;
                color: #222;
            }
            .progreso-table tr:hover {
                background-color: #e9ecef;
            }
          </style>";
    echo "<table class='progreso-table'>";
    echo "<thead><tr><th>Métrica</th><th>Valor</th><th>Fecha</th><th>Notas</th></tr></thead><tbody>";
    while ($row = $progreso->fetch_assoc()) {
        echo "<tr>
                <td>{$row['TipoMetrica']}</td>
                <td>{$row['Valor']}</td>
                <td>{$row['FechaRegistroProgreso']}</td>
                <td>{$row['Notas']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No hay registros de progreso.</p>";
}
?>
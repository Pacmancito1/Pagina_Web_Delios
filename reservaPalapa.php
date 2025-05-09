<?php
session_start();
include("db.php");

$usuario = $_SESSION['email'];

// Obtener ID del inquilino logeado
$sqlInquilino = "SELECT id_inquilino FROM inquilinos WHERE email = '$usuario'";
$resInquilino = mysqli_query($conexion, $sqlInquilino);
$filaInquilino = mysqli_fetch_assoc($resInquilino);
$idInquilino = $filaInquilino['id_inquilino'];

$mensaje = ""; // Para mostrar el mensaje en HTML

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $area = 'palapa';
    $estatus = 'pendiente';

    // Validar si ya existe una reserva en esa fecha, hora y área
    $sqlCheck = "SELECT COUNT(*) as total FROM reservas 
                 WHERE fecha = '$fecha' AND hora = '$hora' AND area = '$area' AND estatus != 'cancelada'";
    $resCheck = mysqli_query($conexion, $sqlCheck);
    $filaCheck = mysqli_fetch_assoc($resCheck);

    if ($filaCheck['total'] > 0) {
        $mensaje = "Ya hay una reserva para esa fecha y hora. Por favor elige otra.";
    } else {
        // No hay conflicto, se inserta
        $sqlInsert = "INSERT INTO reservas (id_inquilino, area, fecha, hora, estatus) 
                      VALUES ($idInquilino, '$area', '$fecha', '$hora', '$estatus')";
        if (mysqli_query($conexion, $sqlInsert)) {
            $mensaje = "¡Reserva registrada exitosamente!";
        } else {
            $mensaje = "Error al registrar la reserva.";
        }
    }
}

// Consultar TODAS las reservas para mostrarlas
$sqlReservas = "SELECT r.*, i.nombre, i.apellidos 
                FROM reservas r 
                JOIN inquilinos i ON r.id_inquilino = i.id_inquilino
                WHERE area = 'palapa'
                ORDER BY fecha DESC, hora DESC";
$reservas = mysqli_query($conexion, $sqlReservas);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Reservar Palapa</h2>
<form method="post">
    <label>Fecha:</label>
    <input type="date" name="fecha" required>
    <label>Hora:</label>
    <input type="time" name="hora" required>
    <button type="submit">Reservar</button>
</form>

<?php if ($mensaje): ?>
    <p style="color: <?= strpos($mensaje, 'exitosamente') !== false ? 'green' : 'red' ?>;">
        <?= htmlspecialchars($mensaje) ?>
    </p>
<?php endif; ?>

<h3>Reservas existentes (Palapa)</h3>
<table>
    <thead>
        <tr>
            <th>Inquilino</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($reserva = mysqli_fetch_assoc($reservas)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($reserva['nombre'] . ' ' . $reserva['apellidos']) . "</td>";
            echo "<td>" . htmlspecialchars($reserva['fecha']) . "</td>";
            echo "<td>" . htmlspecialchars($reserva['hora']) . "</td>";
            echo "<td>" . htmlspecialchars($reserva['estatus']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
</body>
</html>
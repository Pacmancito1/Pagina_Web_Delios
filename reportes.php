<?php
session_start();
include("db.php");

$usuario = $_SESSION['email_comite'];
$sql = "SELECT * FROM comite WHERE email = '$usuario'";
$resultado = $conexion->query($sql);

// Verificar que el usuario existe y obtener los datos
if ($data = $resultado->fetch_assoc()) {
    $nombre = $data['nombre'];
    $apellidos = $data['apellidos'];
    $email = $data['email'];
    $cargo = $data['cargo'];
    // Otros datos que puedas necesitar
}

// Verificar el mes y el año actual
$mes_actual = date('m');  // Mes actual
$año_actual = date('Y');  // Año actual

// Consultas SQL para los reportes
// 1. Reporte mensual
$sql_mensual = "SELECT SUM(monto) AS total_mes
                FROM pagos
                WHERE MONTH(fecha_pago) = '$mes_actual' AND YEAR(fecha_pago) = '$año_actual'";

// 2. Reporte anual
$sql_anual = "SELECT SUM(monto) AS total_anual
              FROM pagos
              WHERE YEAR(fecha_pago) = '$año_actual'";

// Ejecutar las consultas
$resultado_mensual = $conexion->query($sql_mensual);
$resultado_anual = $conexion->query($sql_anual);

// Obtener los resultados
$total_mensual = $resultado_mensual->fetch_assoc()['total_mes'];
$total_anual = $resultado_anual->fetch_assoc()['total_anual'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Financieros</title>
    <link rel="stylesheet" href="styles-comite.css"> <!-- Estilos personalizados -->
</head>
<body>
    <h2>Reportes Financieros</h2>

    <h3>Reporte Mensual (<?php echo date('F Y'); ?>)</h3>
    <table border="1">
        <tr>
            <th>Mes</th>
            <th>Total Pagos</th>
        </tr>
        <tr>
            <td><?php echo date('F Y'); ?></td>
            <td><?php echo $total_mensual ? "$" . number_format($total_mensual, 2) : "No hay pagos este mes"; ?></td>
        </tr>
    </table>

    <h3>Reporte Anual (<?php echo $año_actual; ?>)</h3>
    <table border="1">
        <tr>
            <th>Año</th>
            <th>Total Pagos</th>
        </tr>
        <tr>
            <td><?php echo $año_actual; ?></td>
            <td><?php echo $total_anual ? "$" . number_format($total_anual, 2) : "No hay pagos este año"; ?></td>
        </tr>
    </table>

</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>

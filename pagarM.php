<?php
    session_start();
    include("db.php");

    $usuario = $_SESSION['email']; 
    $cuota = 500;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Mantenimiento</title>
    <link rel="stylesheet" href="styles-pagos.css">
</head>
<body>
    <h2>Pagar Mantenimiento</h2>

    <form action="#" method="POST">
        <label>Concepto:</label>
        <input type="text" name="concepto" value="Pago de mantenimiento" required><br><br>

        <label>Monto a pagar:</label>
        <input type="number" name="monto" step="0.01" required><br><br>

        <!-- En el futuro podríamos poner aquí el input del comprobante -->

        <input type="submit" value="Pagar" name="pagar">
    </form>

    <p><strong>Cuota establecida:</strong> $<?php echo number_format($cuota, 2); ?></p>
</body>
</html>

<?php

if (isset($_POST['pagar'])) {
    $concepto = $_POST['concepto'];
    $monto = $_POST['monto'];

    $consultaId = "SELECT id_inquilino FROM inquilinos WHERE email = '$usuario'";
    $resultado = mysqli_query($conexion, $consultaId);

if ($fila = mysqli_fetch_assoc($resultado)) {
    $id_inquilino = $fila['id_inquilino'];

    $insertarPago = "INSERT INTO pagos (concepto, monto, fecha_pago, id_inquilino, estado)
                     VALUES ('$concepto', $monto, NOW(), $id_inquilino, 'pagado')";

    if (mysqli_query($conexion, $insertarPago)) {
        echo "<p style='color:green;'>Pago registrado exitosamente.</p>";
    } else {
        echo "<p style='color:red;'>Error al registrar el pago: " . mysqli_error($conexion) . "</p>";
    }

} else {
    echo "<p style='color:red;'>No se encontró el inquilino con el email: $usuario</p>";
}
}

?>
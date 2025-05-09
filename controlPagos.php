<?php
session_start();
include("db.php");

$usuario = $_SESSION['email_comite'];
$sql = "SELECT * FROM comite WHERE email = '$usuario'";
$resultado = $conexion->query($sql);

if($data=$resultado->fetch_assoc()){
    $id_comite = $data['id_comite'];
    $numero_casa = $data['numero_casa'];
    $nombre = $data['nombre'];
    $apellidos = $data['apellidos'];
    $email = $data['email'];
    $telefono_principal = $data['telefono_principal'];
    $telefono_alternativo = $data['telefono_alternativo'];
    $fecha_nacimiento = $data['fecha_nacimiento'];
    $genero = $data['genero'];
    $direccion = $data['direccion'];
    $tipo_residencia = $data['tipo_residencia'];
    $tipo_contrato = $data['tipo_contrato'];
    $cargo = $data['cargo'];
    $fecha_inicio = $data['fecha_inicio'];
    $password_hash = $data['password_hash'];
    $fecha_registro = $data['fecha_registro'];
    $ultima_actualizacion = $data['ultima_actualizacion'];
    $esta_activo = $data['esta_activo'];
    $acepto_terminos = $data['acepto_terminos'];

    $sqlSolicitudes = "SELECT s.*, i.nombre, i.apellidos, i.numero_casa 
               FROM solicitudes s 
               INNER JOIN inquilinos i ON s.id_inquilino = i.id_inquilino 
               ORDER BY s.fecha DESC"; // Puedes ajustar el número de resultados
    $resultSolicitudes = $conexion->query($sqlSolicitudes);

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del formulario
    $fecha = $_POST['fecha'];
    $monto = $_POST['monto'];
    $beneficiario = $_POST['beneficiario'];
    $concepto = $_POST['concepto'];
    $responsable = $_POST['responsable'];

    // Usuario actual (email del comité)
    $email = $_SESSION['email_comite'];

    // Obtener ID del comité desde la base de datos
    $sql = "SELECT id_comite FROM comite WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($row = $resultado->fetch_assoc()) {
        $id_comite = $row['id_comite'];

        // Insertar el egreso
        $insert = "INSERT INTO egresos (fecha, monto, beneficiario, concepto, responsable, id_comite)
                   VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($insert);
        $stmt_insert->bind_param("sdsssi", $fecha, $monto, $beneficiario, $concepto, $responsable, $id_comite);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Egreso registrado correctamente'); window.location.href='controlPagos.php';</script>";
        } else {
            echo "<script>alert('Error al registrar egreso'); window.history.back();</script>";
        }

        $stmt_insert->close();
    } else {
        echo "<script>alert('Comité no encontrado'); window.history.back();</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Egreso</title>
    <link rel="stylesheet" href="styles-comite.css"> <!-- Asegúrate de tener este archivo o cámbialo por uno tuyo -->
</head>
<body>
    <h2>Formulario para Registrar Egreso</h2>

    <form action="#" method="POST">
        <label for="fecha">Fecha del Egreso:</label><br>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="monto">Monto:</label><br>
        <input type="number" step="0.01" id="monto" name="monto" required><br><br>

        <label for="beneficiario">Beneficiario:</label><br>
        <input type="text" id="beneficiario" name="beneficiario" required><br><br>

        <label for="concepto">Motivo o Concepto:</label><br>
        <input type="text" id="concepto" name="concepto" required><br><br>

        <!-- Mostrar el cargo -->
        <label for="responsable">Responsable del Registro:</label><br>
        <p class="cargo"><?php echo $cargo; ?></p>
        <!-- Incluir el cargo como un campo oculto en el formulario -->
        <input type="hidden" name="responsable" value="<?php echo $cargo; ?>"><br><br>

        </select><br><br>

        <button type="submit">Registrar Egreso</button>
    </form>
</body>
</html>

<?php
$casa = $_POST['inmueble'];
$usuario = $_POST['email'];
$contraseña = $_POST['password'];
$telefono = $_POST['tel'];

session_start();
$_SESSION['email'] = $usuario;

include('db.php');

$consulta = "SELECT * FROM inquilinos WHERE email = '$usuario' AND numero_casa = '$casa' AND telefono_principal = '$telefono'";
$resultado = mysqli_query($conexion, $consulta);

if ($resultado && mysqli_num_rows($resultado) == 1) {
    $fila = mysqli_fetch_assoc($resultado);

    // Verificamos la contraseña con password_verify
    if (password_verify($contraseña, $fila['password_hash'])) {
        header("Location: inquilino.php");
        exit();
    } else {
        include("inicioSesion.php");
        echo "<h1 class='bad'>Contraseña incorrecta</h1>";
    }
} else {
    include("inicioSesion.php");
    echo "<h1 class='bad'>Datos incorrectos</h1>";
}

mysqli_free_result($resultado);
mysqli_close($conexion);

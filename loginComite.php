<?php
$cargo = $_POST['cargo'];
$usuario = $_POST['email_comite'];
$contraseña = $_POST['codigo'];

session_start();
$_SESSION['email_comite'] = $usuario;
include("db.php");

$consulta = "SELECT * FROM comite WHERE email = '$usuario' AND cargo = '$cargo'";
$resultado = mysqli_query($conexion, $consulta);

if ($resultado && mysqli_num_rows($resultado) == 1) {
    $fila = mysqli_fetch_assoc($resultado);

    // Verificamos la contraseña con password_verify
    if (password_verify($contraseña, $fila['password_hash'])) {
        header("Location: comite.php");
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

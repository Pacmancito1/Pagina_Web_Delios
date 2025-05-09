<?php
$servidor = "localhost:3308";
$usuario = "root";
$clave = "123";
$db = "adminresidencial";

try {
    $enlace = new mysqli($servidor, $usuario, $clave, $db);
    if ($enlace->connect_error) {
        throw new Exception("Conexión fallida: " . $enlace->connect_error);
    }
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Registro de Comite</h1>
<form action="#" method="POST">
  <label>Número de casa:</label>
  <input type="text" name="numero_casa" required><br>

  <label>Nombre:</label>
  <input type="text" name="nombre" required><br>

  <label>Apellidos:</label>
  <input type="text" name="apellidos" required><br>

  <label>Email:</label>
  <input type="email" name="email" required><br>

  <label>Teléfono principal:</label>
  <input type="text" name="telefono_principal" required><br>

  <label>Teléfono alternativo:</label>
  <input type="text" name="telefono_alternativo"><br>

  <label>Fecha de nacimiento:</label>
  <input type="date" name="fecha_nacimiento" required><br>

  <label>Género:</label>
  <select name="genero" required>
    <option value="femenino">Femenino</option>
    <option value="masculino">Masculino</option>
    <option value="otro">Otro</option>
    <option value="prefiero_no_decirlo">Prefiero no decirlo</option>
  </select><br>

  <label>Dirección:</label><br>
  <textarea name="direccion" rows="4" cols="50" required></textarea><br>

  <label>Tipo de residencia:</label>
  <select name="tipo_residencia" required>
    <option value="casa">Casa</option>
    <option value="departamento">Departamento</option>
    <option value="otro">Otro</option>
  </select><br>

  <label>Tipo de contrato:</label>
  <select name="tipo_contrato" required>
    <option value="renta">Renta</option>
    <option value="propietario">Propietario</option>
    <option value="familiar">Familiar</option>
  </select><br>

  <label>Fecha de ingreso:</label>
  <input type="date" name="fecha_registro" required><br>

  <label>Contraseña:</label>
  <input type="password" name="password_hash" required><br>

  <label>Confirmar contraseña:</label>
  <input type="password" name="confirm_password" required><br>


  <label>Cargo:</label>
  <select name="cargo" required>
    <option value="presidente">Presidente</option>
    <option value="secretario">Secretario</option>
    <option value="vocal">Vocal</option>
  </select><br>

  <label>Acepta términos:</label>
  <input type="checkbox" name="acepto_terminos" value="1" required> Sí<br>

  <button type="submit" name="registro">Guardar</button>
</form>

</body>
</html>

<?php
    if (isset($_POST['registro'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $email = $_POST['email'];
        $telefonoP = $_POST['telefono_principal'];
        $telefonoA = $_POST['telefono_alternativo'];
        $direccion = $_POST['direccion'];
        $casa = $_POST['numero_casa'];
        $tipo = $_POST['tipo_residencia'];
        $ingreso = $_POST['fecha_registro'];
        $contrato = $_POST['tipo_contrato'];
        $cargo = $_POST['cargo'];
        $password = $_POST['password_hash'];
        $repeatPass = $_POST['confirm_password'];
        $terminos = isset($_POST['terms']) ? 1 : 0;

        if ($password === $repeatPass) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            

            $insertarDatos = "INSERT INTO comite (
                numero_casa, nombre, apellidos, email, telefono_principal,
                telefono_alternativo, fecha_nacimiento, genero, direccion,
                tipo_residencia, tipo_contrato, cargo, fecha_inicio, 
                password_hash, fecha_registro, ultima_actualizacion, esta_activo, acepto_terminos
            ) VALUES (
                '$casa', '$nombre', '$apellidos', '$email', '$telefonoP',
                '$telefonoA', '$nacimiento', '$genero', '$direccion',
                '$tipo', '$contrato', '$cargo', '$ingreso', 
                '$password_hash', NOW(), NOW(), 1, $terminos
            )";
            
            $ejecutarInsertar = mysqli_query($enlace, $insertarDatos);

            if ($ejecutarInsertar) {
                echo "Registro exitoso.";
            } else {
                echo "Error al registrar: " . mysqli_error($enlace);
            }

        } else {
            echo "Las contraseñas no coinciden.";
        }
    }
?>

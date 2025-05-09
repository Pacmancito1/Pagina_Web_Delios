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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Inquilino | Administración Residencial</title>
    <link rel="stylesheet" href="styles-registro.css">
</head>
<body>
    <div class="registration-container">
        <header class="reg-header">
            <h1>Registro de Nuevo Inquilino</h1>
            <p>Complete todos los campos para registrar un nuevo residente</p>
        </header>

        <main class="reg-main">
            <form class="registration-form" name="adminresidencial" method="POST" action="#">
                <!-- Sección de información básica -->
                <fieldset class="form-section">
                    <legend>Información Personal</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre(s):</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fecha-nacimiento">Fecha de Nacimiento:</label>
                            <input type="date" id="fecha-nacimiento" name="fecha-nacimiento">
                        </div>
                        
                        <div class="form-group">
                            <label for="genero">Género:</label>
                            <select id="genero" name="genero">
                                <option value="">Seleccionar</option>
                                <option value="femenino">Femenino</option>
                                <option value="masculino">Masculino</option>
                                <option value="otro">Otro</option>
                                <option value="prefiero-no-decir">Prefiero no decir</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <!-- Sección de contacto -->
                <fieldset class="form-section">
                    <legend>Datos de Contacto</legend>
                    
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">Teléfono Principal:</label>
                            <input type="tel" id="telefono" name="telefono" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono-alternativo">Teléfono Alternativo:</label>
                            <input type="tel" id="telefono-alternativo" name="telefono-alternativo">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección Completa:</label>
                        <textarea id="direccion" name="direccion" rows="2"></textarea>
                    </div>
                </fieldset>

                <!-- Sección de información residencial -->
                <fieldset class="form-section">
                    <legend>Información Residencial</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="numero-casa">Número de Casa/Inmueble:</label>
                            <input type="text" id="numero-casa" name="numero-casa" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo-residencia">Tipo de Residencia:</label>
                            <select id="tipo-residencia" name="tipo-residencia" required>
                                <option value="">Seleccionar</option>
                                <option value="casa">Casa</option>
                                <option value="departamento">Departamento</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha-ingreso">Fecha de Ingreso:</label>
                        <input type="date" id="fecha-ingreso" name="fecha-ingreso" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo-contrato">Tipo de Contrato:</label>
                        <select id="tipo-contrato" name="tipo-contrato" required>
                            <option value="">Seleccionar</option>
                            <option value="renta">Renta</option>
                            <option value="propietario">Propietario</option>
                            <option value="familiar">Familiar</option>
                        </select>
                    </div>
                </fieldset>

                <!-- Sección de seguridad -->
                <fieldset class="form-section">
                    <legend>Datos de Acceso</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" required minlength="8">
                            <small class="form-hint">Mínimo 8 caracteres</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm-password">Confirmar Contraseña:</label>
                            <input type="password" id="confirm-password" name="confirm-password" required>
                        </div>
                    </div>
                </fieldset>               

                <!-- Términos y condiciones -->
                <div class="form-group terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Acepto los <a href="#">términos y condiciones</a> y el <a href="#">aviso de privacidad</a></label>
                </div>

                <!-- Botones de acción -->
                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Limpiar Formulario</button>
                    <button type="submit" class="btn btn-primary" name="registro">Registrar Inquilino</button>
                </div>
            </form>
        </main>
    </div>

</body>
</html>

<?php
    if (isset($_POST['registro'])) {
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $nacimiento = $_POST['fecha-nacimiento'];
        $genero = $_POST['genero'];
        $email = $_POST['email'];
        $telefonoP = $_POST['telefono'];
        $telefonoA = $_POST['telefono-alternativo'];
        $direccion = $_POST['direccion'];
        $casa = $_POST['numero-casa'];
        $tipo = $_POST['tipo-residencia'];
        $ingreso = $_POST['fecha-ingreso'];
        $contrato = $_POST['tipo-contrato'];
        $password = $_POST['password'];
        $repeatPass = $_POST['confirm-password'];
        $terminos = isset($_POST['terms']) ? 1 : 0;
    
        // Se recomienda validar que las contraseñas coincidan
        if ($password === $repeatPass) {
            // Hashear la contraseña (muy importante por seguridad)
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
            $insertarDatos = "INSERT INTO inquilinos (
                numero_casa, nombre, apellidos, email, telefono_principal,
                telefono_alternativo, fecha_nacimiento, genero, direccion,
                tipo_residencia, tipo_contrato, fecha_ingreso, password_hash,
                fecha_registro, ultima_actualizacion, esta_activo, acepto_terminos
            ) VALUES (
                '$casa', '$nombre', '$apellidos', '$email', '$telefonoP',
                '$telefonoA', '$nacimiento', '$genero', '$direccion',
                '$tipo', '$contrato', '$ingreso', '$password_hash',
                NOW(), NOW(), 1, $terminos
            )";

            $ejecutarInsertar = mysqli_query($enlace, $insertarDatos);

        } else {
            echo "Las contraseñas no coinciden.";
        }
    }
    
    
?>
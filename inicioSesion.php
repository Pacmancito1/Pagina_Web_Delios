<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion Residencial Loggin</title>
    <link rel="stylesheet" href="styles-loggin.css">
</head>
<body>
    <header>
        <h1 class="titulo">
            Inicio de Sesion
        </h1>
    </header>

    <div class="main-container">
        <div class="container-inquilino">
            <h2 class="titulo-inquilino">
                Inquilino
            </h2>
            <form class="loggin-form-inq" action="loginInquilino.php" method="POST" >
                <div class="aux-num-casa">
                    <label for="inmueble">Numero de Casa/Inmuble: </label>
                    <input type="text" id="inmueble" name="inmueble">
                </div>
                <div class="aux-correo">
                    <label for="email">Correo Electronico: </label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="aux-contraseña">
                    <label for="password">Contraseña: </label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="aux-telefono">
                    <label for="tel">Telefono de Contacto: </label>
                    <input type="tel" name="tel" id="tel">
                </div>
                <div class="aux-ingresar">
                    <input type="submit" value="Ingresar">
                </div>
            </form>
            <div class="aux-registro-inq">
                <a href="registro-inq.php">Registrate como Inquilino</a>
            </div>    
        </div>
        <div class="container-comite">
            <h2 class="titulo-comite">
                Comité
            </h2>
            <form class="loggin-form-com" action="loginComite.php" method="POST">
                <div class="aux-cargo">
                    <label for="cargo">Cargo del comite: </label>
                    <select name="cargo" id="cargo" required>
                        <option value="">Seleccione su cargo</option>
                        <option value="presidente">Presidente</option>
                        <option value="secretario">secretario</option>
                        <option value="vocal">Vocal</option>
                    </select>
                </div>
                <div class="aux-correo-comite">
                    <label for="email-comite">Correo Empresarial: </label>
                    <input type="email" id="email_comite" name="email_comite">
                </div>
                <div class="aux-password-comite">
                    <label for="codigo">Codigo de Acceso: </label>
                    <input type="password" id="codigo" name="codigo">
                </div>
                <div class="aux-boton-com">
                    <input type="submit" value="Ingresar">
                </div>
            </form>
            
            <div class="aux-registro-com">
                <a href="registro-com.php">Registrate como Usuario</a>
            </div>
        </div>
    </div>

</body>
</html>
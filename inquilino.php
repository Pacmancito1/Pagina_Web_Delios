<?php
session_start();
include("db.php");

$usuario = $_SESSION['email'];

$sql = "SELECT * FROM inquilinos WHERE email = '$usuario'";
$resultado = $conexion->query($sql);

if ($data = $resultado->fetch_assoc()) {
    // Datos del inquilino
    $id_inquilino = $data['id_inquilino'];
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
    $fecha_ingreso = $data['fecha_ingreso'];
    $password_hash = $data['password_hash'];
    $fecha_registro = $data['fecha_registro'];
    $ultima_actualizacion = $data['ultima_actualizacion'];
    $esta_activo = $data['esta_activo'];
    $acepto_terminos = $data['acepto_terminos'];

    // Insertar solicitud si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $fecha = date('Y-m-d');
        $estatus = 'pendiente';

        $sqlInsert = "INSERT INTO solicitudes (id_inquilino, tipo, descripcion, fecha, estatus)
                      VALUES ($id_inquilino, '$tipo', '$descripcion', '$fecha', '$estatus')";
        mysqli_query($conexion, $sqlInsert);

        // Redirigir para evitar reenvío múltiple
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Obtener solicitudes
    $sqlSolicitudes = "SELECT s.*, i.nombre, i.apellidos 
                       FROM solicitudes s 
                       JOIN inquilinos i ON s.id_inquilino = i.id_inquilino 
                       ORDER BY fecha DESC";
    $resSolicitudes = mysqli_query($conexion, $sqlSolicitudes);

    // Consulta de pagos del inquilino actual
    $sqlPagos = "SELECT * FROM pagos WHERE id_inquilino = $id_inquilino ORDER BY fecha_pago DESC";
    $resPagos = mysqli_query($conexion, $sqlPagos);

    // Calcular el estado de cuenta del mes actual
    $mesActual = date('m'); // mes actual (ej: 04)
    $anioActual = date('Y'); // año actual (ej: 2025)

    $sqlEstadoCuenta = "SELECT * FROM pagos 
        WHERE id_inquilino = $id_inquilino 
        AND MONTH(fecha_pago) = $mesActual 
        AND YEAR(fecha_pago) = $anioActual 
        LIMIT 1";

    $resEstadoCuenta = mysqli_query($conexion, $sqlEstadoCuenta);

    $estadoPago = "Pendiente";
    $fechaPago = "Sin registro";
    $total = 700.00; // Total si hay recargo por no pagar

    if ($rowEstado = mysqli_fetch_assoc($resEstadoCuenta)) {
        $estadoPago = $rowEstado['estado'];
        $fechaPago = $rowEstado['fecha_pago'];
        $total = $rowEstado['total'];
    }

} else {
    // Si no se encuentra el inquilino
    $resPagos = null;
    $resSolicitudes = null;
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administracion Residencial-Inquilino</title>
    <link rel="stylesheet" href="styles-inquilino.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Barra lateral de navegación -->
        <aside class="sidebar">
            <div class="user-info">
                <img src="img/user-avatar.png" alt="Avatar" class="user-avatar">
                <h3><?php echo $nombre . ' ' . $apellidos; ?></h3>
                <p>Casa: <?php echo $numero_casa ?></p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="#" class="active"><i class="icon-home"></i> Inicio</a></li>
                    <li><a href="#"><i class="icon-payments"></i> Pagos</a></li>
                    <li><a href="#"><i class="icon-reservations"></i> Reservas</a></li>
                    <li><a href="#"><i class="icon-services"></i> Servicios</a></li>
                    <li><a href="#"><i class="icon-settings"></i> Configuración</a></li>
                </ul>
            </nav>
            
            <div class="logout-section">
                <a href="cerrarSesion.php" class="btn-logout"><i class="icon-logout"></i> Cerrar Sesión</a>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <header class="content-header">
                <h2>Bienvenido, <?php echo $nombre ?></h2>
                <div class="header-actions">
                    <div class="quick-actions">
                        <a href="pagarM.php">
                            <button class="btn-pay">Pagar mantenimiento</button>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Sección de estado de cuenta -->
            <section class="account-status">
    <div class="status-card">
        <h3>Estado de Cuenta</h3>
        <div class="status-info">
            <div class="status-item">
                <span>Cuota mensual:</span>
                <span>$650.00 MXN</span>
            </div>
            <div class="status-item">
                <span>Estado actual:</span>
                <span class="<?php echo $estadoPago === 'Pagado' ? 'status-paid' : 'status-unpaid'; ?>">
                    <?php echo $estadoPago; ?>
                </span>
            </div>
            <div class="status-item">
                <span>Fecha de pago:</span>
                <span><?php echo $fechaPago; ?></span>
            </div>
            <div class="status-item">
                <span>Total:</span>
                <span>$<?php echo number_format($total, 2); ?> MXN</span>
            </div>
        </div>
    </div>
</section>


            <!-- Sección de pagos -->
            <section class="payments-section">
                <h3>Historial de Pagos</h3>
                <div class="payments-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Comprobante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($resPagos && mysqli_num_rows($resPagos) > 0) {
                                    while ($pago = mysqli_fetch_assoc($resPagos)) {
                                        echo "<tr>";
                                        echo "<td>" . date("d/m/Y", strtotime($pago['fecha_pago'])) . "</td>";
                                        echo "<td>" . htmlspecialchars($pago['concepto']) . "</td>";
                                        echo "<td>$" . number_format($pago['monto'], 2) . "</td>";
                                        echo "<td><span class='status-paid'>Pagado</span></td>";
                                        echo "<td><a href='#' class='btn-download'>Descargar</a></td>"; 
                                        echo "</tr>";
                                     }
                                } else {
                                    echo "<tr><td colspan='5'>No se encontraron pagos registrados.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Sección de reservas -->
            <section class="reservations-section">
                <h3>Reservar Áreas Comunes</h3>
                <div class="reservation-cards">
                    <div class="reservation-card">
                        <h4>Palapa</h4>
                        <p>Capacidad: 20 personas</p>
                        <a href="reservaPalapa.php">
                            <button class="btn-reserve">Reservar</button>
                        </a>
                    </div>
                    <div class="reservation-card">
                        <h4>Alberca</h4>
                        <p>Horario: 9:00 - 20:00 hrs</p>
                        <a href="reservAlberca.php">
                            <button class="btn-reserve">Reservar</button>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Sección de solicitudes -->
            <section class="requests-section">
                <h3>Solicitudes de Servicio</h3>

                    <div class="request-form">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="request-type">Tipo de solicitud:</label>
                                <select id="request-type" name="tipo">
                                    <option value="mantenimiento">Mantenimiento</option>
                                    <option value="reparacion">Reparación</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        <div class="form-group2">
                            <label for="request-details">Descripción:</label>
                            <textarea id="request-details" name="descripcion" rows="3" required></textarea>
                        </div>
                             <button type="submit" class="btn-submit">Enviar Solicitud</button>
                        </form>
                    </div>

                    <div class="requests-list">
    <h4>Solicitudes Recientes</h4>
    <?php if ($resSolicitudes && mysqli_num_rows($resSolicitudes) > 0): ?>
        <?php while ($sol = mysqli_fetch_assoc($resSolicitudes)): ?>
            <div class="request-item">
                <div class="request-header">
                    <strong>Solicitud de <?= htmlspecialchars(ucfirst($sol['tipo'])) ?> realizada por: 
                    <?= htmlspecialchars($sol['nombre'] . ' ' . $sol['apellidos']) ?> el 
                    <?= htmlspecialchars($sol['fecha']) ?></strong>
                </div>
                <div class="request-body">
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($sol['descripcion']) ?></p>
                    <p><strong>Estatus:</strong> 
                        <span class="request-status status-<?= htmlspecialchars(strtolower($sol['estatus'])) ?>">
                            <?= htmlspecialchars(ucfirst($sol['estatus'])) ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay solicitudes registradas.</p>
    <?php endif; ?>
</div>

            </section>

        </main>
    </div>

</body>
</html>
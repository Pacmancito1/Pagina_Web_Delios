<?php
session_start();
include("db.php");

// Asegurar que el usuario esté logueado
if (!isset($_SESSION['email_comite'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['email_comite'];

// Obtener datos del comité
$sql = "SELECT * FROM comite WHERE email = '$usuario'";
$resultado = $conexion->query($sql);

if ($data = $resultado->fetch_assoc()) {
    $cargo = $data['cargo'];
}

$idInquilino = isset($_GET['inquilino']) ? $_GET['inquilino'] : '';

// Obtener inquilinos que ya pagaron
$sqlPagos = "SELECT p.*, i.nombre, i.apellidos, i.numero_casa 
             FROM pagos p 
             INNER JOIN inquilinos i ON p.id_inquilino = i.id_inquilino 
             WHERE p.estado = 'pagado'";

if (!empty($idInquilino)) {
    $sqlPagos .= " AND p.id_inquilino = '$idInquilino'";
}

$sqlPagos .= " ORDER BY p.fecha_pago DESC";

$resultPagos = $conexion->query($sqlPagos);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta</title>
    <link rel="stylesheet" href="styles-comite-pagos-lista.css">
</head>
<body>
    <div class="dashboard-container">
        <main class="main-content">
            <header class="content-header">
                <h2>Estado de Cuenta</h2>
                <div class="user-actions">
                    <span class="user-name"><?php echo $cargo; ?></span>
                    <a href="cerrarSesion.php" class="btn-logout">Cerrar Sesión</a>
                </div>
            </header>

            <section class="requests-section">
                <h3>Inquilinos que han pagado</h3>
                <section class="filter-section">
                    <form method="GET" action="">
                     <label for="inquilino">Filtrar por Inquilino:</label>
                    <select name="inquilino" id="inquilino">
                    <option value="">Todos</option>
                    <?php
            // Aquí haces la consulta para traer a todos los inquilinos
                        $queryInquilinos = "SELECT id_inquilino, nombre, apellidos FROM inquilinos WHERE esta_activo = 1";
                        $resultInquilinos = $conexion->query($queryInquilinos);

                        if ($resultInquilinos->num_rows > 0) {
                            while ($inquilino = $resultInquilinos->fetch_assoc()) {
                                $nombreCompleto = htmlspecialchars($inquilino['nombre'] . ' ' . $inquilino['apellidos']);
                                echo "<option value=\"{$inquilino['id_inquilino']}\">$nombreCompleto</option>";
                            }
                        }
                    ?>
                </select>
                <button type="submit">Aplicar Filtro</button>
                </form>
            </section>

                <div class="payments-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Pago</th>
                                <th>Fecha de Pago</th>
                                <th>Nombre del Inquilino</th>
                                <th>Número de Casa</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultPagos->num_rows > 0): ?>
                                <?php while ($pago = $resultPagos->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $pago['id_pagos']; ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($pago['fecha_pago'])); ?></td>
                                        <td><?php echo htmlspecialchars($pago['nombre'] . ' ' . $pago['apellidos']); ?></td>
                                        <td><?php echo $pago['numero_casa']; ?></td>
                                        <td>$<?php echo number_format($pago['monto'], 2); ?></td>
                                        <td><span class="status-paid">Pagado</span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6">No hay pagos registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

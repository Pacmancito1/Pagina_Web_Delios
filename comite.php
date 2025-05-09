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

        // Total ingresos del mes actual
        $ingresosMesQuery = "
        SELECT SUM(p.monto) AS total_ingresos, COUNT(DISTINCT i.numero_casa) AS casas_pagadas
        FROM pagos p
        INNER JOIN inquilinos i ON p.id_inquilino = i.id_inquilino
        WHERE MONTH(p.fecha_pago) = MONTH(CURDATE()) AND YEAR(p.fecha_pago) = YEAR(CURDATE())
    ";
    
$ingresosMes = $conexion->query($ingresosMesQuery)->fetch_assoc();

// Total egresos del mes actual
$egresosMesQuery = "SELECT SUM(monto) AS total_egresos, COUNT(id_egreso) AS total_conceptos
FROM egresos 
WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
$egresosMes = $conexion->query($egresosMesQuery)->fetch_assoc();

// Saldo actual (ingresos - egresos)
$saldo_actual = floatval($ingresosMes['total_ingresos']) - floatval($egresosMes['total_egresos']);


    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Comité Administrativo</title>
    <link rel="stylesheet" href="styles-comite.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Barra lateral -->
        <aside class="sidebar">
            <div class="comite-info">
                <img src="img/comite-avatar.png" alt="Avatar" class="comite-avatar">
                <h3>Comité Residencial</h3>
                <p class="cargo"><?php echo $cargo; ?></p>
            </div>
            
            <nav class="comite-nav">
                <ul>
                    <li><a href="#" class="active"><i class="icon-dashboard"></i> Panel</a></li>
                    <li><a href="estadoCuenta.php"><i class="icon-finanzas"></i> Finanzas</a></li>
                    <li><a href="controlPagos.php"><i class="icon-pagos"></i> Control de Pagos</a></li>
                    <li><a href="reportes.php"><i class="icon-informe"></i> Informes</a></li>
                    <li><a href="#"><i class="icon-solicitudes"></i> Solicitudes</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <header class="content-header">
                <h2>Panel del Comité</h2>
                <div class="header-actions">
                    <button class="btn-generar-informe">Generar Informe Mensual</button>
                    <div class="user-actions">
                        <span class="user-name"><?php echo $cargo; ?></span>
                        <a href="cerrarSesion.php" class="btn-logout"><i class="icon-logout"></i> Cerrar Sesión</a>
                    </div>
                </div>
            </header>

            <!-- Sección de resumen financiero -->
            <section class="financial-summary">
    <h3>Resumen Financiero</h3>
    <div class="summary-cards">
        <div class="summary-card">
            <h4>Ingresos del Mes</h4>
            <p class="amount">
                $<?php echo number_format($ingresosMes['total_ingresos'], 2); ?>
            </p>
            <p class="detail">
                (<?php echo $ingresosMes['casas_pagadas']; ?> casas pagadas)
            </p>
        </div>
        <div class="summary-card">
            <h4>Egresos del Mes</h4>
            <p class="amount">
                $<?php echo number_format($egresosMes['total_egresos'], 2); ?>
            </p>
            <p class="detail">
                (<?php echo $egresosMes['total_conceptos']; ?> conceptos)
            </p>
        </div>
        <div class="summary-card">
            <h4>Saldo Actual</h4>
            <p class="amount <?php echo $saldo_actual >= 0 ? 'positive' : 'negative'; ?>">
                $<?php echo number_format($saldo_actual, 2); ?>
            </p>
            <p class="detail">Disponible</p>
        </div>
    </div>
</section>

            <!-- Sección de solicitudes -->
            <section class="requests-section">
    <h3>Solicitudes Recientes</h3>
    <div class="requests-grid">
        <?php while ($solicitud = $resultSolicitudes->fetch_assoc()): ?>
            <div class="request-card">
                <div class="request-header">

                    <span class="request-id">#<?php echo str_pad($solicitud['id_solicitud'], 1, '0', STR_PAD_LEFT); ?></span>
                    <span class="request-date"><?php echo date("d/m/Y", strtotime($solicitud['fecha'])); ?></span>
                    <span class="request-status <?php echo strtolower($solicitud['estatus']); ?>">
                        <?php echo ucfirst($solicitud['estatus']); ?>
                    </span>
                </div>
                <p class="request-description"><?php echo htmlspecialchars($solicitud['descripcion']); ?></p>
                <div class="request-meta">
                    <span>Casa <?php echo $solicitud['numero_casa']; ?> - <?php echo $solicitud['nombre'] . " " . $solicitud['apellidos']; ?></span>
                    <button class="btn-responder">Responder</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

        </main>
    </div>

</body>
</html>
<?php
include 'conexion_be.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

include 'validar_sesion.php';

// Obtener el filtro seleccionado
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
$filtro_fecha = isset($_GET['filtro_fecha']) ? $_GET['filtro_fecha'] : 'hoy';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Construir la consulta SQL según el filtro de fecha
if ($filtro_fecha === 'hoy') {
    $consulta_fecha = "DATE(hora) = CURDATE()";
} elseif ($filtro_fecha === 'ayer') {
    $consulta_fecha = "DATE(hora) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
} elseif ($filtro_fecha === 'ultimos_7_dias') {
    $consulta_fecha = "DATE(hora) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($filtro_fecha === 'personalizado' && $fecha_inicio && $fecha_fin) {
    $consulta_fecha = "DATE(hora) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
} else {
    $consulta_fecha = "1"; // Mostrar todos los registros si no hay filtro válido
}

// Construir la consulta SQL según el filtro
if ($filtro === 'todos') {
    $consulta = "
        SELECT c.id, t.nombre, t.apellido, t.cedula, 'Cocinero' AS tipo_trabajador, c.tipo, c.hora
        FROM cocineros c
        INNER JOIN trabajadores t ON c.id_trabajador = t.id_trabajador
        WHERE $consulta_fecha
        UNION
        SELECT v.id, t.nombre, t.apellido, t.cedula, 'Vigilante' AS tipo_trabajador, v.tipo, v.hora
        FROM vigilantes v
        INNER JOIN trabajadores t ON v.id_trabajador = t.id_trabajador
        WHERE $consulta_fecha
        UNION
        SELECT m.id, t.nombre, t.apellido, t.cedula, 'Maestro' AS tipo_trabajador, m.tipo, m.hora
        FROM maestros m
        INNER JOIN trabajadores t ON m.id_trabajador = t.id_trabajador
        WHERE $consulta_fecha
        UNION
        SELECT o.id, t.nombre, t.apellido, t.cedula, 'Obrero' AS tipo_trabajador, o.tipo, o.hora
        FROM obreros o
        INNER JOIN trabajadores t ON o.id_trabajador = t.id_trabajador
        WHERE $consulta_fecha
        ORDER BY hora DESC
    ";
} else {
    $consulta = "
        SELECT c.id, t.nombre, t.apellido, t.cedula, 'Cocinero' AS tipo_trabajador, c.tipo, c.hora
        FROM cocineros c
        INNER JOIN trabajadores t ON c.id_trabajador = t.id_trabajador
        WHERE 'Cocinero' = '$filtro' AND $consulta_fecha
        UNION
        SELECT v.id, t.nombre, t.apellido, t.cedula, 'Vigilante' AS tipo_trabajador, v.tipo, v.hora
        FROM vigilantes v
        INNER JOIN trabajadores t ON v.id_trabajador = t.id_trabajador
        WHERE 'Vigilante' = '$filtro' AND $consulta_fecha
        UNION
        SELECT m.id, t.nombre, t.apellido, t.cedula, 'Maestro' AS tipo_trabajador, m.tipo, m.hora
        FROM maestros m
        INNER JOIN trabajadores t ON m.id_trabajador = t.id_trabajador
        WHERE 'Maestro' = '$filtro' AND $consulta_fecha
        UNION
        SELECT o.id, t.nombre, t.apellido, t.cedula, 'Obrero' AS tipo_trabajador, o.tipo, o.hora
        FROM obreros o
        INNER JOIN trabajadores t ON o.id_trabajador = t.id_trabajador
        WHERE 'Obrero' = '$filtro' AND $consulta_fecha
        ORDER BY hora DESC
    ";
}

// Ejecutar la consulta
$resultado = $enlace->query($consulta);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . $enlace->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asistencias</title>
    <link rel="stylesheet" href="Stilos/styles_asistencias.css">
    <link rel="stylesheet" href="Stilos/styles_tablas.css">
    <link rel="stylesheet" href="Stilos/css/bootstrap.min.css">
</head>
<body>

    <?php include 'vista/top-bar.php'; ?>

    <h1>Gestión de Asistencias</h1>

    <div class="filtros">
        <h2>Filtros</h2>
        <!-- Filtros combinados -->
        <form method="GET" action="">
            <!-- Filtro por tipo de trabajador -->
            <label for="filtro">Filtrar por tipo de trabajador:</label>
            <select name="filtro" id="filtro">
                <option value="todos" <?php echo $filtro === 'todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="Cocinero" <?php echo $filtro === 'Cocinero' ? 'selected' : ''; ?>>Cocinero</option>
                <option value="Vigilante" <?php echo $filtro === 'Vigilante' ? 'selected' : ''; ?>>Vigilante</option>
                <option value="Maestro" <?php echo $filtro === 'Maestro' ? 'selected' : ''; ?>>Maestro</option>
                <option value="Obrero" <?php echo $filtro === 'Obrero' ? 'selected' : ''; ?>>Obrero</option>
            </select>

            <!-- Filtro por rango de fechas -->
            <label for="filtro_fecha">Filtrar por fecha:</label>
            <select name="filtro_fecha" id="filtro_fecha" onchange="toggleFechaPersonalizada()">
                <option value="hoy" <?php echo $filtro_fecha === 'hoy' ? 'selected' : ''; ?>>Hoy</option>
                <option value="ayer" <?php echo $filtro_fecha === 'ayer' ? 'selected' : ''; ?>>Ayer</option>
                <option value="ultimos_7_dias" <?php echo $filtro_fecha === 'ultimos_7_dias' ? 'selected' : ''; ?>>Últimos 7 días</option>
                <option value="personalizado" <?php echo $filtro_fecha === 'personalizado' ? 'selected' : ''; ?>>Personalizado</option>
            </select>

            <!-- Campos para rango de fechas personalizado -->
            <div id="fechas_personalizadas" style="display: <?php echo $filtro_fecha === 'personalizado' ? 'block' : 'none'; ?>;">
                <label for="fecha_inicio">Desde:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                <label for="fecha_fin">Hasta:</label>
                <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo $fecha_fin; ?>">
            </div>

            <!-- Botón para aplicar los filtros -->
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <!-- Tabla para mostrar los registros -->
    <h2>Lista de Registros</h2>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido</th>
            <th scope="col">Cédula</th>
            <th scope="col">Tipo de Trabajador</th>
            <th scope="col">Tipo</th>
            <th scope="col">Hora</th>
          </tr>
        </thead>
        <tbody>
            <?php 
            $numero_fila = 1; // Inicializa el contador de filas
            while ($fila = $resultado->fetch_assoc()): 
            ?>
                <tr>
                    <td scope="row"><?php echo $numero_fila++; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['cedula']; ?></td>
                    <td><?php echo $fila['tipo_trabajador']; ?></td>
                    <td><?php echo $fila['tipo']; ?></td>
                    <td><?php echo substr($fila['hora'], 0, 16); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function toggleFechaPersonalizada() {
            const filtroFecha = document.getElementById('filtro_fecha').value;
            const fechasPersonalizadas = document.getElementById('fechas_personalizadas');
            if (filtroFecha === 'personalizado') {
                fechasPersonalizadas.style.display = 'block';
            } else {
                fechasPersonalizadas.style.display = 'none';
            }
        }
    </script>

    <script src="Java/js/bootstrap.bundle.min.js"></script>
    <script src="Java/js.js"></script>
</body>
</html>
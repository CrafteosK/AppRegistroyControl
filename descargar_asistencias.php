<?php
require('fpdf/fpdf.php');
include 'conexion_be.php'; // Conexión a la base de datos

// Obtener los filtros de la URL
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

// Construir la consulta SQL según el filtro de tipo de trabajador
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

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Registros de Asistencias'), 0, 1, 'C');
$pdf->Ln(10);

// Agregar encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, utf8_decode('Nombre'), 1);
$pdf->Cell(30, 10, utf8_decode('Apellido'), 1);
$pdf->Cell(30, 10, utf8_decode('Cédula'), 1);
$pdf->Cell(40, 10, utf8_decode('Tipo Trabajador'), 1);
$pdf->Cell(20, 10, utf8_decode('Tipo'), 1);
$pdf->Cell(30, 10, utf8_decode('Hora'), 1);
$pdf->Ln();

// Agregar los datos al PDF
$pdf->SetFont('Arial', '', 10);
while ($fila = $resultado->fetch_assoc()) {
    $pdf->Cell(30, 10, utf8_decode($fila['nombre']), 1);
    $pdf->Cell(30, 10, utf8_decode($fila['apellido']), 1);
    $pdf->Cell(30, 10, utf8_decode($fila['cedula']), 1);
    $pdf->Cell(40, 10, utf8_decode($fila['tipo_trabajador']), 1);
    $pdf->Cell(20, 10, utf8_decode($fila['tipo']), 1);
    $pdf->Cell(30, 10, utf8_decode(substr($fila['hora'], 0, 16)), 1); // Mostrar solo fecha y hora sin segundos
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'asistencias_filtradas.pdf'); // Descarga directa del archivo
exit();
?>
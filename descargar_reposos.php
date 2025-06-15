<?php
require('fpdf/fpdf.php');
include 'conexion_be.php'; // Conexión a la base de datos

// Obtener los filtros de la URL
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
$filtro_fecha = isset($_GET['filtro_fecha']) ? $_GET['filtro_fecha'] : 'todos';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Construir la consulta SQL según el filtro de fecha
if ($filtro_fecha === 'hoy') {
    $consulta_fecha = "DATE(m.vence) = CURDATE()";
} elseif ($filtro_fecha === 'ayer') {
    $consulta_fecha = "DATE(m.vence) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
} elseif ($filtro_fecha === 'ultimos_7_dias') {
    $consulta_fecha = "DATE(m.vence) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($filtro_fecha === 'personalizado' && $fecha_inicio && $fecha_fin) {
    $consulta_fecha = "DATE(m.vence) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
} else {
    $consulta_fecha = "1"; // Mostrar todos los registros si no hay filtro válido
}

// Construir la consulta SQL según el filtro de tipo de trabajador
if ($filtro === 'todos') {
    $consulta = "
        SELECT 
            t.nombre, 
            t.apellido, 
            t.cedula, 
            c.cargo AS tipo_trabajador, 
            m.expedicion AS fecha_expedicion, 
            m.vence AS fecha_vencimiento
        FROM medical_rest m
        INNER JOIN trabajadores t ON m.id_trabajador = t.id_trabajador
        INNER JOIN cargos c ON t.cargos = c.id_cargo
        WHERE $consulta_fecha
        ORDER BY m.vence DESC
    ";
} else {
    $consulta = "
        SELECT 
            t.nombre, 
            t.apellido, 
            t.cedula, 
            c.cargo AS tipo_trabajador, 
            m.expedicion AS fecha_expedicion, 
            m.vence AS fecha_vencimiento
        FROM medical_rest m
        INNER JOIN trabajadores t ON m.id_trabajador = t.id_trabajador
        INNER JOIN cargos c ON t.cargos = c.id_cargo
        WHERE c.cargo = '$filtro' AND $consulta_fecha
        ORDER BY m.vence DESC
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
    $pdf->SetFont('Arial', 'B', 16); // Fuente más grande para el título principal
    $pdf->Cell(0, 10, utf8_decode('C.E.I. Simoncito Guayana'), 0, 1, 'C');
    $pdf->Ln(5); // Espacio debajo del título principal

    $pdf->SetFont('Arial', 'B', 14); // Fuente más pequeña para el subtítulo
    $pdf->Cell(0, 10, utf8_decode('Registro de reportes medicos'), 0, 1, 'C');
    $pdf->Ln(1);

// Agregar encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, utf8_decode('Nombre'), 1);
$pdf->Cell(30, 10, utf8_decode('Apellido'), 1);
$pdf->Cell(30, 10, utf8_decode('Cédula'), 1);
$pdf->Cell(40, 10, utf8_decode('Tipo Trabajador'), 1);
$pdf->Cell(30, 10, utf8_decode('Expedición'), 1);
$pdf->Cell(30, 10, utf8_decode('Vencimiento'), 1);
$pdf->Ln();

// Agregar los datos al PDF
$pdf->SetFont('Arial', '', 10);
while ($fila = $resultado->fetch_assoc()) {
    $pdf->Cell(30, 10, utf8_decode($fila['nombre']), 1);
    $pdf->Cell(30, 10, utf8_decode($fila['apellido']), 1);
    $pdf->Cell(30, 10, utf8_decode($fila['cedula']), 1);
    $pdf->Cell(40, 10, utf8_decode($fila['tipo_trabajador']), 1);
    $pdf->Cell(30, 10, utf8_decode(date('d/m/Y', strtotime($fila['fecha_expedicion']))), 1);
    $pdf->Cell(30, 10, utf8_decode(date('d/m/Y', strtotime($fila['fecha_vencimiento']))), 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output('D', 'reposos_filtrados.pdf'); // Descarga directa del archivo
exit();
?>
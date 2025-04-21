<?php
error_reporting(E_ERROR | E_PARSE); // Solo mostrar errores críticos
require('fpdf/fpdf.php');
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica si se ha enviado el parámetro "file"
if (isset($_GET['file'])) {
    $file = $_GET['file'];

    // Configura el nombre del archivo PDF, el título y la consulta SQL según el archivo solicitado
    if ($file === 'registros_maestros.pdf') {
        $titulo = 'Registros de Maestros';
        $consulta = "
            SELECT m.id, t.nombre, t.apellido, t.cedula, m.tipo, m.hora
            FROM maestros m
            INNER JOIN trabajadores t ON m.id_trabajador = t.id_trabajador
        ";
    } elseif ($file === 'registros_cocineros.pdf') {
        $titulo = 'Registros de Cocineros';
        $consulta = "
            SELECT c.id, t.nombre, t.apellido, t.cedula, c.tipo, c.hora
            FROM cocineros c
            INNER JOIN trabajadores t ON c.id_trabajador = t.id_trabajador
        ";
    } elseif ($file === 'registros_vigilantes.pdf') {
        $titulo = 'Registros de Vigilantes';
        $consulta = "
            SELECT v.id, t.nombre, t.apellido, t.cedula, v.tipo, v.hora
            FROM vigilantes v
            INNER JOIN trabajadores t ON v.id_trabajador = t.id_trabajador
        ";
    } elseif ($file === 'registros_obreros.pdf') {
        $titulo = 'Registros de Obreros';
        $consulta = "
            SELECT o.id, t.nombre, t.apellido, t.cedula, o.tipo, o.hora
            FROM obreros o
            INNER JOIN trabajadores t ON o.id_trabajador = t.id_trabajador
        ";
    } elseif ($file === 'registros_trabajadores.pdf') {
        $titulo = 'Registros de Trabajadores';
        $consulta = "
            SELECT id_trabajador, nombre, apellido, cedula, telefono, cargos
            FROM trabajadores 
        ";
    } else {
        die('Archivo no válido.');
    }

    // Ejecuta la consulta
    $resultado = $enlace->query($consulta);

    if (!$resultado) {
        die("Error en la consulta: " . $enlace->error);
    }

    // Crea el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
    $pdf->Ln(10);

    

    

    // Agrega encabezados y datos específicos para registros_trabajadores.pdf
    if ($file === 'registros_trabajadores.pdf') {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'ID', 1);
        $pdf->Cell(40, 10, 'Nombre', 1);
        $pdf->Cell(40, 10, 'Apellido', 1);
        $pdf->Cell(30, 10, 'Cedula', 1);
        $pdf->Cell(30, 10, 'Telefono', 1);
        $pdf->Cell(30, 10, 'Cargo', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        while ($fila = $resultado->fetch_assoc()) {
            $pdf->Cell(20, 10, $fila['id_trabajador'], 1);
            $pdf->Cell(40, 10, $fila['nombre'], 1);
            $pdf->Cell(40, 10, $fila['apellido'], 1);
            $pdf->Cell(30, 10, $fila['cedula'], 1);
            $pdf->Cell(30, 10, $fila['telefono'], 1);
            $pdf->Cell(30, 10, $fila['cargos'], 1);
            $pdf->Ln();
        }
    } else {
        // Encabezados de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'ID', 1);
        $pdf->Cell(40, 10, 'Nombre', 1);
        $pdf->Cell(40, 10, 'Apellido', 1);
        $pdf->Cell(30, 10, 'Cedula', 1);
        $pdf->Cell(30, 10, 'Tipo', 1);
        $pdf->Cell(30, 10, 'Hora', 1);
        $pdf->Ln();

        // Datos de la tabla
        $pdf->SetFont('Arial', '', 10);
        while ($fila = $resultado->fetch_assoc()) {
            // Verifica si las claves existen antes de usarlas
            $id = isset($fila['id']) ? $fila['id'] : 'N/A';
            $tipo = isset($fila['tipo']) ? $fila['tipo'] : 'N/A';
            $hora_sin_segundos = isset($fila['hora']) ? substr($fila['hora'], 0, 16) : 'N/A';

            $pdf->Cell(20, 10, $id, 1);
            $pdf->Cell(40, 10, $fila['nombre'], 1);
            $pdf->Cell(40, 10, $fila['apellido'], 1);
            $pdf->Cell(30, 10, $fila['cedula'], 1);
            $pdf->Cell(30, 10, $tipo, 1);
            $pdf->Cell(30, 10, $hora_sin_segundos, 1);
            $pdf->Ln();
        }
    }

    // Salida del PDF
    $pdf->Output('D', $file); // Descarga directa del archivo
    exit();
} else {
    die('No se especificó ningún archivo.');
}
?>
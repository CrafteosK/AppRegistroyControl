<?php
// filepath: c:\xampp\htdocs\AppRegistroyControl\maestros.php

include 'conexion_be.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

// Consulta SQL para obtener los registros de la tabla maestros junto con el nombre, apellido y cédula del trabajador
$resultado = $enlace->query("
    SELECT m.id, t.nombre, t.apellido, t.cedula, m.tipo, m.hora
    FROM maestros m
    INNER JOIN trabajadores t ON m.id_trabajador = t.id_trabajador
");

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
    <title>Gestión de Maestros</title>
    <link rel="stylesheet" href="Stilos/stilos_maestros.css"> <!-- Opcional: agrega estilos -->
    <link rel="stylesheet" href="Stilos/css/bootstrap.min.css"> <!-- Opcional: agrega estilos a las tablas -->
    <link rel="stylesheet" href="Stilos/styles_tablas.css"> <!-- Opcional: agrega estilos a las tablas -->
</head>
<body>

    <?php include 'vista/top-bar.php'; ?>

    <h1>Gestión de Maestros</h1>

    <!-- Tabla para mostrar los maestros -->
    <h2>Lista de Maestros</h2>
    <a href="descargar.php?file=registros_maestros.pdf" class="download-button">Descargar</a>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido</th>
            <th scope="col">Cédula</th>
            <th scope="col">Tipo</th>
            <th scope="col">Hora</th>
          </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td scope="row" id="td"><?php echo isset($fila['id']) ? $fila['id'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['nombre']) ? $fila['nombre'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['apellido']) ? $fila['apellido'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['cedula']) ? $fila['cedula'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['tipo']) ? $fila['tipo'] : 'N/A'; ?></td>
                    <td id="td">
                        <?php 
                        // Mostrar la hora sin segundos
                        echo isset($fila['hora']) ? substr($fila['hora'], 0, 16) : 'N/A'; 
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="Java/js/bootstrap.bundle.min.js"></script>
    <script src="Java/js.js"></script>

</body>
</html>
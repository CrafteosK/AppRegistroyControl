<?php
// Asegúrate de incluir la conexión a la base de datos


// Verificar si se ha pasado el nombre de la tabla
if (!isset($_GET['tabla'])) {
    die("No se especificó ninguna tabla.");
}

// Obtener el nombre de la tabla desde la URL
$tabla = htmlspecialchars($_GET['tabla']);

// Validar que la tabla sea una de las permitidas
$tablas_permitidas = ['maestros', 'obreros', 'cocineros', 'vigilantes'];
if (!in_array($tabla, $tablas_permitidas)) {
    die("Tabla no permitida.");
}

// Consulta SQL para obtener los datos de la tabla especificada
$resultado = $enlace->query("
    SELECT id_trabajador AS id, nombre, cedula, telefono, hora_entrada, hora_salida
    FROM $tabla
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
</head>
<body>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Cédula</th>
            <th scope="col">Teléfono</th>
            <th scope="col">Hora de Entrada</th>
            <th scope="col">Hora de Salida</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td scope="row" id="td"><?php echo isset($fila['id']) ? $fila['id'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['nombre']) ? $fila['nombre'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['cedula']) ? $fila['cedula'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['telefono']) ? $fila['telefono'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['hora_entrada']) ? $fila['hora_entrada'] : 'N/A'; ?></td>
                    <td id="td"><?php echo isset($fila['hora_salida']) ? $fila['hora_salida'] : 'N/A'; ?></td>
                    <td>
                        <!-- Botón para eliminar -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo isset($fila['id']) ? $fila['id'] : ''; ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
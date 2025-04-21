<?php
// filepath: c:\xampp\htdocs\AppRegistroyControl\cocineros.php

include 'conexion_be.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

// Manejar la adición de un nuevo cocinero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $hora_entrada = trim($_POST['hora_entrada']);
    $hora_salida = trim($_POST['hora_salida']);

    if (!empty($nombre) && !empty($cedula) && !empty($telefono) && !empty($hora_entrada) && !empty($hora_salida)) {
        $stmt = $enlace->prepare("INSERT INTO cocineros (nombre, cedula, telefono, hora_entrada, hora_salida) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $cedula, $telefono, $hora_entrada, $hora_salida);
        $stmt->execute();
        $stmt->close();
    }
}

// Manejar la eliminación de un cocinero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = intval($_POST['id']);
    $stmt = $enlace->prepare("DELETE FROM cocineros WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Obtener todos los registros de la tabla cocineros
$resultado = $enlace->query("SELECT * FROM cocineros");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cocineros</title>
    <link rel="stylesheet" href="Stilos/styles_cocineros.css"> <!-- Opcional: agrega estilos -->
</head>
<body>

    <?php include 'vista/top-bar.php'; ?>


    <h1>Gestión de Cocineros</h1>

    <!-- Formulario para agregar un nuevo cocinero -->
    <form method="POST" action="">
        <h2>Agregar Cocinero</h2>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="cedula" placeholder="Cédula" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="time" name="hora_entrada" placeholder="Hora de Entrada" required>
        <input type="time" name="hora_salida" placeholder="Hora de Salida" required>
        <button type="submit" name="agregar">Agregar</button>
        <a href="descargar.php?file=registros_cocineros.pdf" class="download-button">Descargar</a>
    </form>

    <!-- Tabla para mostrar los cocineros -->
    <h2>Lista de Cocineros</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['cedula']; ?></td>
                    <td><?php echo $fila['telefono']; ?></td>
                    <td><?php echo $fila['hora_entrada']; ?></td>
                    <td><?php echo $fila['hora_salida']; ?></td>
                    <td>
                        <!-- Botón para eliminar -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="Java/js.js"></script>

</body>
</html>
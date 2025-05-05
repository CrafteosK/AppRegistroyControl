<?php
// Incluir la conexión a la base de datos
include 'conexion_be.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos
include 'validar_sesion.php';

// Manejar la adición de un nuevo trabajador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellido = htmlspecialchars(trim($_POST['apellido']));
    $cedula = htmlspecialchars(trim($_POST['cedula']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $cargos = intval($_POST['cargos']);

    // Validar campos vacíos
    if (empty($nombre) || empty($apellido) || empty($cedula) || empty($telefono) || empty($cargos)) {
        echo '<script>
            alert("Por favor, complete todos los campos.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Validar formato de cédula (solo números y longitud específica)
    if (!preg_match('/^\d{7,8}$/', $cedula)) {
        echo '<script>
            alert("La cédula debe contener entre 7 y 8 dígitos.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Validar formato de teléfono (solo números y longitud específica)
    if (!preg_match('/^\d{11}$/', $telefono)) {
        echo '<script>
            alert("El teléfono debe contener exactamente 11 dígitos.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Validar longitud de nombre y apellido
    if (strlen($nombre) > 50 || strlen($apellido) > 50) {
        echo '<script>
            alert("El nombre y el apellido no deben exceder los 50 caracteres.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Verificar si la cédula ya existe para el mismo cargo
    $verificar_cedula = $enlace->prepare("SELECT id_trabajador FROM trabajadores WHERE cedula = ? AND cargos = ?");
    $verificar_cedula->bind_param("si", $cedula, $cargos);
    $verificar_cedula->execute();
    $verificar_cedula->store_result();

    if ($verificar_cedula->num_rows > 0) {
        echo '<script>
            alert("La cédula ya está registrada para este cargo. Por favor, use una cédula diferente o seleccione otro cargo.");
            window.location.href = "trabajadores.php";
        </script>';
        $verificar_cedula->close();
        exit();
    }
    $verificar_cedula->close();

    // Insertar el nuevo trabajador
    $stmt = $enlace->prepare("INSERT INTO trabajadores (nombre, apellido, cedula, telefono, cargos) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nombre, $apellido, $cedula, $telefono, $cargos);
    if ($stmt->execute()) {
        echo '<script>
            alert("Trabajador registrado correctamente.");
            window.location = "trabajadores.php";
        </script>';
    } else {
        echo '<script>alert("Ocurrió un error al registrar el trabajador.");</script>';
    }
    $stmt->close();
}

// Manejar la eliminación de un trabajador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id_trabajador = intval($_POST['id']);
    $stmt = $enlace->prepare("DELETE FROM trabajadores WHERE id_trabajador = ?");
    $stmt->bind_param("i", $id_trabajador);
    $stmt->execute();
    $stmt->close();

    // Redirigir para evitar el reenvío de datos
    header('Location: trabajadores.php');
    exit();
}

// Manejar la actualización de un trabajador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id_trabajador = intval($_POST['id']);
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellido = htmlspecialchars(trim($_POST['apellido']));
    $cedula = htmlspecialchars(trim($_POST['cedula']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $cargos = intval($_POST['cargos']);

    // Validar campos vacíos
    if (empty($nombre) || empty($apellido) || empty($cedula) || empty($telefono) || empty($cargos)) {
        echo '<script>
            alert("Por favor, complete todos los campos.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Validar formato de cédula y teléfono
    if (!preg_match('/^\d{7,8}$/', $cedula)) {
        echo '<script>
            alert("La cédula debe contener entre 7 y 8 dígitos.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }
    if (!preg_match('/^\d{11}$/', $telefono)) {
        echo '<script>
            alert("El teléfono debe contener exactamente 11 dígitos.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Validar longitud de nombre y apellido
    if (strlen($nombre) > 50 || strlen($apellido) > 50) {
        echo '<script>
            alert("El nombre y el apellido no deben exceder los 50 caracteres.");
            window.location.href = "trabajadores.php";
        </script>';
        exit();
    }

    // Actualizar el trabajador en la base de datos
    $stmt = $enlace->prepare("UPDATE trabajadores SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, cargos = ? WHERE id_trabajador = ?");
    $stmt->bind_param("ssssii", $nombre, $apellido, $cedula, $telefono, $cargos, $id_trabajador);

    if ($stmt->execute()) {
        echo '<script>
            alert("Trabajador actualizado correctamente.");
            window.location = "trabajadores.php";
        </script>';
    } else {
        echo '<script>alert("Ocurrió un error al actualizar el trabajador.");</script>';
    }
    $stmt->close();
}

// Obtener todos los registros de la tabla trabajadores con el nombre del cargo
$resultado = $enlace->query("
    SELECT trabajadores.id_trabajador, trabajadores.nombre, trabajadores.apellido, trabajadores.cedula, trabajadores.telefono, cargos.cargo, trabajadores.cargos 
    FROM trabajadores
    INNER JOIN cargos ON trabajadores.cargos = cargos.id_cargo
");

// Obtener todos los registros de la tabla cargos
$cargos_resultado = $enlace->query("SELECT id_cargo, cargo FROM cargos");

// Obtener todos los registros de la tabla cargos para el modal
$cargos_resultado_modal = $enlace->query("SELECT id_cargo, cargo FROM cargos");
$cargos_array = [];
while ($cargo = $cargos_resultado_modal->fetch_assoc()) {
    $cargos_array[] = $cargo;
}
?>

<script>
    function advertencia() {
        var not = confirm("¿Está seguro de que desea eliminar este trabajador?");
        return not;
    }
</script>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajadores</title>
    <link rel="stylesheet" href="Stilos/css/bootstrap.min.css">  <!--Opcional: agrega estilos a las tablas -->
    <link rel="stylesheet" href="Stilos/styles_tablas.css">  <!--Opcional: agrega estilos a las tablas -->
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="Stilos/jquery.dataTables.min.css">
    <script src="Java/jquery.min.js"></script>
    <script src="Java/jquery.dataTables.min.js"></script>
</head>
<body class="trab-body">
    <?php include 'vista/top-bar.php'; ?>
    <?php  ?>

    <div class="container">
        <h1>Registro de Trabajadores</h1>
        <!-- Formulario para filtrar por cargo -->
        <form method="GET" action="trabajadores.php" class="mb-3 d-flex align-items-center from-rigth">
            <label for="filtro-cargo" class="form-label me-2">Filtrar Cargo:</label>
            <select name="cargo" id="filtro-cargo" class="form-select me-3 sel2" onchange="this.form.submit()">
                <option value="todos" <?php echo (!isset($_GET['cargo']) || $_GET['cargo'] === 'todos') ? 'selected' : ''; ?>>Todos</option>
                <?php
                // Obtener los cargos de la base de datos
                $cargos_resultado_filtro = $enlace->query("SELECT id_cargo, cargo FROM cargos");
                while ($cargo = $cargos_resultado_filtro->fetch_assoc()):
                ?>
                    <option value="<?php echo $cargo['id_cargo']; ?>" <?php echo (isset($_GET['cargo']) && $_GET['cargo'] == $cargo['id_cargo']) ? 'selected' : ''; ?>>
                        <?php echo $cargo['cargo']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="button" class="btn btn-primary btn-pad" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                <i class="fa-solid fa-plus"></i> Agregar Trabajador
            </button>
            <a href="descargar.php?file=registros_trabajadores.pdf" class="download-button">Descargar</a>
        </form>

        <!-- Modal para agregar un nuevo trabajador -->
        <div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addWorkerModalLabel">Agregar Trabajador</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="trabajadores.php">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="apellido" placeholder="Apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="cedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control" name="cedula" placeholder="Cédula" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" placeholder="Teléfono" required>
                            </div>
                            <div class="mb-3">
                                <label for="cargos" class="form-label">Cargo</label>
                                <select class="form-select" name="cargos" required>
                                    <option value="" disabled selected>Seleccione un cargo</option>
                                    <?php while ($cargo = $cargos_resultado->fetch_assoc()): ?>
                                        <option value="<?php echo $cargo['id_cargo']; ?>"><?php echo $cargo['cargo']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" name="agregar" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h2>Lista de Trabajadores</h2>
        <table class="table table-striped table-hover" id="data-tables">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="trabajadores-list">

                <!-- Aquí se llenarán los datos de los trabajadores -->
                <?php 
                // Obtener el filtro de cargo
                $cargo_filtro = isset($_GET['cargo']) ? $_GET['cargo'] : 'todos';

                // Modificar la consulta según el filtro
                if ($cargo_filtro === 'todos') {
                    $resultado = $enlace->query("
                        SELECT trabajadores.id_trabajador, trabajadores.nombre, trabajadores.apellido, trabajadores.cedula, trabajadores.telefono, cargos.cargo 
                        FROM trabajadores
                        INNER JOIN cargos ON trabajadores.cargos = cargos.id_cargo
                    ");
                } else {
                    $stmt = $enlace->prepare("
                        SELECT trabajadores.id_trabajador, trabajadores.nombre, trabajadores.apellido, trabajadores.cedula, trabajadores.telefono, cargos.cargo 
                        FROM trabajadores
                        INNER JOIN cargos ON trabajadores.cargos = cargos.id_cargo
                        WHERE trabajadores.cargos = ?
                    ");
                    $stmt->bind_param("i", $cargo_filtro);
                    $stmt->execute();
                    $resultado = $stmt->get_result();
                }
                
                // Mostrar los resultados en la tabla
                while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_trabajador']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['cedula']; ?></td>
                    <td><?php echo $fila['telefono']; ?></td>
                    <td><?php echo $fila['cargo']; ?></td> <!-- Mostrar el nombre del cargo -->
                    <td>
                        <!-- Botón para eliminar editar y abrir estadisticas-->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $fila['id_trabajador']; ?>">
                            <button type="button" class="btn btn-success ">
                            <i class="fa-solid fa-square-poll-vertical"></i>
                            </button>
                            <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $fila['id_trabajador']; ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>     
                            <button type="submit" name="eliminar" class="btn btn-danger " onclick="return advertencia()"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>

                <!-- Modal para actualizar un trabajador -->
                <div class="modal fade" id="exampleModal<?php echo $fila['id_trabajador']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $fila['id_trabajador']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $fila['id_trabajador']; ?>">Editar Trabajador</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <!-- Campo oculto para enviar el ID del trabajador -->
                                    <input type="hidden" name="id" value="<?php echo $fila['id_trabajador']; ?>">

                                    <!-- Campos para editar los datos del trabajador -->
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" value="<?php echo $fila['nombre']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" name="apellido" value="<?php echo $fila['apellido']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cedula" class="form-label">Cédula</label>
                                        <input type="text" class="form-control" name="cedula" value="<?php echo $fila['cedula']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" name="telefono" value="<?php echo $fila['telefono']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cargos" class="form-label">Cargo</label>
                                        <select class="form-select" name="cargos" required>
                                            <option value="" disabled>Seleccione un cargo</option>
                                            <?php foreach ($cargos_array as $cargo): ?>
                                                <option value="<?php echo $cargo['id_cargo']; ?>" 
                                                    <?php echo (isset($fila['cargos']) && $cargo['id_cargo'] == $fila['cargos']) ? 'selected' : ''; ?>>
                                                    <?php echo $cargo['cargo']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Botones del modal -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="Java/js/bootstrap.bundle.min.js"></script>
    <script src="Java/js.js"></script>
     <script>
        $(document).ready(function() {
            $('#data-tables').DataTable()
        });
                
     </script>
    
</body>
</html>
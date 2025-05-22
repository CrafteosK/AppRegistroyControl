<?php
include 'conexion_be.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos
include 'validar_sesion.php';
include 'validar_level_user.php';

// Permitir acceso a todos los roles, pero restringir acciones para el rol Usuario (rol_id = 3)
$solo_visualizar = ($_SESSION['rol_id'] == 3); // Si es Usuario, solo puede visualizar


include 'validar_acceso.php';

// Solo Administradores (rol_id = 1) y Moderadores (rol_id = 2) pueden agregar/editar trabajadores
if ($_SESSION['rol'] == 3) { // Usuario
    echo '<script>
        alert("No tienes permiso para agregar o editar trabajadores.");
        window.location.href = "inicio.php";
    </script>';
    exit();
}

// Incluir la conexión a la base de datos



// Manejar la adición de un nuevo trabajador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $data_tipo = $_POST['data_tipo']; // Recoge el valor del campo oculto
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
            alert("La cédula ya está registrada para este cargo.");
            window.location.href = "trabajadores.php";
        </script>';
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
    <title>Gestión de Trabajadores</title>
    <link rel="stylesheet" href="Stilos/styles_trabajadores.css">
    <link rel="stylesheet" href="Stilos/css/bootstrap.min.css">  <!--Opcional: agrega estilos a las tablas -->
    <link rel="stylesheet" href="Stilos/styles_tablas.css">  <!--Opcional: agrega estilos a las tablas -->
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="Stilos/jquery.dataTables.min.css">
    <script src="Java/jquery.min.js"></script>
    <script src="Java/jquery.dataTables.min.js"></script>
    <script src="Java/notificaciones.js" defer></script>

</head>
<body class="trab-body">
    <?php include 'vista/top-bar.php'; ?>
    <?php include 'vista/notificaciones.php'; // Incluir el archivo de notificaciones ?>

    <div class="container">
        <h1>Gestión de Trabajadores</h1>
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
            <?php if (!$solo_visualizar): // Mostrar solo si no es Usuario ?>
                <button type="button" class="btn btn-primary btn-pad" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                    <i class="fa-solid fa-plus"></i> Agregar Trabajador
                </button>
            <?php endif; ?>
            <a href="descargar.php?cargo=<?php echo isset($_GET['cargo']) ? $_GET['cargo'] : 'todos'; ?>" class="btn btn-primary btn-pad">
                <i class="fa-solid fa-download"></i> Descargar
            </a>
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
                            <input type="hidden" name="data_tipo" value="agregar" />
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
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Cargo</th>
                    <?php if (!$solo_visualizar): // Solo si NO es usuario nivel 3 ?>
                        <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="trabajadores-list">
                <?php 
                // Inicializar el contador de filas
                $numero_fila = 1;

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
                    <td><?php echo $numero_fila++; ?></td> <!-- Mostrar el número de fila -->
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['cedula']; ?></td>
                    <td><?php echo $fila['telefono']; ?></td>
                    <td><?php echo $fila['cargo']; ?></td> <!-- Mostrar el nombre del cargo -->
                    <?php if (!$solo_visualizar): // Solo si NO es usuario nivel 3 ?>
                    <td>
                        <!-- Botón para eliminar, editar y abrir estadísticas -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $fila['id_trabajador']; ?>">
                            <!--<button type="button" class="btn btn-success" onclick="window.location.href='graficas.php?cedula=<?php echo $fila['cedula']; ?>'">
                                <i class="fa-solid fa-square-poll-vertical"></i>
                            </button>-->
                            <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $fila['id_trabajador']; ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>     
                            <button type="submit" name="eliminar" class="btn btn-danger " onclick="return advertencia()"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                    <?php endif; ?>
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
                                    <input type="hidden" name="id" value="<?php echo $fila['id_trabajador']; ?>">

                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" value="<?php echo $fila['nombre']; ?>" <?php echo $solo_visualizar ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" name="apellido" value="<?php echo $fila['apellido']; ?>" <?php echo $solo_visualizar ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cedula" class="form-label">Cédula</label>
                                        <input type="text" class="form-control" name="cedula" value="<?php echo $fila['cedula']; ?>" <?php echo $solo_visualizar ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" name="telefono" value="<?php echo $fila['telefono']; ?>" <?php echo $solo_visualizar ? 'readonly' : ''; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cargos" class="form-label">Cargo</label>
                                        <select class="form-select" name="cargos" <?php echo $solo_visualizar ? 'disabled' : ''; ?>>
                                            <option value="" disabled>Seleccione un cargo</option>
                                            <?php foreach ($cargos_array as $cargo): ?>
                                                <option value="<?php echo $cargo['id_cargo']; ?>" 
                                                    <?php echo (isset($fila['cargos']) && $cargo['id_cargo'] == $fila['cargos']) ? 'selected' : ''; ?>>
                                                    <?php echo $cargo['cargo']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <?php if (!$solo_visualizar): // Mostrar botones solo si no es Usuario ?>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
//document.addEventListener('DOMContentLoaded', function() {
//    const params = new URLSearchParams(window.location.search);
//    const error = params.get('error');
//    if (error) {
//        let descripcion = '';
//        switch (error) {
//            case 'campos':
//                descripcion = 'Por favor, complete todos los campos.';
//                break;
//            case 'cedula':
//                descripcion = 'La cédula debe contener entre 7 y 8 dígitos.';
//                break;
//            case 'telefono':
//                descripcion = 'El teléfono debe contener exactamente 11 dígitos.';
//                break;
//            case 'longitud':
//                descripcion = 'El nombre y el apellido no deben exceder los 50 caracteres.';
//                break;
//            case 'duplicado':
//                descripcion = 'La cédula ya está registrada para este cargo.';
//                break;
//            default:
//                descripcion = 'Ocurrió un error.';
//            
//           
//        }
//        agregarToast({
//            tipo: 'alert',
//            titulo: 'Alerta',
//            descripcion: descripcion,
//            autoCierre: false // <--- Esto es lo importante
//        });
//        // Opcional: abrir el modal automáticamente
//        var modal = new bootstrap.Modal(document.getElementById('addWorkerModal'));
//        modal.show();
//    }
//});
</script>

    <script src="Java/js/bootstrap.bundle.min.js"></script>
    <script src="Java/js.js"></script>
     <script>
        $(document).ready(function() {
            $('#data-tables').DataTable()
        });
                
     </script>
    
</body>
</html>
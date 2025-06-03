<?php
include 'conexion_be.php';
include 'validar_sesion.php';
include 'validar_level_user.php';

// Mostrar la notificación solo al iniciar sesión
    if (isset($_GET['status']) && isset($_GET['data_tipo'])) {
        $status = $_GET['status'];
        $data_tipo = $_GET['data_tipo'];

        $tipo = $status === 'info' ? 'info' : '';
        $titulo = $status === 'info' ? 'Información' : '';
        $descripcion = $status === 'info' 
            ? 'Por favor, asegúrese de que la fecha y hora de su ordenador estén configuradas correctamente para que el sistema funcione adecuadamente.' 
            : '';

        echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                agregarToast({
                    tipo: '$tipo',
                    titulo: '$titulo',
                    descripcion: '$descripcion',
                    
                });
            });
        </script>";
    }

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula']) && !empty($_POST['cedula'])) {
        $cedula = htmlspecialchars(trim($_POST['cedula']));
        $hora_actual = date('Y-m-d H:i:s'); // Obtener la hora actual con segundos

        // Validar formato de cédula
        if (!preg_match('/^\d{7,8}$/', $cedula)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    agregarToast({
                        tipo: 'error',
                        titulo: 'Error',
                        descripcion: 'La cédula debe contener entre 7 y 8 dígitos.',
                        autoCierre: true
                    });
                });
            </script>";
        } else {
            // Verificar si la cédula existe en la base de datos
            $consulta = $enlace->prepare("
                SELECT t.nombre, t.apellido, t.cedula, c.cargo AS tipo_trabajador
                FROM trabajadores t
                INNER JOIN cargos c ON t.cargos = c.id_cargo
                WHERE t.cedula = ?
            ");
            $consulta->bind_param("s", $cedula);
            $consulta->execute();
            $resultado = $consulta->get_result();

            if ($resultado->num_rows > 0) {
                // Obtener los datos del trabajador
                $trabajador = $resultado->fetch_assoc();
                $nombre = $trabajador['nombre'];
                $apellido = $trabajador['apellido'];
                $tipo_trabajador = $trabajador['tipo_trabajador'];

                // Registrar entrada o salida en la tabla asistencias
                if (isset($_POST['btnentrada'])) {
                    $tipo = 'entrada';
                } elseif (isset($_POST['btnsalida'])) {
                    $tipo = 'salida';
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', () => {
                            agregarToast({
                                tipo: 'alert',
                                titulo: 'Alerta',
                                descripcion: 'Debe seleccionar un tipo de asistencia.',
                                autoCierre: true
                            });
                        });
                    </script>";
                    return;
                }

                // Verificar si ya existe un registro de este tipo hoy
                $fecha_hoy = date('Y-m-d');
                $verificar = $enlace->prepare("
                    SELECT id FROM asistencias 
                    WHERE cedula = ? AND tipo = ? AND DATE(hora) = ?
                ");
                $verificar->bind_param("sss", $cedula, $tipo, $fecha_hoy);
                $verificar->execute();
                $verificar->store_result();

                if ($verificar->num_rows > 0) {
                    // Ya existe registro de este tipo hoy
                    $mensaje = $tipo === 'entrada' 
                        ? 'Ya se registró la entrada de este trabajador hoy.'
                        : 'Ya se registró la salida de este trabajador hoy.';
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', () => {
                            agregarToast({
                                tipo: 'alert',
                                titulo: 'Alerta',
                                descripcion: '$mensaje',
                                autoCierre: true
                            });
                        });
                    </script>";
                } else {
                    // Insertar los datos en la tabla asistencias
                    $stmt = $enlace->prepare("
                        INSERT INTO asistencias (nombre, apellido, cedula, tipo_trabajador, tipo, hora)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param("ssssss", $nombre, $apellido, $cedula, $tipo_trabajador, $tipo, $hora_actual);

                    if ($stmt->execute()) {
                        // Mostrar notificación toast de éxito siempre que se registre asistencia
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', () => {
                                agregarToast({
                                    tipo: 'exito',
                                    titulo: 'Éxito',
                                    descripcion: 'Asistencia registrada correctamente',
                                    autoCierre: true
                            });
                        });
                    </script>";
                    } else {
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', () => {
                                agregarToast({
                                    tipo: 'error',
                                    titulo: 'Error',
                                    descripcion: 'Error al registrar la asistencia.',
                                    autoCierre: true
                            });
                        });
                    </script>";
                    }
                    $stmt->close();
                }
                $verificar->close();
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', () => {
                        agregarToast({
                            tipo: 'error',
                            titulo: 'Error',
                            descripcion: 'La cédula no está registrada en la base de datos.',
                            autoCierre: true
                        });
                    });
                </script>";
            }
            $consulta->close();
        }
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                agregarToast({
                    tipo: 'error',
                    titulo: 'Error',
                    descripcion: 'Por favor, ingrese una cédula.',
                    autoCierre: true
                });
            });
        </script>";
    }
}
include 'vista/notificaciones.php'; // Incluir el archivo de notificaciones

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Stilos/inicio.css">
    <title>Inicio</title>
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/css/all.css">
    <script src="Java/notificaciones.js" defer></script>

</head>
<body>

    <?php include 'vista/top-bar.php'; ?>

    <!-- Contenido principal -->
    <main class="content" id="content">
        <h1>Bienvenido al sistema de asistencias</h1>
        <h2>Por favor registre su asistencia</h2>
        <div class="container">
            <p class="CI">Ingrese su Cedula</p>
            <form action="" method="POST">
                <input type="hidden" name="data_tipo" value="asistencia" />
                <input type="text" id="cedula" name="cedula" placeholder="Cedula del Trabajador" required>
                <div class="btn-inicio">
                    <button type="submit" class="entrada" name="btnentrada">ENTRADA</button>
                    <button type="submit" class="salida" name="btnsalida">SALIDA</button>
                </div>
            </form>
        </div>
    </main>

    <script src="Java/js.js"></script>
    <script>
    // Evitar submit con Enter en el input de cédula
    document.addEventListener('DOMContentLoaded', function() {
        const cedulaInput = document.getElementById('cedula');
        const form = cedulaInput.closest('form');
        cedulaInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                agregarToast({
                    tipo: 'alert',
                    titulo: 'Alerta',
                    descripcion: 'Debe seleccionar un tipo de asistencia.',
                });
            }
        });
       
    });
    </script>

    <?php
    if (isset($_GET['toast_tipo']) && isset($_GET['toast_titulo']) && isset($_GET['toast_descripcion'])) {
        $toast_tipo = htmlspecialchars($_GET['toast_tipo']);
        $toast_titulo = htmlspecialchars($_GET['toast_titulo']);
        $toast_descripcion = htmlspecialchars($_GET['toast_descripcion']);
        echo "<script>
            document.addEventListener('DOMContentLoaded', () => {
                agregarToast({
                    tipo: '$toast_tipo',
                    titulo: '$toast_titulo',
                    descripcion: '$toast_descripcion',
                    autoCierre: true
                });
            });
        </script>";
    }
    ?>
</body>
</html>

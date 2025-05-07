<?php
session_start();
include 'conexion_be.php';
include 'validar_sesion.php';
include 'validar_level_user.php';

// Mostrar la notificación solo al iniciar sesión
if (!isset($_SESSION['notificacion_mostrada']) || $_SESSION['notificacion_mostrada'] === false) {
    echo '<script>
        alert("Por favor, asegúrese de que la fecha y hora de su ordenador estén configuradas correctamente para que el sistema funcione adecuadamente.");
    </script>';
    $_SESSION['notificacion_mostrada'] = true; // Marcar como mostrada
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cedula']) && !empty($_POST['cedula'])) {
        $cedula = htmlspecialchars(trim($_POST['cedula']));
        $hora_actual = date('Y-m-d H:i'); // Obtener la hora actual sin segundos

        // Validar formato de cédula
        if (!preg_match('/^\d{7,8}$/', $cedula)) {
            echo '<script>
                alert("La cédula debe contener entre 7 y 8 dígitos.");
                window.location.href = "inicio.php";
            </script>';
            exit();
        }

        // Verificar si la cédula existe en la base de datos
        $consulta = $enlace->prepare("
            SELECT t.id_trabajador, c.cargo 
            FROM trabajadores t 
            INNER JOIN cargos c ON t.cargos = c.id_cargo 
            WHERE t.cedula = ?
        ");
        $consulta->bind_param("s", $cedula);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $trabajador = $resultado->fetch_assoc();
            $id_trabajador = $trabajador['id_trabajador'];
            $cargo = strtolower($trabajador['cargo']); // Convertir el cargo a minúsculas para evitar errores

            // Determinar la tabla según el cargo
            $tabla = '';
            if ($cargo === 'maestro') {
                $tabla = 'maestros';
            } elseif ($cargo === 'obrero') {
                $tabla = 'obreros';
            } elseif ($cargo === 'vigilante') {
                $tabla = 'vigilantes';
            } elseif ($cargo === 'cocinero') {
                $tabla = 'cocineros';
            } else {
                echo '<script>
                    alert("El cargo no está asociado a una tabla válida.");
                    window.location.href = "inicio.php";
                </script>';
                exit();
            }

            // Registrar entrada
            if (isset($_POST['btnentrada'])) {
                $stmt = $enlace->prepare("INSERT INTO $tabla (id_trabajador, tipo, hora) VALUES (?, 'entrada', ?)");
                $stmt->bind_param("is", $id_trabajador, $hora_actual);
                if ($stmt->execute()) {
                    echo '<script>
                        alert("Hora de entrada registrada correctamente en la tabla ' . $tabla . '.");
                        window.location.href = "inicio.php";
                    </script>';
                } else {
                    echo '<script>
                        alert("Error al registrar la hora de entrada.");
                        window.location.href = "inicio.php";
                    </script>';
                }
                $stmt->close();
            }

            // Registrar salida
            if (isset($_POST['btnsalida'])) {
                $stmt = $enlace->prepare("INSERT INTO $tabla (id_trabajador, tipo, hora) VALUES (?, 'salida', ?)");
                $stmt->bind_param("is", $id_trabajador, $hora_actual);
                if ($stmt->execute()) {
                    echo '<script>
                        alert("Hora de salida registrada correctamente en la tabla ' . $tabla . '.");
                        window.location.href = "inicio.php";
                    </script>';
                } else {
                    echo '<script>
                        alert("Error al registrar la hora de salida.");
                        window.location.href = "inicio.php";
                    </script>';
                }
                $stmt->close();
            }
        } else {
            echo '<script>
                alert("La cédula no está registrada en la base de datos.");
                window.location.href = "inicio.php";
            </script>';
        }
        $consulta->close();
    } else {
        echo '<script>
            alert("Por favor, ingrese una cédula.");
            window.location.href = "inicio.php";
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Stilos/inicio.css">
    <title>Inicio</title>
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
                <input type="text" id="cedula" name="cedula" placeholder="Cedula del Trabajador" required>
                <div class="btn-inicio">
                    <button type="submit" class="entrada" name="btnentrada">ENTRADA</button>
                    <button type="submit" class="salida" name="btnsalida">SALIDA</button>
                </div>
            </form>
        </div>
    </main>

    <script src="Java/js.js"></script>
</body>
</html>

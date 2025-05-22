<?php
include 'conexion_be.php'; // <-- Agrega esta línea al inicio

// filepath: c:\xampp\htdocs\AppRegistroyControl\restore_db.php
$host = "localhost";
$user = "root"; // Cambia esto si tienes un usuario diferente
$password = ""; // Cambia esto si tienes una contraseña
$database = "registro"; // Cambia esto por el nombre de tu base de datos

// Restaurar desde backup existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_file'])) {
    $restore_file = basename($_POST['restore_file']); // Seguridad: solo nombre, sin ruta
    $backup_file = __DIR__ . "/uploads/" . $restore_file;

    if (file_exists($backup_file)) {
        $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';
        $command = "\"$mysqlPath\" --host=$host --user=$user --password=$password $database < \"$backup_file\"";
        exec($command . " 2>&1", $output, $result);

        if ($result === 0) {
            // Registrar la restauración como un nuevo backup
            $nombreArchivo = $restore_file;
            $fecha = date('Y-m-d H:i:s');
            $enlace->query("INSERT INTO backup (nombre_archivo, fecha) VALUES ('$nombreArchivo', '$fecha')");

            // Eliminar el más antiguo si hay más de 5 backups
            $backups = $enlace->query("SELECT id FROM backup ORDER BY fecha DESC");
            if ($backups && $backups->num_rows > 5) {
                $ids = [];
                while ($row = $backups->fetch_assoc()) {
                    $ids[] = $row['id'];
                }
                $ids_a_eliminar = array_slice($ids, 5);
                if (!empty($ids_a_eliminar)) {
                    $ids_a_eliminar_str = implode(',', $ids_a_eliminar);
                    $enlace->query("DELETE FROM backup WHERE id IN ($ids_a_eliminar_str)");
                }
            }

            echo '<script>
                alert("Base de datos restaurada exitosamente desde la copia seleccionada.");
                window.location.href = "settings.php";
            </script>';
            exit();
        } else {
            echo "<pre>Error al restaurar la base de datos.\nComando ejecutado: $command\n";
            print_r($output);
            echo "</pre>";
            exit();
        }
    } else {
        echo '<script>alert("El archivo de respaldo no existe."); window.location.href = "settings.php";</script>';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
    // Verificar si se subió un archivo
    if ($_FILES['sql_file']['error'] === UPLOAD_ERR_OK) {
        // Ruta temporal del archivo subido
        $uploaded_file = $_FILES['sql_file']['tmp_name'];

        // Crear el directorio "uploads" si no existe
        $uploads_dir = __DIR__ . "/uploads";
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        $backup_file = $uploads_dir . "/" . $_FILES['sql_file']['name']; // Guardar en el directorio "uploads"

        // Mover el archivo subido al directorio "uploads"
        if (move_uploaded_file($uploaded_file, $backup_file)) {
            // Cambia la ruta según tu instalación de XAMPP
            $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';
            $command = "\"$mysqlPath\" --host=$host --user=$user --password=$password $database < \"$backup_file\"";
            exec($command . " 2>&1", $output, $result);

            if ($result === 0) {
                // 1. Insertar SIEMPRE el nuevo backup
                $nombreArchivo = $_FILES['sql_file']['name'];
                $fecha = date('Y-m-d H:i:s');
                $enlace->query("INSERT INTO backup (nombre_archivo, fecha) VALUES ('$nombreArchivo', '$fecha')");

                // 2. Eliminar el más antiguo si hay más de 5 backups
                $backups = $enlace->query("SELECT id FROM backup ORDER BY fecha DESC");
                if ($backups && $backups->num_rows > 5) {
                    // Obtener los IDs ordenados del más reciente al más antiguo
                    $ids = [];
                    while ($row = $backups->fetch_assoc()) {
                        $ids[] = $row['id'];
                    }
                    // Los IDs a eliminar son los que están después del 5to más reciente
                    $ids_a_eliminar = array_slice($ids, 5);
                    if (!empty($ids_a_eliminar)) {
                        $ids_a_eliminar_str = implode(',', $ids_a_eliminar);
                        $enlace->query("DELETE FROM backup WHERE id IN ($ids_a_eliminar_str)");
                    }
                }

                echo '<script>
                    alert("Base de datos restaurada exitosamente.");
                    window.location.href = "settings.php";
                </script>';
                exit();
            } else {
                echo "<pre>Comando ejecutado: $command\n";
                print_r($output);
                echo "</pre>";
                echo '<script>
                    alert("Error al restaurar la base de datos. Verifica el archivo SQL.");
                    window.location.href = "settings.php";
                </script>';
            }
        } else {
            echo '<script>
                alert("Error al mover el archivo subido.");
                window.location.href = "settings.php";
            </script>';
        }
    } else {
        echo '<script>
            alert("Error al subir el archivo. Verifica que sea un archivo válido.");
            window.location.href = "settings.php";
        </script>';
    }
}
?>
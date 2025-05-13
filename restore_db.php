<?php
// filepath: c:\xampp\htdocs\AppRegistroyControl\restore_db.php
$host = "localhost";
$user = "root"; // Cambia esto si tienes un usuario diferente
$password = ""; // Cambia esto si tienes una contraseña
$database = "registro"; // Cambia esto por el nombre de tu base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
    // Verificar si se subió un archivo
    if ($_FILES['sql_file']['error'] === UPLOAD_ERR_OK) {
        // Ruta temporal del archivo subido
        $uploaded_file = $_FILES['sql_file']['tmp_name'];
        $backup_file = __DIR__ . "/uploads/" . $_FILES['sql_file']['name']; // Guardar en el directorio "uploads"

        // Mover el archivo subido al directorio "uploads"
        if (move_uploaded_file($uploaded_file, $backup_file)) {
            // Comando para importar la base de datos
            $command = "mysql --host=$host --user=$user --password=$password $database < $backup_file";

            // Ejecutar el comando
            exec($command, $output, $result);

            if ($result === 0) {
                echo '<script>
                    alert("Base de datos restaurada exitosamente.");
                    window.location.href = "index.php";
                </script>';
            } else {
                echo '<script>
                    alert("Error al restaurar la base de datos. Verifica el archivo SQL.");
                    window.location.href = "index.php";
                </script>';
            }
        } else {
            echo '<script>
                alert("Error al mover el archivo subido.");
                window.location.href = "index.php";
            </script>';
        }
    } else {
        echo '<script>
            alert("Error al subir el archivo. Verifica que sea un archivo válido.");
            window.location.href = "index.php";
        </script>';
    }
}
?>
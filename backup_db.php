<?php
// filepath: c:\xampp\htdocs\AppRegistroyControl\backup_db.php
$host = "localhost";
$user = "root"; // Cambia esto si tienes un usuario diferente
$password = ""; // Cambia esto si tienes una contraseña
$database = "registro"; // Cambia esto por el nombre de tu base de datos

// Nombre del archivo de respaldo
$backup_file = __DIR__ . "/vista/registro.sql"; // Guardar como registro.sql en el directorio "vista"

// Comando para exportar la base de datos
$command = "C:\\xampp\\mysql\\bin\\mysqldump --host=$host --user=$user --password=$password $database > $backup_file";

// Ejecutar el comando
exec($command, $output, $result);

if ($result === 0) {
    // Respaldo exitoso
    echo '<script>
        alert("Respaldo de la base de datos creado exitosamente.");
        window.location.href = "inicio.php";
    </script>';
} else {
    // Error al crear el respaldo
    echo '<script>
        alert("Error al crear el respaldo. Verifica la configuración de mysqldump.");
        window.location.href = "inicio.php";
    </script>';
}
?>
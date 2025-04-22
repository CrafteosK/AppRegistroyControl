<?php

include 'conexion_be.php';

if (!$enlace) {
    die("Error en la conexión: " . mysqli_connect_error());
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validar si los datos necesarios están presentes
if (isset($_POST['ID']) && isset($_POST['new_password'])) {
    $ID = intval($_POST['ID']); // Convertir el ID a un número entero
    $new_password = $_POST['new_password'];
    $new_password = hash('sha512', $new_password); // Encriptar la nueva contraseña

    // Depuración: Verificar los datos recibidos
    echo "ID recibido: $ID<br>";
    echo "Nueva contraseña (encriptada): $new_password<br>";

    // Actualizar la contraseña en la base de datos
    $stmt = "UPDATE usuarios SET password = '$new_password' WHERE ID = $ID";
    $ejecutar = mysqli_query($enlace, $stmt);

    if ($ejecutar) {
        echo '<script>
            alert("Contraseña actualizada exitosamente");
            window.location = "index.php?message=ok";
        </script>';
    } else {
        echo '<script>
            alert("Error al actualizar la contraseña");
            window.location = "index.php?message=error";
        </script>';
    }

    mysqli_close($enlace);
} else {
    echo '<script>
        alert("Error: Datos incompletos");
        window.location = "index.php?message=error";
    </script>';
}

?>
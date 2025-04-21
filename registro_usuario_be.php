<?php

include 'conexion_be.php';

if (!$enlace) {
    die("Error en la conexión: " . mysqli_connect_error());
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['registro'])) {
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $usuario = $_POST['user'];
    $contraseña = $_POST['password'];
    // encriptar contraseña
    $contraseña = hash('sha512', $contraseña);

    $stmt = "INSERT INTO usuarios VALUES ('', '$nombre_completo', '$email', '$usuario', '$contraseña')";

    //verificar que el correo no se repita en la base de datos
    
    $verificar_correo = mysqli_query($enlace, "SELECT * FROM usuarios WHERE email = '$email'");
    if (mysqli_num_rows($verificar_correo) > 0) {
        echo '<script>
            alert("Este correo ya esta registrado, intenta con otro diferente");
            window.location = "index.php";
        </script>';
        exit();
    }

    //verificar que el usuario no se repita en la base de datos
    
    $verificar_usuario = mysqli_query($enlace, "SELECT * FROM usuarios WHERE user = '$usuario'");
    if (mysqli_num_rows($verificar_usuario) > 0) {
        echo '<script>
            alert("Este usuario ya esta registrado, intenta con otro diferente");
            window.location = "index.php";
        </script>';
        exit();
    }

    $ejecutarInsertar = mysqli_query($enlace, $stmt);

    if (!$ejecutarInsertar) {
        die("Error en la consulta: " . mysqli_error($enlace));
    }

    if($ejecutarInsertar) {
        echo '<script>
            alert("Usuario registrado exitosamente");
            window.location = "index.php";
        </script>';
    } else {
        echo '<script>
            alert("Usuario no registrado, intentalo de nuevo");
            window.location = "index.php";
        </script>';
    }

    mysqli_close($enlace);
}    


?>
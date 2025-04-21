<?php
session_start();
include 'conexion_be.php';

if (isset($_POST['user']) && isset($_POST['password'])) {
    $usuario = $_POST['user'];
    $contraseña = $_POST['password'];
    $contraseña = hash('sha512', $contraseña); // Encriptar la contraseña ingresada

    // Verificar si el usuario y la contraseña coinciden en la base de datos
    $query = "SELECT * FROM usuarios WHERE user = '$usuario' AND password = '$contraseña'";
    $resultado = mysqli_query($enlace, $query);

    if (mysqli_num_rows($resultado) > 0) {
        // Credenciales correctas, iniciar sesión
        $_SESSION['usuario'] = $usuario;
        header('Location: inicio.php'); // Redirigir a la página de inicio
        exit();
    } else {
        // Credenciales incorrectas
        echo '<script>
            alert("Usuario o contraseña incorrectos");
            window.location = "index.php";
        </script>';
        exit();
    }
} else {
    echo '<script>
        alert("Por favor completa todos los campos");
        window.location = "index.php";
    </script>';
    exit();
}
?>
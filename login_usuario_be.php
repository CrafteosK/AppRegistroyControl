<?php
session_start();
include 'conexion_be.php';

if (isset($_POST['user']) && isset($_POST['password'])) {
    $usuario = $_POST['user'];
    $contraseña = $_POST['password'];
    $contraseña = hash('sha512', $contraseña); // Encriptar la contraseña ingresada

    // Verificar si el usuario y la contraseña coinciden en la base de datos
    $query = "SELECT u.ID, u.user, u.password, l.roles as rol FROM usuarios u LEFT JOIN level_user l ON u.rol_id = l.id WHERE user = '$usuario' AND password = '$contraseña'";
    $resultado = mysqli_query($enlace, $query);
    $row = $resultado->fetch_assoc();

    if (mysqli_num_rows($resultado) > 0) {
        // Credenciales correctas, iniciar sesión
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['id_usuario'] = $row['ID']; // Asegúrate de almacenar el ID del usuario
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
<?php
session_start();
include 'conexion_be.php';

if (isset($_POST['user']) && isset($_POST['password'])) {
    $data_tipo = $_POST['data_tipo']; // Recoge el valor del campo oculto
    $usuario = $_POST['user'];
    $contraseña = $_POST['password'];
    $contraseña = hash('sha512', $contraseña); // Encriptar la contraseña ingresada

    // Verificar si el usuario y la contraseña coinciden en la base de datos
    $query = "SELECT u.ID, u.user, u.password, l.roles as rol, u.rol_id 
              FROM usuarios u 
              LEFT JOIN level_user l ON u.rol_id = l.id 
              WHERE user = '$usuario' AND password = '$contraseña'";
    $resultado = mysqli_query($enlace, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = $resultado->fetch_assoc();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $row['rol']; // Nombre del rol (Administrador, Moderador, Usuario)
        $_SESSION['rol_id'] = $row['rol_id']; // ID del rol (1, 2, 3)
        $_SESSION['id_usuario'] = $row['ID']; // ID del usuario

        header("Location: inicio.php?status=info&data_tipo=$data_tipo");
        exit();
    } else {
        // Credenciales incorrectas
        header("Location: index.php?status=error2&data_tipo=$data_tipo"); // Redirige con error
        exit();
    }
} else {
    // Si no se enviaron los datos correctamente
    header("Location: index.php?status=error&data_tipo=$data_tipo"); // Redirige con error
    exit();
}
?>
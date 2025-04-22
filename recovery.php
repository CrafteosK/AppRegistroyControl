<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="Stilos/stylos_recuperar_contrasena.css">
</head>
<body>
    <div class="form">
        <form action="recovery_be.php" method="POST">
            <h1>Ingresa tu Correo</h1>
            <h3>Correo electrónico:</h3>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>
            <button type="submit">Recuperar Contraseña</button>
            <p>¿Ya tienes cuenta? <a href="index.php">Iniciar sesión</a></p>
            <p>¿No tienes cuenta? <a href="register.php">Registrarse</a></p>
        </form>
    </div>
</body>
</html>
<?php
session_start();
$ID = isset($_GET['id']) ? $_GET['id'] : null; // Cambiar 'ID' a 'id'

if (!$ID) {
    die("Error: No se proporcionó un ID válido.");
}
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
        <form action="change_password_be.php" method="POST">
            <h1>Cambiar Contraseña</h1>
            <label for="password">Nueva Contraseña</label>
            <input type="password" id="password" name="new_password" placeholder="Contraseña" required>
            <!-- Campo oculto para enviar el ID -->
            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($ID); ?>">
            <button type="submit">Recuperar Contraseña</button>
            <p>¿Ya tienes cuenta? <a href="index.php">Iniciar sesión</a></p>
            <p>¿No tienes cuenta? <a href="register.php">Registrarse</a></p>
        </form>
    </div>
</body>
</html>
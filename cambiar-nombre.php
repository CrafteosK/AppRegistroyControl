<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Perfil</title>
    <link rel="stylesheet" href="Stilos/styles_perfil.css">
</head>
<body>

<?php include 'vista/top-bar.php'; ?>

    <div class="container-user">
        <h1>Actualizar Datos del Usuario</h1>
        <form action="update_user.php" method="post" enctype="multipart/form-data">
            
            <div class="profile-image-container">
                <img id="profile-image" src="imagen/default-profile.png" alt="Imagen de perfil">
                <input type="file" id="profile-image-input" name="profile_image" accept="image/*">
            </div>

       
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" placeholder="Ingresa tu nuevo nombre" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Ingresa tu nuevo correo electrónico" required>

        
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Ingresa tu nueva contraseña" required>

            <button type="submit">Actualizar Datos</button>
        </form>
    </div>
</body>
</html>
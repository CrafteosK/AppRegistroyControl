<?php

session_start();

if(isset($_SESSION['usuario'])){
    header('Location: inicio.php');
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro y control de asistencias</title>
  <link rel="stylesheet" href="Stilos/styles_login-register.css"/>
  


</head>
<body>
  <main>
    <div class="Contenedor__login-register">          
      <!-- Registro-->
      <div class="register-container">
        <div class="register-form" id="contenedor-botones">
          <img src="imagen/Picsart_25-03-31_14-46-19-016.png" alt="Logo" class="logo" />
          <h2>Registro</h2>
          <p>Por favor introduce tus datos</p>
          <form action="registro_usuario_be.php" class="formulario__register" method="post">
            <input type="hidden" name="data_tipo" value="registro" />
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Introduzca su nombre" required />

            <label for="Correo_electronico">Correo electrónico</label>
            <input type="email" id="Correo_electronico" name="email" placeholder="Introduzca su Correo electronico" required />

            <label for="usuario-registro">Usuario</label>
            <input type="text" id="usuario-registro" name="user" placeholder="Introduzca el usuario" required />

            <label for="password-registro">Contraseña</label>
            <input type="password" id="password-registro" name="password" placeholder="Introduzca la contraseña" required />

            <button type="submit" name="registro" id="btn__registrarse">Registrar</button>
            <br>
            <a id="" href="index.php">¿Ya tienes cuenta?</a>
          </form>
        </div>
      <div class="register-bg"></div>
      </div>
    </div>
  </main>    

  <script src="Java/Script.js"></script>
</body>
</html>


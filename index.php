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
  <link rel="stylesheet" href="Stilos/css/bootstrap.min.css"> <!-- Opcional: agrega estilos a las tablas -->
  <link rel="stylesheet" href="Stilos/styles_login-register.css"/>
</head>
<body>
  <main>
    <div class="Contenedor__login-register">
    <div class="login-container">
      <div class="login-form">
        <img src="imagen/Picsart_25-03-31_14-46-19-016.png" alt="Logo" class="logo" />
        <!-- Inicio de sesion -->
        <h2>Inicio de sesion</h2>
        <p>Por favor introduce tu usuario</p>
        <form action="login_usuario_be.php" class="formulario__login" method="post">
          <label for="usuario">Usuario</label>
          <input type="text" id="usuario" name="user" placeholder="Introduzca el usuario" required />

          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="Introduzca la contraseña" required />

          <div class="remember">
            <input type="checkbox" id="remember" />
            <label for="remember">¿Recordar?</label>
          </div>

          <button type="submit">Entrar</button>
          <br>
          <h4>¿No tienes cuenta?</h4> <a id="" href="register.php">Da click aqui para registrarte</a>
          <h4>¿Olvidaste tu contraseña?</h4> <a href="recovery.php">Recuperar contraseña</a>
        </form>
      </div>
      <div class="login-bg"></div>
    </div>
    
  </main> 
  <?php
    if(isset($_GET['message'])){
          switch($_GET['message']){
            case 'ok':
              ?>
              <div class="alert alert-success alerta" role="alert">
              <?php
              echo 'Correo enviado correctamente. Revisa tu correo';
              ?>
              </div>
              <?php
              break;
            default:
            ?>
              <div class="alert alert-danger" role="alert">
              <?php
                echo 'Ha ocurrido un error. Intenta nuevamente.';
                ?>
              </div>
              <?php
              break;
          }
        
    }
    ?>   
  <script src="Java/Script.js"></script>
</body>
</html>
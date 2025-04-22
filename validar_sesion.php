<?php
session_start();

    if (isset($_SESSION['usuario'])) {
        echo '<h1 class="hola">Bienvenido: ' . $_SESSION['usuario'] . '</h1>';
    } else {
        echo '<script>
            alert("Debes iniciar sesión para acceder a esta página");
            window.location = "index.php";
        </script>';
        session_destroy();
        die();
    }
?>
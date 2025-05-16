<?php
include 'conexion_be.php';
include 'validar_sesion.php';
include 'validar_level_user.php';

// settings.php
// Este archivo contiene la configuración de la base de datos y otras configuraciones necesarias para el funcionamiento del sistema.
// Configuración de la base de datos
$servidor = "localhost"; // Cambia esto si tu servidor de base de datos es diferente
$usuario = "root"; // Cambia esto si tu usuario de base de datos es diferente
$contraseña = ""; // Cambia esto si tu contraseña de base de datos es diferente
$base_datos = "registro"; // Cambia esto si tu base de datos es diferente


// Configuración de la zona horaria
date_default_timezone_set('America/Caracas'); // Cambia esto si tu zona horaria es diferente
// Configuración de la codificación de caracteres
$enlace->set_charset("utf8"); // Cambia esto si tu codificación de caracteres es diferente

include 'vista/notificaciones.php'; // Incluir el archivo de notificaciones

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Stilos/inicio.css">

</head>
<body>
    <?php include 'vista/top-bar.php'; ?>
<meta>
    <div class="container-settings">
        <div class="nav-settings">
            <div class="titulo-settings"><h2>Configuración de la Base de Datos</h2></div>
            <div class="nav">
                <ul>
                    <li>
                        Importar<i class="fa-solid fa-upload"></i>
                    </li>
                    <li>
                        Exportar<i class="fa-solid fa-download"></i>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</meta>
    

</body>
</html>
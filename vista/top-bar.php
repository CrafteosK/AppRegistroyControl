<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="Stilos/header.css">  Opcional: agrega estilos -->
</head>
<body>
    <!-- Header -->
    <header class="header">
        <button id="toggleBtn">☰</button>
        <div class="logo">
            <img src="imagen/Picsart_25-03-31_14-46-19-016.png" alt="logo">
            
        </div>
        <nav>
            <ul class="nav-links">
                <li> <a href="inicio.php" class="inicio">Inicio</a></li>
                <li> <a href="#" class="mision">Mision</a></li>
                <li> <a href="#" class="vision">Vision</a></li>
            </ul>
        </nav>
        <a href="cerrar_sesion.php" class="btn"><button>Cerrar sesion</button></a>
    </header>

    <!-- Barra lateral -->
    <nav class="sidebar" id="sidebar">
        <ul>
            <li><a href="asistencias.php">Asistencias</a></li>
            <li><a href="trabajadores.php">Trabajadores</a></li>
        </ul>
    </nav>
</body>
</html>
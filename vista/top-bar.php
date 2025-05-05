<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Stilos/header.css">
    <title>Inicio</title>
</head>
<body>
    <header>
        <div class="left">
            <div class="menu-container">
                <div class="menu" id="menu">
                    <div class="iconos"><ion-icon name="grid-outline"></ion-icon></div>

                </div>
            </div>
        </div>

        <div class="right">
         <h2 id="fecha"></h2>
            <a href="notificacion.php">
                <div class="iconos"><ion-icon name="notifications-outline"></ion-icon></div>
            </a>

            <a href="cambiar-nombre.php">
            <div class="iconos"><ion-icon name="person-circle-outline"></ion-icon></div>
            </a>

            <a href="cerrar_sesion.php">
                <div class="iconos"><ion-icon name="log-out-outline"></ion-icon></div>
            </a>
        </div>
    </header>
    <div class="sidebar" id="sidebar">
        <ul>
            <li class="logo" style="--bg:#ffffff;">
                <a href="#">
                    <div class="icon"><img src="imagen/Picsart_25-03-31_14-46-19-016.png" alt=""></div>
                    <div class="text">C.E.I Simoncito Guayana</div>
                </a>
            </li>
            <div class="Menulist">
                <li style="--bg:#ff4d4d;" class="active">
                    <a href="inicio.php">
                        <div class="icon"><ion-icon name="home-outline"></ion-icon></div>
                        <div class="text">Inicio</div>
                    </a>
                </li>
                <li style="--bg:#ff7e4d;">
                    <a href="asistencias.php">
                        <div class="icon"><ion-icon name="clipboard-outline"></ion-icon></div>
                        <div class="text">Asistencias</div>
                    </a>
                </li>
                <li style="--bg:#ffdf4d;">
                    <a href="trabajadores.php">
                        <div class="icon"><ion-icon name="man-outline"></ion-icon></div>
                        <div class="text">Trabajadores</div>
                    </a>
                </li>
                <li style="--bg:#4dff4d;">
                    <a href="#">
                        <div class="icon"><ion-icon name="medkit-outline"></ion-icon></div>
                        <div class="text">Reposo medico</div>
                    </a>
                </li>
                <li style="--bg:#884dff;">
                    <a href="#">
                        <div class="icon"><ion-icon name="person-outline"></ion-icon></div>
                        <div class="text">Usuario</div>
                    </a>
                </li>
            </div>
        </ul>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menu = document.getElementById('menu');
        const sidebar = document.getElementById('sidebar');
        const Menulist = document.querySelectorAll('.Menulist > li');
        const mainContent = document.getElementById('main-content');

        // Alternar la visibilidad del menú lateral
        menu.addEventListener('click', () => {
            sidebar.classList.toggle('menu-toggle');
        });

        // Función para establecer el enlace activo basado en la URL actual
        function setActiveLink() {
            const currentPath = window.location.pathname.split('/').pop(); // Obtiene el nombre del archivo actual
            Menulist.forEach((item) => {
                const link = item.querySelector('a');
                if (link) {
                    const href = link.getAttribute('href').split('/').pop(); // Obtiene el nombre del archivo del href
                    if (href === currentPath) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                }
            });
        }

        // Llama a la función al cargar la página
        setActiveLink();

        // Agrega el evento de clic para cambiar el enlace activo manualmente
        Menulist.forEach((item) => {
            item.addEventListener('click', function () {
                Menulist.forEach((el) => el.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Carga dinámica del contenido de los módulos
        if (mainContent) {
            const links = document.querySelectorAll('.Menulist a');

            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault(); // Evita la recarga completa
                    const url = link.getAttribute('href');

                    // Carga el contenido del módulo
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.text();
                        })
                        .then(data => {
                            mainContent.innerHTML = data; // Inserta el contenido en el contenedor
                            history.pushState(null, '', url); // Actualiza la URL sin recargar
                            setActiveLink(); // Actualiza el enlace activo
                        })
                        .catch(error => console.error('Error al cargar el módulo:', error));
                });
            });
        }
    });
</script>

</body>
</html>

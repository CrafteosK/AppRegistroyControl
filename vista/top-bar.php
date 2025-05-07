<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="Stilos/header.css">
    <title>Inicio</title>
</head>
<body>
    <header>
        <div class="left">
            <div class="menu-container">
                <div class="menu" id="menu">
                    <div class="i"><i class="fa-solid fa-bars"></i></div>

                </div>
            </div>
        </div>

        <div class="right">
         <h2 id="fecha"></h2>
            <a href="notificacion.php">
                <div class="iconos"><i class="fa-solid fa-bell"></i></div>
            </a>

            <a href="cambiar-nombre.php">
            <div class="iconos"><i class="fa-solid fa-circle-user"></i></div>
            </a>

            <a href="cerrar_sesion.php">
                <div class="iconos"><i class="fa-solid fa-right-to-bracket"></i></div>
            </a>
        </div>
    </header>
    <div class="sidebar" id="sidebar">
        <ul>
            <li class="logo" style="--bg:#ffffff;">
                <a href="inicio.php">
                    <div class="icon"><img src="imagen/Picsart_25-03-31_14-46-19-016.png" alt=""></div>
                    <div class="text">C.E.I Simoncito Guayana</div>
                </a>
            </li>
            <div class="Menulist">
                <li style="--bg:#ff4d4d;" class="active">
                    <a href="inicio.php">
                        <div class="icon"><i class="fa-solid fa-house"></i></div>
                        <div class="text">Inicio</div>
                    </a>
                </li>
                <li style="--bg:#ff7e4d;">
                    <a href="asistencias.php">
                        <div class="icon"><i class="fa-solid fa-clipboard"></i></div>
                        <div class="text">Asistencias</div>
                    </a>
                </li>
                <li style="--bg:#ffdf4d;">
                    <a href="trabajadores.php">
                        <div class="icon"><i class="fa-solid fa-person"></i></div>
                        <div class="text">Trabajadores</div>
                    </a>
                </li>
                <li style="--bg:#4dff4d;">
                    <a href="reposo_medico.php">
                        <div class="icon"><i class="fa-solid fa-kit-medical"></i></div>
                        <div class="text">Reposo medico</div>
                    </a>
                </li>
                <li style="--bg:#884dff;">
                    <a href="usuarios.php">
                        <div class="icon"><i class="fa-solid fa-user"></i></div>
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

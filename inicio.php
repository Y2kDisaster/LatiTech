<!DOCTYPE html>
<html>
<head>
    <title>Inicio</title>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/inicio.css">
    <?php
        session_start();

        // Verificar si la sesión está iniciada y el usuario está autenticado
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // Redirigir al usuario a la página de inicio de sesión o mostrar un mensaje de error
            header("Location: loging.php");
            exit();
        }

        // Cerrar sesión al hacer clic en el botón
        if (isset($_POST['logout'])) {
            session_unset(); // Eliminar todas las variables de sesión
            session_destroy(); // Destruir la sesión
            header("Location: loging.php");
            exit();
        }
    ?>
</head>
<body>
<div class="header"> 
   <div>
   <a href="https://www.latitude.mx"><img class="logo" src="img/latitude2.png" alt="Logo de la empresa"></a>
    </div>
    <div class="menu-container">
        <!-- Aquí puedes agregar los elementos de tu menú -->
        <a class="button-menu logout-button" href="loging.php">Cerrar sesión</a>
    </div>
</div>

<div class="container">
    <h1>Bienvenido</h1>
    <h3>Selecciona a qué datos quieres acceder</h3>
    <div class="button-container">
        <a href="equipos.php" class="button">
            <i class="fa fa-desktop"></i>
            <span>Equipos</span>
            <img src="img/compu.png" />
        </a>
        <a href="empleados.php" class="button">
            <i class="fa fa-user"></i>
            <span>Empleados</span>
            <img src="img/empleados.png" />
        </a>
        <a href="asignaciones.php" class="button">
            <i class="fa fa-asignacion"></i>
            <span>Asignaciones</span>
            <img src="img/asig.png" />
        </a>
        <a href="organizacion.php" class="button">
            <i class="fa fa-asignacion"></i>
            <span>Organigrama</span>
            <img src="img/organigrama.png" />
        </a>         
    </div>
</div>
<form id="logout-form" action="loging.php" method="POST" style="display: none;">
    <input type="hidden" name="logout" value="1">
</form>

<script type="text/javascript">
    // Agregar evento al botón de cerrar sesión
    var logoutButton = document.querySelector('.logout-button');
    logoutButton.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('logout-form').submit();
    });
</script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/consultas.css">
    <title>Equipos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/equipos.js"></script>
</head>
<body>
    <div class="header">
        <div>
        <a href="https://www.latitude.mx"><img class="logo" src="img/latitude2.png" alt="Logo de la empresa"></a>
        </div>
        <div class="menu-container">
            <!-- Aquí puedes agregar los elementos de tu menú -->
            <a class="button-menu" href="inicio.php">Inicio</a>
            <a class="button-menu" href="asignaciones.php">Asignaciones</a>
            <a class="button-menu" href="empleados.php">Empleados</a>
            <a class="button-menu" href="equipos.php">Equipos</a>
            <a class="button-menu" href="usuarios.php">Usuarios</a>
            <a class="button-menu" href="organizacion.php">Organigrama</a>
            <a class="button-menu" href="reportes.php">Reportes</a>
            <a class="button-menu logout" href="loging.php">Cerrar sesión</a>
        </div>
    </div>
    <h1>Consulta de Equipos</h1>
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div style="margin-bottom: 20px;">
        <label for="filtro" style="font-weight: bold;">Buscar: </label>
        <input type="text" id="filtro" name="filtro" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
        <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: #fff; border: none; border-radius: 5px;">Buscar</button>
    </div>
</form>
    <?php
    include('conexion.php');
    include('consulta_equipos.php');
    ?>
</body>
</html>

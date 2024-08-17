<?php
$servername = "localhost";  // Nombre del servidor
$username = "root";         // Usuario de MySQL (por defecto en XAMPP)
$password = "";             // Contraseña de MySQL (por defecto en XAMPP)
$dbname = "latitudemx";     // Nombre de la base de datos

// Crear una conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión es exitosa
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}
session_start();

// Verificar si la sesión está iniciada y el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirigir al usuario a la página de inicio de sesión o mostrar un mensaje de error
    header("Location: loging.php");
    exit();
}
// Verificar si se ha enviado el formulario de ingreso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos ingresados en el formulario
    $nombre_equipo = $_POST["nombre_equipo"];
    $estado = $_POST["estado"];
    $marca_equipo = $_POST["marca_equipo"];
    $modelo_equipo = $_POST["modelo_equipo"];
    $sistema_operativo = $_POST["sistema_operativo"];
    $marca_procesador = $_POST["marca_procesador"];
    $modelo_procesador = $_POST["modelo_procesador"];
    $velocidad_procesador = $_POST["velocidad_procesador"];
    $ram = $_POST["ram"];
    $num_discos = $_POST["num_discos"];
    $tipo_disco0 = $_POST["tipo_disco0"];
    $tipo_disco1 = $_POST["tipo_disco1"];
    $idproducto = $_POST["idproducto"];
    $numero_serie = $_POST["numero_serie"];

    // Consulta SQL para verificar si el campo NOMBRE_EQUIPO está duplicado
    $check_duplicate_sql = "SELECT NOMBRE_EQUIPO FROM equipos WHERE NOMBRE_EQUIPO = '$nombre_equipo'";
    $duplicate_result = $conn->query($check_duplicate_sql);

    if ($duplicate_result->num_rows > 0) {
        echo '<script>alert("El nombre de equipo \'' . $nombre_equipo . '\' ya está duplicado. Por favor, ingresa otro nombre.");</script>';
    } else {
        // Consulta SQL para insertar un nuevo registro en la tabla "equipos"
        $insert_sql = "INSERT INTO equipos (NOMBRE_EQUIPO, ESTADO, MARCA_EQUIPO, MODELO_EQUIPO, SISTEMA_OPERATIVO, MARCA_PROCESADOR, MODELO_PROCESADOR, VELOCIDAD_PROCESADOR, RAM, NUMERO_DISCOS, TIPO_DISCO0, TIPO_DISCO1, ID_PRODUCTO, NUMERO_SERIE)
                VALUES ('$nombre_equipo', '$estado', '$marca_equipo', '$modelo_equipo', '$sistema_operativo', '$marca_procesador', '$modelo_procesador', '$velocidad_procesador', '$ram', '$num_discos', '$tipo_disco0', '$tipo_disco1', '$idproducto', '$numero_serie')";

        if ($conn->query($insert_sql) === true) {
            echo '<script>alert("Registro insertado correctamente.");</script>';
        } else {
            echo '<script>alert("Error al insertar el registro: ' . $conn->error . '");</script>';
        }
    }
}
// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <title>Ingresar Registro de Equipo</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-top: 70px
        }

        form {
            max-width: 600px; /* Aumenta el ancho del formulario */
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Divide el formulario en 2 columnas */
            grid-gap: 30px; /* Espacio entre los campos */
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: white;
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: auto;
        }

        .menu-container {
            margin-right: 45px;
            flex: 1;
            display: flex;
            justify-content: flex-end;
        }

        .logo-container {
            margin-left: 0;
        }

        .logo {
            width: 300px;
            height: 80px;
        }

        .button-menu {
        display: inline-block;
        padding: 8px 16px; /* Ajusta el relleno (padding) según sea necesario */
        font-size: 14px; /* Ajusta el tamaño de fuente según sea necesario */
        text-align: center;
        text-decoration: none;
        background-color: #4CAF50;
        color: #fff;
        border-radius: 4px;
        flex: 1;
        margin: 0 5px;
        }

        .button-menu.logout {
         background-color: red;
        }
        /* Estilos para la barra de desplazamiento en WebKit (Chrome, Safari) */
        ::-webkit-scrollbar {
            width: 10px; /* Ancho de la barra de desplazamiento */
            background-color: #f2f2f2; /* Color de fondo de la barra de desplazamiento */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #888; /* Color del "pulgar" de la barra de desplazamiento */
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #555; /* Color del "pulgar" de la barra de desplazamiento al pasar el cursor sobre él */
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img class="logo" src="img/latitude2.png" alt="Logo de la empresa">
        </div>
        <div class="menu-container">
            <!-- Aquí puedes agregar los elementos de tu menú -->
            <a class="button-menu" href="inicio.php">Inicio</a>
            <a class="button-menu" href="#">Asignaciones</a>
            <a class="button-menu" href="#">Empleados</a>
            <a class="button-menu" href="equipos.php">Equipos</a>
            <a class="button-menu logout" href="loging.php">Cerrar sesión</a>
        </div>
    </div>
    <h1>Agregar Equipo</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div>
            <label for="nombre_equipo">Nombre:</label>
            <input type="text" name="nombre_equipo" required>
        </div>

        <div>
            <label for="estado">Estado:</label>
            <input type="text" name="estado" required>
        </div>

        <div>
            <label for="marca_equipo">Marca:</label>
            <input type="text" name="marca_equipo" required>
        </div>

        <div>
            <label for="modelo_equipo">Modelo:</label>
            <input type="text" name="modelo_equipo" required>
        </div>

        <div>
            <label for="sistema_operativo">Sistema Operativo:</label>
            <input type="text" name="sistema_operativo" required>
        </div>

        <div>
            <label for="marca_procesador">Marca del Procesador:</label>
            <input type="text" name="marca_procesador" required>
        </div>

        <div>
            <label for="modelo_procesador">Modelo del Procesador:</label>
            <input type="text" name="modelo_procesador" required>
        </div>

        <div>
            <label for="velocidad_procesador">Velocidad del Procesador:</label>
            <input type="text" name="velocidad_procesador" required>
        </div>

        <div>
            <label for="ram">Memoria RAM:</label>
            <input type="text" name="ram" required>
        </div>

        <div>
            <label for="num_discos">Número de Discos:</label>
            <input type="text" name="num_discos" required>
        </div>

        <div>
            <label for="tipo_disco0">Tipo de Disco 0:</label>
            <input type="text" name="tipo_disco0" required>
        </div>

        <div>
            <label for="tipo_disco1">Tipo de Disco 1:</label>
            <input type="text" name="tipo_disco1" required>
        </div>

        <div>
            <label for="idproducto">ID Producto:</label>
            <input type="text" name="idproducto" required>
        </div>

        <div>
            <label for="numero_serie">Número de Serie:</label>
            <input type="text" name="numero_serie" required>
        </div>

        <div>
            <input type="submit" value="Agregar">
        </div>
    </form>
</body>
</html>

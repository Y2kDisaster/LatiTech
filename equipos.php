<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/equipos.css">
    <title>Consulta de Equipos</title>
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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latitudemx";

    // Create the connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
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
    // Verificar si se ha enviado el formulario de eliminar
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_equipo"])) {
        $nombre_equipo = $_POST["nombre_equipo"];
        eliminarRegistro($nombre_equipo);
    }

    function eliminarRegistro($nombre_equipo) {
        global $conn;

        // Consulta SQL para eliminar el registro
        $sql = "DELETE FROM equipos WHERE NOMBRE_EQUIPO = '$nombre_equipo'";

        if ($conn->query($sql) === true) {
            echo "Registro eliminado correctamente.";
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }
    }

    // Construir la consulta SQL base
    $sql = "SELECT * FROM equipos";

    // Verificar si se envió un filtro
    if (isset($_GET["filtro"])) {
        // Obtener el filtro del formulario
        $filtro = $_GET["filtro"];
        
        // Agregar el filtro a la consulta SQL
        $sql .= " WHERE NOMBRE_EQUIPO LIKE '%$filtro%' OR NUMERO_SERIE LIKE '%$filtro%' OR ESTADO LIKE '%$filtro%' OR MARCA_EQUIPO LIKE '%$filtro%' OR MODELO_EQUIPO 
        LIKE '%$filtro%' OR SISTEMA_OPERATIVO LIKE '%$filtro%' OR MARCA_PROCESADOR LIKE '%$filtro%' OR MODELO_PROCESADOR LIKE '%$filtro%' OR VELOCIDAD_PROCESADOR LIKE 
        '%$filtro%' OR RAM LIKE '%$filtro%' OR NUMERO_DISCOS LIKE '%$filtro%' OR TIPO_DISCO0 LIKE '%$filtro%' OR TIPO_DISCO1 LIKE '%$filtro%'";
    }
    // Consulta a la tabla "equipos"
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Imprimir los registros en una tabla
        echo "<table class='table'>";
        echo "<tr><th>Nombre</th><th>Número de Serie</th><th>Estado</th><th>Marca</th><th>Modelo</th><th>Sistema Operativo</th><th>Marca Procesador</th><th>Modelo Procesador</th>
        <th>Velocidad Procesador</th><th>RAM</th><th>Número de Discos</th><th class='disco'>Disco 0</th><th class='disco'>Disco 1</th><td colspan='2'><center>
        <a class='button' href='agregar_equipo.php'>Agregar un nuevo registro</a></center></td></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='registro'>" . $row["NOMBRE_EQUIPO"] . "</td>";
            echo "<td class='registro'>" . $row["NUMERO_SERIE"] . "</td>";
            echo "<td class='registro'>" . $row["ESTADO"] . "</td>";
            echo "<td class='registro'>" . $row["MARCA_EQUIPO"] . "</td>";
            echo "<td class='registro'>" . $row["MODELO_EQUIPO"] . "</td>";
            echo "<td class='registro'>" . $row["SISTEMA_OPERATIVO"] . "</td>";
            echo "<td class='registro'>" . $row["MARCA_PROCESADOR"] . "</td>";
            echo "<td class='registro'>" . $row["MODELO_PROCESADOR"] . "</td>";
            echo "<td class='registro'>" . $row["VELOCIDAD_PROCESADOR"] . "</td>";
            echo "<td class='registro'>" . $row["RAM"] . "</td>";
            echo "<td class='registro'><center>" . $row["NUMERO_DISCOS"] . "</center></td>";
            echo "<td class='registro'>" . $row["TIPO_DISCO0"] . "</td>";
            echo "<td class='registro'>" . $row["TIPO_DISCO1"] . "</td>";
            echo "<td><a class='button edit' href='modificar_equipo.php?id=" . $row["NOMBRE_EQUIPO"] . "'><img src='img/editar.png'/></a></td>"     ;
            echo "<td><a class='button delete' onclick='eliminarRegistro(\"" . $row["NOMBRE_EQUIPO"] . "\")'><img src='img/eliminar.png'/></a></td>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron registros.";
    }
    // Agregar botón para agregar un nuevo registro
    echo "<div class='add-button'><a class='button' href='agregar_equipo.php'>Agregar un nuevo registro</a></div>";
    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>

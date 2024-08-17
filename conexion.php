<!DOCTYPE html>
<html>

<head>
    <link rel="icon" type="image/x-icon" href="latitude.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Consulta de Equipos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: auto; /* Ajuste automático de la tabla */
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            width: 200px;
        }

        td {
            width: 150px;
        }

        a.button {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 4px;
        }

        a.button:hover {
            background-color: #45a049;
        }

        .add-button {
            text-align: center;
            margin-top: 20px;
        }

        .registro {
            font-size: 10px; /* Tamaño de letra deseado */
        }

        * {
            margin: 0px;
            padding: 0px;
        }

        #header {
            margin: auto;
            width: 500px;
            font-family: Arial, Helvetica, sans-serif;
        }

        ul,
        ol {
            list-style: none;
        }

        .nav > li {
            float: left;
        }

        .nav li a {
            background-color: #91acb5;
            color: #fff;
            text-decoration: none;
            padding: 10px 12px;
            display: block;
        }

        .nav li a:hover {
            background-color: #434343;
        }

        .nav li ul {
            display: none;
            position: absolute;
            min-width: 140px;
        }

        .nav li:hover > ul {
            display: block;
        }

        .nav li ul li {
            position: relative;
        }

        .nav li ul li ul {
            right: -140px;
            top: 0px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            width: 150px;
        }
    </style>
    <script>
        function eliminarRegistro(nombreEquipo) {
            if (confirm("¿Estás seguro de que deseas eliminar este equipo?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "eliminar_equipo.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        // Eliminación exitosa, actualizar la tabla y ajustarla
                        location.reload();
                    }
                };
                xhr.send("nombre_equipo=" + encodeURIComponent(nombreEquipo));
            }
        }

        $(document).ready(function () {
            adjustTable();
        });

        function adjustTable() {
            var table = $("table");
            table.css("table-layout", "auto");
            table.width("100%");
        }
    </script>
</head>

<body>
    <div id="header">
        <div class="header-content">
            <img class="logo" src="latitude2.png" />
            <ul class="nav">
                <li><a href="">Inicio</a></li>
                <li><a href="">Asignaciones</a>
                    <ul>
                        <li><a href=""></a></li>
                        <li><a href=""></a></li>
                        <li><a href=""></a>
                            <ul>
                                <li><a href="">Submenu1</a></li>
                                <li><a href="">Submenu2</a></li>
                                <li><a href="">Submenu3</a></li>
                                <li><a href="">Submenu4</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="">Empleados</a>
                    <ul>
                        <li><a href="">Submenu1</a></li>
                        <li><a href="">Submenu2</a></li>
                        <li><a href="">Submenu3</a></li>
                        <li><a href="">Submenu4</a></li>
                    </ul>
                </li>
                <li><a href="">Equipos</a></li>
            </ul>
        </div>
    </div>

    <h1>Consulta de Equipos</h1>

    <!-- Resto del código HTML omitido por brevedad -->
</body>

</html>

    
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

    // Consulta a la tabla "equipos"
    $sql = "SELECT * FROM equipos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Imprimir los registros en una tabla
        echo "<table class = 'table'>";
        echo "<tr><th>Nombre</th><th>Estado</th><th>Marca</th><th>Modelo</th><th>Sistema Operativo</th><th>Marca Procesador</th><th>Modelo Procesador</th><th>Velocidad Procesador</th><th>RAM</th><th>Número de Discos</th><th>Disco 0</th><th>Disco 1</th><th>ID Producto</th><th>Número de Serie</th><td colspan ='2'><center><a class='button' href='agregar_equipo.php'>Agregar un nuevo registro</a></center></td></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td class = 'registro'>" . $row["NOMBRE_EQUIPO"] . "</td>";
    echo "<td class = 'registro'>" . $row["ESTADO"] . "</td>";
    echo "<td class = 'registro'>" . $row["MARCA_EQUIPO"] . "</td>";
    echo "<td class = 'registro'>" . $row["MODELO_EQUIPO"] . "</td>";
    echo "<td class = 'registro'>" . $row["SISTEMA_OPERATIVO"] . "</td>";
    echo "<td class = 'registro'>" . $row["MARCA_PROCESADOR"] . "</td>";
    echo "<td class = 'registro'>" . $row["MODELO_PROCESADOR"] . "</td>";
    echo "<td class = 'registro'>" . $row["VELOCIDAD_PROCESADOR"] . "</td>";
    echo "<td class = 'registro'>" . $row["RAM"] . "</td>";
    echo "<td class = 'registro'>" . $row["NUMERO_DISCOS"] . "</td>";
    echo "<td class = 'registro'>" . $row["TIPO_DISCO0"] . "</td>";
    echo "<td class = 'registro'>" . $row["TIPO_DISCO1"] . "</td>";
    echo "<td class = 'registro'>" . $row["ID_PRODUCTO"] . "</td>";
    echo "<td class = 'registro'>" . $row["NUMERO_SERIE"] . "</td>";
    echo "<td><a class='button' href='modificar_equipo.php?id=" . $row["NOMBRE_EQUIPO"] . "'>Editar</a></td>";
    echo "<td><a class='button' onclick='eliminarRegistro(\"" . $row["NOMBRE_EQUIPO"] . "\")'>Eliminar</a></td>";
    echo "</tr>";
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

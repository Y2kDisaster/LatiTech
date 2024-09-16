<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latitudemx";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: loging.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ID_EMPLEADO = $_POST["ID_EMPLEADO"];
    $ID_EQUIPO = $_POST["ID_EQUIPO"];

    $check_duplicate_sql = "SELECT ID_EMPLEADO FROM empleados WHERE ID_EMPLEADO = '$ID_EMPLEADO'";
    $duplicate_result = $conn->query($check_duplicate_sql);

    if ($duplicate_result->num_rows > 0) {
        echo '<script>alert("El ID de empleado \'' . $ID_EMPLEADO . '\' ya existe. Por favor, ingresa otro.");</script>';
    } else {
        $insert_sql = "INSERT INTO empleados (ID_EMPLEADO, NOMBRE)
                VALUES ('$ID_EMPLEADO', '$ID_EQUIPO')";

        if ($conn->query($insert_sql) === true) {
            echo '<script>alert("Registro insertado correctamente.");</script>';
        } else {
            echo '<script>alert("Error al insertar el registro: ' . $conn->error . '");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/agregar_asignacion.css">
    <title>Ingresar Registro de Asignación</title>
</head>
<body>
    <div class="header">
        <div>
            <a href="https://www.latitude.mx"><img class="logo" src="img/latitude2.png" alt="Logo de la empresa"></a>
        </div>
        <div class="menu-container">
            <a class="button-menu" href="inicio.php">Inicio</a>
            <a class="button-menu" href="#">Asignaciones</a>
            <a class="button-menu" href="empleados.php">Empleados</a>
            <a class="button-menu" href="equipos.php">Equipos</a>
            <a class="button-menu logout" href="loging.php">Cerrar sesión</a>
        </div>
    </div>
    <h1>Agregar Asignación</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div>
            <label for="ID_EMPLEADO">ID Empleado:</label>
            <select name="ID_EMPLEADO" required>
                <option value="">Seleccionar Empleado</option>
                <?php
                // Consulta para obtener los empleados
                $sql_id = "SELECT ID_EMPLEADO FROM empleados";
                $result_empleados = $conn->query($sql_id);

                if ($result_empleados->num_rows > 0) {
                    while ($row = $result_empleados->fetch_assoc()) {
                        echo '<option value="' . $row["ID_EMPLEADO"] . '">' . $row["ID_EMPLEADO"] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay empleados disponibles</option>';
                }
                ?>
            </select>
        </div>

        <div>
            <label for="NOMBRE">Nombre:</label>
            <input type="text" name="NOMBRE" value="<?php
                // Consulta para obtener el nombre del empleado seleccionado
                if (!empty($_POST['ID_EMPLEADO'])) {
                    $ID_EMPLEADO = $_POST['ID_EMPLEADO'];
                    $sql_nombre = "SELECT NOMBRES FROM empleados WHERE ID_EMPLEADO = '$ID_EMPLEADO'";
                    $result_nombre = $conn->query($sql_nombre);

                    if ($result_nombre->num_rows > 0) {
                        $row_nombre = $result_nombre->fetch_assoc();
                        echo htmlspecialchars($row_nombre['NOMBRES']);
                    } else {
                        echo "";
                    }
                }
            ?>">
        </div>

        <div>
            <label for="ID_EQUIPO">Nombre del Equipo:</label>
            <select name="ID_EQUIPO" required>
                <option value="">Seleccionar Equipo</option>
                <?php
                // Consulta para obtener los equipos con estado 'STOCK'
                $sql_equipos = "SELECT NOMBRE_EQUIPO FROM equipos WHERE ESTADO = 'STOCK'";
                $result_equipos = $conn->query($sql_equipos);

                if ($result_equipos->num_rows > 0) {
                    while ($row = $result_equipos->fetch_assoc()) {
                        echo '<option value="' . $row["NOMBRE_EQUIPO"] . '">' . $row["NOMBRE_EQUIPO"] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay equipos disponibles en STOCK</option>';
                }
                ?>
            </select>
        </div>

        <div>
            <input type="submit" value="Agregar">
        </div>
    </form>

    <?php $conn->close(); // Cerrando la conexión al final ?>
</body>
</html>

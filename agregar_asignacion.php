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
    <link rel="stylesheet" type="text/css" href="css/agregar_empleado.css">
    <title>Ingresar Registro de Equipo</title>
</head>
<body>
    <div class="header">
        <div>
            <img class="logo" src="img/latitude2.png" alt="Logo de la empresa">
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
            <input type="text" name="ID_EMPLEADO" required>
        </div>

        <div>
            <label for="ID_EQUIPO">Nombre del Equipo:</label>
            <select name="ID_EQUIPO" required>
                <option value="">Seleccione un equipo</option>
                <?php
                // Consulta para obtener los equipos con estado 'STOCK'
                $sql = "SELECT ID_EQUIPO FROM equipos WHERE ESTADO = 'STOCK'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["ID_EQUIPO"] . '">' . $row["NOMBRE_EQUIPO"] . '</option>';
                    }
                } else {
                    echo '<option value="">No hay equipos disponibles en STOCK</option>';
                }

                $conn->close();
                ?>
            </select>
        </div>

        <div>
            <input type="submit" value="Agregar">
        </div>
    </form>
</body>
</html>
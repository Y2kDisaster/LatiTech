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

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los valores de ID_EMPLEADO e ID_EQUIPO del formulario
    $ID_EMPLEADO = $_POST["ID_EMPLEADO"];
    $ID_EQUIPO = $_POST["ID_EQUIPO"];

    // Consulta para verificar si el empleado ya tiene una asignación de equipo
    $check_duplicate_sql = "SELECT ID_EMPLEADO FROM asignaciones WHERE ID_EMPLEADO = ?";
    $stmt = $conn->prepare($check_duplicate_sql);  // Usamos prepared statements para seguridad
    $stmt->bind_param("s", $ID_EMPLEADO);  // 's' indica que se está enlazando un string
    $stmt->execute();
    $duplicate_result = $stmt->get_result();

    // Verifica si el empleado ya tiene una asignación
    if ($duplicate_result->num_rows > 0) {
        echo '<script>alert("El ID de empleado \'' . $ID_EMPLEADO . '\' ya tiene una asignación. Por favor, ingresa otro.");</script>';
    } else {
        // Inserta el nuevo registro en la tabla asignaciones
        $insert_sql = "INSERT INTO asignaciones (ID_EMPLEADO, ID_EQUIPO) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("ss", $ID_EMPLEADO, $ID_EQUIPO);  // 'ss' indica dos strings

        if ($stmt_insert->execute()) {
            echo '<script>alert("Registro insertado correctamente.");</script>';
        } else {
            echo '<script>alert("Error al insertar el registro: ' . $conn->error . '");</script>';
        }

        $stmt_insert->close();
    }

    // Cierra la declaración y la conexión
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/agregar_asignacion.css">
    <title>Ingresar Registro de Equipo</title>
</head>
<body>
    <div class="header">
        <div>
            <img class="logo" src="img/latitude2.png" alt="Logo de la empresa">
        </div>
        <div class="menu-container">
            <a class="button-menu" href="inicio.php">Inicio</a>
            <a class="button-menu" href="asignaciones.php">Asignaciones</a>
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
        $sql = "SELECT NOMBRE_EQUIPO FROM equipos WHERE ESTADO = 'STOCK'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Mostrar el nombre del equipo como el valor y el texto de la opción
                echo '<option value="' . $row["NOMBRE_EQUIPO"] . '">' . $row["NOMBRE_EQUIPO"] . '</option>';
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
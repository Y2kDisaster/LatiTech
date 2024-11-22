<?php
include('conexion.php');
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
    $ID_EMPLEADO = $_POST["ID_EMPLEADO"];
    $NOMBRE = $_POST["NOMBRE"];
    $APELLIDOS = $_POST["APELLIDOS"];
    $CORREO = $_POST["CORREO"];

    // Consulta SQL para verificar si el campo ID_EMPLEADO está duplicado
    $check_duplicate_sql = "SELECT ID_EMPLEADO FROM empleados WHERE ID_EMPLEADO = '$ID_EMPLEADO'";
    $duplicate_result = $conn->query($check_duplicate_sql);

    if ($duplicate_result->num_rows > 0) {
        echo '<script>alert("El nombre de equipo \'' . $ID_EMPLEADO . '\' ya está duplicado. Por favor, ingresa otro nombre.");</script>';
    } else {
        // Consulta SQL para insertar un nuevo registro en la tabla "empleados"
        $insert_sql = "INSERT INTO empleados (ID_EMPLEADO, NOMBRES, APELLIDOS, CORREO, FECHA_CREACION)
                VALUES ('$ID_EMPLEADO', '$NOMBRE', '$APELLIDOS', '$CORREO',NOW())";

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
    <link rel="stylesheet" type="text/css" href="css/agregar_empleado.css">
    <title>Ingresar Registro de Equipo</title>
</head>
<body>
    <div class="header">
        <div>
        <a href="https://www.latitude.mx"> <img class="logo" src="img/latitude2.png" alt="Logo de la empresa"></a>
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
    <h1>Agregar Empleado</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div>
            <label for="ID_EMPLEADO">ID:</label>
            <input type="text" name="ID_EMPLEADO" required>
        </div>

        <div>
            <label for="NOMBRE">Nombre(s):</label>
            <input type="text" name="NOMBRE" required>
        </div>

        <div>
            <label for="APELLIDOS">Apellidos:</label>
            <input type="text" name="APELLIDOS" required>
        </div>

        <div>
            <label for="CORREO">Correo:</label>
            <input type="text" name="CORREO" required>
        </div>

        <div>
            <input type="submit" value="Agregar">
        </div>
    </form>
</body>
</html>

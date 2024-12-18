<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: loging.php");
    exit();
}

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los valores de ID_ACCESO e NOMBRE del formulario
    $NOMBRE = $_POST["NOMBRE"];
    $USUARIO = $_POST["USUARIO"];
    $PASSWORD = $_POST["PASSWORD"];

    // Consulta para verificar si el empleado ya tiene una asignación de equipo
    $check_duplicate_sql = "SELECT ID_ACCESO FROM accesos WHERE ID_ACCESO = ?";
    $stmt = $conn->prepare($check_duplicate_sql);  // Usamos prepared statements para seguridad
    $stmt->bind_param("s", $ID_ACCESO);  // 's' indica que se está enlazando un string
    $stmt->execute();
    $duplicate_result = $stmt->get_result();

    // Verifica si el empleado ya tiene una asignación
    if ($duplicate_result->num_rows > 0) {
        echo '<script>alert("El ID de empleado \'' . $ID_ACCESO . '\' ya tiene una asignación. Por favor, ingresa otro.");</script>';
    } else {
        // Inserta el nuevo registro en la tabla accesos
        $insert_sql = "INSERT INTO accesos (NOMBRE, USUARIO, PASSWORD,FECHA_CREACION) VALUES (?, ?, ?,NOW())";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sss", $NOMBRE, $USUARIO, $PASSWORD);  // 'ss' indica dos strings

        if ($stmt_insert->execute()) {
            echo '<script>alert("Registro insertado correctamente.");</script>';
            header("Location: usuarios.php");
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
            <a class="button-menu" href="accesos.php">Accesos</a>
            <a class="button-menu" href="empleados.php">Empleados</a>
            <a class="button-menu" href="equipos.php">Equipos</a>
            <a class="button-menu logout" href="loging.php">Cerrar sesión</a>
        </div>
    </div>
    <h1>Agregar Usuario</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div>
            <label for="NOMBRE">Nombre:</label>
            <input type="text" name="NOMBRE" required>
        </div>
        <div>
            <label for="USUARIO">Usuario:</label>
            <input type="text" name="USUARIO" required>
        </div>
        <div>
            <label for="PASSWORD">Contraseña:</label>
            <input type="text" name="PASSWORD" required>
        </div>        
        <div>
            <input type="submit" value="Agregar">
        </div>
    </form>
</body>
</html>
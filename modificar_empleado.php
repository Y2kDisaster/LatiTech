<?php
// Iniciar sesión para usar variables de sesión
session_start();

// Verificar si se ha proporcionado un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "ID de empleado no válido";
    header("Location: empleados.php");
    exit();
}

// Obtener el ID del empleado de la URL
$id = $_GET['id'];

// Verificar si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y procesar los datos enviados en el formulario
    $id_empleado = $_POST['id_empleado'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    
    // ... Aquí puedes realizar validaciones y procesamiento adicional según tus necesidades

    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latitudemx";

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Actualizar el registro en la tabla "empleados"
    $sql = "UPDATE empleados SET 
            ID_EMPLEADO = '$id_empleado',
            NOMBRES = '$nombre',
            APELLIDOS = '$apellidos',
            CORREO = '$correo'
            WHERE ID_EMPLEADO = '$id'";

    if ($conn->query($sql) === true) {
        // Mensaje de actualización exitosa
        $_SESSION['message'] = "El empleado se ha actualizado correctamente";
    } else {
        // Mensaje de error si la actualización falla
        $_SESSION['message'] = "Error al actualizar el empleado: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();

    // Redirigir a la página "empleados.php"
    header("Location: empleados.php");
    exit();
} else {
    // Obtener los datos del empleado de la base de datos
    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "latitudemx";

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consultar el empleado por ID
    $sql = "SELECT * FROM empleados WHERE ID_EMPLEADO = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Obtener los datos del empleado
        $row = $result->fetch_assoc();
        $id_empleado = $row["ID_EMPLEADO"];
        $nombre = $row["NOMBRES"];
        $apellidos = $row["APELLIDOS"];
        $correo = $row["CORREO"];
    } else {
        $_SESSION['message'] = "No se encontró el empleado";
        header("Location: empleados.php");
        exit();
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/modificar_empleado.css">
    <title>Editar Equipo</title>
</head>
<body>
    <h1>Editar Empleado</h1>

    <?php if(isset($_SESSION['message'])): ?>
        <div><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="id_empleado">ID del Empleado:</label>
            <input type="text" id="id_empleado" name="id_empleado" value="<?php echo $id_empleado; ?>" required>
        </div>
        <div>
            <label for="nombre">Nombre(s):</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>
        <div>
            <label for="apellidos">Apellido:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" required>
        </div>
        <div>
            <label for="correo">Correo:</label>
            <input type="text" id="correo" name="correo" value="<?php echo $correo; ?>" required>
        </div>

        <div>
            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</body>
</html>

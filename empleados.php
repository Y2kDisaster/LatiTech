<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/empleados.css">
    <title>Consulta de Empleados</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/empleados.js"></script>
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
    <h1>Consulta de Empleados</h1>
    <div class="search-container">
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div style="margin-bottom: 20px;">
        <label for="filtro" style="font-weight: bold;">Buscar: </label>
        <input type="text" id="filtro" name="filtro" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
        <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: #fff; border: none; border-radius: 5px;">Buscar</button>
    </div>
</form>
</body>
</html>

<?php
include('conexion.php');
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_empleado"])) {
    $id_empleado = $_POST["id_empleado"];
    eliminarRegistro($id_empleado);
}

function eliminarRegistro($id_empleado) {
    global $conn;

    // Consulta SQL para eliminar el registro
    $sql = "DELETE FROM empleados WHERE ID_EMPLEADO = '$id_empleado'";

    if ($conn->query($sql) === true) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}
$filtro = "";

// Verificar si se envió una palabra clave para buscar
if (isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
}

$sql = "SELECT * FROM empleados WHERE 
        ID_EMPLEADO LIKE '%$filtro%' OR 
        NOMBRES LIKE '%$filtro%' OR 
        APELLIDOS LIKE '%$filtro%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Imprimir los registros en una tabla
    echo "<table class='table'>";
    echo "<tr><th>ID</th><th>Nombre(s)</th><th>Apellido</th><th>Correo</th><td class = 'modify' colspan='2'><center>
    <a class='button' href='agregar_empleado.php'>Agregar un nuevo registro</a></center></td></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='registro'>" . $row["ID_EMPLEADO"] . "</td>";
        echo "<td class='registro'>" . $row["NOMBRES"] . "</td>";
        echo "<td class='registro'>" . $row["APELLIDOS"] . "</td>";
        echo "<td class='registro'>" . $row["CORREO"] . "</td>";
        echo "<td><a class='button edit' href='modificar_empleado.php?id=" . $row["ID_EMPLEADO"] . "'><img src='img/editar.png'/></a></td>"     ;
        echo "<td><a class='button delete' onclick='eliminarRegistro(\"" . $row["ID_EMPLEADO"] . "\")'><img src='img/eliminar.png'/></a></td>";
    }
    echo "</table>";
} else {
    echo "No se encontraron registros.";
}

// Agregar botón para agregar un nuevo registro
echo "<div class='add-button'><a class='button' href='agregar_empleado.php'>Agregar un nuevo registro</a></div>";

// Cerrar la conexión
$conn->close();
?>
</body>
</html>

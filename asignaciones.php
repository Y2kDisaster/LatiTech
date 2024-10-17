<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/asignaciones.css">
    <title>Consulta de Empleados</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/asignaciones.js"></script>
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
    <h1>Consulta de Asignaciones</h1>
    <div class="search-container">
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div style="margin-bottom: 20px;">
        <label for="filtro" style="font-weight: bold;">Buscar: </label>
        <input type="text" id="filtro" name="filtro" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
        <button type="submit" style="padding: 8px 15px; background-color: #007bff; color: #fff; border: none; border-radius: 5px;">Buscar</button>
    </div>
</form>
    </div>
    <!-- Resto del código HTML omitido por brevedad -->
</body>
</html>

<?php
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_asignacion"])) {
    $id_asignacion = $_POST["id_asignacion"];
    eliminarRegistro($id_asignacion);
}

function eliminarRegistro($id_) {
    global $conn;

    // Consulta SQL para eliminar el registro
    $sql = "DELETE FROM asignaciones WHERE ID_ASIGNACION = '$id_'";

    if ($conn->query($sql) === true) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}

// Inicializar la variable de búsqueda
$filtro = "";

// Verificar si se envió una palabra clave para buscar
if (isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
}

// Consulta a la tabla "asignaciones" con unión a la tabla "Empleados" y aplicación del filtro de búsqueda
$sql = "SELECT a.*, e.`Nombres`, e.Apellidos FROM asignaciones a INNER JOIN Empleados e ON a.ID_EMPLEADO = e.ID_EMPLEADO WHERE 
        a.ID_EQUIPO LIKE '%$filtro%' OR 
        e.`Nombres` LIKE '%$filtro%' OR 
        e.Apellidos LIKE '%$filtro%'";

$result = $conn->query($sql);

if ($result === false) {
    // Si hay un error en la consulta SQL, imprimimos el error y detenemos la ejecución del script
    echo "Error en la consulta SQL: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    // Imprimir los registros en una tabla
    echo "<table class='table'>";
    echo "<tr><th>Nombre del Equipo</th><th>ID Empleado</th><th>Nombres</th><th>Apellidos</th><td class='modify' colspan='2'><center>
    <a class='button' href='agregar_asignacion.php'>Agregar un nuevo registro</a></center></td></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='registro'>" . $row["ID_EQUIPO"] . "</td>";
        echo "<td class='registro'>" . $row["ID_EMPLEADO"] . "</td>";
        echo "<td class='registro'>" . $row["Nombres"] . "</td>";
        echo "<td class='registro'>" . $row["Apellidos"] . "</td>";
        echo "<td><a class='button edit' href='modificar_asignacion.php?id=" . $row["ID_ASIGNACION"] . "'><img src='img/editar.png'/></a></td>";
        echo "<td><a class='button delete' onclick='eliminarRegistro(\"" . $row["ID_ASIGNACION"] . "\")'><img src='img/eliminar.png'/></a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron registros.";
}

// Agregar botón para agregar un nuevo registro
echo "<div class='add-button'><a class='button' href='agregar_asignacion.php'>Agregar un nuevo registro</a></div>";
// Cerrar la conexión
$conn->close();
?>
</body>
</html>

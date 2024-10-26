<?php
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

function eliminarRegistro($id_asignacion) {
    global $conn;

    // Consulta SQL para eliminar el registro
    $sql = "DELETE FROM asignaciones WHERE ID_ASIGNACION = '$id_asignacion'";

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
$sql = "SELECT * FROM asignaciones WHERE 
        ID_ASIGNACION LIKE '%$filtro%' OR 
        ID_EMPLEADO LIKE '%$filtro%' OR 
        ID_EQUIPO LIKE '%$filtro%'";

$sql = "SELECT asignaciones.ID_ASIGNACION, empleados.NOMBRES, empleados.APELLIDOS, asignaciones.ID_EQUIPO 
        FROM asignaciones 
        JOIN empleados ON asignaciones.ID_EMPLEADO = empleados.ID_EMPLEADO";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error en la consulta SQL: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<tr><th>No. de Asignación</th><th>Empleado</th><th>Nombre del Equipo</th><td class='modify' colspan='2'><center>
    <a class='button' href='agregar_asignacion.php'>Agregar un nuevo registro</a></center></td></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='registro'>" . $row["ID_ASIGNACION"] . "</td>";
        echo "<td class='registro'>" . $row["NOMBRES"] . " " . $row["APELLIDOS"] . "</td>";
        echo "<td class='registro'>" . $row["ID_EQUIPO"] . "</td>";
        echo "<td><a class='button report' target='_blank' href='carta_responsiva.php?id=" . $row["ID_ASIGNACION"] . "'><img src='img/report.png'/></a></td>";
        echo "<td><a class='button edit' href='modificar_asignacion.php?id=" . $row["ID_ASIGNACION"] . "'><img src='img/editar.png'/></a></td>";
        // Formulario para eliminar el registro
        echo "<td><form method='POST' action='' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
        <input type='hidden' name='id_asignacion' value='" . $row["ID_ASIGNACION"] . "' />
        <button type='submit' class='button delete'><img src='img/eliminar.png'/></button>
        </form>
        </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron registros.";
}

echo "<div class='add-button'><a class='button' href='agregar_asignacion.php'>Agregar un nuevo registro</a></div>";
?>
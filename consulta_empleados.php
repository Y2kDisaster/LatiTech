<?php
session_start();

// Verificar si la sesión está iniciada y el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: loging.php");
    exit();
}

// Cerrar sesión al hacer clic en el botón
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
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

// Consulta SQL para obtener los registros ordenados por FECHA_CREACION de forma descendente
$sql = "SELECT ID_EMPLEADO, NOMBRES, APELLIDOS, CORREO FROM empleados WHERE 
        ID_EMPLEADO LIKE '%$filtro%' OR 
        NOMBRES LIKE '%$filtro%' OR 
        APELLIDOS LIKE '%$filtro%'
        ORDER BY FECHA_CREACION DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Imprimir los registros en una tabla
    echo "<table class='table'>";
    echo "<tr><th>ID</th><th>Nombre(s)</th><th>Apellido</th><th>Correo</th><td class='modify' colspan='2'><center>
    <a class='button' href='agregar_empleado.php'>Agregar un nuevo registro</a></center></td></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='registro'>" . $row["ID_EMPLEADO"] . "</td>";
        echo "<td class='registro'>" . $row["NOMBRES"] . "</td>";
        echo "<td class='registro'>" . $row["APELLIDOS"] . "</td>";
        echo "<td class='registro'>" . $row["CORREO"] . "</td>";
        echo "<td><a class='button edit' href='modificar_empleado.php?id=" . $row["ID_EMPLEADO"] . "'><img src='img/editar.png'/></a></td>";
        echo "<td><a class='button delete' onclick='eliminarRegistro(\"" . $row["ID_EMPLEADO"] . "\")'><img src='img/eliminar.png'/></a></td>";
        echo "</tr>";
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

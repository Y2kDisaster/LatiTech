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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_acceso"])) {
    $id_acceso = $_POST["id_acceso"];
    eliminarRegistro($id_acceso);
}

function eliminarRegistro($id_acceso) {
    global $conn;

    // Consulta SQL para eliminar el registro
    $sql = "DELETE FROM asignaciones WHERE ID_ACCESO = '$id_acceso'";

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
        ID_ACCESO LIKE '%$filtro%' OR 
        ID_EMPLEADO LIKE '%$filtro%' OR 
        ID_EQUIPO LIKE '%$filtro%'";

$sql = "SELECT * FROM accesos";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error en la consulta SQL: " . $conn->error;
    exit();
}

if ($result->num_rows > 0) {
    echo "<table class='table'>";
    echo "<tr><th>Nombre</th><th>Usuario</th><th>Contraseña</th><td class='modify' colspan='2'><center>
    <a class='button' href='agregar_asignacion.php'>Agregar un nuevo registro</a></center></td></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='registro'>" . $row["NOMBRE"] . "</td>";
        echo "<td class='registro'>" . $row["USUARIO"] . "</td>";
        echo "<td class='registro'>
        <span id='password_" . $row["ID_ACCESO"] . "' style='display: none;'>" . $row["PASSWORD"] . "</span>
        <input type='password' id='password_input_" . $row["ID_ACCESO"] . "' value='" . $row["PASSWORD"] . "' readonly>
        <button id='show' type='button' onclick='togglePassword(" . $row["ID_ACCESO"] . ")'>Mostrar</button></td>";
        echo "<td><a class='button edit' href='modificar_asignacion.php?id=" . $row["ID_ACCESO"] . "'><img src='img/editar.png'/></a></td>";
        // Formulario para eliminar el registro
        echo "<td><form method='POST' action='' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
        <input type='hidden' name='id_acceso' value='" . $row["ID_ACCESO"] . "' />
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
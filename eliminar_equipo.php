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

// Verificar si se ha enviado el formulario de eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_equipo"])) {
    $nombre_equipo = $_POST["nombre_equipo"];

    // Consulta SQL para eliminar el registro
    $sql = "DELETE FROM equipos WHERE NOMBRE_EQUIPO = '$nombre_equipo'";

    if ($conn->query($sql) === true) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>

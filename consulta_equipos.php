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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre_equipo"])) {
    $nombre_equipo = $_POST["nombre_equipo"];
    eliminarRegistro($nombre_equipo);
}

function eliminarRegistro($nombre_equipo) {
    global $conn;

    // Consulta SQL para eliminar el registro
    $sql = "DELETE FROM equipos WHERE NOMBRE_EQUIPO = '$nombre_equipo'";

    if ($conn->query($sql) === true) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }
}

// Construir la consulta SQL base con orden descendente por fecha de creación
$sql = "SELECT * FROM equipos";

// Verificar si se envió un filtro
if (isset($_GET["filtro"])) {
    $filtro = $_GET["filtro"];
    
    // Agregar el filtro a la consulta SQL
    $sql .= " WHERE NOMBRE_EQUIPO LIKE '%$filtro%' OR NUMERO_SERIE LIKE '%$filtro%' 
            OR ESTADO LIKE '%$filtro%' OR MARCA_EQUIPO LIKE '%$filtro%' 
            OR MODELO_EQUIPO LIKE '%$filtro%' OR SISTEMA_OPERATIVO LIKE '%$filtro%' 
            OR MARCA_PROCESADOR LIKE '%$filtro%' OR MODELO_PROCESADOR LIKE '%$filtro%' 
            OR VELOCIDAD_PROCESADOR LIKE '%$filtro%' OR RAM LIKE '%$filtro%' 
            OR NUMERO_DISCOS LIKE '%$filtro%' OR TIPO_DISCO0 LIKE '%$filtro%' 
            OR TIPO_DISCO1 LIKE '%$filtro%'";
}

// Agregar ordenamiento por fecha de creación en orden descendente
$sql .= " ORDER BY FECHA_CREACION DESC";

// Consulta a la tabla "equipos"
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Imprimir los registros en una tabla
    echo "<table class='table'>";
    echo "<tr><th>Nombre</th><th>Número de Serie</th><th>Estado</th><th>Marca</th><th>Modelo</th><th>Sistema Operativo</th><th>Marca Procesador</th><th>Modelo Procesador</th>
          <th>Velocidad Procesador</th><th>RAM</th><th>Número de Discos</th><th class='disco'>Disco 0</th><th class='disco'>Disco 1</th><td colspan='2'><center>
          <a class='button' href='agregar_equipo.php'>Agregar un nuevo registro</a></center></td></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td class='registro'>" . $row["NOMBRE_EQUIPO"] . "</td>";
        echo "<td class='registro'>" . $row["NUMERO_SERIE"] . "</td>";
        echo "<td class='registro'>" . $row["ESTADO"] . "</td>";
        echo "<td class='registro'>" . $row["MARCA_EQUIPO"] . "</td>";
        echo "<td class='registro'>" . $row["MODELO_EQUIPO"] . "</td>";
        echo "<td class='registro'>" . $row["SISTEMA_OPERATIVO"] . "</td>";
        echo "<td class='registro'>" . $row["MARCA_PROCESADOR"] . "</td>";
        echo "<td class='registro'>" . $row["MODELO_PROCESADOR"] . "</td>";
        echo "<td class='registro'>" . $row["VELOCIDAD_PROCESADOR"] . "</td>";
        echo "<td class='registro'>" . $row["RAM"] . "</td>";
        echo "<td class='registro'><center>" . $row["NUMERO_DISCOS"] . "</center></td>";
        echo "<td class='registro'>" . $row["TIPO_DISCO0"] . "</td>";
        echo "<td class='registro'>" . $row["TIPO_DISCO1"] . "</td>";
        echo "<td><a class='button edit' href='modificar_equipo.php?id=" . $row["NOMBRE_EQUIPO"] . "'><img src='img/editar.png'/></a></td>";
        echo "<td><a class='button delete' onclick='eliminarRegistro(\"" . $row["NOMBRE_EQUIPO"] . "\")'><img src='img/eliminar.png'/></a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron registros.";
}

// Agregar botón para agregar un nuevo registro
echo "<div class='add-button'><a class='button' href='agregar_equipo.php'>Agregar un nuevo registro</a></div>";

// Cerrar la conexión
$conn->close();
?>
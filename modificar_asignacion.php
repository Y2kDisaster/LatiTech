<?php
// Verificar si se ha proporcionado un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de asignación no válido";
    exit();
}

// Obtener el ID de la asignación de la URL
$id = $_GET['id'];

// Conexión a la base de datos
include('conexion.php');

// Consultar la asignación actual
$sql = "SELECT * FROM asignaciones WHERE ID_ASIGNACION = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id_equipo_actual = $row["ID_EQUIPO"];
    $id_empleado_actual = $row["ID_EMPLEADO"];
} else {
    echo "No se encontró la asignación";
    exit();
}

// Obtener la lista de equipos disponibles para asignación
$sql_equipos = "SELECT NOMBRE_EQUIPO FROM equipos WHERE NOMBRE_EQUIPO NOT IN (SELECT ID_EQUIPO FROM asignaciones)";
$result_equipos = $conn->query($sql_equipos);

$sql_empleados = "SELECT ID_EMPLEADO, NOMBRES, APELLIDOS FROM empleados";
$result_empleados = $conn->query($sql_empleados);
// Verificar si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_equipo = $_POST['id_equipo'];
    $id_empleado = $_POST['id_empleado'];

    // Actualizar la asignación en la tabla
    $sql_update = "UPDATE asignaciones SET 
                   ID_EQUIPO = '$id_equipo', 
                   ID_EMPLEADO = '$id_empleado' 
                   WHERE ID_ASIGNACION = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "La asignación se actualizó correctamente.";
        header("Location: asignaciones.php");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/modificar_asignacion.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 400px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }
        input[type="submit"] {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
    <title>Editar Asignación</title>
</head>
<body>
    <h1>Editar Asignación</h1>
    <form method="POST" action="">
        <div>
            <label for="id_equipo">Equipo:</label>
            <select id="id_equipo" name="id_equipo" required>
                <?php
                while ($row_equipo = $result_equipos->fetch_assoc()) {
                    // Verificar si este equipo es el asignado actualmente
                    $selected = ($id_equipo_actual == $row_equipo['ID_EQUIPO']) ? "selected" : "";
                    echo "<option value='" . $row_equipo['NOMBRE_EQUIPO'] . "' $selected>" . $row_equipo['NOMBRE_EQUIPO'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="id_empleado">Empleado:</label>
            <select id="id_empleado" name="id_empleado" required>
                <?php
                while ($row_empleado = $result_empleados->fetch_assoc()) {
                    // Verificar si este empleado es el asignado actualmente
                    $selected = ($id_empleado_actual == $row_empleado['ID_EMPLEADO']) ? "selected" : "";
                    echo "<option value='" . $row_empleado['ID_EMPLEADO'] . "' $selected>" . $row_empleado['NOMBRES'] . " " . $row_empleado['APELLIDOS'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</body>
</html>

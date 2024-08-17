<?php
// Verificar si se ha proporcionado un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de equipo no válido";
    exit();
}

// Obtener el ID del equipo de la URL
$id = $_GET['id'];

// Verificar si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y procesar los datos enviados en el formulario
    $id_asignacion = $_POST['id_asignacion'];
    $id_equipo = $_POST['id_equipo'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $id_empleado = $_POST['id_empleado'];
    
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

    // Actualizar el registro en la tabla "asignaciones"
    $sql = "UPDATE asignaciones SET 
            ID_ASIGNACION = '$id_asignacion',
            NOMBRE_EQUIPO = '$id_equipo',
            NOMBRE = '$nombre',
            APELLIDOS = '$apellidos',
            ID_EMPLEADO = '$id_empleado'
            WHERE ID_ASIGNACION = '$id'";

    if ($conn->query($sql) === true) {
        echo "El equipo se ha actualizado correctamente";
    } else {
        echo "Error al actualizar el equipo: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
    // Redirigir a la página "asignaciones.php"
    header("Location: asignaciones.php");
    exit();
} else {
    // Obtener los datos del equipo de la base de datos
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

    // Consultar el equipo por ID
    $sql = "SELECT * FROM asignaciones WHERE ID_ASIGNACION = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Obtener los datos del equipo
        $row = $result->fetch_assoc();
        $id_asignacion = $row["ID_ASIGNACION"];
        $id_equipo = $row["ID_EQUIPO"];
        $id_empleado = $row["ID_EMPLEADO"];
    } else {
        echo "No se encontró el equipo";
        exit();
    }
    
    // Cerrar la conexión
    $conn->close();
}

// Obtener la lista de equipos disponibles para asignación
$conn = new mysqli($servername, $username, $password, $dbname);
$sql_equipos = "SELECT NOMBRE_EQUIPO, NOMBRE_EQUIPO FROM equipos WHERE NOMBRE_EQUIPO NOT IN (SELECT NOMBRE_EQUIPO FROM asignaciones)";
$result_equipos = $conn->query($sql_equipos);

// Obtener la lista de empleados para selección
$sql_empleados = "SELECT ID_EMPLEADO, NOMBRE, APELLIDOS FROM empleados";
$result_empleados = $conn->query($sql_empleados);

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
            width: 300px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }
        input[type="submit"] {
            margin-top: 10px;
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
    <script>
        function mostrarDatosEmpleado() {
            var empleadoSelect = document.getElementById("id_empleado");
            var nombreInput = document.getElementById("nombre");
            var apellidosInput = document.getElementById("apellidos");

            // Obtener el valor seleccionado del ID de empleado
            var idEmpleado = empleadoSelect.value;

            // Realizar una solicitud AJAX para obtener los datos del empleado
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Analizar la respuesta JSON y asignar los valores a los campos correspondientes
                    var empleado = JSON.parse(this.responseText);
                    nombreInput.value = empleado.nombre;
                    apellidosInput.value = empleado.apellidos;
                }
            };
            xhttp.open("GET", "obtener_empleado.php?id=" + idEmpleado, true);
            xhttp.send();
        }
    </script>
    <title>Editar Asignación</title>
</head>
<body>
    <h1>Editar Asignación</h1>

    <form method="POST" action="">

        <div>
            <label for="id_equipo">ID del Equipo:</label>
            <select id="id_equipo" name="id_equipo" required>
                <?php
                while ($row_equipo = $result_equipos->fetch_assoc()) {
                    echo "<option value='" . $row_equipo['NOMBRE_EQUIPO'] . "'>" . $row_equipo['NOMBRE_EQUIPO'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <label for="id_empleado">ID del Empleado:</label>
            <select id="id_empleado" name="id_empleado" onchange="mostrarDatosEmpleado()" required>
                <?php
                while ($row_empleado = $result_empleados->fetch_assoc()) {
                    echo "<option value='" . $row_empleado['ID_EMPLEADO'] . "'>" . $row_empleado['NOMBRE'] . " " . $row_empleado['APELLIDOS'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required>
        </div>
        <div>
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" required>
        </div>

        <div>
            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</body>
</html>

<?php
// Verificar si se ha proporcionado un ID válido en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID no válido";
    exit();
}

// Obtener el ID del registro de la URL
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y procesar los datos enviados en el formulario
    $id_acceso = $_POST['ID_ACCESO']; // Usar una variable clara y distinta
    $NOMBRE = $_POST['NOMBRE'];
    $USUARIO = $_POST['USUARIO'];
    $PASSWORD = $_POST['PASSWORD'];

    include('conexion.php');

    // Verificar la existencia de la columna y tabla correctas
    $sql = "UPDATE accesos SET 
            NOMBRE = '$NOMBRE',
            USUARIO = '$USUARIO',
            PASSWORD = '$PASSWORD'
            WHERE ID_ACCESO = '$id_acceso'";

    if ($conn->query($sql) === true) {
        echo "El registro se ha actualizado correctamente";
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }

    $conn->close();
    header("Location: usuarios.php");
    exit();
} else {
    include('conexion.php');

    // Obtener los datos del registro
    $sql = "SELECT * FROM accesos WHERE ID_ACCESO = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $NOMBRE = $row["NOMBRE"];
        $USUARIO = $row["USUARIO"];
        $PASSWORD = $row["PASSWORD"];
    } else {
        echo "No se encontró el registro";
        exit();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/modificar_equipo.css">
    <title>Editar Equipo</title>
</head>
<body>
    <h1>Editar Equipo</h1>

    <form method="POST" action="">
        <input type="hidden" name="ID_ACCESO" value="<?php echo $id; ?>">
        <div>
            <label for="NOMBRE">NOMBRE:</label>
            <input type="text" id="NOMBRE" name="NOMBRE" value="<?php echo $NOMBRE; ?>" required>
        </div>
        <div>
            <label for="USUARIO">USUARIO:</label>
            <input type="text" id="USUARIO" name="USUARIO" value="<?php echo $USUARIO; ?>" required>
        </div>
        <div>
            <label for="PASSWORD">Contraseña:</label>
            <input type="text" id="PASSWORD" name="PASSWORD" value="<?php echo $PASSWORD; ?>" required>
        </div>
        <div>
            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</body>
</html>
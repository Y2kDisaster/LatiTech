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
    $nombreEquipo = $_POST['nombre_equipo'];
    $estado = $_POST['estado'];
    $marcaEquipo = $_POST['marca_equipo'];
    $modeloEquipo = $_POST['modelo_equipo'];
    $sistemaOperativo = $_POST['sistema_operativo'];
    $marcaProcesador = $_POST['marca_procesador'];
    $modeloProcesador = $_POST['modelo_procesador'];
    $velocidadProcesador = $_POST['velocidad_procesador'];
    $ram = $_POST['ram'];
    $numeroDiscos = $_POST['numero_discos'];
    $tipoDisco0 = $_POST['tipo_disco0'];
    $tipoDisco1 = $_POST['tipo_disco1'];
    $idProducto = $_POST['id_producto'];
    $numeroSerie = $_POST['numero_serie'];
    
    include('conexion.php');

    // Actualizar el registro en la tabla "equipos"
    $sql = "UPDATE equipos SET 
            NOMBRE_EQUIPO = '$nombreEquipo',
            ESTADO = '$estado',
            MARCA_EQUIPO = '$marcaEquipo',
            MODELO_EQUIPO = '$modeloEquipo',
            SISTEMA_OPERATIVO = '$sistemaOperativo',
            MARCA_PROCESADOR = '$marcaProcesador',
            MODELO_PROCESADOR = '$modeloProcesador',
            VELOCIDAD_PROCESADOR = '$velocidadProcesador',
            RAM = '$ram',
            NUMERO_DISCOS = '$numeroDiscos',
            TIPO_DISCO0 = '$tipoDisco0',
            TIPO_DISCO1 = '$tipoDisco1',
            ID_PRODUCTO = '$idProducto',
            NUMERO_SERIE = '$numeroSerie'
            WHERE NOMBRE_EQUIPO = '$id'";

    if ($conn->query($sql) === true) {
        echo "El equipo se ha actualizado correctamente";
    } else {
        echo "Error al actualizar el equipo: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
    // Redirigir a la página "equipos.php"
    header("Location: equipos.php");
    exit();
} else {
    // Obtener los datos del equipo de la base de datos
    // Configuración de la conexión a la base de datos
    include('conexion.php');

    // Consultar el equipo por ID
    $sql = "SELECT * FROM equipos WHERE NOMBRE_EQUIPO = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Obtener los datos del equipo
        $row = $result->fetch_assoc();
        $nombreEquipo = $row["NOMBRE_EQUIPO"];
        $estado = $row["ESTADO"];
        $marcaEquipo = $row["MARCA_EQUIPO"];
        $modeloEquipo = $row["MODELO_EQUIPO"];
        $sistemaOperativo = $row["SISTEMA_OPERATIVO"];
        $marcaProcesador = $row["MARCA_PROCESADOR"];
        $modeloProcesador = $row["MODELO_PROCESADOR"];
        $velocidadProcesador = $row["VELOCIDAD_PROCESADOR"];
        $ram = $row["RAM"];
        $numeroDiscos = $row["NUMERO_DISCOS"];
        $tipoDisco0 = $row["TIPO_DISCO0"];
        $tipoDisco1 = $row["TIPO_DISCO1"];
        $numeroSerie = $row["NUMERO_SERIE"];
    } else {
        echo "No se encontró el equipo";
        exit();
    }

    // Cerrar la conexión
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
        <div>
            <label for="nombre_equipo">Nombre del Equipo:</label>
            <input type="text" id="nombre_equipo" name="nombre_equipo" value="<?php echo $nombreEquipo; ?>" required>
        </div>
        <div>
            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo $estado; ?>" required>
        </div>
        <div>
            <label for="marca_equipo">Marca del Equipo:</label>
            <input type="text" id="marca_equipo" name="marca_equipo" value="<?php echo $marcaEquipo; ?>" required>
        </div>
        <div>
            <label for="modelo_equipo">Modelo del Equipo:</label>
            <input type="text" id="modelo_equipo" name="modelo_equipo" value="<?php echo $modeloEquipo; ?>" required>
        </div>
        <div>
            <label for="sistema_operativo">Sistema Operativo:</label>
            <input type="text" id="sistema_operativo" name="sistema_operativo" value="<?php echo $sistemaOperativo; ?>" required>
        </div>
        <div>
            <label for="marca_procesador">Marca del Procesador:</label>
            <input type="text" id="marca_procesador" name="marca_procesador" value="<?php echo $marcaProcesador; ?>" required>
        </div>
        <div>
            <label for="modelo_procesador">Modelo del Procesador:</label>
            <input type="text" id="modelo_procesador" name="modelo_procesador" value="<?php echo $modeloProcesador; ?>" required>
        </div>
        <div>
            <label for="velocidad_procesador">Velocidad del Procesador:</label>
            <input type="text" id="velocidad_procesador" name="velocidad_procesador" value="<?php echo $velocidadProcesador; ?>" required>
        </div>
        <div>
            <label for="ram">RAM:</label>
            <input type="text" id="ram" name="ram" value="<?php echo $ram; ?>" required>
        </div>
        <div>
            <label for="numero_discos">Número de Discos:</label>
            <input type="text" id="numero_discos" name="numero_discos" value="<?php echo $numeroDiscos; ?>" required>
        </div>
        <div>
            <label for="tipo_disco0">Tipo de Disco 0:</label>
            <input type="text" id="tipo_disco0" name="tipo_disco0" value="<?php echo $tipoDisco0; ?>" required>
        </div>
        <div>
            <label for="tipo_disco1">Tipo de Disco 1:</label>
            <input type="text" id="tipo_disco1" name="tipo_disco1" value="<?php echo $tipoDisco1; ?>" required>
        </div>
        <div>
            <label for="numero_serie">Número de Serie:</label>
            <input type="text" id="numero_serie" name="numero_serie" value="<?php echo $numeroSerie; ?>" required>
        </div>

        <div>
            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</body>
</html>

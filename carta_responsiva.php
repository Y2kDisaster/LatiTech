<?php
// Iniciar el buffer de salida
ob_start();

// Conexión a la base de datos
include('conexion.php'); // Asegúrate de que este archivo tenga la conexión a tu base de datos

// Obtener el ID de asignación desde la URL
$id_asignacion = $_GET['id'];

// Consultar los datos del colaborador
$sql_colaborador = "SELECT empleados.NOMBRES, empleados.APELLIDOS
                    FROM asignaciones
                    JOIN empleados ON asignaciones.ID_EMPLEADO = empleados.ID_EMPLEADO
                    WHERE asignaciones.ID_ASIGNACION = '$id_asignacion'";
$result_colaborador = $conn->query($sql_colaborador);

if ($result_colaborador) {
    if ($result_colaborador->num_rows > 0) {
        // Obtener el nombre del colaborador
        $row_colaborador = $result_colaborador->fetch_assoc();
        $colaborador = $row_colaborador['NOMBRES'] . ' ' . $row_colaborador['APELLIDOS'];
    } else {
        echo "No se encontraron datos del colaborador";
        exit();
    }
} else {
    // Manejo de error en la consulta
    echo "Error en la consulta del colaborador: " . $conn->error;
    exit();
}

// Consultar los datos del equipo usando el ID de equipo desde la asignación
$sql_equipo = "SELECT NOMBRE_EQUIPO, MARCA_EQUIPO, MODELO_EQUIPO, NUMERO_SERIE
               FROM equipos
               JOIN asignaciones ON asignaciones.ID_EQUIPO = equipos.NOMBRE_EQUIPO
               WHERE asignaciones.ID_ASIGNACION = '$id_asignacion'";

$result_equipo = $conn->query($sql_equipo);

if ($result_equipo) {
    if ($result_equipo->num_rows > 0) {
        // Obtener los datos del equipo
        $row_equipo = $result_equipo->fetch_assoc();
        $nombre_equipo = $row_equipo['NOMBRE_EQUIPO'];
        $marca_equipo = $row_equipo['MARCA_EQUIPO'];
        $modelo_equipo = $row_equipo['MODELO_EQUIPO'];
        $numero_serie = $row_equipo['NUMERO_SERIE'];
    } else {
        echo "No se encontraron datos del equipo";
        exit();
    }
} else {
    // Manejo de error en la consulta
    echo "Error en la consulta del equipo: " . $conn->error;
    exit();
}

// HTML para el PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cláusula de Responsabilidad</title>
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <style>
/* Estilos generales */
* {
    margin: 0px;
    padding: 0px;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    background-color: #f4f4f4;
    padding: 20px;
}

.container {
    background: white;
    padding: 20px;
    padding-top: 0PX;
    margin: 0 auto;
    max-width: 800px;
}

h1, h2 {
    color: #333;
    margin-bottom: 10px;
}

p, ul {
    margin-bottom: 10px;
}

ul {
    list-style-type: none;
}

ul li {
    margin-bottom: 5px;
}

strong {
    color: #333;
}

/* Estilo de firma */
.firma {
    margin-top: 70px;
    text-align: center;
}

button:hover {
    opacity: 0.9;
}

button:first-of-type {
    background-color: #555; /* Gris oscuro para el botón de limpiar */
}

button:last-of-type {
    background-color: #007bff; /* Azul para el botón de finalizar */
}

.footer {
    margin-top: 60px;
    text-align: center;
    font-size: 0.9em;
    color: #555;
}

.footer p {
    margin-bottom: 1px;
}
</style>
</head>
<body>
    <div class="container">
        <h1>Cláusula de Responsabilidad y Compensación</h1>
        <p>El Colaborador asignado en este caso <strong>' . $colaborador . '</strong> acepta y reconoce que asume la completa 
        responsabilidad de salvaguardar y proteger el siguiente equipo de cómputo recibido de Latitude Constructora S.A. de C.V.</p>
        
        <h2>Equipo ' . $nombre_equipo . '</h2>
        <ul>
            <li><strong>Marca:</strong> ' . $marca_equipo . '</li>
            <li><strong>Modelo:</strong> ' . $modelo_equipo . ' </li>
            <li><strong>Número de serie:</strong> ' . $numero_serie . ' </li>
        </ul>

        <p>En caso de pérdida, robo, extravío, daño o avería del equipo de cómputo por causa imputable al Colaborador asignado, este se compromete 
        a pagar el valor total del equipo de cómputo calculado al momento del siniestro.</p>
        <p>El valor del equipo de cómputo se determinará tomando en consideración su estado y características al momento de la ocurrencia del siniestro, 
        así como su valor de mercado o su costo de reposición.</p>
        <p>El Colaborador asignado se compromete a utilizar el equipo de cómputo exclusivamente para los fines relacionados con su trabajo. 
        Además, deberá mantener el equipo en condiciones adecuadas de funcionamiento y conservación siguiendo las instrucciones de uso y cuidado proporcionadas por el fabricante.</p>
        <p>En caso de que el equipo de cómputo sufra daños o averías que no sean imputables al Colaborador asignado, este deberá notificar de inmediato a 
        Latitude Constructora S.A. de C.V. por medio del Lic. Ricardo Acosta y/o el Depto. de Sistemas para colaborar en las gestiones necesarias para su reparación o reemplazo.</p>
        <p>El Colaborador asignado comprende y acepta que cualquier incumplimiento de las disposiciones establecidas en esta cláusula podría 
        resultar en su obligación de indemnizar a Latitude Constructora S.A. de C.V. por el valor del equipo de cómputo y los perjuicios ocasionados.</p>
        
        <div class="firma">
            <p>_____________________________________</p>
            <p>Fecha y Firma</p>
        </div>

        <div class="footer">
            <p><strong>Fecha de expedición:</strong> '. date("d/m/Y").'</p>
            <p>Washington No. 2760, Col Deportivo Obispado, Monterrey N.L</p>
            <p>+52(81) 2126.4343 | info@latitude.mx | www.latitude.mx</p>
        </div>
    </div>
</body>
</html>';

// Limpiar el buffer de salida y no mostrar nada antes de generar el PDF
ob_end_clean();
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Carta_Responsabilidad_'.$nombre_equipo.'_'.$colaborador.'.pdf',array("Attachment"=>1));
?>

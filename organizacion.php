<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/latitude.ico">
    <link rel="stylesheet" type="text/css" href="css/organigrama.css">
    <title>Organigrama</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="header">
        <div>
        <a href="https://www.latitude.mx"><img class="logo" src="img/latitude2.png" alt="Logo de la empresa"></a>
        </div>
        <div class="menu-container">
            <a class="button-menu" href="inicio.php">Inicio</a>
            <a class="button-menu" href="asignaciones.php">Asignaciones</a>
            <a class="button-menu" href="empleados.php">Empleados</a>
            <a class="button-menu" href="equipos.php">Equipos</a>
            <a class="button-menu" href="usuarios.php">Usuarios</a>
            <a class="button-menu" href="organizacion.php">Organigrama</a>
            <a class="button-menu" href="reportes.php">Reportes</a>
            <a class="button-menu logout" href="loging.php">Cerrar sesión</a>
        </div>
    </div>
    
    <h1>Organigrama</h1>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {packages:["orgchart"]});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Name');
    data.addColumn('string', 'Manager');
    data.addRows([
        ['Omar Serna Garza <br> <b>CEO</b>', ''],
        ['Sergio Benavides García<br><b>Director de Proyectos y Administración</b>', 'Omar Serna Garza <br> <b>CEO</b>'],
        ['Ji Eun Yoo <br> <b>Director de Ingeniería y Diseño<b>', 'Sergio Benavides García<br><b>Director de Proyectos y Administración</b>'],
        ['Francisco Javier Ramírez<br><b>Gerente de Contrucción</b>', 'Sergio Benavides García<br><b>Director de Proyectos y Administración</b>'],
        ['Blanca Berenice Cordova <br> <b>Gerente de Compras</b>', 'Sergio Benavides García<br><b>Director de Proyectos y Administración</b>'],
        ['Jaime Ricardo Acosta Hinojosa <br> <b>Especialista de Administración</b>', 'Sergio Benavides García<br><b>Director de Proyectos y Administración</b>'],
        ['Hilda Ivett Lopez <br><b>Gerente de Administración</b>', 'Sergio Benavides García<br><b>Director de Proyectos y Administración</b>'],
        ['Pedro Enrique Hernández <br> <b>Gerente de Costos y Proyectos</b>', 'Sergio Benavides García<br><b>Director de Proyectos y Administración</b>'], 
        ['Yareli Samara Limón', 'Blanca Berenice Cordova <br> <b>Gerente de Compras</b>'],
        ['Rebeca Serna', 'Ji Eun Yoo <br> <b>Director de Ingeniería y Diseño<b>'],
        ['César Rodrigo García<br><b>Ingeniero en Sistemas</b>', 'Jaime Ricardo Acosta Hinojosa <br> <b>Especialista de Administración</b>'],
        ['Juan Roberto Campos', 'Francisco Javier Ramírez<br><b>Gerente de Contrucción</b>'],
        ['Rodolfo Torres', 'Francisco Javier Ramírez<br><b>Gerente de Contrucción</b>'],
        ['Fidel Machuca', 'Francisco Javier Ramírez<br><b>Gerente de Contrucción</b>'], 
        ['María Quecholac', 'Rodolfo Torres'],   
        ['Rubén Arellano', 'Rodolfo Torres'],  
    ]);
    var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
    chart.draw(data, {allowHtml:true});
  }
</script>
<div id="chart_div"></div>

</body>
</html>

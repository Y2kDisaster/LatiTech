<?php
$servername = "bgj4eddbzovcemeemg6m-mysql.services.clever-cloud.com";
$username = "unqahplkbugt8lid";
$password = "w9SvA7Y7n0eDQURlPOhO";
$dbname = "bgj4eddbzovcemeemg6m";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
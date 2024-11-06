<?php
$servername = "b62wyfxgdtyouektp7fm-mysql.services.clever-cloud.com";
$username = "ukcjjdbnwpncyy5x";
$password = "MY420ekW4n2F2R8Es2KN";
$dbname = "b62wyfxgdtyouektp7fm";
$port = "3306";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
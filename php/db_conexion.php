<?php
$servername = "db"; // Nombre del servicio de base de datos en docker-compose
$username = "root";
$password = "rootpassword"; // Contraseña definida en docker-compose
$dbname = "mi_proyecto";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
$conn->set_charset("utf8mb4");
?>

<?php
// includes/database.php

// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'smoke_bd';
$username = 'root';
$password = '';

// Crear la conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
<?php
// includes/database.php

// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'smoke_bd';
$username = 'root';
$password = '';

// Crear la conexión
$conexion = new mysqli($host, $username, $password, $dbname);

// Verificar si hay errores en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>

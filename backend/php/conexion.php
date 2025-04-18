<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$basededatos = "smoke_bd";

$conexion = new mysqli($servidor, $usuario, $password, $basededatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>

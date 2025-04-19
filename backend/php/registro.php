<?php
include 'conexion-bd.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$password = $_POST['password'];
$rol = 'cliente'; // Rol predeterminado para nuevos usuarios

// Encriptar la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Verificar si el email ya existe
$verificar = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
$verificar->bind_param("s", $email);
$verificar->execute();
$verificar->store_result();

if ($verificar->num_rows > 0) {
    echo "<script>
        alert('El email ya está registrado. Por favor, utiliza otro.');
        window.location.href = '/frontend/public/html/register.html';
    </script>";
    $verificar->close();
    $conexion->close();
    exit();
}
$verificar->close();

// Preparar la consulta SQL
$query = "INSERT INTO usuarios (nombre, apellidos, email, password, rol) 
          VALUES (?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($query);

if ($stmt) {
    $stmt->bind_param("sssss", $nombre, $apellidos, $email, $password, $rol);

    if ($stmt->execute()) {
        echo "<script>
            alert('Usuario registrado correctamente');
            window.location.href = '../../frontend/public/html/login.html';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar: " . $stmt->error . "');
            window.location.href = '../../frontend/public/html/register.html';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('Error en la preparación de la consulta: " . $conexion->error . "');
        window.location.href = '/frontend/public/html/register.html';
    </script>";
}

$conexion->close();

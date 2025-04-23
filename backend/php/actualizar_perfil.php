<?php
session_start();
require_once 'conexion-bd.php';
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

$id = $_SESSION['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$password = $_POST['password'];

// Verificar si el email ya existe para otro usuario
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'El email ya está en uso']);
    exit;
}

// Preparar la consulta base
$sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?";
$params = [$nombre, $apellidos, $email];
$types = "sss";

// Si se proporcionó una nueva contraseña, incluirla en la actualización
if (!empty($password)) {
    $sql .= ", password = ?";
    $params[] = password_hash($password, PASSWORD_DEFAULT);
    $types .= "s";
}

$sql .= " WHERE id = ?";
$params[] = $id;
$types .= "i";

$stmt = $conexion->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Actualizar el nombre en la sesión
    $_SESSION['nombre'] = $nombre;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar el perfil']);
}

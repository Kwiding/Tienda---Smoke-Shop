<?php
session_start();
require_once 'conexion-bd.php';
header('Content-Type: application/json');

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);

    if (empty($nombre)) {
        echo json_encode(['success' => false, 'error' => 'El nombre no puede estar vacío']);
        exit();
    }

    $query = "UPDATE categorias SET nombre = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar la categoría']);
    }
}

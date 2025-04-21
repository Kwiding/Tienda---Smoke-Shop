<?php
session_start();
include('conexion-bd.php');
header('Content-Type: application/json');

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Obtener información de la imagen antes de eliminar
    $query = "SELECT imagen FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
    
    // Eliminar el producto
    $query = "DELETE FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Si existe una imagen, eliminarla del servidor
        if (!empty($producto['imagen'])) {
            $ruta_imagen = '../../frontend/public/img/' . $producto['imagen'];
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}
$conexion->close();

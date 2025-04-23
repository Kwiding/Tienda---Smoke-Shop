<?php
session_start();
require_once 'conexion-bd.php';
header('Content-Type: application/json');

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Verificar si hay productos usando esta categoría
    $check_query = "SELECT COUNT(*) as count FROM productos WHERE categoria_id = ?";
    $check_stmt = $conexion->prepare($check_query);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        echo json_encode(['success' => false, 'error' => 'No se puede eliminar la categoría porque tiene productos asociados']);
        exit();
    }
    
    // Eliminar la categoría
    $delete_query = "DELETE FROM categorias WHERE id = ?";
    $delete_stmt = $conexion->prepare($delete_query);
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar la categoría']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}

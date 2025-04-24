<?php
session_start();
include('conexion-bd.php');

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /Tienda---Smoke-Shop/frontend/public/html/productos.php');
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
        header('Location: /Tienda---Smoke-Shop/frontend/public/html/gestinar-producto.php');
        exit();
    } else {
        header('Location: /Tienda---Smoke-Shop/frontend/public/html/gestinar-producto.php?error=1');
        exit();
    }
    $stmt->close();
} else {
    header('Location: /Tienda---Smoke-Shop/frontend/public/html/gestinar-producto.php?error=2');
    exit();
}
$conexion->close();

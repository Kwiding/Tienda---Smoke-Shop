<?php
require_once __DIR__ . '/conexion.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Eliminar el pedido
    $sql = "DELETE FROM pedidos WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../../frontend/public/html/gestion-pedidos.php?deleted=1");
    } else {
        header("Location: ../../frontend/public/html/gestion-pedidos.php?error=1");
    }
    
    $stmt->close();
} else {
    header("Location: ../../frontend/public/html/gestion-pedidos.php?error=2");
}

$conexion->close();
?>

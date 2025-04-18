<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $contacto = $_POST['contacto'];
    $estado = $_POST['estado'];
    
    $sql = "UPDATE pedidos SET 
            nombre_cliente = ?, 
            apellido_cliente = ?,
            departamento = ?,
            ciudad = ?,
            direccion = ?,
            contacto = ?,
            estado = ?
            WHERE id = ?";
            
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssi", $nombre, $apellido, $departamento, $ciudad, $direccion, $contacto, $estado, $id);
    
    if ($stmt->execute()) {
        header("Location: ../../frontend/public/html/gestion-pedidos.php?updated=1");
        exit();
    } else {
        header("Location: ../../frontend/public/html/editar_pedido.php?id=$id&error=1");
        exit();
    }
    
    $stmt->close();
}

$conexion->close();
?>

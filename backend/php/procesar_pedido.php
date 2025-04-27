<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

try {
    $conexion->begin_transaction();

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $contacto = $_POST['contacto'];
    $metodo_pago = $_POST['metodo_pago'];
    $usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    
    // Insertar en la tabla pedidos
    $sql = "INSERT INTO pedidos (usuario_id, nombre_cliente, apellido_cliente, departamento, ciudad, direccion, contacto, metodo_pago) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isssssss", $usuario_id, $nombre, $apellido, $departamento, $ciudad, $direccion, $contacto, $metodo_pago);
    $stmt->execute();
    
    $pedido_id = $conexion->insert_id;

    // Procesar el carrito
    $carrito = json_decode($_POST['carrito'], true);
    
    foreach($carrito as $item) {
        $sql = "INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $pedido_id, $item['id'], $item['cantidad']);
        $stmt->execute();

        // Actualizar stock
        $sql = "UPDATE productos SET stock = stock - ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $item['cantidad'], $item['id']);
        $stmt->execute();
    }

    $conexion->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conexion->close();

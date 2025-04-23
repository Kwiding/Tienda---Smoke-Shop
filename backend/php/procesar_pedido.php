<?php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $contacto = $_POST['contacto'];
    $metodo_pago = $_POST['metodo_pago'];
    
    // Obtener el ID del usuario si está logueado
    $usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    
    $sql = "INSERT INTO pedidos (usuario_id, nombre_cliente, apellido_cliente, departamento, ciudad, direccion, contacto, metodo_pago, estado, fecha, hora) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pendiente', CURDATE(), CURTIME())";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("isssssss", $usuario_id, $nombre, $apellido, $departamento, $ciudad, $direccion, $contacto, $metodo_pago);
    
    if ($stmt->execute()) {
        // Vaciar el carrito después de procesar el pedido exitosamente
        unset($_SESSION['carrito']);
        header("Location: ../../frontend/public/html/gestion-pedidos.php?success=1");
        exit();
    } else {
        header("Location: ../../frontend/public/html/form-pedidos.html?error=1");
        exit();
    }
    
    $stmt->close();
    $conexion->close();
}
?>

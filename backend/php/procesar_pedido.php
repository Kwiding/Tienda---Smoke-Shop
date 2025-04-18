<?php
require_once __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $contacto = $_POST['contacto'];
    $metodo_pago = $_POST['metodo_pago'];
    
    $sql = "INSERT INTO pedidos (nombre_cliente, apellido_cliente, departamento, ciudad, direccion, contacto, metodo_pago, estado, fecha, hora) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente', CURDATE(), CURTIME())";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $apellido, $departamento, $ciudad, $direccion, $contacto, $metodo_pago);
    
    if ($stmt->execute()) {
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

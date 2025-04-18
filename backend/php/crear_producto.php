<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $categoria_id = $_POST['categoria_id'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    // Manejo de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = date('YmdHis') . '_' . $imagen['name'];
    $imagen_temporal = $imagen['tmp_name'];
    $ruta_destino = '../../uploads/' . $imagen_nombre;
    
    if (move_uploaded_file($imagen_temporal, $ruta_destino)) {
        $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, fecha, imagen) 
                VALUES (?, ?, ?, ?, ?, CURRENT_DATE, ?)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("issdis", $categoria_id, $nombre, $descripcion, $precio, $stock, $imagen_nombre);
        
        if ($stmt->execute()) {
            header("Location: ../../frontend/public/html/productos.php");
        } else {
            echo "Error al crear el producto: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al subir la imagen";
    }
}
?>

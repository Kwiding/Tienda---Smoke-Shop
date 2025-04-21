<?php
session_start();
include('conexion-bd.php');

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: productos.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $categoria_id = $_POST['categoria_id'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    // Si se sube una nueva imagen
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = $_FILES['imagen'];
        $imagen_nombre = date('YmdHis') . '_' . $imagen['name'];
        $ruta_destino = '../../frontend/public/img/' . $imagen_nombre;
        
        if(move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
            // Eliminar imagen anterior si existe
            $query = "SELECT imagen FROM productos WHERE id = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $producto = $resultado->fetch_assoc();
            
            if (!empty($producto['imagen'])) {
                $ruta_imagen_anterior = '../../frontend/public/img/' . $producto['imagen'];
                if (file_exists($ruta_imagen_anterior)) {
                    unlink($ruta_imagen_anterior);
                }
            }
            
            // Actualizar con nueva imagen
            $sql = "UPDATE productos SET nombre=?, categoria_id=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sisddsi", $nombre, $categoria_id, $descripcion, $precio, $stock, $imagen_nombre, $id);
        }
    } else {
        // Actualizar sin cambiar la imagen
        $sql = "UPDATE productos SET nombre=?, categoria_id=?, descripcion=?, precio=?, stock=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sisddi", $nombre, $categoria_id, $descripcion, $precio, $stock, $id);
    }
    
    if ($stmt->execute()) {
        header('Location: ../../frontend/public/html/gestinar-producto.php');
        exit();
    } else {
        echo "Error al actualizar el producto: " . $stmt->error;
    }
}

$conexion->close();
?>

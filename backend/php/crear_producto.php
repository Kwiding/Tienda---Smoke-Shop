<?php
session_start();
include('conexion-bd.php');

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../frontend/public/html/productos.php');
    exit();
}

// Función para redimensionar imagen
function redimensionar_imagen($ruta_temporal, $ruta_destino) {
    // Verificar si GD está instalado
    if (!extension_loaded('gd')) {
        // Si GD no está disponible, solo copiar la imagen
        return move_uploaded_file($ruta_temporal, $ruta_destino);
    }

    list($ancho_original, $alto_original, $tipo) = getimagesize($ruta_temporal);
    
    $ancho_nuevo = 400;
    $alto_nuevo = 400;
    
    $imagen_nueva = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
    
    switch($tipo) {
        case IMAGETYPE_JPEG:
            $imagen_original = imagecreatefromjpeg($ruta_temporal);
            break;
        case IMAGETYPE_PNG:
            $imagen_original = imagecreatefrompng($ruta_temporal);
            // Mantener transparencia
            imagealphablending($imagen_nueva, false);
            imagesavealpha($imagen_nueva, true);
            break;
        case IMAGETYPE_GIF:
            $imagen_original = imagecreatefromgif($ruta_temporal);
            break;
        default:
            return false;
    }
    
    imagecopyresampled(
        $imagen_nueva, $imagen_original,
        0, 0, 0, 0,
        $ancho_nuevo, $alto_nuevo,
        $ancho_original, $alto_original
    );
    
    switch($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($imagen_nueva, $ruta_destino, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($imagen_nueva, $ruta_destino, 9);
            break;
        case IMAGETYPE_GIF:
            imagegif($imagen_nueva, $ruta_destino);
            break;
    }
    
    imagedestroy($imagen_original);
    imagedestroy($imagen_nueva);
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $categoria_id = $_POST['categoria_id'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    // Manejo de la imagen
    $imagen_nombre = '';
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = $_FILES['imagen'];
        $imagen_nombre = date('YmdHis') . '_' . $imagen['name'];
        $imagen_temporal = $imagen['tmp_name'];
        $ruta_destino = '../../frontend/public/img/' . $imagen_nombre;
        
        // Intentar procesar la imagen
        $resultado = redimensionar_imagen($imagen_temporal, $ruta_destino);
        if($resultado) {
            // Continuar con la inserción en la base de datos
            $sql = "INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, imagen, fecha) 
                    VALUES (?, ?, ?, ?, ?, ?, CURDATE())";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("issdis", $categoria_id, $nombre, $descripcion, $precio, $stock, $imagen_nombre);
            
            if ($stmt->execute()) {
                echo "<script>
                    alert('Producto creado exitosamente');
                    window.location.href = '../../frontend/public/html/gestinar-producto.php';
                </script>";
                exit();
            } else {
                echo "<script>
                    alert('Error al crear el producto: " . $stmt->error . "');
                    window.history.back();
                </script>";
            }
            $stmt->close();
        } else {
            echo "<script>
                alert('Error al procesar la imagen');
                window.history.back();
            </script>";
        }
    }
}
?>

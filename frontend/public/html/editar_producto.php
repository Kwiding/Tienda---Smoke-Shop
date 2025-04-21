<?php
session_start();
require_once '../../../backend/php/conexion-bd.php';

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: productos.php');
    exit();
}

// Obtener producto
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();

    // Obtener categorías para el select
    $query_categorias = "SELECT * FROM categorias";
    $resultado_categorias = mysqli_query($conexion, $query_categorias);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../css/style2.css">
</head>
<body>
    <div class="container">
        <form class="login-form" method="POST" action="../../../backend/php/actualizar_producto.php" enctype="multipart/form-data">
            <h2>Editar Producto</h2>
            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
            
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>

            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria_id" required>
                <?php while($categoria = mysqli_fetch_assoc($resultado_categorias)): ?>
                    <option value="<?php echo $categoria['id']; ?>" 
                        <?php if($categoria['id'] == $producto['categoria_id']) echo 'selected'; ?>>
                        <?php echo $categoria['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $producto['precio']; ?>" required>

            <label for="imagen">Nueva Imagen (opcional)</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
            <?php if(!empty($producto['imagen'])): ?>
                <p>Imagen actual: <?php echo $producto['imagen']; ?></p>
            <?php endif; ?>

            <div class="button-group">
                <button type="submit">Actualizar producto</button>
                <button type="button" onclick="window.history.back()">Cancelar</button>
            </div>
        </form>
    </div>
</body>
</html>

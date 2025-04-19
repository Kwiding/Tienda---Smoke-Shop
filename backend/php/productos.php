<?php
session_start();
// Conexión a la base de datos
include('conexion-bd.php');

// Verificar si es admin
$es_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';

// Obtener categoría seleccionada
$categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;

// Modificar la consulta para filtrar por categoría si se seleccionó una
$query = "SELECT * FROM productos";
if ($categoria_id) {
    $query .= " WHERE categoria_id = " . intval($categoria_id);
}
$resultado = mysqli_query($conexion, $query);

// Obtener las categorías para mostrarlas en el menú de navegación
$categorias_query = "SELECT * FROM categorias";
$categorias_result = mysqli_query($conexion, $categorias_query);

// Verificar si la consulta tiene resultados
if (mysqli_num_rows($resultado) == 0) {
    echo "No hay productos disponibles";
} else {
    echo "Productos encontrados: " . mysqli_num_rows($resultado);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../../frontend/public/css/style.productos.css">
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <!-- Mostrar las categorías dinámicamente -->
                <?php while ($categoria = mysqli_fetch_assoc($categorias_result)) : ?>
                    <a href="productos.php?categoria_id=<?php echo $categoria['id']; ?>">
                        <?php echo $categoria['nombre']; ?>
                    </a>
                <?php endwhile; ?>
                <a href="carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a>
            </nav>
        </header>

            <h2 style="text-align: center;">Productos</h2>
            <div class="products-grid">
                <?php while ($producto = mysqli_fetch_assoc($resultado)) : ?>
                    <div class="product-card active">
                        <!-- Verifica si 'imagen' no está vacía antes de mostrarla -->
                        <?php if (!empty($producto['imagen'])): ?>
                            <img src="../../frontend/public/img/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
                        <?php else: ?>
                            <img src="../../frontend/public/img/default.jpg" alt="Imagen no disponible">
                        <?php endif; ?>
                        <h3><?php echo $producto['nombre']; ?></h3>
                        <p class="descripcion"><?php echo $producto['descripcion']; ?></p>
                        <p class="precio">$ <?php echo number_format($producto['precio'], 2, ',', '.'); ?></p>
                        <div class="product-actions">
                            <a href="carrito.php?action=add&id=<?php echo $producto['id']; ?>" class="btn-agregar" style="text-decoration: none; color: white; background-color: #28a745; padding: 10px 20px; border-radius: 5px;">
                                Agregar al carrito
                            </a>
                            <?php if($es_admin): ?>
                                <br><br><br><a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>" 
                                   class="btn-eliminar"
                                   onclick="return confirm('¿Está seguro de eliminar este producto?')" style="text-decoration: none; color: white; background-color: #dc3545; padding: 10px 20px; border-radius: 5px;">
                                    Eliminar Producto
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>

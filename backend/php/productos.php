<?php
// Conexión a la base de datos
include('conexion-bd.php');

// Obtener los productos de la base de datos
$query = "SELECT * FROM productos";
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
                <a href="#"><i class="fas fa-home"></i> Inicio</a>
                <a href="#"><i class="fas fa-user"></i> Iniciar Sesión</a>
                <a href="#"><i class="fas fa-user-plus"></i> Registrarse</a>
                <a href="/backend/php/carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a>
            </nav>
            <div class="search-bar">
                <input type="text" placeholder="Buscar">
            </div>
        </header>

        <main>
            <h2>Productos</h2>
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
                        <p>Precio: $ <?php echo number_format($producto['precio'], 2, ',', '.'); ?></p>
                        <a href="carrito.php?action=add&id=<?php echo $producto['id']; ?>">
                            <button>Agregar al carrito</button>
                        </a>
                        <a href="#">Detalles...</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>

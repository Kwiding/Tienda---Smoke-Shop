<?php
session_start();
// Conexión a la base de datos
require_once __DIR__ . '/../../../backend/php/conexion-bd.php';

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

// Obtener la cantidad de productos en el carrito
$cantidad_productos = 0;
if (isset($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $cantidad_productos += $producto['cantidad'];
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../css/style.productos.css">
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }

        .category-select {
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #ffffff;
            color: #000000;
            border: 1px solid rgba(0, 0, 0, 0.2);
            margin-right: 15px;
            cursor: pointer;
            font-family: inherit;
            min-width: 200px;
            margin: 0 15px;
        }
    </style>
</head>

<body>
    <div class="notification" id="notification"></div>
    <div class="container">
        <?php include __DIR__ . '/../../../backend/php/includes/navbar.php'; ?>

        <h2 style="text-align: center;">Productos</h2>
        <div class="products-grid">
            <?php while ($producto = mysqli_fetch_assoc($resultado)) : ?>
                <div class="product-card active" id="producto-<?php echo $producto['id']; ?>">
                    <!-- Verifica si 'imagen' no está vacía antes de mostrarla -->
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="../img/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">
                    <?php else: ?>
                        <img src="../img/default.jpg" alt="Imagen no disponible">
                    <?php endif; ?>
                    <h3><?php echo $producto['nombre']; ?></h3>
                    <p class="descripcion"><?php echo $producto['descripcion']; ?></p>
                    <p class="precio">$ <?php echo number_format($producto['precio'], 2, ',', '.'); ?></p>

                    <!-- Agregar cantidad -->
                    <input type="number" id="cantidad-<?php echo $producto['id']; ?>" value="1" min="1" max="<?php echo $producto['stock']; ?>" style="width: 60px;">

                    <div class="product-actions">
                        <a href="#" onclick="agregarAlCarrito(<?php echo $producto['id']; ?>); return false;"
                            class="btn-agregar" style="text-decoration: none; color: white; background-color: #28a745; padding: 10px 20px; border-radius: 5px;">
                            Agregar al carrito
                        </a>
                        <?php if ($es_admin): ?>
                            <a href="#"
                                onclick="eliminarProducto(<?php echo $producto['id']; ?>); return false;"
                                class="btn-eliminar" style="text-decoration: none; color: white; background-color: #dc3545; padding: 10px 20px; border-radius: 5px;">
                                Eliminar Producto
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Agregar el contador de productos aquí -->
        <div class="product-counter">
            <?php if (mysqli_num_rows($resultado) == 0): ?>
                <p>No hay productos disponibles</p>
            <?php else: ?>
                <p>Productos encontrados: <span id="contador-productos"><?php echo mysqli_num_rows($resultado); ?></span></p>
            <?php endif; ?>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        function agregarAlCarrito(id) {
            const cantidad = document.getElementById('cantidad-' + id).value;

            // Enviar la solicitud al servidor para agregar al carrito
            fetch(`../../../backend/php/carrito.php?action=add&id=${id}&cantidad=${cantidad}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar la notificación de éxito
                        const notification = document.getElementById('notification');
                        notification.textContent = 'Producto agregado al carrito';
                        notification.style.display = 'block';
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 3000);

                        // Actualizar el contador de productos en el carrito
                        document.querySelector('.cart-info a').textContent = `Carrito: ${data.total} productos`;
                    } else {
                        // Mostrar la notificación de error si el stock se excede
                        const notification = document.getElementById('notification');
                        notification.textContent = data.message; // Mensaje de error devuelto desde el servidor
                        notification.style.backgroundColor = '#f44336'; // Cambiar color para error
                        notification.style.display = 'block';
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 3000);
                    }
                });
        }

        
    </script>
</body>

</html>

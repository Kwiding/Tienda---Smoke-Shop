<?php
session_start();
include('conexion-bd.php');

// Verifica si el carrito está inicializado, si no, lo inicializa
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Añadir un producto al carrito
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $id_producto = $_GET['id'];
    $cantidad = 1; // Asumimos que se agrega una unidad por vez

    // Verificar si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
    } else {
        // Si no está en el carrito, obtener detalles del producto de la base de datos
        $query = "SELECT * FROM productos WHERE id = $id_producto";
        $resultado = mysqli_query($conexion, $query);

        if ($row = mysqli_fetch_assoc($resultado)) {
            $_SESSION['carrito'][$id_producto] = array(
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'precio' => $row['precio'],
                'cantidad' => $cantidad,
                'imagen' => $row['imagen'] // Asegúrate de que la columna 'imagen' exista en la tabla 'productos'
            );
        } else {
            echo "Producto no encontrado en la base de datos.";
        }
    }
}

// Vaciar carrito
if (isset($_GET['action']) && $_GET['action'] == 'empty') {
    unset($_SESSION['carrito']);
}

// Eliminar un producto
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $id_producto = $_GET['id'];
    unset($_SESSION['carrito'][$id_producto]);
}

// Calcular total
$total = 0;
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../../frontend/public/css/style-carrito.css">
</head>
<body>

    <div class="container">
        <header>
            <nav>
                <a href="../../frontend/public/html/productos.php"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </nav>
        </header>

        <main>
            <h2>Carrito de Compras</h2>
            <div class="cart-items">
                <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                    <?php foreach ($_SESSION['carrito'] as $id_producto => $producto): ?>
                        <div class="cart-item">
                            <div class="item-details">
                                <h3><?php echo $producto['nombre']; ?></h3>
                                <p>$ <?php echo number_format($producto['precio'], 2, ',', '.'); ?></p>
                                <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                                <a href="carrito.php?action=remove&id=<?php echo $id_producto; ?>" class="remove-item">Eliminar</a>
                            </div>
                            <img src="../../frontend/public/img/<?php echo $producto['imagen']; ?>" 
                                 alt="<?php echo $producto['nombre']; ?>"
                                 style="width: 300px; height: 300px; object-fit: cover;">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay productos en el carrito.</p>
                <?php endif; ?>
            </div>

            <div class="cart-actions">
                <a href="carrito.php?action=empty" class="empty-cart">Vaciar Carrito</a>
                <div class="total-price">Precio Total: $ <?php echo number_format($total, 2, ',', '.'); ?></div>
                <button class="checkout">Hacer Pedido</button>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>

<?php
session_start();
require_once 'conexion.php';

// Manejo de acciones AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch($_GET['action']) {
        case 'add':
            $id_producto = $_GET['id'];
            $cantidad = 1;

            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }

            if (isset($_SESSION['carrito'][$id_producto])) {
                $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
            } else {
                $query = "SELECT * FROM productos WHERE id = $id_producto";
                $resultado = mysqli_query($conexion, $query);

                if ($row = mysqli_fetch_assoc($resultado)) {
                    $_SESSION['carrito'][$id_producto] = array(
                        'id' => $row['id'],
                        'nombre' => $row['nombre'],
                        'precio' => $row['precio'],
                        'cantidad' => $cantidad,
                        'imagen' => $row['imagen']
                    );
                }
            }
            
            echo json_encode(['success' => true]);
            exit;
            
        case 'empty':
            unset($_SESSION['carrito']);
            echo json_encode(['success' => true]);
            exit;
            
        case 'remove':
            $id_producto = $_GET['id'];
            unset($_SESSION['carrito'][$id_producto]);
            echo json_encode(['success' => true]);
            exit;
            
        case 'get_total':
            $total = 0;
            if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
                foreach ($_SESSION['carrito'] as $producto) {
                    $total += $producto['precio'] * $producto['cantidad'];
                }
            }
            echo json_encode([
                'success' => true, 
                'total' => $total, 
                'carrito' => $_SESSION['carrito'] ?? []
            ]);
            exit;
    }
}

// Si no es una petición AJAX, mostrar la vista del carrito
$total = 0;
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
}

// Vista del carrito
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
                <a href="#" onclick="vaciarCarrito(); return false;" class="empty-cart">Vaciar Carrito</a>
                <div class="total-price">Precio Total: $ <?php echo number_format($total, 2, ',', '.'); ?></div>
                <button class="checkout" onclick="window.location.href='../../frontend/public/html/form-pedidos.html'">Hacer Pedido</button>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
    function vaciarCarrito() {
        if(confirm('¿Está seguro de vaciar el carrito?')) {
            fetch('carrito.php?action=empty')
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    }
                });
        }
    }
    </script>
</body>
</html>

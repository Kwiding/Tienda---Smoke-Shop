<?php
session_start();
require_once __DIR__ . '/conexion-bd.php';

// Manejo de acciones AJAX
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add') {
        $id_producto = $_GET['id'];
        $cantidad = $_GET['cantidad'];

        // Obtener el stock disponible
        $query = "SELECT stock FROM productos WHERE id = $id_producto";
        $resultado = mysqli_query($conexion, $query);
        $producto = mysqli_fetch_assoc($resultado);
        $stock = $producto['stock'];

        // Verificar si la cantidad no excede el stock
        if ($cantidad > $stock) {
            // Si la cantidad excede el stock, retornar un error
            echo json_encode(['success' => false, 'message' => 'Cantidad excede el stock disponible.']);
            exit;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$id_producto])) {
            $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
        } else {
            // Obtener los detalles del producto
            $query = "SELECT * FROM productos WHERE id = $id_producto";
            $resultado = mysqli_query($conexion, $query);
            $producto = mysqli_fetch_assoc($resultado);
            $_SESSION['carrito'][$id_producto] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'imagen' => $producto['imagen']
            ];
        }

        // Actualizar el total de productos en el carrito
        $total = 0;
        foreach ($_SESSION['carrito'] as $producto) {
            $total += $producto['cantidad'];
        }

        echo json_encode(['success' => true, 'total' => $total]);
        exit;
    } elseif ($_GET['action'] == 'remove') {
        $id_producto = $_GET['id'];
        
        if (isset($_SESSION['carrito'][$id_producto])) {
            unset($_SESSION['carrito'][$id_producto]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    } elseif ($_GET['action'] == 'get_total') {
        $total = 0;
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $producto) {
                $total += $producto['precio'] * $producto['cantidad'];
            }
        }
        echo json_encode([
            'success' => true, 
            'total' => $total,
            'carrito' => $_SESSION['carrito']
        ]);
        exit;
    }
}

// Vista del carrito
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
                                <a href="#" onclick="eliminarProducto(<?php echo $id_producto; ?>); return false;" class="remove-item">Eliminar</a>
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
                <div class="total-price">Precio Total: $ <span id="total-precio"><?php echo number_format($total, 2, ',', '.'); ?></span></div>
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

    function eliminarProducto(id) {
        if(confirm('¿Está seguro de eliminar este producto?')) {
            fetch(`carrito.php?action=remove&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload(); // Recarga la página automáticamente
                    }
                });
        }
    }

    function agregarAlCarrito(id) {
        const cantidad = parseInt(document.getElementById('cantidad-' + id).value);
        fetch(`carrito.php?action=add&id=${id}&cantidad=${cantidad}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar notificación de éxito
                    alert('Producto agregado al carrito');
                    // Actualizar contador del carrito en la vista
                    document.getElementById('contador-carrito').textContent = data.total;
                } else {
                    alert(data.message);  // Mostrar mensaje de error si excede el stock
                }
            });
    }
    </script>
</body>
</html>

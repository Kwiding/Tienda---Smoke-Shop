<?php
session_start();
require_once '../../../backend/php/conexion-bd.php';

// Obtener productos con sus categorías
$query = "SELECT p.*, c.nombre as categoria_nombre 
          FROM productos p 
          LEFT JOIN categorias c ON p.categoria_id = c.id";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="../css/gestinar-producto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <header>
            <?php include __DIR__ . '../../../../backend/php/includes/navbar.php'; ?>
        </header>

        <main class="management-page">
            <div class="management-header">
                <i class="fas fa-box-open"></i>
                <h2>Gestionar Productos</h2>
            </div>
            <div class="button-container">
                <button onclick="window.history.back()" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($producto = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo $producto['id']; ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td class="actions">
                                <button class="edit-btn" onclick="location.href='editar_producto.php?id=<?php echo $producto['id']; ?>'">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="delete-btn" onclick="if(confirm('¿Está seguro de eliminar este producto?')) location.href='../../../backend/php/eliminar_producto.php?id=<?php echo $producto['id']; ?>'">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <button class="add-product-btn" onclick="window.location.href='crear-producto.php'">
                <i class="fas fa-plus-circle"></i> Agregar Nuevo Producto
            </button>
        </main>
    </div>
</body>

</html>
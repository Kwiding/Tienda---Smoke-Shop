<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <link rel="stylesheet" href="../css/style-producto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '../../../../backend/php/includes/navbar.php'; ?>
        <main>
            <section class="product">
                <h2>Productos</h2>
                <div class="product-grid">
                    <?php
                    require_once '../../../backend/php/conexion.php';
                    
                    $sql = "SELECT p.*, c.nombre as categoria_nombre 
                            FROM productos p 
                            JOIN categorias c ON p.categoria_id = c.id";
                    $resultado = $conexion->query($sql);

                    while ($producto = $resultado->fetch_assoc()) {
                        echo "<div class='product-card'>";
                        echo "<img src='../../../uploads/{$producto['imagen']}' alt='{$producto['nombre']}'>";
                        echo "<h3>{$producto['nombre']}</h3>";
                        echo "<p class='categoria'>{$producto['categoria_nombre']}</p>";
                        echo "<p class='descripcion'>{$producto['descripcion']}</p>";
                        echo "<p class='precio'>$" . number_format($producto['precio'], 2) . "</p>";
                        echo "<p class='stock'>Stock: {$producto['stock']}</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
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
                <div class="product-details">
                    <div class="product-image">
                        <img src="../img/gotas.jpg" alt="Gotas Cannabicas">
                    </div>
                    <div class="product-info">
                        <h3>DESCRIPCIÓN</h3>
                        <p>Las gotas de cannabis están compuestas por extractos de la planta de cannabis, que contienen cannabinoides como el tetrahidrocannabinol (THC) y el cannabidiol (CBD). Estos cannabinoides son los principales compuestos activos responsables de los efectos medicinales y terapéuticos del cannabis.</p>
                        <p><strong>NOMBRE:</strong> GOTAS CANNABICAS</p>
                        <p><strong>PRECIO:</strong> 20.000</p>
                        <p><strong>STOCK:</strong> 20</p>
                        <div class="product-buttons">
                            <button class="add-to-cart">Agregar al carrito</button>
                            <button class="cancel">Cancelar</button>
                        </div>
                    </div>
                </div>
                <a href="#" class="view-products">Ver otros productos</a>
            </section>
        </main>
    </div>
</body>
</html>
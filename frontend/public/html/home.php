<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <link rel="stylesheet" href="../css/style-home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <?php include __DIR__ . '../../../../backend/php/includes/navbar.php'; ?>
        </header>
        <main>
            <section class="product">
                <h2>Productos Destacados</h2>
                <!-- Cartas de productos -->
                <div class="product-grid">
                    <div class="product-card">
                        <img src="../img/indica.jpg" alt="Flor Indica">
                        <h4>Flor Indica</h4>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>

                    <div class="product-card">
                        <img src="../img/sativa.webp" alt="Flor Sativa">
                        <h4>Flor Sativa</h4>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>

                    <div class="product-card">
                        <img src="../img/aceite.webp" alt="Aceite CBD">
                        <h4>Aceite CBD</h4>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                </div>
                </section>
                <!-- Descripción -->
                <div class="product-details">
                    <div class="product-info">
                        <h3>DESCRIPCIÓN</h3>
                        <p>Somos una Smoke Shop dedicada a ofrecer productos de alta calidad para quienes viven
                            y disfrutan la cultura alternativa. Vendemos pipas, bongs, vaporizadores, papel, blunts,
                            filtros, moledores, encendedores, bandejas, artículos de limpieza, ropa y accesorios
                            únicos que combinan estilo, funcionalidad y personalidad. Nos enorgullece brindar una experiencia segura, auténtica y libre de prejuicios, con una selección cuidadosamente curada para cada tipo de cliente. Entre lo más buscado por nuestro público están los bongs de vidrio artesanales, el papel orgánico sin blanquear, los vaporizadores portátiles, los moledores de aluminio y la ropa con diseño urbano. Más que una tienda,
                            somos una comunidad que celebra la libertad de expresión y el buen gusto.</p>
                    </div>
                </div>
        </main>
    </div>
</body>
</html>

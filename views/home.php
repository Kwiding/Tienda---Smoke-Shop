<?php 
include 'partials/header.php'; 
include 'partials/navbar.php'; 
?>

<div class="hero-section" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/hero-bg.jpg'); background-size: cover; background-position: center; height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; color: white;">
    <div class="container">
        <h1 class="display-4">Bienvenido a GreenLeaf</h1>
        <p class="lead">Tu tienda de cannabis medicinal y recreativo de confianza</p>
        <a href="/products" class="btn btn-primary btn-lg">Explorar Productos</a>
    </div>
</div>

<div class="container my-5">
    <h2 class="text-center mb-4">Productos Destacados</h2>
    <div class="row">
        <?php 
        if (isset($featuredProducts)) {
            while ($product = $featuredProducts->fetch(PDO::FETCH_ASSOC)): 
        ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 cannabis-card">
                <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                    <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/products/<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Ver más</a>
                </div>
            </div>
        </div>
        <?php 
            endwhile; 
        } else {
            echo '<p class="text-center">No hay productos destacados disponibles en este momento.</p>';
        }
        ?>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <i class="fas fa-cannabis fa-3x mb-3 text-success"></i>
                <h3>Productos de Calidad</h3>
                <p>Cultivados con los más altos estándares de calidad y seguridad.</p>
            </div>
            <div class="col-md-4 text-center mb-4 mb-md-0">
                <i class="fas fa-shipping-fast fa-3x mb-3 text-success"></i>
                <h3>Envío Discreto</h3>
                <p>Empaquetado neutro y entrega rápida para tu total privacidad.</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-headset fa-3x mb-3 text-success"></i>
                <h3>Soporte 24/7</h3>
                <p>Nuestro equipo está siempre disponible para resolver tus dudas.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
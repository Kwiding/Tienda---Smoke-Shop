<?php include '../views/partials/header.php'; ?>
<?php include '../views/partials/navbar.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Nuestras Variedades de Cannabis</h1>
    
    <div class="row">
        <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 cannabis-card">
                <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                    <p class="card-text"><?php echo substr($product['description'], 0, 100); ?>...</p>
                    <div class="cannabis-specs">
                        <span class="badge bg-success">THC: <?php echo $product['thc_content']; ?>%</span>
                        <span class="badge bg-info">CBD: <?php echo $product['cbd_content']; ?>%</span>
                        <span class="badge bg-warning"><?php echo ucfirst($product['strain_type']); ?></span>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                        <a href="/products/<?php echo $product['id']; ?>" class="btn btn-primary">Ver Detalles</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../views/partials/footer.php'; ?>
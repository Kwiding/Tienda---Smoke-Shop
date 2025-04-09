<?php include '../partials/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Tu Carrito de Compras</h1>
    
    <?php if(empty($_SESSION['cart'])): ?>
        <div class="alert alert-info">
            Tu carrito está vacío. <a href="/products">Explora nuestros productos</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $id => $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="60" class="me-3">
                                <div>
                                    <h6><?php echo $item['name']; ?></h6>
                                    <small class="text-muted">THC: <?php echo $item['thc_content']; ?>% | CBD: <?php echo $item['cbd_content']; ?>%</small>
                                </div>
                            </div>
                        </td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary decrease-quantity" data-id="<?php echo $id; ?>">-</button>
                                <input type="text" class="form-control text-center quantity-input" value="<?php echo $item['quantity']; ?>" data-id="<?php echo $id; ?>">
                                <button class="btn btn-outline-secondary increase-quantity" data-id="<?php echo $id; ?>">+</button>
                            </div>
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-item" data-id="<?php echo $id; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="/products" class="btn btn-outline-secondary">Seguir Comprando</a>
            <a href="/cart/checkout" class="btn btn-primary">Proceder al Pago</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../partials/footer.php'; ?>
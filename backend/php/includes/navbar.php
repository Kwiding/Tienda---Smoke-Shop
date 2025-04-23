<?php
require_once __DIR__ . '/../conexion.php';
$query_categorias = "SELECT * FROM categorias";
$resultado_categorias = $conexion->query($query_categorias);
?>
<nav class="main-navbar">
    <a href="../../../frontend/public/html/home.php"><i class="fas fa-home"></i> Inicio</a>
    <?php if(isset($_SESSION['id'])): ?>
        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <a href="../../../frontend/public/html/gestion-pedidos.php"><i class="fas fa-clipboard-list"></i> Gestionar Pedidos</a>
            <a href="../../../frontend/public/html/crear-categoria.php"><i class="fas fa-tags"></i> Crear Categoría</a>
            <a href="../../../frontend/public/html/gestinar-producto.php"><i class="fas fa-box"></i> Gestionar Productos</a>
            <a href="../../../backend/php/carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a>
        <?php else: ?>
            <select onchange="window.location.href=this.value" class="category-select" style="background-color: #fff; border:none; color: #333;">
                <option value="../../../frontend/public/html/productos.php">Todas las categorías</option>
                <?php while($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <option value="../../../frontend/public/html/productos.php?categoria_id=<?php echo $categoria['id']; ?>"
                            <?php echo (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                        <?php echo $categoria['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <a href="../../../backend/php/carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a>
        <?php endif; ?>
        <a href="../../../frontend/public/html/productos.php"><i class="fas fa-store"></i> Productos</a>
        <div class="user-profile" style="margin-left: 0;">
            <a href="../../../frontend/public/html/perfil.php">
                <i class="fas fa-user" style="color: white;"></i>
                <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </a>
        </div>
    <?php else: ?>
        <a href="../../../frontend/public/html/login.html" style="margin-left: auto;">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </a>
    <?php endif; ?>
</nav>

<style>
.category-select {
    padding: 8px;
    border-radius: 5px;
    background-color: transparent;
    color: #333;
    border: 1px solid #ddd;
    margin: 0 10px;
    cursor: pointer;
}

.category-select:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.user-profile {
    position: relative;
    margin-left: 0;
}

.user-profile a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    text-decoration: none;
    color: white;
    border-radius: 5px;
}

.user-profile a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.user-profile i {
    font-size: 1.2em;
    color: white;
}
</style>
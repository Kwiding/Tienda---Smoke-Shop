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
        <?php else: ?>
            <select onchange="window.location.href=this.value" class="category-select">
                <option value="">Seleccionar Categoría</option>
                <?php while($categoria = $resultado_categorias->fetch_assoc()): ?>
                    <option value="/backend/php/productos.php?categoria_id=<?php echo $categoria['id']; ?>">
                        <?php echo $categoria['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        <?php endif; ?>
        <a href="../../../frontend/public/html/productos.php"><i class="fas fa-store"></i> Productos</a>
        <a href="/backend/php/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    <?php else: ?>
        <a href="/frontend/public/html/login.html"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
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
</style>
<?php
include('../../../backend/php/conexion-bd.php');
$query_categorias = "SELECT * FROM categorias";
$resultado_categorias = mysqli_query($conexion, $query_categorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/style2.css">
</head>
<body>
    <div class="container">
        <form class="login-form" method="POST" action="../../../backend/php/crear_producto.php" enctype="multipart/form-data">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>

            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria_id" required>
                <option value="">Seleccione una categoría</option>
                <?php while($categoria = mysqli_fetch_assoc($resultado_categorias)): ?>
                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" required>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Descripción" rows="4" required></textarea> 

            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" step="0.01" required>

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" >
            
            <button type="submit">Crear producto</button>
        </form>
    </div>
</body>
</html>
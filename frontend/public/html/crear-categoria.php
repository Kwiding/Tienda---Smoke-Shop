<?php
include('../../../backend/php/conexion-bd.php');

// Lógica para guardar la categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);

    if (!empty($nombre)) {
        // Insertar categoría en la base de datos
        $query = "INSERT INTO categorias (nombre) VALUES ('$nombre')";
        $result = mysqli_query($conexion, $query);

        // Verificar si la categoría se guardó correctamente
        if ($result) {
            $success_message = "¡Categoría creada exitosamente!";
        } else {
            $error = "Hubo un problema al guardar la categoría. Intente nuevamente.";
        }
    } else {
        $error = "El nombre de la categoría no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Categoría</title>
    <link rel="stylesheet" href="../css/crear-categoria.css">
</head>
<body>
    <div class="container">
        <header>
            <?php include __DIR__ . '../../../../backend/php/includes/navbar.php'; ?>
        </header>

        <main>
            <div class="content-wrapper">
                <div class="category-form">
                    <h2>Crear Categoría</h2>
                    <!-- Mostrar mensaje de éxito o error -->
                    <?php if (isset($success_message)) : ?>
                        <p style="color: green;"><?php echo $success_message; ?></p>
                    <?php elseif (isset($error)) : ?>
                        <p style="color: red;"><?php echo $error; ?></p>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="input-group">
                            <label for="categoryName">Nombre de la nueva categoría</label>
                            <input type="text" id="categoryName" name="nombre" placeholder="Nombre de la nueva categoría" required>
                        </div>
                        <div class="button-group">
                            <button type="submit">Crear categoría</button>
                            <button type="button" onclick="window.history.back()">Volver</button>
                        </div>
                    </form>
                </div>
                <div class="category-image">
                    <img src="../img/mari2.avif" alt="Imagen de categoría">
                </div>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>
</html>

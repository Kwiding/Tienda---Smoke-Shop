<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="../css/gestinar-producto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <!-- Navbar -->
        <?php include __DIR__ . '../../../../backend/php/includes/navbar.php'; ?>

        <main class="management-page">
            <div class="management-header">
                <i class="fas fa-box-open"></i>
                <h2>Gestionar Productos</h2>
            </div>
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Gotas Cannabicas 3000mg</td>
                            <td>CBD</td>
                            <td>$22.540</td>
                            <td>25</td>
                            <td class="actions">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Editar</button>
                                <button class="delete-btn"><i class="fas fa-trash-alt"></i> Eliminar</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Aceite de Coco Orgánico</td>
                            <td>Aceites</td>
                            <td>$15.990</td>
                            <td>50</td>
                            <td class="actions">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Editar</button>
                                <button class="delete-btn"><i class="fas fa-trash-alt"></i> Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="add-product-btn" onclick="window.location.href='crear-producto.php'">
                <i class="fas fa-plus-circle"></i> Agregar Nuevo Producto
            </button>
        </main>
    </div>
</body>

</html>
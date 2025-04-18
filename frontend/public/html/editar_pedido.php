<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link rel="stylesheet" href="../css/style-form-pedido.css">
</head>
<body>
    <div class="container">
        <main class="form-page">
            <div class="form-card">
                <h2>Editar Pedido</h2>
                <?php
                require_once '../../../backend/php/conexion.php';
                
                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql = "SELECT * FROM pedidos WHERE id = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $pedido = $result->fetch_assoc();
                ?>
                <form action="../../../backend/php/actualizar_pedido.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">
                    
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($pedido['nombre_cliente']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($pedido['apellido_cliente']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="departamento">Departamento:</label>
                        <input type="text" id="departamento" name="departamento" value="<?php echo htmlspecialchars($pedido['departamento']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ciudad">Ciudad:</label>
                        <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($pedido['ciudad']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($pedido['direccion']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contacto">Contacto:</label>
                        <input type="text" id="contacto" name="contacto" value="<?php echo htmlspecialchars($pedido['contacto']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select name="estado" id="estado">
                            <option value="Pendiente" <?php if($pedido['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                            <option value="En proceso" <?php if($pedido['estado'] == 'En proceso') echo 'selected'; ?>>En proceso</option>
                            <option value="Completado" <?php if($pedido['estado'] == 'Completado') echo 'selected'; ?>>Completado</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="confirm-button">Actualizar Pedido</button>
                </form>
                <?php
                } else {
                    echo "<p>No se especificó un ID de pedido.</p>";
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>

<?php
session_start();
require_once '../../../backend/php/conexion-bd.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit();
}

// Obtener datos del usuario
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Consulta con precios actuales desde productos
$query_pedidos = "
  SELECT 
    p.id,
    p.fecha,
    p.hora,
    p.estado,
    GROUP_CONCAT(COALESCE(pr.nombre, 'Producto no disponible') SEPARATOR ', ') as productos_nombres,
    GROUP_CONCAT(COALESCE(lp.unidades, 0) SEPARATOR ', ') as cantidades,
    GROUP_CONCAT(COALESCE(pr.precio, 0) SEPARATOR ', ') as precios_unitarios,
    COALESCE(SUM(pr.precio * lp.unidades), 0) as total
  FROM pedidos p
  LEFT JOIN lineas_pedidos lp ON p.id = lp.pedido_id 
  LEFT JOIN productos pr ON lp.producto_id = pr.id
  WHERE p.usuario_id = ?
  GROUP BY p.id, p.fecha, p.hora, p.estado
  ORDER BY p.fecha DESC, p.hora DESC
";
$stmt_pedidos = $conexion->prepare($query_pedidos);
$stmt_pedidos->bind_param("i", $_SESSION['id']);
$stmt_pedidos->execute();
$resultado_pedidos = $stmt_pedidos->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="profile-sections" style="display: flex; gap: 20px;">
            <!-- Sección de Perfil -->
            <form class="login-form" id="profileForm" style="flex: 1;">
                <h2>Perfil de Usuario</h2>
                <div class="avatar">
                    <img src="../img/user.png" alt="Avatar">
                </div>
                <div class="profile-info" id="viewMode">
                    <p><strong>Nombre:</strong> <span><?php echo htmlspecialchars($usuario['nombre']); ?></span></p>
                    <p><strong>Apellidos:</strong> <span><?php echo htmlspecialchars($usuario['apellidos']); ?></span></p>
                    <p><strong>Email:</strong> <span><?php echo htmlspecialchars($usuario['email']); ?></span></p>
                    <button type="button" onclick="enableEdit()">Editar Perfil</button>
                    <br><br>
                    <a href="../../../backend/php/logout.php" class="logout-btn" style="text-align: center;">Cerrar Sesión</a>
                </div>
                <div class="profile-edit" id="editMode" style="display:none;">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>"><br>
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>"><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>"><br>
                    <label for="password">Nueva Contraseña (dejar en blanco para mantener la actual):</label>
                    <input type="password" id="password" name="password"><br>
                    <div class="button-group">
                        <button type="button" onclick="updateProfile()">Guardar Cambios</button>
                        <button type="button" onclick="cancelEdit()">Cancelar</button>
                    </div>
                </div>
            </form>

            <!-- Sección de Historial de Compras -->
            <div class="purchase-history">
                <h3><i class="fas fa-history"></i> Historial de Compras</h3>
                <div class="history-list">
                    <?php if($resultado_pedidos->num_rows > 0): ?>
                    <table class="purchase-table">
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Productos</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($pedido = $resultado_pedidos->fetch_assoc()): 
                            $productos = explode(', ', $pedido['productos_nombres']);
                            $cantidades = explode(', ', $pedido['cantidades']);
                            $precios = explode(', ', $pedido['precios_unitarios']);
                        ?>
                            <tr>
                                <td class="order-id">#<?php echo $pedido['id']; ?></td>
                                <td>
                                    <?php 
                                    for($i = 0; $i < count($productos); $i++) {
                                        echo htmlspecialchars($productos[$i]) . "<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    foreach($cantidades as $cantidad) {
                                        echo $cantidad . "<br>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    foreach($precios as $precio) {
                                        if(is_numeric($precio)) {
                                            echo "$" . number_format((float)$precio, 2, ',', '.') . "<br>";
                                        } else {
                                            echo "$0,00<br>";
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="total-cell">
                                    $<?php echo number_format((float)$pedido['total'], 2, ',', '.'); ?>
                                </td>
                                <td>
                                    <div class="order-status <?php echo strtolower($pedido['estado']); ?>">
                                        <?php echo htmlspecialchars($pedido['estado']); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p>No hay pedidos registrados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function enableEdit() {
        document.getElementById('viewMode').style.display = 'none';
        document.getElementById('editMode').style.display = 'block';
    }
    function cancelEdit() {
        document.getElementById('viewMode').style.display = 'block';
        document.getElementById('editMode').style.display = 'none';
    }
    function updateProfile() {
        const formData = new FormData(document.getElementById('profileForm'));
        fetch('../../../backend/php/actualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Perfil actualizado con éxito');
                location.reload();
            } else {
                alert('Error al actualizar el perfil: ' + data.error);
            }
        });
    }
    </script>
</body>
</html>

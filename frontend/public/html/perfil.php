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
    GROUP_CONCAT(pr.nombre ORDER BY lp.id ASC SEPARATOR '||')            AS productos,
    GROUP_CONCAT(pr.precio ORDER BY lp.id ASC SEPARATOR '||')            AS precios,
    GROUP_CONCAT(lp.unidades ORDER BY lp.id ASC SEPARATOR '||')          AS cantidades,
    GROUP_CONCAT(pr.imagen ORDER BY lp.id ASC SEPARATOR '||')            AS imagenes,
    COALESCE(SUM(pr.precio * lp.unidades), 0)                            AS total
  FROM pedidos p
  LEFT JOIN lineas_pedidos lp ON p.id = lp.pedido_id
  LEFT JOIN productos pr ON lp.producto_id = pr.id
  WHERE p.usuario_id = ?
  GROUP BY p.id
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
<!-- Sección de Historial de Compras -->
<div class="purchase-history">
    <h3><i class="fas fa-history"></i> Historial de Compras</h3>
    <div class="history-list">
        <?php if($resultado_pedidos->num_rows > 0): ?>
        <table class="purchase-table">
            <thead>
                <tr>
                    <th>Pedido #</th>
                    <th>Fecha / Hora</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php while($pedido = $resultado_pedidos->fetch_assoc()): ?>
                <tr>
                    <td class="order-id">#<?php echo $pedido['id']; ?></td>
                    <td><?php echo $pedido['fecha'].'<br>'.$pedido['hora']; ?></td>
                    <td class="total-cell">$<?php echo number_format($pedido['total'],2,',','.'); ?></td>
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

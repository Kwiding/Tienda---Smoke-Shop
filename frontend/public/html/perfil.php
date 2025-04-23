<?php
session_start();
require_once '../../../backend/php/conexion-bd.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.html');
    exit();
}

$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
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
        <form class="login-form" id="profileForm">
            <h2>Perfil de Usuario</h2>
            
            <div class="avatar">
                <img src="../img/user.png" alt="Avatar">
            </div>

            <div class="profile-info" id="viewMode">
                <p><strong>Nombre:</strong> <span><?php echo htmlspecialchars($usuario['nombre']); ?></span></p>
                <p><strong>Apellidos:</strong> <span><?php echo htmlspecialchars($usuario['apellidos']); ?></span></p>
                <p><strong>Email:</strong> <span><?php echo htmlspecialchars($usuario['email']); ?></span></p>
                
                <button type="button" onclick="enableEdit()">Editar Perfil</button>
                <br><br><a href="../../../backend/php/logout.php" class="logout-btn" style="text-align: center;">Cerrar Sesión</a>
            </div>

            <div class="profile-edit" id="editMode" style="display:none;">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>"><br>
                
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>">
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>">
                <br>
                <label for="password">Nueva Contraseña (dejar en blanco para mantener la actual):</label>
                <input type="password" id="password" name="password">
                
                <div class="button-group">
                    <button type="button" onclick="updateProfile()">Guardar Cambios</button>
                    <button type="button" onclick="cancelEdit()">Cancelar</button>
                </div>
            </div>
        </form>
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
        .then(response => response.json())
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

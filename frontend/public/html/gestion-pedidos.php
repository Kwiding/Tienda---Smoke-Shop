<?php
session_start();
// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: productos.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="../css/style-gestionar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <?php include __DIR__ . '../../../../backend/php/includes/navbar.php'; ?>
        </header>
        <main class="management-page">
            <div class="management-header">
                <i class="fas fa-file-alt"></i>
                <h2>Gestionar Pedidos</h2>
            </div>
            <div class="table-container">
                <table class="table-content">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Ciudad</th>
                            <th>Dirección</th>
                            <th>Contacto</th>
                            <th>Método de Pago</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../../../backend/php/conexion.php';
                        
                        $sql = "SELECT * FROM pedidos ORDER BY fecha DESC, hora DESC";
                        $result = $conexion->query($sql);

                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>#" . $row['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['nombre_cliente']) . " " . htmlspecialchars($row['apellido_cliente']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['contacto']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['metodo_pago']) . "</td>";
                            echo "<td class='status " . strtolower($row['estado']) . "'>" . $row['estado'] . "</td>";
                            echo "<td>" . $row['fecha'] . " " . $row['hora'] . "</td>";
                            echo "<td class='actions'>
                                    <a href='../../../backend/php/eliminar_pedido.php?id=" . $row['id'] . "' 
                                       class='delete-btn' 
                                       onclick='return confirm(\"¿Estás seguro de que deseas eliminar este pedido?\")'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                    <a href='editar_pedido.php?id=" . $row['id'] . "' class='edit-btn'>
                                        <i class='fas fa-edit'></i>
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
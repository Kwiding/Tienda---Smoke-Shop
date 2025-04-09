<?php

include '../../config/database.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simula la validación de usuario (reemplaza esto con tu lógica de base de datos)
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '1234') {
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = 'Admin';
        $_SESSION['user_role'] = 'admin';
        header('Location: /');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto mt-40 bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6 text-purple-800">Iniciar Sesión</h1>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-medium mb-2">Usuario</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Contraseña</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500 transition font-semibold" formaction="../product/index.php">Ingresar</button>
        </form>
    </div>
</body>
</html>
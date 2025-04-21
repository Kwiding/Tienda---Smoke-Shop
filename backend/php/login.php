<?php
session_start();
include 'conexion-bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);

    $query = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password' LIMIT 1";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        
        if ($usuario['rol'] == 'admin') {
            header("Location: ../../frontend/public/html/gestinar-producto.php");
            exit();
        } else {
            header("Location: ../../frontend/public/html/home.php");
            exit();
        }
    } else {
        echo "<script>
            alert('Usuario o contraseña incorrectos');
            window.location.href = '/frontend/public/html/login.html';
        </script>";
    }
}

mysqli_close($conexion);
?>

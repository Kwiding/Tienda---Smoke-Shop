<?php
session_start();

// Destruir todas las variables de sesión, incluyendo el carrito
$_SESSION = array();

// Destruir la cookie de sesión si existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destruir la sesión
session_destroy();

// Redireccionar al home
header("Location: ../../frontend/public/html/home.php");
exit();
?>

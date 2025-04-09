<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'models/Category.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/CategoryController.php';
require_once 'controllers/CartController.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Enrutamiento básico
switch($url[0]) {
    case 'home':
        include 'views/home.php';
        break;
    case 'products':
        $productController = new ProductController($db);
        if(isset($url[1]) && is_numeric($url[1])) {
            $productController->show($url[1]);
        } else if(isset($url[1]) && $url[1] == 'create') {
            $productController->create();
        } else {
            $productController->index();
        }
        break;
    case 'login':
        $authController = new AuthController($db);
        $authController->login();
        break;
    case 'register':
        $authController = new AuthController($db);
        $authController->register();
        break;
    case 'cart':
        $cartController = new CartController($db);
        if(isset($url[1]) && $url[1] == 'checkout') {
            $cartController->checkout();
        } else {
            $cartController->index();
        }
        break;
    default:
        include 'views/home.php';
        break;
}
?>
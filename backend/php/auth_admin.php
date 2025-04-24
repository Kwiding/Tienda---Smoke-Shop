<?php
session_start();

function validarAdmin() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header('Location: /Tienda---Smoke-Shop/frontend/public/html/productos.php');
        exit();
    }
}

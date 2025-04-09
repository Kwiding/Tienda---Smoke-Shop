<?php
require_once 'database.php';
require_once '../models/User.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $userModel = new User($db);

    // Validar edad (mínimo 18 años)
    $birthDate = new DateTime($_POST['date_of_birth']);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;

    if ($age < 18) {
        $_SESSION['error'] = "Debes ser mayor de 18 años para registrarte.";
        header("Location: ../views/auth/register.php");
        exit;
    }

    // Preparar datos para insertar
    $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
        'role' => 'customer',
        'date_of_birth' => $_POST['date_of_birth'],
        'address' => trim($_POST['address']),
        'phone' => trim($_POST['phone'])
    ];

    // Intentar crear el usuario
    if ($userModel->create($data)) {
        $_SESSION['success'] = "Registro exitoso. Por favor inicia sesión.";
        header("Location: ../views/auth/login.php");
    } else {
        $_SESSION['error'] = "Error al registrar. El email ya existe.";
        header("Location: ../views/auth/register.php");
    }
}
?>
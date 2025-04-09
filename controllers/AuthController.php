<?php
class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar edad (mínimo 18 años)
            $age = date_diff(date_create($_POST['birth_date']), date_create('today'))->y;
            if ($age < 18) {
                $_SESSION['error'] = "Debes ser mayor de 18 años para registrarte";
                header("Location: /register");
                exit;
            }

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
                'role' => 'customer',
                'birth_date' => $_POST['birth_date'],
                'address' => trim($_POST['address']),
                'phone' => trim($_POST['phone'])
            ];

            if ($this->userModel->create($data)) {
                $_SESSION['success'] = "Registro exitoso. Por favor inicia sesión.";
                header("Location: /login");
            } else {
                $_SESSION['error'] = "Error al registrar. El email ya existe.";
                header("Location: /register");
            }
        } else {
            include 'views/auth/register.php';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->findByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: /");
            } else {
                $_SESSION['error'] = "Credenciales incorrectas";
                header("Location: /login");
            }
        } else {
            include 'views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
    }

    public function verifyAge() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $age = date_diff(date_create($_POST['birth_date']), date_create('today'))->y;
            if ($age >= 18) {
                $_SESSION['age_verified'] = true;
                header("Location: /");
            } else {
                $_SESSION['error'] = "Debes ser mayor de 18 años para acceder";
                header("Location: /age-verification");
            }
        } else {
            include 'views/auth/age_verification.php';
        }
    }
}
?>
<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $birth_date;
    public $address;
    public $phone;
    public $created_at;
    public $verified;
    public $verification_token;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        // Verificar si el email ya existe
        if ($this->findByEmail($data['email'])) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " 
                 (name, email, password, role, date_of_birth, address, phone, verification_token)
                 VALUES (:name, :email, :password, :role, :date_of_birth, :address, :phone, :verification_token)";

        $stmt = $this->conn->prepare($query);

        // Generar token de verificación
        $token = bin2hex(random_bytes(32));

        // Limpiar y vincular datos
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':date_of_birth', $data['birth_date']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':verification_token', $token);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT id, name, email, role, birth_date, address, phone, created_at 
                  FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyEmail($token) {
        $query = "UPDATE " . $this->table . " 
                 SET verified = 1, verification_token = NULL 
                 WHERE verification_token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }

    public function updateProfile($id, $data) {
        $query = "UPDATE " . $this->table . " 
                 SET name = :name, email = :email, birth_date = :birth_date, 
                     address = :address, phone = :phone 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':birth_date', $data['birth_date']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $query = "UPDATE " . $this->table . " 
                 SET password = :password 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function getAll($role = null) {
        $query = "SELECT id, name, email, role, created_at FROM " . $this->table;
        
        if ($role) {
            $query .= " WHERE role = :role";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($role) {
            $stmt->bindParam(':role', $role);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<?php
class Order {
    private $conn;
    private $table = 'orders';

    public $id;
    public $user_id;
    public $order_date;
    public $status;
    public $total;
    public $payment_method;
    public $shipping_address;
    public $tracking_number;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (user_id, status, total, payment_method, shipping_address, notes)
                 VALUES (:user_id, :status, :total, :payment_method, :shipping_address, :notes)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':total', $data['total']);
        $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':shipping_address', $data['shipping_address']);
        $stmt->bindParam(':notes', $data['notes']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                  FROM " . $this->table . " o
                  JOIN users u ON o.user_id = u.id
                  WHERE o.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                  ORDER BY order_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll($status = null) {
        $query = "SELECT o.*, u.name as user_name 
                  FROM " . $this->table . " o
                  JOIN users u ON o.user_id = u.id";
        
        if ($status) {
            $query .= " WHERE o.status = :status";
        }
        
        $query .= " ORDER BY o.order_date DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " 
                 SET status = :status 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function updateTracking($id, $tracking_number) {
        $query = "UPDATE " . $this->table . " 
                 SET tracking_number = :tracking_number, status = 'enviado'
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tracking_number', $tracking_number);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.name as product_name, p.image as product_image 
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addOrderItem($order_id, $product_id, $quantity, $price) {
        $query = "INSERT INTO order_items 
                 (order_id, product_id, quantity, price)
                 VALUES (:order_id, :product_id, :quantity, :price)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        
        return $stmt->execute();
    }

    public function getSalesStats() {
        $query = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(total) as total_revenue,
                    AVG(total) as average_order_value
                  FROM " . $this->table . " 
                  WHERE status != 'cancelado'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
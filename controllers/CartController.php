<?php
class CartController {
    private $productModel;
    private $orderModel;

    public function __construct($db) {
        $this->productModel = new Product($db);
        $this->orderModel = new Order($db);
    }

    public function index() {
        if (!isset($_SESSION['age_verified'])) {
            header("Location: /age-verification");
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Calcular total
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        include 'views/cart/index.php';
    }

    public function add($product_id) {
        if (!isset($_SESSION['age_verified'])) {
            header("Location: /age-verification");
            exit;
        }

        $product = $this->productModel->getById($product_id);
        
        if (!$product) {
            $_SESSION['error'] = "Producto no encontrado";
            header("Location: /products");
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Verificar si el producto ya está en el carrito
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'image' => $product['image'],
                'thc_content' => $product['thc_content'],
                'cbd_content' => $product['cbd_content']
            ];
        }

        $_SESSION['success'] = "Producto añadido al carrito";
        header("Location: /cart");
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['quantity'] as $product_id => $quantity) {
                if (isset($_SESSION['cart'][$product_id])) {
                    if ($quantity > 0) {
                        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                    } else {
                        unset($_SESSION['cart'][$product_id]);
                    }
                }
            }
            
            $_SESSION['success'] = "Carrito actualizado";
        }
        header("Location: /cart");
    }

    public function remove($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = "Producto eliminado del carrito";
        }
        header("Location: /cart");
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_to'] = '/cart/checkout';
            header("Location: /login");
            exit;
        }

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Tu carrito está vacío";
            header("Location: /products");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Calcular total
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Crear la orden
            $order_id = $this->orderModel->create([
                'user_id' => $_SESSION['user_id'],
                'status' => 'pendiente',
                'total' => $total,
                'payment_method' => $_POST['payment_method'],
                'shipping_address' => $_POST['shipping_address'],
                'notes' => $_POST['notes']
            ]);

            if ($order_id) {
                // Añadir items a la orden
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $this->orderModel->addOrderItem(
                        $order_id,
                        $product_id,
                        $item['quantity'],
                        $item['price']
                    );
                }

                // Vaciar carrito y redirigir a confirmación
                unset($_SESSION['cart']);
                $_SESSION['success'] = "¡Pedido realizado con éxito!";
                header("Location: /orders/$order_id");
                exit;
            } else {
                $_SESSION['error'] = "Error al procesar el pedido";
                header("Location: /cart/checkout");
                exit;
            }
        }

        include 'views/cart/checkout.php';
    }

    public function clear() {
        unset($_SESSION['cart']);
        $_SESSION['success'] = "Carrito vaciado";
        header("Location: /products");
    }
}
?>
<?php
class ProductController {
    private $productModel;
    
    public function __construct($db) {
        $this->productModel = new Product($db);
    }
    
    public function index() {
        // Obtener productos destacados
        $featuredProducts = $this->productModel->readFeatured();
        
        // Incluir la vista principal (home.php)
        include 'views/home.php';
    }
    
    public function show($id) {
        $this->productModel->id = $id;
        $product = $this->productModel->readOne();
        include 'views/product/show.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar formulario
            $this->productModel->name = $_POST['name'];
            $this->productModel->description = $_POST['description'];
            $this->productModel->price = $_POST['price'];
            $this->productModel->category_id = $_POST['category_id'];
            $this->productModel->thc_content = $_POST['thc_content'];
            $this->productModel->cbd_content = $_POST['cbd_content'];
            $this->productModel->strain_type = $_POST['strain_type'];
            
            // Procesar imagen
            if (isset($_FILES['image'])) {
                $target_dir = "assets/images/products/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $this->productModel->image = $target_file;
            }
            
            if ($this->productModel->create()) {
                header("Location: /products");
            } else {
                echo "Error al crear el producto";
            }
        } else {
            // Mostrar formulario
            include 'views/product/create.php';
        }
    }
}
?>
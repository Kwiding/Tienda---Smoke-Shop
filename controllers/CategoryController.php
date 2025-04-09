<?php
class CategoryController {
    private $categoryModel;

    public function __construct($db) {
        $this->categoryModel = new Category($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /login");
            exit;
        }

        $categories = $this->categoryModel->getAll();
        include 'views/admin/categories/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            // Procesar imagen
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/categories/';
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['image'] = $targetFile;
                }
            }

            if ($this->categoryModel->create($data)) {
                $_SESSION['success'] = "Categoría creada exitosamente";
                header("Location: /admin/categories");
            } else {
                $_SESSION['error'] = "Error al crear categoría";
                header("Location: /admin/categories/create");
            }
        } else {
            include 'views/admin/categories/create.php';
        }
    }

    public function edit($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /login");
            exit;
        }

        $category = $this->categoryModel->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            // Procesar imagen si se subió una nueva
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/categories/';
                $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Eliminar la imagen anterior si existe
                    if ($category['image']) {
                        unlink($category['image']);
                    }
                    $data['image'] = $targetFile;
                }
            }

            if ($this->categoryModel->update($data)) {
                $_SESSION['success'] = "Categoría actualizada exitosamente";
                header("Location: /admin/categories");
            } else {
                $_SESSION['error'] = "Error al actualizar categoría";
                header("Location: /admin/categories/edit/$id");
            }
        } else {
            include 'views/admin/categories/edit.php';
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /login");
            exit;
        }

        $category = $this->categoryModel->getById($id);
        
        // Eliminar imagen asociada si existe
        if ($category['image']) {
            unlink($category['image']);
        }

        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = "Categoría eliminada exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar categoría";
        }
        
        header("Location: /admin/categories");
    }
}
?>
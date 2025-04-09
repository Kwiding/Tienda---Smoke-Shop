<?php
class Product
{
    private $conn;
    private $table = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $thc_content;
    public $cbd_content;
    public $strain_type; // indica, sativa, híbrido
    public $image;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readFeatured()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE featured = 1 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  SET name=:name, description=:description, price=:price, 
                  category_id=:category_id, thc_content=:thc_content, 
                  cbd_content=:cbd_content, strain_type=:strain_type, image=:image";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->thc_content = htmlspecialchars(strip_tags($this->thc_content));
        $this->cbd_content = htmlspecialchars(strip_tags($this->cbd_content));
        $this->strain_type = htmlspecialchars(strip_tags($this->strain_type));
        $this->image = htmlspecialchars(strip_tags($this->image));

        // Vincular parámetros
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":thc_content", $this->thc_content);
        $stmt->bindParam(":cbd_content", $this->cbd_content);
        $stmt->bindParam(":strain_type", $this->strain_type);
        $stmt->bindParam(":image", $this->image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Otros métodos como update, delete, readOne, etc.
}

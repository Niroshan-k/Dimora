<?php

class House{
    private $conn;
    private $table = 'house';
    public $id;
    public $name;
    public $price;
    public $type;
    public $description;
    public $image;
    public $location;
    public $bedroom;
    public $bathroom;
    public $carpark;
    public $area;
    public $pool;
    public $user_id;

    public function __construct($db){
        $this->conn = $db;
    }

    public function createADD() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, price, type, description, image, location, bedroom, bathroom, carpark, area, pool, user_id) 
                  VALUES (:name, :price, :type, :description, :image, :location, :bedroom, :bathroom, :carpark, :area, :pool, :user_id)";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':description', $this->description);
        
        // Bind the image as binary data
        $stmt->bindParam(':image', $this->image, PDO::PARAM_LOB);
    
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':bedroom', $this->bedroom);
        $stmt->bindParam(':bathroom', $this->bathroom);
        $stmt->bindParam(':carpark', $this->carpark);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':pool', $this->pool);
        $stmt->bindParam(':user_id', $this->user_id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    

    public function getAdvertisementsByUserId($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  

    public function getAdvertisementById($house_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE house_id = :house_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':house_id', $house_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateAdvertisement() {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, price = :price, type = :type, description = :description, 
                      location = :location, bedroom = :bedroom, bathroom = :bathroom, 
                      carpark = :carpark, area = :area, pool = :pool";
    
        // Only update the image if a new one is provided
        if ($this->image !== null) {
            $query .= ", image = :image";
        }
    
        $query .= " WHERE house_id = :house_id";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':bedroom', $this->bedroom);
        $stmt->bindParam(':bathroom', $this->bathroom);
        $stmt->bindParam(':carpark', $this->carpark);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':pool', $this->pool);
    
        if ($this->image !== null) {
            $stmt->bindParam(':image', $this->image, PDO::PARAM_LOB);
        }
    
        $stmt->bindParam(':house_id', $this->id);
    
        return $stmt->execute();
    }
    
    public function deleteAdvertisement() {
        $query = "DELETE FROM " . $this->table . " WHERE house_id = :house_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':house_id', $this->id);
        return $stmt->execute();
    }
    
    public function getAllAdvertisements() {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
}
?>
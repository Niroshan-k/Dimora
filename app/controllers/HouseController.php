<?php

require_once '../../config/customerDB.php';
require_once '../../app/models/HouseModel.php';

class HouseController{
    private $db; // Declare the $db property
    private $House; // Declare the $addvertisement property
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();//Initialize the database connection
        $this->House = new House($this->db);//Pass the connection to the employee model
    }

    public function createADD() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $database = new Database();
            $db = $database->connect();
    
            $House = new House($db);
            $House->name = $_POST['name'];
            $House->price = $_POST['price'];
            $House->type = $_POST['type'];
            $House->description = $_POST['description'];
            $House->location = $_POST['location'];
            $House->bedroom = $_POST['bedroom'];
            $House->bathroom = $_POST['bathroom'];
            $House->carpark = $_POST['carpark'];
            $House->area = $_POST['area'];
            $House->pool = $_POST['pool'];
            $House->user_id = $_POST['user_id'];
    
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageTmpPath = $_FILES['image']['tmp_name'];
                if (is_uploaded_file($imageTmpPath)) {
                    $imageData = file_get_contents($imageTmpPath);
                    $House->image = $imageData;
                } else {
                    $_SESSION['error']['image'] = "Invalid file upload.";
                    $House->image = null;
                }
            } else {
                $_SESSION['error']['image'] = "No file uploaded or upload error.";
                $House->image = file_get_contents('/path/to/default/image.jpg'); // Optional placeholder
            }
    
            $result = $House->createADD();
    
            if ($result === true) {
                header('Location: http://localhost/Dimora/App/views/sellerDashboard.php');
                exit;
            } else {
                error_log('Failed to create advertisement: ' . print_r($stmt->errorInfo(), true));
                $_SESSION['error']['general'] = "Failed to create.";
                header('Location: http://localhost/Dimora/App/views/createAdvertisement.php');
                exit;
            }
        }
    }
    
    
    public function fetchUserAdvertisements($user_id) {
        return $this->House->getAdvertisementsByUserId($user_id);
    }

    public function fetchAdvertisementById($house_id) {
        return $this->House->getAdvertisementById($house_id);
    }
    
    public function updateAdvertisement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->House->id = $_POST['house_id'];
            $this->House->name = $_POST['name'];
            $this->House->price = $_POST['price'];
            $this->House->type = $_POST['type'];
            $this->House->description = $_POST['description'];
            $this->House->location = $_POST['location'];
            $this->House->bedroom = $_POST['bedroom'];
            $this->House->bathroom = $_POST['bathroom'];
            $this->House->carpark = $_POST['carpark'];
            $this->House->area = $_POST['area'];
            $this->House->pool = $_POST['pool'];
    
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageTmpPath = $_FILES['image']['tmp_name'];
                $imageData = file_get_contents($imageTmpPath);
                $this->House->image = $imageData;
            } else {
                $this->House->image = null; // No new image uploaded
            }
    
            // Call model function
            $result = $this->House->updateAdvertisement();
    
            if ($result) {
                header('Location: http://localhost/Dimora/App/views/sellerDashboard.php');
                exit;
            } else {
                $_SESSION['error']['general'] = "Failed to update the advertisement.";
                header("Location: editHouseAdd.php?house_id=" . $this->House->id);
                exit;
            }
        }
    }
    public function deleteAdvertisement($house_id) {
        $this->House->id = $house_id;
        return $this->House->deleteAdvertisement();
    }
    
    public function loadAdvertisements() {
        $this->advertisements = $this->House->getAllAdvertisements();
    }
}

?>
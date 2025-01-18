<?php

require_once '../config/customerDB.php';
require_once '../app/models/HouseModel.php';

class PublicController{
    private $db; // Declare the $db property
    private $House; // Declare the $addvertisement property
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();//Initialize the database connection
        $this->House = new House($this->db);//Pass the connection to the employee model
    }

    public function loadAdvertisements() {
        $this->advertisements = $this->House->getAllAdvertisements();
    }

    public function fetchAdvertisementById($house_id) {
        return $this->House->getAdvertisementById($house_id);
    }
}

?>
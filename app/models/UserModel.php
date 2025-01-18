<?php

class User {
    private $conn;
    private $table = 'user';
    public $id;
    public $email;
    public $username;
    public $password;
    public $role;
    public $NIC;
    public $profilePicture;

    public function __construct($db){
        $this->conn = $db;
    }
    //create in the database
    public function create(){
        // Check if username exists
        if ($this->usernameExists($this->username)) {
            return "Username already exists"; // Return a message indicating duplication
        }

        $query = "INSERT INTO " . $this->table . "(email, username, password, role, NIC, profilePicture) VALUES (:email,:username, :password, :role, :NIC, :profilePicture)";
        $stml = $this->conn->prepare($query);

        //Bind parameters
        $stml->bindParam(':email',$this->email);
        $stml->bindParam(':username',$this->username);
        $stml->bindParam(':password',$this->password);
        $stml->bindParam(':role',$this->role);
        $stml->bindParam(':NIC',$this->NIC);
        $stml->bindParam(':profilePicture',$this->profilePicture);

        if ($stml->execute()){
            return true;
        }
        return false;
    }

    public function usernameExists($username) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0; // Return true if the username exists
    }  
    
    public function verifyCredentials($username, $password, $role) {
        // Query to get the hashed password and user ID
        $query = "SELECT user_id, username, password FROM " . $this->table . " WHERE username = :username AND role = :role";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && password_verify($password, $result['password'])) {
            // Return user_id and username if credentials are valid
            return [
                'user_id' => $result['user_id'],
                'username' => $result['username']
            ];
        }
    
        return false; // Invalid credentials
    }
    
    
    
    public function getUserById($user_id){
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch a single row
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return user data or null if not found
        return $result ?: null;
    }
    
    public function updateProfile($user_id, $email, $username, $NIC, $profilePictureData) {
        $query = "UPDATE " . $this->table . " 
                  SET email = :email, username = :username, NIC = :NIC" .
                  ($profilePictureData ? ", profilePicture = :profilePicture" : "") . 
                  " WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':NIC', $NIC);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if ($profilePictureData) {
            $stmt->bindParam(':profilePicture', $profilePictureData);
        }
    
        // Execute and return the result
        return $stmt->execute();
    }
    
 }
?>
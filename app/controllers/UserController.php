<?php

require_once '../../config/customerDB.php';
require_once '../../app/models/UserModel.php';

class UserController{
    private $db; // Declare the $db property
    private $User; // Declare the $employee property
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();//Initialize the database connection
        $this->User = new User($this->db);//Pass the connection to the employee model
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start(); // Start the session to store error messages
    
            // Check if the file was uploaded
            if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
                // Get the uploaded file
                $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
                $fileName = $_FILES['profilePicture']['name'];
                $fileSize = $_FILES['profilePicture']['size'];
                $fileType = $_FILES['profilePicture']['type'];
    
                // Read the file content
                $profilePictureData = file_get_contents($fileTmpPath);
            } else {
                // Handle the error if no file is uploaded or there is an issue
                $_SESSION['error']['general'] = "Please upload a valid profile picture.";
                header('Location: http://localhost/Dimora/app/views/signup.php');
                exit;
            }
    
            // Connect to the database
            $database = new Database();
            $db = $database->connect();
    
            // Create a new User object
            $User = new User($db);
            $User->email = $_POST['email'];
            $User->username = $_POST['username'];
    
            // Hash the password securely before storing it
            $User->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
            $User->role = $_POST['role'];
            $User->NIC = $_POST['NIC'];
            $User->profilePicture = $profilePictureData; // Store the image as BLOB
    
            // Attempt to create the user in the database
            $result = $User->create();
    
            // Check if the user was created successfully
            if ($result === true) {
                // Redirect based on role
                if ($User->role === 'customer') {
                    header('Location: http://localhost/Dimora/App/views/signin.php');
                } elseif ($User->role === 'seller') {
                    header('Location: http://localhost/Dimora/App/views/signin.php');
                }
                exit;
            } elseif ($result === "Username already exists") {
                $_SESSION['error']['username'] = "Username already exists"; // Store the error
                header('Location: http://localhost/Dimora/app/views/signup.php');
                exit;
            } else {
                $_SESSION['error']['general'] = "Failed to create user.";
                header('Location: http://localhost/Dimora/app/views/signup.php');
                exit;
            }
        }
    }    
    
    public function signin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Start the session
            session_start();
    
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];
    
            $database = new Database();
            $db = $database->connect();
            $User = new User($db);
    
            // Verify credentials
            $userData = $User->verifyCredentials($username, $password, $role);
    
            if ($userData !== false) {
                // Store user details in session
                $_SESSION['user'] = [
                    'user_id' => $userData['user_id'],
                    'username' => $userData['username'],
                    'role' => $role
                ];
    
                // Redirect user based on role
                if ($role === 'customer') {
                    header('Location: http://localhost/Dimora/App/views/customerDashboard.php');
                } elseif ($role === 'seller') {
                    header('Location: http://localhost/Dimora/App/views/sellerDashboard.php');
                }
                exit;
            } else {
                // Invalid credentials
                $_SESSION['error']['signin'] = "Invalid username, password, or role";
                header('Location: http://localhost/Dimora/App/views/signin.php');
                exit;
            }
        }
    }
    
    
    
    public function getProfile($user_id){
        return $this->User->getUserById($user_id);
    }
    
    public function update($user_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Start session to handle feedback messages
    
            // Retrieve and sanitize input data
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $NIC = $_POST['NIC'] ?? '';
            
    
            // Check if a new profile picture is uploaded
            $profilePictureData = null;
            if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
                $profilePictureData = file_get_contents($fileTmpPath); // Get file content
            }
    
            // Update the user's profile in the database
            $result = $this->User->updateProfile($user_id, $email, $username, $NIC, $profilePictureData);
    
            // Redirect or display appropriate message
            if ($result) {
                header('Location: userprofile.php'); // Reload the profile page
            } else {
                $_SESSION['error'] = "Failed to update profile.";
                header('Location: userprofile.php');
            }
            exit;
        }
    }
    
}
?>
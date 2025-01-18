<?php
require_once '../../config/customerDB.php';
require_once '../../app/models/UserModel.php';

class CustomerController{
    private $db; // Declare the $db property
    private $User; // Declare the $employee property
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();//Initialize the database connection
        $this->User = new User($this->db);//Pass the connection to the employee model
    }
    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $database = new Database();
            $db = $database->connect();

            $User = new Customer($db);
            $User->email = $_POST['email'];
            $User->username = $_POST['username'];
            $User->password = $_POST['password'];
            $User->role = $_POST['role'];
            $User->NIC = $_POST['NIC'];
            $User->profilePicture = $_POST['profilePicture'];

            if($User->create()){
                //redirect to the list page
                header('Location: http://localhost/Dimora/App/views/CustomerDashboard.php');
                exit;//ensure no further code executes after the redirection
            }else{
                echo"Failed to create customer.";
            }
        }
    }
    
    public function index(){
        return $this->User->getAll();
    }
    public function delete($id){
        $this->User->id =$id;
        if ($this->employee->delete()){
            //redirect to the list page
            header('Location: http://localhost/Employee/App/views/employees/list.php');
            exit;
        }else{
            echo"Failed to delete employee.";
        }
    }
}
?>
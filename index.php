<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';

$objDb = new DbConnect;
$conn = $objDb->connect(); 

//read data
// POST does not read jason data


$method = $_SERVER['REQUEST_METHOD'];
switch($method){
    case "GET":
        $sql = "SELECT * FROM `react-crud`";
        // get url and the split it at the /
        $path = explode('/', $_SERVER['REQUEST_URI']);
        // If statement for an individual user
        if(isset($path[3]) && is_numeric($path[3])){
            $sql .= "WHERE id = :id";
            $stmt = $conn ->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // if we are not working with an individual
            //ut rather all the users
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        echo json_encode($users);
        break;

    case "POST":
        $user = json_decode(file_get_contents('php://input')); //so we can read the data in JSON
        $sql = "INSERT INTO `react-crud`(id, name, email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
    
        // created at variable
        $created_at = date('Y-m-d');

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':created_at', $created_at);
    
        if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'User created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create user.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        $user = json_decode(file_get_contents('php://input')); //so we can read the data in JSON
        $sql = "UPDATE `react-crud` SET `name` =:name, `email` =:email, `mobile` =:mobile, `updated_at` =:updated_at WHERE `react-crud`.`id` =:id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':updated_at', $updated_at);
        
        if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'User updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update user.'];
        }
        echo json_encode($response);
        break; 
        
    case "DELETE":
        $sql = "DELETE FROM `react-crud` WHERE id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);

        if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'Redord deleted successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete record.'];
        }

        echo json_encode($response);
        break;
}


<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';

$objDb = new DbConnect;
$conn = $objDb->connect();

//read data
// POST does not read jason data


$method = $_SERVER['REQUEST_METHOD'];
switch($method){
    case "GET":
        $sql = "SELECT * FROM `react-crud`";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}


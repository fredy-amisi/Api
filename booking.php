<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Origin: http:// 192.168.184.207:3000");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

include 'DbConnect.php';


$dbConnect = new DbConnect();
$conn = $dbConnect->conn;

$method = $_SERVER['REQUEST_METHOD'];
switch($method){
    case "POST":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO booking(id, fullName, email, appointmentDate, service, paymentMethod, appointmentTime)
         VALUES (null, :fullName, :email, :appointmentDate, :service, :paymentMethod, :appointmentTime)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fullName', $user->fullName);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':appointmentDate', $user->appointmentDate);
        $stmt->bindParam(':service', $user->service);
        $stmt->bindParam(':paymentMethod', $user->paymentMethod);
        $stmt->bindParam(':appointmentTime', $user->appointmentTime);

       if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'Appointment Booked successful!'];
            echo json_encode($response);
        }
        else{
            $response = ['status' => 0, 'message' => 'Failed To Book'];
            echo json_encode($response);
        }
        break;
        
}



echo("Testing");

?>
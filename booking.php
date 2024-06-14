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
        $sql = "INSERT INTO booking(id, name, email, datein, alonepartner, dateout, PaymentInformation)
         VALUES (null, :name, :email, :date-in, :alone-partner, :date-out, :PaymentInformation)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':date-in', $user->datein);
        $stmt->bindParam(':alone-partner', $user->alonepartner);
        $stmt->bindParam(':date-out', $user->dateout);
        $stmt->bindParam(':PaymentInformation', $user->PaymentInformation);

       if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'Booking Was successful.'];
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
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
$objDb = new DbConnect;
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch($method){
    case "POST":
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO booking(id, PickupLocation, DeliveryLocation, DateforPickup, PaymentInformation, TypeofGoods, Quantity)
         VALUES (null, :PickupLocation, :DeliveryLocation, :DateforPickup, :PaymentInformation, :TypeofGoods, :Quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':PickupLocation', $user->PickupLocation);
        $stmt->bindParam(':DeliveryLocation', $user->DeliveryLocation);
        $stmt->bindParam(':DateforPickup', $user->DateforPickup);
        $stmt->bindParam(':PaymentInformation', $user->PaymentInformation);
        $stmt->bindParam(':TypeofGoods', $user->TypeofGoods);
        $stmt->bindParam(':Quantity', $user->Quantity);

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
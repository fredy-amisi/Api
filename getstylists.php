<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "BMS"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

$sql = "SELECT id, name, role, image FROM stylists";
$result = $conn->query($sql);

$stylists = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stylists[] = $row;
    }
}

echo json_encode($stylists);

$conn->close();
?>

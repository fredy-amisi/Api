<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BMS";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$service_name = $_POST['service_name'];
$description = $_POST['description'];

// Handle file upload
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["service_image"]["name"]);
move_uploaded_file($_FILES["service_image"]["tmp_name"], $targetFile);

$sql = "INSERT INTO services (service_name, description, service_image) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $service_name, $description, $targetFile);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Service uploaded successfully";
} else {
    $response['success'] = false;
    $response['message'] = "Error: " . $conn->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>

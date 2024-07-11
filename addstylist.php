<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "BMS"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

$name = isset($_POST['name']) ? $_POST['name'] : null;
$role = isset($_POST['role']) ? $_POST['role'] : null;
$image = isset($_FILES['image']) ? $_FILES['image'] : null;

// Validate inputs
if (empty($name) || empty($role) || empty($image)) {
    echo json_encode(array("success" => false, "message" => "Name, role, and image are required."));
    $conn->close();
    exit();
}

// Handle image upload
$target_dir = "uploads/";
$target_file = $target_dir . basename($image["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
$allowed_types = array("jpg", "jpeg", "png", "gif");

// Check if file type is allowed
if (!in_array($imageFileType, $allowed_types)) {
    echo json_encode(array("success" => false, "message" => "Only JPG, JPEG, PNG, and GIF files are allowed."));
    $conn->close();
    exit();
}

// Check if file already exists
if (file_exists($target_file)) {
    echo json_encode(array("success" => false, "message" => "File already exists."));
    $conn->close();
    exit();
}

// Check file size (limit to 5MB)
if ($image["size"] > 5000000) {
    echo json_encode(array("success" => false, "message" => "File is too large. Maximum file size is 5MB."));
    $conn->close();
    exit();
}

if (!move_uploaded_file($image["tmp_name"], $target_file)) {
    echo json_encode(array("success" => false, "message" => "Error uploading file."));
    $conn->close();
    exit();
}

// Insert into database
$sql = "INSERT INTO stylists (name, role, image) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $role, $target_file);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Stylist added successfully."));
} else {
    echo json_encode(array("success" => false, "message" => "Error adding stylist: " . $stmt->error));
}

$stmt->close();
$conn->close();
?>

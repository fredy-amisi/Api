<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-Attendance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);
$student_name = $data['student_name'];
$email = $data['email'];
$course_id = $data['course_id'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO enrollments (student_name, email, course_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $student_name, $email, $course_id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Enrollment successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

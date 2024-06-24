<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-Attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_name = $_POST['course_name'];
$course_code = $_POST['course_code'];
$description = $_POST['description'];
$teacher_id = $_POST['teacher_id'];

// Handle file upload
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["course_image"]["name"]);
move_uploaded_file($_FILES["course_image"]["tmp_name"], $targetFile);

$sql = "INSERT INTO courses (course_name, course_code, description, teacher_id, course_image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $course_name, $course_code, $description, $teacher_id, $targetFile);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Course uploaded successfully";
} else {
    $response['success'] = false;
    $response['message'] = "Error: " . $conn->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-Attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $data->id;

    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);

    $response = array();
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Course deleted successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();

echo json_encode($response);
?>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow requests from specific origin
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database connection
require_once 'DbConnect.php';

// Create an instance of DbConnect to establish the connection
$dbConnect = new DbConnect();
$conn = $dbConnect->conn;

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Query to get details from enrollments table with course name
    $stmt = $conn->prepare("SELECT enrollments.id, enrollments.student_name, enrollments.email, enrollments.enrollment_date, courses.course_name 
                            FROM enrollments 
                            JOIN courses ON enrollments.course_id = courses.id");
    $stmt->execute();
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($enrollments) {
        // Send successful response with enrollments data
        echo json_encode($enrollments);
    } else {
        // No enrollments found, send empty array
        echo json_encode([]);
    }
} else {
    // If the request method is not GET, send error response
    $response = array("success" => false, "error" => "Invalid request method");
    http_response_code(405); // Method Not Allowed
    echo json_encode($response);
}
?>

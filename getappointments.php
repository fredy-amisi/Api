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
    // Query to get details from booking table
    $stmt = $conn->prepare("SELECT id, fullName, email, service, appointmentDate, appointmentTime, paymentMethod FROM booking");
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($appointments) {
        // Send successful response with appointments data
        echo json_encode($appointments);
    } else {
        // No appointments found, send empty array
        echo json_encode([]);
    }
} else {
    // If the request method is not GET, send error response
    $response = array("success" => false, "error" => "Invalid request method");
    http_response_code(405); // Method Not Allowed
    echo json_encode($response);
}
?>

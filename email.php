<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the JSON request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the required keys are set in the $data array
    if (isset($data['name']) && isset($data['email']) && isset($data['number']) && isset($data['message'])) {
        $name = $data["name"];
        $email = $data["email"];
        $number = $data["number"];
        $message = $data["message"];

        // Set SMTP server settings
        ini_set('SMTP', 'smtp.gmail.com');
        ini_set('smtp_port', '587');

        // Email information
        $to = "amisifredy20@gmail.com";
        $subject = "New Message from Fash Contact Form";
        $body = "Name: $name\nEmail: $email\nPhone Number: $number\nMessage:\n$message";

        // Send email
        if (mail($to, $subject, $body)) {
            $response = array("success" => true, "message" => "Message sent successfully");
            echo json_encode($response);
        } else {
            $response = array("success" => false, "error" => "Failed to send email. Please try again later.");
            echo json_encode($response);
        }
    } else {
        $response = array("success" => false, "error" => "Required fields are missing");
        echo json_encode($response);
    }
}
?>

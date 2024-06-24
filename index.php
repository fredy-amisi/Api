<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'DbConnect.php';
$dbConnect = new DbConnect();
$conn = $dbConnect->conn;

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        $sql = "SELECT id, name, email, username, role, phonenumber, signin_date FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case "POST":
        $user = json_decode(file_get_contents('php://input'));

        // Check if all required fields are provided
        if (isset($user->name) && isset($user->email) && isset($user->username) && isset($user->role) && isset($user->phonenumber) && isset($user->password)) {
            
            // Check if an admin already exists
            $adminCheck = $conn->prepare("SELECT COUNT(*) AS admin_count FROM users WHERE role = 'Admin'");
            $adminCheck->execute();
            $result = $adminCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['admin_count'] > 0 && $user->role === 'Admin') {
                // If an admin already exists, return an error response
                $response = ['success' => false, 'message' => 'There can only be one admin.'];
                echo json_encode($response);
                exit();
            }

            // Hash the password
            $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users(name, email, username, role, phonenumber, password) VALUES (:name, :email, :username, :role, :phonenumber, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':role', $user->role);
            $stmt->bindParam(':phonenumber', $user->phonenumber);
            $stmt->bindParam(':password', $hashedPassword); // Store hashed password

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'User created successfully.'];
                echo json_encode($response);
            } else {
                $response = ['success' => false, 'message' => 'Failed to create user.'];
                echo json_encode($response);
            }
        } else {
            // Required data not provided, send error response
            $response = ['success' => false, 'message' => 'Missing required fields.'];
            http_response_code(400); // Bad request
            echo json_encode($response);
        }
        break;
}
?>

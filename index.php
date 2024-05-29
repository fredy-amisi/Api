<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off error display in production

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

include 'DbConnect.php';
$dbConnect = new DbConnect(); // Create an instance of DbConnect to establish the connection
$conn = $dbConnect->conn; // Access the connection property

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        $sql = "SELECT id, name, email, username, department, phonenumber FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case "POST":
        $user = json_decode(file_get_contents('php://input'));

        // Hash the password
        $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users(name, email, username, department, phonenumber, password) VALUES (:name, :email, :username, :department, :phonenumber, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':department', $user->department);
        $stmt->bindParam(':phonenumber', $user->phonenumber);
        $stmt->bindParam(':password', $hashedPassword); // Store hashed password

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'User created successfully.'];
            echo json_encode($response);
        } else {
            $response = ['success' => false, 'message' => 'Failed to create user.'];
            echo json_encode($response);
        }
        break;
}
?>

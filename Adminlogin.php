<?php
require_once 'DbConnect.php';


$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

$stmt = $pdo->prepare('SELECT * FROM admin WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

$response = ['success' => false];

if ($user && password_verify($password, $user['password'])) {
    $response['success'] = true;
    $response['user'] = [
        'email' => $user['email'],
        'role' => $user['role'],
    ];
} else {
    $response['message'] = 'Invalid credentials';
}

echo json_encode($response);
?>

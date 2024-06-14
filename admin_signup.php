<?php
require_once 'DbConnect.php';


$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];
$token = $data['token'];

$stmt = $pdo->prepare('SELECT * FROM admin_invites WHERE token = ?');
$stmt->execute([$token]);
$invite = $stmt->fetch();

if (!$invite || new DateTime() > new DateTime($invite['expires'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare('INSERT INTO admin (email, password, role) VALUES (?, ?, ?)');
$stmt->execute([$email, $passwordHash, 'admin']);

$stmt = $pdo->prepare('DELETE FROM admin_invites WHERE token = ?');
$stmt->execute([$token]);

echo json_encode(['success' => true]);
?>
